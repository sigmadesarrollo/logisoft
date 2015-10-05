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


			$where = " where folio = '$_GET[valor]' ";


		}else if($_GET[campo]=="nombre"){


			$where = " where CONCAT_WS(' ', nombrecliente, apellidopaternocliente, apellidomaternocliente) like '$_GET[valor]%' ";


		}


		


		$s = "SELECT folio, facturaestado as estado, CONCAT_WS(' ', nombrecliente, apellidopaternocliente, apellidomaternocliente) AS nombrec, 


		DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha FROM facturacion


		$where  ";


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


				<fecha>$f->fecha</fecha>";


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


		return '<span onClick="parent.<?=$_GET[funcion]?>(\''+devolver+'\');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">'+valor+'</span>';


	}


	function limpiarTabla(){


		if(vartabla	== ""){


			vartabla = document.all.txtHint.innerHTML;


		}


		document.all.txtHint.innerHTML = vartabla;


	}





	function pedirFacturas(tipo,valor2){


		switch(tipo){


			case 1:


				consulta("mostrarFactura", "buscarFacturasGen.php?accion=1&campo=folio&valor="+valor2+"&vran="+Math.random());


				break;


			case 2:


				consulta("mostrarFactura", "buscarFacturasGen.php?accion=1&campo=nombre&valor="+valor2+"&vran="+Math.random());


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


				


				insertar_en_tabla(valt1,rellenarLink(folio,folio)+"└"+rellenarLink(cliente,folio)+"└"+rellenarLink(estado,folio)+"└"+rellenarLink(fecha,folio));


			}


		}else{


			alerta("No se encontro ninguna factura","¡Atencion!","buscarfactura");


		}


	}


</script>


<body>


<form name="buscar" >


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


      <td colspan="4"><div id="txtHint" style="width:100%; height:300px; overflow: scroll;">


      <table width="100%" border="0" align="center" class="Tablas" id="tablafacturas" alagregar="" alborrar="">


      			<tr >


       <td width="58" class="Tablas" ></td>


            <td colspan="2" class="Tablas"></td>


<td width="81"></td>


          </tr>	


				<tr >


       <td width="58" class="Tablas" id="idf_0" ></td>


            <td width="256" class="Tablas">&nbsp;</td>


            <td width="83" class="Tablas">&nbsp;</td>


            <td width="81" class="Tablas"></td>


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