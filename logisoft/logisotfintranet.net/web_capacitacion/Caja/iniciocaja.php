<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../Conectar.php");
	$link = Conectarse('webpmm');
	$usuario = $_SESSION[NOMBREUSUARIO]; 
	$idusuario = $_SESSION[IDUSUARIO]; 
	$accion = $_POST['accion']; 
	$sltcaja = $_POST['sltcaja']; 
	$caja = $_POST['caja']; 
	$fecha = $_POST['fecha']; 
	$fondo = $_POST['fondo']; 
	$codigo = $_POST['codigo']; 
	$sucursalorigen = $_POST['sucursalorigen'];
	$fecha = date("d/m/Y");
	
	$s = "SELECT IFNULL(MAX(id),0) as id FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciodia = CURDATE()";
	$ri = mysql_query($s,$link) or die($s); $f = mysql_fetch_object($ri);
	
	$s = "SELECT iniciodia FROM cierredia WHERE iniciodia='".$f->id."'";
	$rc= mysql_query($s,$link) or die($s);
	
	$s = "SELECT * FROM cierrecaja WHERE tipocierre='definitivo' AND fechacierre=CURDATE() AND usuariocaja=$_SESSION[IDUSUARIO] and sucursal = $_SESSION[IDSUCURSAL] ";
	$t = mysql_query($s,$link) or die($s);
	
	$s = "SELECT * FROM cierreprincipal WHERE fechacierre = CURDATE() AND sucursal = $_SESSION[IDSUCURSAL] AND estado = 'CERRADA'";
	$p = mysql_query($s,$link) or die($s);
	
	if(mysql_num_rows($rc)>0){ 
		$diacerrado = 1;
	}
	
	if(mysql_num_rows($t)>0){
		$cierrecajadefinitivo = 1;
	}
	
	if(mysql_num_rows($p)>0){
		$cierreprincipal = 1;
	}
	
	$sql = mysql_query("SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) as nombre
	FROM catalogoempleado WHERE id='".$_SESSION[IDUSUARIO]."'",$link);
	$row = mysql_fetch_array($sql);
	$usuariocaja = $row[0];
	
	if($accion==""){
		$sql = mysql_query("SELECT cajachica FROM catalogosucursal WHERE id='".$_SESSION[IDSUCURSAL]."'",$link);
		$row = mysql_fetch_array($sql);
		$fondo = $row[0];
		
	}else if($accion=="grabar"){
		$sql = mysql_query("INSERT INTO iniciocaja 
		(usuariocaja, fechainiciocaja, fondo, sucursal, usuario, fecha) 
		VALUES 
		('$idusuario', '".cambiaf_a_mysql($fecha)."', '$fondo', 
		".$_SESSION[IDSUCURSAL].", '$usuario', current_timestamp())",$link);
		$codigo=mysql_insert_id();		
		$mensaje = "Los datos han sido guardados correctamente";
		
	}else if($accion=="modificar"){
		$sql = mysql_query("UPDATE iniciocaja SET usuariocaja='$usuariocaja', 
		caja='$caja', fechainiciocaja='$fechainiciocaja', fondo='$fondo', 
		usuario='$usuario', fecha=current_timestamp() WHERE id='$codigo'",$link);
				
	}else if($accion=="limpiar"){
		$accion = ""; $sltcaja = ""; $caja = ""; $fecha = ""; $fondo = ""; $codigo = "";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ajax.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script>
	var u = document.all;
	var sucursalorigen;
	var nav4 = window.Event ? true : false;
	var mens = new ClaseMensajes();
	mens.iniciar("../javascript");
	
	window.onload = function(){
		validarFondo();
		u.fondo.focus();
	}
	
	function Numeros(evt){
		// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46, ',' = 44 
	var key = nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
	}
	function validar(){
		if(u.diacerrado.value==1){
			mens.show('A','No se puede iniciar caja por que el dia ha sido cerrado','�Atenci�n!');
			return false;
		}
		
		if(u.cierreprincipal.value==1){
			mens.show('A','No se puede iniciar caja por que la caja principal ha sido cerrada','�Atenci�n!');
			return false;
		}
		
		if(u.cierrecajadefinitivo.value==1){
			mens.show('A','No se puede iniciar caja por que la caja ha sido cerrada definitivamente','�Atenci�n!');
			return false;
		}
		
		if(u.inicio.value == "NO"){
			mens.show('A','No puede iniciar Caja sin iniciar D�a','�Atenci�n!','fondo');
		}/*else if(u.existe.value==1){
		 	mens.show('A','El usuario no puede tener 2 cajas iniciadas','�Atenci�n!','fondo');
		}*/else if(u.iniciocaja.value==0){
			mens.show('A','La caja ya ha sido iniciada','�Atenci�n!','fondo');
		}else if(u.fondo.value==""){
			mens.show('A','Debe Capturar Fondo de Caja','�Atenci�n!','fondo');
		}else if(parseFloat(u.fondo.value) < 0){
			mens.show('A','El Fondo de Caja debe ser mayor a Cero','�Atenci�n!','fondo');		
		}else if(parseFloat(u.fondo.value) > parseFloat(u.fondocaja.value)){
			mens.show('A','El Fondo de caja NO debe ser mayor al fondo de caja configurado','�Atenci�n!','fondo');				
		}else{
			u.accion.value = "grabar";
			document.form1.submit();
		}
	}
	
	function validarInicioCaja(){
		consulta("obtenerInicioCaja","consultas.php?accion=3&fecha="+u.fecha.value
		+"&idusuario=<?=$_SESSION[IDUSUARIO];?>&s="+Math.random()+"&sucursal=<?=$_SESSION[IDSUCURSAL]?>");
	}	
	function obtenerInicioCaja(datos){		
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){
			u.iniciocaja.value = datos.getElementsByTagName('validar').item(0).firstChild.data;	
			u.existe.value = datos.getElementsByTagName('existe').item(0).firstChild.data;
		}
	}	
	function validarFondo(){	
		consulta("obtenerFondo","consultas.php?accion=2&sucursal="+<?=$_SESSION[IDSUCURSAL];?>+"&s="+Math.random());
	}	
	function obtenerFondo(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){
			u.fondocaja.value = datos.getElementsByTagName('fondocaja').item(0).firstChild.data;
		}
		validarInicioDia();
	}
	function validarInicioDia(){
			consulta("verificarInicioDia","consultas.php?accion=1&fecha="+u.fecha.value);
	}
	function verificarInicioDia(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){
			u.inicio.value = datos.getElementsByTagName('existe').item(0).firstChild.data;
		}else{
			u.inicio.value = "NO";
		}
		validarInicioCaja();
	}	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
.Estilo1 {font-size: 14px}
.Estilo2 {
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}
-->
</style>
<style type="text/css">
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
-->
</style>
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body >
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="350" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="287" class="FondoTabla Estilo4">INICIO DE CAJA</td>
  </tr>
  <tr>
    <td><br>
    <table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="54">Usuario:</td>
        <td colspan="3">
          <input name="$usuariocaja" type="text" class="Tablas" id="$usuariocaja" style="width:242px;background:#FFFF99" value="<?=$usuariocaja ?>" readonly=""/></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Fecha:</td>
        <td width="116">
          <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>        </td>
        <td width="44">Fondo:</td>
        <td width="86">
          <input name="fondo" type="text" onKeyPress="return Numeros(event)" class="Tablas" id="fondo" style="width:80px" value="<?=$fondo ?>" size="10" maxlength="10">       </td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
      
      
      <tr>
        <td colspan="4" align="right"><div class="ebtn_iniciarcaja" onClick="validar(); this.style.display='none';"></div></td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4"><div align="center">
          <input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
          <input name="codigo" type="hidden" id="codigo" value="<?=$codigo ?>">
          <input name="fondocaja" type="hidden" id="fondocaja" value="<?=$fondocaja ?>">
          <input name="iniciocaja" type="hidden" id="iniciocaja" value="<?=$iniciocaja ?>">
          <input name="inicio" type="hidden" id="inicio" value="<?=$inicio ?>">
          <input name="existe" type="hidden" id="existe" value="<?=$existe ?>">
          <input name="sucursalorigen" type="hidden" id="sucursalorigen" value="<?=$sucursalorigen ?>">
          <input name="diacerrado" type="hidden" id="diacerrado" value="<?=$diacerrado ?>">
          <input name="cierrecajadefinitivo" type="hidden" id="cierrecajadefinitivo" value="<?=$cierrecajadefinitivo ?>">
          <input name="cierreprincipal" type="hidden" id="cierreprincipal" value="<?=$cierreprincipal ?>">
        </div></td>
      </tr>
    </table>
      </td>
  </tr>
</table>
</form>
</body>
</html>
<? 	
	if($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>mens.show('I','".$mensaje."', 'Operaci�n realizada correctamente');</script>";
}
	
//} ?>