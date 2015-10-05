<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../Conectar.php");
	$link = Conectarse('webpmm');
	$accion = $_POST['accion'];	
	$usuario = $_SESSION[NOMBREUSUARIO]; 
	$fecha = date("d/m/Y");	
	$fechainicio = date("d/m/Y");
	$idusuario = $_SESSION[IDUSUARIO];
	
	$s = "SELECT DAYOFWEEK(ADDDATE(CURRENT_DATE, INTERVAL -1 DAY)) dia";
	$r = mysql_query($s,$link) or die($s);
	$f = mysql_fetch_object($r);
	if($f->dia==1)
		$diasmenos = 2;
	else
		$diasmenos = 1;
	
		$s = "SELECT id FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciodia = ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY)";
		$aa = mysql_query($s,$link) or die($s);
		
		if(mysql_num_rows($aa)>0){
			$s = "SELECT IFNULL(MAX(id),0) as id FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciodia = ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY)";
			$rt = mysql_query($s,$link) or die($s); $ft = mysql_fetch_object($rt);
			
			$s = "SELECT iniciodia FROM cierredia WHERE fechacierredia=ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY) AND sucursal = $_SESSION[IDSUCURSAL] AND iniciodia = $ft->id";
			$ci= mysql_query($s,$link) or die($s);
			$cerrarondia = ((mysql_num_rows($ci)==0)?1:0);	
			
			$s = "SELECT * FROM cierreprincipal WHERE fechacierre = ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY) AND sucursal = $_SESSION[IDSUCURSAL] AND estado = 'CERRADA'";
			$cp= mysql_query($s,$link) or die($s);
			$cerraronprincipal = ((mysql_num_rows($cp)==0)?1:0);		
		}
	
	$sql = "SELECT * FROM iniciodia WHERE fechainiciodia='".cambiaf_a_mysql($fecha)."' and sucursal = $_SESSION[IDSUCURSAL]";
	$rsql = mysql_query($sql,$link);
	if(mysql_num_rows($rsql)>0){
		$existe="SI";
	}
	$idMax = mysql_query("SELECT IFNULL(MAX(id),0) as id FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciodia = CURDATE()",$link);		
	$row = mysql_fetch_array($idMax);
	
	$sql_cierre = mysql_query("SELECT iniciodia FROM cierredia WHERE iniciodia='".$row[0]."'",$link);
	$r_cierre = mysql_fetch_array($sql_cierre);
	
	if($r_cierre[0]==""){
		$cierre=0;
	}else{
		$cierre=1;
	}
	$r = mysql_query("select date_format(date(iniciodia.fechainiciodia), '%d/%m/%Y') fechainicio, 
	date(cierredia.fechacierredia) fechacierre
	from iniciodia
	left join cierredia on iniciodia.id = cierredia.iniciodia
	and iniciodia.fechainiciodia<current_date
	where  iniciodia.idusuario = $_SESSION[IDUSUARIO] and iniciodia.sucursal = $_SESSION[IDSUCURSAL]
	having isnull(fechacierre)",$link);
	$rowd = mysql_num_rows($r);
	
	if($accion == "grabar"){		
		$fechainicio = $_POST['fechainicio'];
		$sqlins = mysql_query("INSERT INTO iniciodia 
		(idusuario, fechainiciodia, sucursal, usuario, fecha) 
		VALUES 
		('".$_SESSION[IDUSUARIO]."', '".cambiaf_a_mysql($fechainicio)."', ".$_SESSION[IDSUCURSAL].",
		'$usuario', current_timestamp())",$link);		
		
		$mensaje = "El día se inició correctamente";
		
		$s = "INSERT INTO cierreprincipal_fechas SET fecha = CURDATE(), sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$link) or die($s);
		
		$sql = "SELECT * FROM iniciodia WHERE fechainiciodia='".cambiaf_a_mysql($fecha)."' 
		AND idusuario='".$_SESSION[IDUSUARIO]."'";
		$rsql = mysql_query($sql,$link);		
		if(mysql_num_rows($rsql)>0){
			$existe="SI";
		}
		
		$idMax = mysql_query("SELECT IFNULL(MAX(id),0) AS id FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciodia = CURDATE()",$link);		
		$row = mysql_fetch_array($idMax);
		
		$sql_cierre = mysql_query("SELECT iniciodia FROM cierredia WHERE iniciodia='".$row[0]."'",$link);
		$r_cierre = mysql_fetch_array($sql_cierre);
		if($r_cierre[0]==""){
			$cierre=0;
		}else{
			$cierre=1;
		}
		$r = mysql_query("select date_format(date(iniciodia.fechainiciodia), '%d/%m/%Y') fechainicio, 
		date(cierredia.fechacierredia) fechacierre
		from iniciodia
		left join cierredia on iniciodia.id = cierredia.iniciodia
		and iniciodia.fechainiciodia<current_date
		where  iniciodia.idusuario = $_SESSION[IDUSUARIO] and iniciodia.sucursal = $_SESSION[IDSUCURSAL]
		having isnull(fechacierre)",$link);
		$rowd = mysql_num_rows($r);
	}
	
	$s = "select date_format(date(iniciodia.fechainiciodia), '%d/%m/%Y') fechainicio, 
	date(cierredia.fechacierredia) fechacierre
	from iniciodia
	left join cierredia on iniciodia.id = cierredia.iniciodia
	and iniciodia.fechainiciodia<current_date
	where  iniciodia.idusuario = $_SESSION[IDUSUARIO] and iniciodia.sucursal = $_SESSION[IDSUCURSAL]
	having isnull(fechacierre)";
	$r = mysql_query($s,$link) or die($s);
	$fechas = "";
	while($f = mysql_fetch_object($r)){
		$fechas .= (($fechas!="")?",":"")."$f->fechainicio";
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script src="../javascript/ajax.js"></script>
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></LINK>
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script>
	var u = document.all;
	window.onload = function(){
		obtenerDiaInhabil();
	}
	function validar(){
		<?=$cpermiso->verificarPermiso("323",$_SESSION[IDUSUARIO]);?>
		if(u.cerrarondia.value==1){			
			alerta3("No se ha cerrado el dia anterior, "+((u.cerraronprincipal.value==1)?"ni caja principal, ":"")+"es necesario cerrarlos para poder continuar.","&iexcl;Atencion!");
			return false;
		}
		
		if(u.cerraronprincipal.value==1){
			alerta3('No se ha cerrado la caja principal del dia anterior, es necesario cerrarlos para poder continuar.','¡Atención');			
			return false;
		}
		
		if(u.cierre.value==1){
			alerta3('El dia '+u.fecha.value+' ya fue cerrado','¡Atención');
			return false;
		}
		
		if(u.existe.value=="SI"){
			alerta3('El Día ' + u.fecha.value + ' ya ha sido iniciado','¡Atención!');
		}else if(parseFloat(u.inicio.value)>0){
			alerta3('Debe Cerrar Los Dias <?=$fechas?>','¡Atención');
		}else if(u.fechaInhabil.value=="SI" || u.dia.value==1){
			alerta3('No puede iniciar día por que es un dia Inhábil','¡Atención');
		}else if(u.cierre.value!="" && u.inicio.value==0){
			u.accion.value = "grabar";
			document.form1.submit();
		}
	}
	
	function obtenerDiaInhabil(){
		consultaTexto("mostrarDiaInhabil","iniciodia_con.php?accion=1&s="+Math.random());
	}
	
	function mostrarDiaInhabil(datos){
		
		var obj = eval(datos);
		u.dia.value = obj.principal.dia;
		
		for(var i=0; i < obj.detalle.length; i++){
			if(u.fechainicio.value == obj.detalle[i].dia){
				u.fechaInhabil.value = "SI";
				return false;
			}
		}
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
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body >
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="250" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="161" class="FondoTabla Estilo4">INICIO DE D&Iacute;A</td>
  </tr>
  <tr>
    <td><table width="249" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td width="36"><label></label></td>
        <td width="37">Fecha:</td>
        <td width="176"><span class="Tablas">
          <label>
          <input name="fechainicio" readonly="" style="background:#FFFF99; text-align:center" class="Tablas" type="text" id="fechainicio" value="<?=$fechainicio ?>">
          </label>
        </span></td>
      </tr>
      <tr>
        <td colspan="3" align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="center"><div class="ebtn_iniciar" onClick="validar(); this.style.display='none';"></div></td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"></td>
      </tr>
      <tr>
        <td colspan="3">
			<input name="existe" type="hidden" id="existe" value="<?=$existe ?>">
			<input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
			<input name="fecha" type="hidden" id="fecha" value="<?=$fecha ?>">
			<input name="msg" type="hidden" id="msg" value="<?=$msg ?>">
			<input name="id_inicio" type="hidden" id="id_inicio" value="<?=$row[0] ?>">
			<input name="cierre" type="hidden" id="cierre" value="<?=$cierre ?>">
			<input name="fechaMax" type="hidden" id="fechaMax" value="<?=$fechaMax ?>">
			<input name="inicio" type="hidden" id="inicio" value="<?=$rowd ?>">
			<input name="fechaInhabil" type="hidden" id="fechaInhabil" value="<?=$fechaInhabil ?>">
			<input name="dia" type="hidden" id="dia" value="<?=$dia ?>">
			<input name="cerrarondia" type="hidden" id="cerrarondia" value="<?=$cerrarondia ?>">
			<input name="cerraronprincipal" type="hidden" id="cerraronprincipal" value="<?=$cerraronprincipal ?>"></td>
      </tr>
    </table>
      </td>
  </tr>
</table>
</form>
</body>
</html>
<?
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
}
?>