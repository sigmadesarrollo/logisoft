<? 


	session_start();


	if(!$_SESSION[IDUSUARIO]!=""){


		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");


	}


	include('../Conectar.php');


	$l=Conectarse('webpmm');


	


	if($_GET[accion]==1){


		


		if($_GET[campo]=="folio"){


			$where = " where folio = '$_GET[valor]' ";


		}else if($_GET[campo]=="nombre"){


			$where = " where CONCAT_WS(' ', nombrecliente, apellidopaternocliente, apellidomaternocliente) like '$_GET[valor]' ";


		}


		


		$s = "SELECT folio, CONCAT_WS(' ', nombrecliente, apellidopaternocliente, apellidomaternocliente) AS nombrec, 


		DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha FROM facturacion


		$where  ";


	}else{


?>


<html xmlns="http://www.w3.org/1999/xhtml">


<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />


<script language="javascript" src="../javascript/funciones_tablas.js"></script>


<script language="javascript" src="../javascript/ajax.js"></script>


<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />


<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />


<title>Documento sin t&iacute;tulo</title>


</head>


<script>


	function pedirFacturas(valor){


		switch(valor){


			case 1:


				consulta("mostrarFactura", "buscarFacturasGen");


				break;


			case 2:


				break;


		}


	}


	


	function mostrarFactura(datos){


		


	}


</script>


<body>


<form name="buscar" >


<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


	<tr>


      <td width="13%" class="FondoTabla">Factura</td>


      <td class="FondoTabla">Cliente</td>


      <td class="FondoTabla">&nbsp;</td>


</tr>


    <tr>


      <td width="13%" class="FondoTabla"><input class="Tablas" name="buscarfactura" type="text" onKeyPress="ObtenerConsulta(event,this.name,this.value)" style="border:none; text-transform:uppercase; width:50px" /></td>


      <td width="74%" class="FondoTabla">


      <input class="Tablas" name="buscarcliente" type="text" onKeyPress="ObtenerConsulta(event,this.name,this.value)" style="border:none; text-transform:uppercase; width:250px" />      </td>


      <td width="13%" class="FondoTabla">Fecha</td>


    </tr>


<tr>


      <td colspan="3" class="Tablas"><div id="txtHint" style="width:100%; height:300px; overflow: scroll;">


      <table width="100%" border="0" align="center" class="Tablas">


				<tr >


       <td width="58" class="Tablas" >


<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[0];?></span></td>


            <td width="343" class="Tablas">&nbsp;</td>


            <td width="81"></td>


          </tr>	


      </table></div></td>


    </tr>


    <tr>


      <td colspan="3" align="center"></td>


    </tr>


  </table> 


</form>


</body>


</html>


<? } ?>