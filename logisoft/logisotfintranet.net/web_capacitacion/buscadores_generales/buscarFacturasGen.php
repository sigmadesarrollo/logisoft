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
			$criterio = " AND f.folio = '$_GET[valor]' ";
		}else if($_GET[campo]=="nombre"){
			$criterio = " AND CONCAT_WS(' ', f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente) like '$_GET[valor]%' ";

		}else if($_GET[campo]=="cliente"){//Modificacion
			$criterio = " AND f.cliente = '$_GET[valor]' ";
		}else if($_SESSION[IDSUCURSAL]!=1){
			$criterio = " AND f.idsucursal = '$_SESSION[IDSUCURSAL]' ";
		}
		
		if($_GET[todas]=="SI"){
			$s = "SELECT f.folio, IF(f.estadocobranza='C','PAGADA',f.facturaestado) AS estado, 
			CONCAT_WS(' ', f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente) AS nombrec, 
			DATE_FORMAT(f.fecha, '%d/%m/%Y') AS fecha FROM facturacion f
			where 1=1 $criterio ".(($_SESSION[IDSUCURSAL]==1)?"":" AND f.idsucursal = $_SESSION[IDSUCURSAL]");
		}else{		
			if($_GET[campo]=="cliente"){
				
				if($_GET[foliofactura]!="")
					$foliobusq = " WHERE folio='$_GET[foliofactura]' ";
				
				$s = "SELECT folio,estado,nombrec,fecha FROM(
					(SELECT f.folio, IF(f.estadocobranza='C','PAGADA',f.facturaestado) AS estado, 
					CONCAT_WS(' ', f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente) AS nombrec, 
					DATE_FORMAT(f.fecha, '%d/%m/%Y') AS fecha
					FROM guiasventanilla gv
					INNER JOIN pagoguias pg ON gv.id=pg.guia
					INNER JOIN facturacion f ON gv.factura=f.folio
					INNER JOIN catalogocliente cc ON f.cliente=cc.id
					WHERE pg.pagado='N' AND f.cliente='".$_GET[valor]."'  AND f.facturaestado = 'GUARDADO'
					and f.estadocobranza <> 'C'
					".(($_SESSION[IDSUCURSAL]==1)?"":" AND f.idsucursal = $_SESSION[IDSUCURSAL]")."
					GROUP BY gv.id)
				UNION 
					(SELECT f.folio, IF(f.estadocobranza='C','PAGADA',f.facturaestado) AS estado, 
					CONCAT_WS(' ', f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente) AS nombrec, 
					DATE_FORMAT(f.fecha, '%d/%m/%Y') AS fecha
					FROM guiasempresariales ge
					INNER JOIN pagoguias pg ON ge.id=pg.guia
					INNER JOIN facturacion f ON ge.factura=f.folio
					INNER JOIN catalogocliente cc ON f.cliente=cc.id
					WHERE pg.pagado='N' AND f.cliente='".$_GET[valor]."'  AND f.facturaestado = 'GUARDADO' 
					".(($_SESSION[IDSUCURSAL]==1)?"":" AND f.idsucursal = $_SESSION[IDSUCURSAL]")."
					and f.estadocobranza <> 'C'
					GROUP BY ge.id)
				UNION
					(SELECT f.folio, IF(f.estadocobranza='C','PAGADA',f.facturaestado) AS estado, 
					CONCAT_WS(' ', f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente) AS nombrec, 
					DATE_FORMAT(f.fecha, '%d/%m/%Y') AS fecha
					FROM solicitudguiasempresariales sg
					INNER JOIN facturacion f ON sg.factura=f.folio
					INNER JOIN catalogocliente cc ON f.cliente=cc.id
					WHERE f.estadocobranza<>'C' AND f.cliente='".$_GET[valor]."'  AND f.facturaestado = 'GUARDADO' 
					".(($_SESSION[IDSUCURSAL]==1)?"":" AND f.idsucursal = $_SESSION[IDSUCURSAL]")."
					and f.estadocobranza <> 'C'
					GROUP BY sg.id)
				UNION
					(SELECT f.folio, IF(f.estadocobranza='C','PAGADA',f.facturaestado) AS estado, 
					CONCAT_WS(' ', f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente) AS nombrec, 
					DATE_FORMAT(f.fecha, '%d/%m/%Y') AS fecha
					FROM facturacion f
					INNER JOIN catalogocliente cc ON f.cliente=cc.id
					WHERE f.estadocobranza<>'C' AND f.cliente='".$_GET[valor]."'  AND f.facturaestado = 'GUARDADO'  
					AND f.otrosmontofacturar>0
					".(($_SESSION[IDSUCURSAL]==1)?"":" AND f.idsucursal = $_SESSION[IDSUCURSAL]")."
					and f.estadocobranza <> 'C'
					GROUP BY f.folio)
				) AS t1
				$foliobusq";
			}else{
				$s="SELECT folio,estado,nombrec,fecha FROM(		
				SELECT f.folio, IF(f.estadocobranza='C','PAGADA',f.facturaestado) AS estado, 
				CONCAT_WS(' ', f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente) AS nombrec, 
				DATE_FORMAT(f.fecha, '%d/%m/%Y') AS fecha FROM facturacion f
				INNER JOIN guiasventanilla gv ON f.folio=gv.factura
				INNER JOIN pagoguias pg ON gv.id=pg.guia
				WHERE f.facturaestado<>'CANCELADA' AND pg.pagado='N' and f.estadocobranza <> 'C'
				".(($_SESSION[IDSUCURSAL]==1)?"":" AND f.idsucursal = $_SESSION[IDSUCURSAL]")."
				$criterio
				GROUP BY f.folio
				UNION
				SELECT f.folio, IF(f.estadocobranza='C','PAGADA',f.facturaestado) AS estado, 
				CONCAT_WS(' ', f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente) AS nombrec, 
				DATE_FORMAT(f.fecha, '%d/%m/%Y') AS fecha FROM facturacion f
				INNER JOIN guiasempresariales gv ON f.folio=gv.factura
				INNER JOIN pagoguias pg ON gv.id=pg.guia
				WHERE f.facturaestado<>'CANCELADA' AND pg.pagado='N' and f.estadocobranza <> 'C' 
				".(($_SESSION[IDSUCURSAL]==1)?"":" AND f.idsucursal = $_SESSION[IDSUCURSAL]")."
				$criterio
				GROUP BY f.folio
				)Tabla ORDER BY fecha";
			}
		}
