<? 
		session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	if($_GET[accion]==1){	

		header('Content-type: text/xml');
		include('../Conectar.php');
		$l=Conectarse('webpmm');
		if($_GET[campo]=="guia"){
			$where = " having guia = '$_GET[valor]' ";
		}else if($_GET[campo]=="nombre"){
			$where = " having cliente like '$_GET[valor]%' ";
		}
		
		$s="SELECT guia,cliente, direccion,rfc FROM (
		SELECT gv.id AS guia,CONCAT(cc.nombre,'',cc.paterno,'',cc.materno)AS cliente,
		CONCAT(d.calle,' ',d.numero,'',d.colonia,' ',d.cp)AS direccion,cc.rfc FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON cs.id   = IF (gv.tipoflete=0,gv.idsucursalorigen,gv.idsucursaldestino)
		INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
		INNER JOIN direccion d ON cc.id=d.codigo AND d.origen='cl'
		$where
		UNION ALL
		SELECT gv.id AS guia,CONCAT(cc.nombre,'',cc.paterno,'',cc.materno)AS cliente,
		CONCAT(d.calle,' ',d.numero,'',d.colonia,' ',d.cp)AS direccion,cc.rfc FROM guiasempresariales gv
		INNER JOIN catalogosucursal cs ON cs.id   = IF (gv.tipoflete=0,gv.idsucursalorigen,gv.idsucursaldestino)
		INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
		INNER JOIN direccion d ON cc.id=d.codigo AND d.origen='cl'
		$where) guias 
		GROUP BY guia
		ORDER BY guia";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$encontro = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>$encontro</encontro>";
			while($f = mysql_fetch_object($r)){
				$xml .= "
				<guia>$f->guia</guia>
				<direccion>$f->direccion</direccion>
				<cliente>$f->cliente</cliente>
				<rfc>$f->rfc</rfc>";
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
		return '<span onClick="parent.<?=$_GET[funcion]?>(\''+devolver+'\');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">'+valor+'</span>';
	}
	
	function limpiarTabla(){
		if(vartabla	== ""){
			vartabla = document.all.txtHint.innerHTML;
		}
		document.all.txtHint.innerHTML = vartabla;
	}

	function pedirGuias(tipo,valor2){
		switch(tipo){
			case 1:
				consulta("mostrarGuias","buscarguianotacredito.php?accion=1&campo=guia&valor="+valor2+"&vran="+Math.random());
				break;
			case 2:
				consulta("mostrarGuias","buscarguianotacredito.php?accion=1&campo=nombre&valor="+valor2+"&vran="+Math.random());
				break;
		}
	}
	
	function mostrarGuias(datos){
		

		limpiarTabla();
		var econ = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		
		if(econ>0){
			for(var i = 0; i<econ; i++){
				var guia 	= datos.getElementsByTagName('guia').item(i).firstChild.data;
				var direccion	= datos.getElementsByTagName('direccion').item(i).firstChild.data;
				var cliente = datos.getElementsByTagName('cliente').item(i).firstChild.data;
				var rfc 	= datos.getElementsByTagName('rfc').item(i).firstChild.data;
				insertar_en_tabla(valt1,rellenarLink(guia,guia)+"└"+rellenarLink(cliente,guia)+"└"+rellenarLink(direccion,guia)+"└"+rellenarLink(rfc,guia));
			}
		}else{
			alerta("No se encontro ninguna guia","¡Atencion!","buscarguia");
		}
	}
	
</script>
<body>
<form name="buscar" >
<table width="650"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
	<tr>
      <td width="16%" class="FondoTabla">No.Guia</td>
      <td colspan="2" class="FondoTabla">Cliente</td>
<td class="FondoTabla">&nbsp;</td>
</tr>
    <tr>
      <td width="16%" class="FondoTabla"><input name="buscarguia" type="text" class="Tablas" id="buscarguia" style="border:none; text-transform:uppercase; width:100px" onKeyPress="if(event.keyCode==13){pedirGuias(1,this.value)}" /></td>
      <td width="48%" class="FondoTabla"><input class="Tablas" name="buscarcliente" type="text" onKeyPress="if(event.keyCode==13){pedirGuias(2,this.value)}" style="border:none; text-transform:uppercase; width:250px" /></td>
      <td width="24%" class="FondoTabla">Dirección</td>
      <td width="12%" class="FondoTabla">Rfc</td>
    </tr>
<tr>
      <td colspan="4" class="Tablas"><div id="txtHint" style="width:100%; height:300px; overflow: scroll;">
      <table width="100%" border="0" align="center" class="Tablas" id="tablafacturas" alagregar="" alborrar="">
      			<tr >
       <td width="95" class="Tablas" ></td>
            <td colspan="2" class="Tablas"></td>
<td width="109"></td>
          </tr>	
		  <tr >
       		<td width="95" class="Tablas" id="idf_0" ></td>
            <td width="250" class="Tablas">&nbsp;</td>
            <td width="174" class="Tablas">&nbsp;</td>
            <td width="109" class="Tablas"></td>
          </tr>	
      </table></div></td>
    </tr>
    <tr>
      <td colspan="4" align="center"></td>
    </tr>
  </table> 
</form>
</body>
</html>
<? } ?>