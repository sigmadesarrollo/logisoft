<? 
		session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	if($_GET[accion]==1){
		header('Content-type: text/xml');
		include('../Conectar.php');
		$l=Conectarse('webpmm');
		
		if($_GET[campo]=="folio"){
			$where = " where folio = '$_GET[valor]' 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal = $_SESSION[IDSUCURSAL]" :"")." ";
		}else if($_GET[campo]=="nombre"){
			$where = " where ".(($_SESSION[IDSUCURSAL]!=1)? " sucursal = $_SESSION[IDSUCURSAL] AND " :"")." 
			CONCAT_WS(' ', nombre, apaterno, amaterno) like '$_GET[valor]%' ";
		}
		
		if($_GET[pestado]!="TODOS"){
			if($_GET[pestado] == "EN AUTORIZACION"){
				$estadopropuesta = " and estadopropuesta LIKE '$_GET[pestado]%' ";
			}else{
				$estadopropuesta = " and estadopropuesta = '$_GET[pestado]' ";
			}
		}
		
		$s = "SELECT folio, estadopropuesta as estado, CONCAT_WS(' ', nombre, apaterno, amaterno) AS nombrec, 
		DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha, UCASE(SUBSTRING(tipoautorizacion,17)) AS tipoautorizacion FROM propuestaconvenio
		$where $estadopropuesta ";
		//echo $s;
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$encontro = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>$encontro</encontro>";
			while($f = mysql_fetch_object($r)){
				$xml .= "
				<folio>$f->folio</folio>
				<estado>$f->estado</estado>
				<cliente>$f->nombrec</cliente>
				<fecha>$f->fecha</fecha>
				<tipoautorizacion>$f->tipoautorizacion</tipoautorizacion>";
			}
			$xml .= "</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}
		echo $xml;
	}else{
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script language="javascript" src="../javascript/funciones_tablas.js"></script>
<script language="javascript" src="../javascript/ajax.js"></script>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
</head>
<script>
	var valt1 			= agregar_una_tabla("tablafacturas", "idf_", 1, "Tablas└Tablas","");
	var vartabla 		= "";

	function rellenarLink(valor,devolver){
		return '<span onClick="parent.<?=$_GET[funcion]?>('+devolver+');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">'+valor+'</span>';
	}
	function limpiarTabla(){
		if(vartabla	== ""){
			vartabla = document.all.txtHint.innerHTML;
		}
		document.all.txtHint.innerHTML = vartabla;
		reiniciar_indice(valt1);
	}

	function pedirFacturas(tipo,valor2){
		switch(tipo){
			case 1:
				consulta("mostrarFactura", "buscarPropuestaConvenioGen.php?accion=1&pestado=<?=$_GET[pestado]?>&campo=folio&valor="+valor2+"&vran="+Math.random());
				break;
			case 2:
				consulta("mostrarFactura", "buscarPropuestaConvenioGen.php?accion=1&pestado=<?=$_GET[pestado]?>&campo=nombre&valor="+valor2+"&vran="+Math.random());
				break;
		}
	}
	function mostrarFactura(datos){
		limpiarTabla();
		var econ = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		
		if(econ>0){
			for(var i = 0; i<econ; i++){
				var folio 	= datos.getElementsByTagName('folio').item(i).firstChild.data;
				var estado	= datos.getElementsByTagName('estado').item(i).firstChild.data;
				var cliente = datos.getElementsByTagName('cliente').item(i).firstChild.data;
				var fecha 	= datos.getElementsByTagName('fecha').item(i).firstChild.data;
				var tipo 	= datos.getElementsByTagName('tipoautorizacion').item(i).firstChild.data;
				
				insertar_en_tabla(valt1,rellenarLink(folio,folio)+"└"+rellenarLink(cliente,folio)+"└"+rellenarLink(estado,folio)+"└"+rellenarLink(fecha,folio)+"└"+rellenarLink(tipo,folio));
			}
		}else{
			alerta("No se encontro ninguna propuesta","¡Atencion!","buscarfactura");
		}
	}
</script>
<body>
<form name="buscar" >
<table width="550"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
	<tr>
      <td width="13%" class="FondoTabla">Folio </td>
      <td colspan="2" class="FondoTabla">Prospecto</td>
<td colspan="2" class="FondoTabla">&nbsp;</td>
</tr>
    <tr>
      <td width="13%" class="FondoTabla"><input class="Tablas" name="buscarfactura" type="text" onKeyPress="if(event.keyCode==13){pedirFacturas(1,this.value)}" style="border:none; text-transform:uppercase; width:50px" /></td>
      <td width="45%" class="FondoTabla">
      <input class="Tablas" name="buscarcliente" type="text" onKeyPress="if(event.keyCode==13){pedirFacturas(2,this.value)}" style="border:none; text-transform:uppercase; width:200px" />      </td>
      <td width="14%" class="FondoTabla">Estado</td>
      <td width="12%" class="FondoTabla">Fecha</td>
      <td width="16%" class="FondoTabla">Autorizacion</td>
    </tr>
<tr>
      <td colspan="5" class="Tablas"><div id="txtHint" style="width:100%; height:300px; overflow: scroll;">
      <table width="100%" border="0" align="center" class="Tablas" id="tablafacturas" alagregar="" alborrar="">
      			<tr >
       <td width="64" class="Tablas" ></td>
            <td colspan="2" class="Tablas"></td>
<td colspan="2"></td>
          </tr>	
				<tr >
       <td width="64" class="Tablas" id="idf_0" ></td>
            <td width="221" class="Tablas">&nbsp;</td>
            <td width="95" class="Tablas">&nbsp;</td>
            <td width="87" class="Tablas"></td>
            <td width="57" class="Tablas"></td>
			</tr>	
      </table></div></td>
    </tr>
    <tr>
      <td colspan="5" align="center"></td>
    </tr>
  </table> 
</form>
</body>
</html>
<? } ?>