//die($s);

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
				<cliente>".cambio_texto($f->nombrec)."</cliente>
				<fecha>".cambio_texto($f->fecha)."</fecha>";
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
		return '<span onClick="parent.<?=$_GET[funcion]?>(\''+devolver+'\');try{parent.VentanaModal.cerrar();}catch(e){parent.mens.cerrar();}" style="cursor: pointer; color:#0000FF">'+valor+'</span>';
	}

	window.onload=function(){
		if('<?=$_GET[cliente]?>'!=""){
			pedirFacturas(3);
		}
	}

	function limpiarTabla(){
		if(vartabla	== ""){
			vartabla = document.all.txtHint.innerHTML;
		}
		reiniciar_indice(valt1);
		document.all.txtHint.innerHTML = vartabla;
	}

	function pedirFacturas(tipo,valor2){
		if(document.all.modulo.value=="abonocliente"){
			if(valor2!=null && valor2!="")
				valor2 = "&foliofactura="+valor2;
			consulta("mostrarFactura", "buscarFacturasGen.php?accion=1<?=(($_GET[todas])?"&todas=SI":"&todas=NO");?>&campo=cliente&valor=<?=$_GET[cliente]?>"+valor2+"&vran="+Math.random());
		}else{
			switch(tipo){
				case 1:
					consulta("mostrarFactura", "buscarFacturasGen.php?accion=1<?=(($_GET[todas])?"&todas=SI":"&todas=NO");?>&campo=folio&valor="+valor2+"&vran="+Math.random());
					break;
				case 2:
					consulta("mostrarFactura", "buscarFacturasGen.php?accion=1<?=(($_GET[todas])?"&todas=SI":"&todas=NO");?>&campo=nombre&valor="+valor2+"&vran="+Math.random());
					break;
			}
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
				
				insertar_en_tabla(valt1,rellenarLink(folio,folio)+"└"+rellenarLink(cliente,folio)+"└"+rellenarLink(estado,folio)+"└"+rellenarLink(fecha,folio));
			}
		}else{
			alerta("No se encontro ninguna factura","¡Atencion!","buscarfactura");
		}
	}
</script>
<body>

<form name="buscar" ><input type="hidden" name="modulo" value="<?=$_GET[modulo]?>">
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
	<tr>
      <td width="13%" class="FondoTabla">Factura</td>
      <td colspan="2" class="FondoTabla">Cliente</td>
<td class="FondoTabla">&nbsp;</td>
</tr>
    <tr>
      <td width="13%" class="FondoTabla"><input class="Tablas" name="buscarfactura" type="text" onKeyPress="if(event.keyCode==13){pedirFacturas(1,this.value)}" style="border:none; text-transform:uppercase; width:50px" /></td>
      <td width="55%" class="FondoTabla">
      <input class="Tablas" name="buscarcliente" type="text" onKeyPress="if(event.keyCode==13){pedirFacturas(2,this.value)}" style="border:none; text-transform:uppercase; width:250px" />      </td>
      <td width="19%" class="FondoTabla">Estado</td>
      <td width="13%" class="FondoTabla">Fecha</td>
    </tr>
<tr>
      <td colspan="4" style="border:5px;"><div id="txtHint" style="width:100%; height:300px; overflow: scroll; float:left">
      <table width="100%" border="0" align="center" class="Tablas" id="tablafacturas" alagregar="" alborrar="">
        <tr >
            <td height="1px" width="100" ></td>
            <td width="226"></td>
            <td width="83" ></td>
            <td width="81"></td>
        </tr>	
        <tr >
	       <td class="Tablas" id="idf_0" ></td>
            <td class="Tablas"></td>
            <td class="Tablas"></td>
            <td class="Tablas"></td>
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