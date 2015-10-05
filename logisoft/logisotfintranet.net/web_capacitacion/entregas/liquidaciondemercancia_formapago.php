<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	$total=$_GET[importe];
	
	$s = "SELECT pagominimocheques FROM configuradorgeneral";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$pagominimo = $f->pagominimocheques;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/shortcut.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/ClaseTabla.js"></script>
<link href="../estilos_estandar.css" />
<script src="../javascript/ajax.js"></script>
<script>
	var nav4 = window.Event ? true : false;
	var	u		= document.all;
	window.onload = function(){
		u.efectivo.value= parseFloat(u.total.value.replace("$ ","").replace(/,/,""));
		blockear(u.nc,true);
		u.efectivo.select();
	}
	
	function Numeros(evt){ 
		var key = nav4 ? evt.which : evt.keyCode; 
		return (key <= 13 || (key >= 48 && key <= 57) || key==46);
	}
	
	function saltar(valor){
		var objeto = Array("efectivo","cheque","banco","ncheque");
		for(i=valor;i<6;i++){
			if(u[objeto[i]].readOnly==false){
				u[objeto[i]].focus();
				break;
			}
		}
	}
	
	function blockear(objeto,valor){
			objeto.readOnly = valor
			objeto.style.backgroundColor = (valor)?"#FFFF99":"";
			objeto.value="";
	}
	
	function validarEfectivo(){
		calcular();
		var total = parseFloat(u.total.value.replace("$ ","").replace(/,/,""));
		var acumulado = parseFloat(u.acumulado.value.replace("$ ","").replace(/,/,""));
		if(acumulado>=total){
			//blockear(u.nc,true);
			//blockear(u.cheque,true);
			calcular();
			u.cheque.focus();
		}else{
			//blockear(u.nc,false);
			//blockear(u.cheque,false);
			saltar(2);
		}
		u.cheque.focus();
	}
	
	function validarCheque(){
		calcular();
		var total = parseFloat(u.total.value.replace("$ ","").replace(/,/,""));
		var acumulado = parseFloat(u.acumulado.value.replace("$ ","").replace(/,/,""));
		
		if(acumulado>=total){
			calcular();
		}else{
			saltar(3);
		}
		u.banco.disable=true;
		u.banco.focus();
	}
	
	function validarbanco(){
		if (u.banco.value=="0" && u.cheque.value!=""){
			alerta3("Debe seleccionar un banco");
			return false;
		}else{
			u.ncheque.focus();
		}
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	
	function calcular(){
		var efectivo		=	parseFloat((u.efectivo.value!="")?u.efectivo.value.replace("$ ","").replace(/,/g,""):"0");
		var cheque			=	parseFloat((u.cheque.value!="")?u.cheque.value.replace("$ ","").replace(/,/g,""):"0");
		var nc				=	parseFloat((u.nc.value!="")?u.nc.value.replace("$ ","").replace(/,/g,""):"0");
		var total			= 	parseFloat((u.total.value!="")?u.total.value.replace("$ ","").replace(/,/g,""):"0");
		var acumulado		= efectivo+cheque+nc;
		u.acumulado.value	= "$ "+numcredvar(acumulado.toLocaleString());
	}
	
	function verificarTotales(){

		$total=0;

		if (u.ncheque.value=="" && u.cheque.value!="" && u.cheque.value!="0"){
				alerta3("El Numero de Cheque es Obligatorio");
				return false;
		}

		if (u.efectivo.value==""){
			u.efectivo.value=0;
		}

		if (u.nc.value==""){
			u.nc.value=0;
		}

		if (u.cheque.value==""){
			u.cheque.value=0;
		}

		u.acumulado.value=parseFloat(u.efectivo.value.replace("$ ","").replace(/,/,""))+parseFloat(u.nc.value.replace("$ ","").replace(/,/,""))+parseFloat(u.cheque.value.replace("$ ","").replace(/,/,""));

		u.acumulado.value	= "$ "+numcredvar(u.acumulado.value.toLocaleString());

		$total=parseFloat(u.efectivo.value.replace("$ ","").replace(/,/,""))+parseFloat(u.nc.value.replace("$ ","").replace(/,/,""))+parseFloat(u.cheque.value.replace("$ ","").replace(/,/,""));
		if ($total.toFixed(2)>parseFloat(u.total.value.replace("$ ","").replace(/,/,""))){
			alerta3("La sumatoria es mayor que el total a pagar");
			return false;
		}else if($total<parseFloat(u.total.value.replace("$ ","").replace(/,/,""))){
			alerta3("La sumatoria es menor que el total a pagar");
			return false;
		}else{
			validarTotales();
		}	
	}
	
	function validarTotales(){
		//var cambio 		= parseFloat(u.cambio.value.replace("$ ","").replace(/,/,""));
		var efectivo 	= parseFloat(u.efectivo.value.replace("$ ","").replace(/,/,""));

		if(u.total.value != u.acumulado.value){
			var total 		= parseFloat(u.total.value.replace("$ ","").replace(/,/,""));
			var acumulado	= parseFloat(u.acumulado.value.replace("$ ","").replace(/,/,""));
			var comparacion	= total - acumulado;

			if(comparacion>0){
				alerta("Faltan "+(Math.round(comparacion*100)/100).toLocaleString().replace("-","")+" pesos para alcanzar el total","¡Atencion!","efectivo");	
			}else if(comparacion<0 && u.cambio.value == "$ 0.00"){
				alerta("Sobrepasaste por "+(Math.round(comparacion*100)/100).toLocaleString().replace("-","")+" pesos el total","¡Atencion!","efectivo");
			}
			var datos = Object();
			var efectivo=0;
			var cheque=0;
			var ncheque=0;
			var nnotacredito=0;
			var notacredito =0;
	
			if (parseFloat(u.efectivo.value.replace("$ ","").replace(/,/,""))==""){
				efectivo=0;
			}else{
				efectivo = parseFloat(u.efectivo.value.replace("$ ","").replace(/,/,""));
			}

			if (parseFloat(u.cheque.value.replace("$ ","").replace(/,/,""))==""){
				cheque=0;
			}else{
				cheque=parseFloat(u.cheque.value.replace("$ ","").replace(/,/,""));
			}
	
			if (u.ncheque.value==""){
				ncheque=0;
			}else{
				ncheque=u.ncheque.value;
			}
	
			if (u.folio.value==""){
				nnotacredito=0;
			}else{
				nnotacredito=u.folio.value;
			}
	
			if (parseFloat(u.nc.value.replace("$ ","").replace(/,/,""))==""){
				notacredito=0;
			}else{
				notacredito=parseFloat(u.nc.value.replace("$ ","").replace(/,/,""));
			}
			datos.efectivo = 			efectivo;
			datos.cheque = 				cheque;
			datos.ncheque = 			ncheque;
			datos.banco = 				u.banco.value;
			datos.nnotacredito = 		nnotacredito;
			datos.notacredito = 		notacredito;
			parent.actualizarFormaPago(datos);
		}else{
			var datos = Object();
			var efectivo=0;
			var cheque=0;
			var ncheque=0;
			var nnotacredito=0;
			var notacredito =0;
	
			if (parseFloat(u.efectivo.value.replace("$ ","").replace(/,/,""))==""){
				efectivo=0;
			}else{
				efectivo = parseFloat(u.efectivo.value.replace("$ ","").replace(/,/,""));
			}

			if (parseFloat(u.cheque.value.replace("$ ","").replace(/,/,""))==""){
				cheque=0;
			}else{
				cheque=parseFloat(u.cheque.value.replace("$ ","").replace(/,/,""));
			}
	
			if (u.ncheque.value==""){
				ncheque=0;
			}else{
				ncheque=u.ncheque.value;
			}
	
			if (u.folio.value==""){
				nnotacredito=0;
			}else{
				nnotacredito=u.folio.value;
			}
	
			if (parseFloat(u.nc.value.replace("$ ","").replace(/,/,""))==""){
				notacredito=0;
			}else{
				notacredito=parseFloat(u.nc.value.replace("$ ","").replace(/,/,""));
			}
			datos.efectivo = 			efectivo;
			datos.cheque = 				cheque;
			datos.ncheque = 			ncheque;
			datos.banco = 				u.banco.value;
			datos.nnotacredito = 		nnotacredito;
			datos.notacredito = 		notacredito;
			parent.actualizarFormaPago(datos);	
		}
	}
	
	function obtenerFolionotascredito(folio){
		u.folio.value = folio;
		consultaTexto("mostrarnc","liquidaciondemercancia_con.php?accion=14&folio="+folio+"&suerte="+Math.random());
	}
	
	function mostrarnc(datos){
		if (datos!=0){
			var obj = eval(convertirValoresJson(datos));
			u.nc.value= obj[0].importe;
			u.nc.value="$ "+numcredvar(u.nc.value.toLocaleString());
		}
	}
	
	function limpiarTodo(){
			u.efectivo.value		= u.total.value;
			u.folio.value			= "";
			u.nc.value	= "";
			u.cheque.value			= "";
			u.banco.value			= 0;
			u.ncheque.value			= "";
			u.acumulado.value		= u.total.value;
	}
	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Forma de Pago</title>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="puntovta.css" rel="stylesheet" type="text/css">


<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
@import url("file://///Dbserver/webpmm/web/guias/Tablas.css");
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style3 {	font-size: 9px;
	color: #464442;
}
.style5 {color: #FFFFFF ; font-size:9px}
-->
</style>
</head>
<body>
<form name="form1" method="post" action="">
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
    <table width="405" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
            <tr>
              <td width="401" class="FondoTabla">Datos Generales </td>
            </tr>
            <tr>
          <td height="147"><br><table width="472" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="Tablas">Total:</td>
                    <td colspan="4"><input onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" name="total" type="text" id="total" class="Tablas"  style="font:tahoma; font-size:9px; background:#FFFF99; text-align:right" readonly="true" value="<?=$total ?>" size="15"   ></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Efectivo:</td>
                    <td width="101"><input name="efectivo" type="text" class="Tablas"  style="font:tahoma; font-size:9px; text-align:right" onFocus="this.value=this.value.replace('$ ','').replace(/,/g,''); this.select()" onBlur="if(this.value!=''){this.value='$ '+numcredvar(this.value);calcular();}" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){validarEfectivo()}" value="<?=$efectivo ?>" size="15" maxlength="15"  ></td>
                    <td width="91">&nbsp;</td>
                    <td width="102" class="Tablas">Cheque:</td>
                    <td width="101"><input name="cheque" type="text" class="Tablas"  style="font:tahoma; font-size:9px; text-align:right;} text-align:right" onFocus="this.value=this.value.replace('$ ','').replace(/,/g,''); this.select()" onBlur="if(this.value!=''){this.value='$ '+numcredvar(this.value.replace('$ ',''));}else if(this.value==''){blockear(u.ncheque,true);}" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){if(parseFloat(this.value) < <?=$pagominimo?>){this.value = '<?=$pagominimo?>'; alerta3('El pago minimo con cheque es <?=$pagominimo?>','¡Atención!');} validarCheque();blockear(u.ncheque,false);}" value="<?=$cheque ?>" size="15" maxlength="15" ></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Nota Cr&eacute;dito: </td>
                    <td><input name="folio" type="text" class="Tablas" id="folio" style="width:70px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
                      <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarFolionotacredito.php?cliente<>0&funcion=obtenerFolionotascredito', 300, 300, 'ventana', 'Busqueda')" /></td>
                    <td><input onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" name="nc" type="text" id="nc" class="Tablas"  style="font:tahoma; font-size:9px; background:#FFFF99; text-align:right" readonly="true" value="<?=$nc ?>" size="15"   ></td>
                    <td class="Tablas">Banco:</td>
                    <td><select name="banco" id="banco" style="font:tahoma; font-size:9px; text-align:right;width:100px" onKeyDown="if(event.keyCode==13){validarbanco();}">
                      <option value="0" style="text-transform:none" >....</option>
                      <?            
                    $sl = "SELECT id,descripcion FROM catalogobanco";
                    $sq = mysql_query($sl,$l) or die($sl);
					
					while($row = mysql_fetch_row($sq))
					{ 
					?>
                      <option value="<?=$row[0]?>" <?=$row[descripcion] == $row[0] ? "selected" : "" ?>>
                      <?=$row[1]?>
                      </option>
                      <?
					}
					?>
                    </select></td>
                  </tr>
                  <tr>
                    <td width="77" class="Tablas">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="Tablas">#Cheque:</td>
                    <td><input onKeyPress="return Numeros(event)" class="Tablas" onBlur="calcular()" onKeyDown="" name="ncheque" type="text"  style="font:tahoma; font-size:9px; text-align:right; background:#FFFF99;text-align:right"  value="<?=$ncheque?>" size="15" ></td>
                  </tr>
                  <tr>
                  	<td>&nbsp;</td>
                  	<td>&nbsp;</td>
                  	<td>&nbsp;</td>
                  	<td class="Tablas">Acumulado:</td>
                  	<td><input onKeyPress="return Numeros(event)" class="Tablas" name="acumulado" type="text"  style="font:tahoma; font-size:9px; background:#FFFF99; text-align:right" readonly="true" value="<?=$acumulado ?>" size="15"  ></td>
                  </tr>
                  <tr>
                    <td height="45" colspan="5"><span class="Tablas">
                  <input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
                      </span>
                      <table width="164" border="0" align="right">
                        <tr>
                          <td width="77"><div id="limpiar" class="ebtn_nuevo" onClick="limpiarTodo()"></div></td>
                          <td width="77"><img src="../img/Boton_Aceptar.gif" alt="E" width="70" height="20" style="cursor:pointer" onClick="verificarTotales()"></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
              </table>
          <br></td>
            </tr>
        </table></td>
    </tr>
  </table>
</form>
</body>
</html>
<script>
	calcular();
</script>
<?
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
	}
//	}
?>