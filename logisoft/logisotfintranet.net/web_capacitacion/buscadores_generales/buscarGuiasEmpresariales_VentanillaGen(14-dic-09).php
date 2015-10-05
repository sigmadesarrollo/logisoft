<? session_start();


	include('../Conectar.php');


	$link=Conectarse('webpmm');


	


	if($_GET[tipo]==1){


		//Traspasar mercancia.php // Almacenes


		$condicion="where estado='ALMACEN DESTINO' and ocurre=1 and idsucursaldestino = $_SESSION[IDSUCURSAL]";


	}


	if($_GET[tipo]==2){


		//Traspasar mercancia.php // Almacenes


		$condicion="where ubicacion<>'TRASPASO PENDIENTE'";


	}


	$get=@mysql_query('SELECT (SELECT COUNT(*)  FROM guiasventanilla '.$condicion.' )


+(SELECT COUNT(*) FROM guiasempresariales '.$condicion.' )');


	$total =@mysql_result($get,0);


	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }


	$pp = 20;


?>


<html xmlns="http://www.w3.org/1999/xhtml">


<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />


<script src="select.js"></script>


<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />


<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />


<title>Documento sin t&iacute;tulo</title>


</head>





<body>


<form name="buscar" >


<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


    <tr>


      <td width="27%" class="FondoTabla">Folio</td>


      <td width="73%" class="FondoTabla">Fecha</td>


    </tr>


    <tr>


      <td height="300px" colspan="2" valign="top" class="Tablas"><table width="100%" border="0" align="center" class="Tablas">


          <?		  


		$get =@mysql_query('(SELECT id,DATE_FORMAT(fecha, "%d/%m/%Y") AS fecha FROM guiasventanilla '.$condicion.' )


UNION (SELECT id,DATE_FORMAT(fecha, "%d/%m/%Y") AS fecha FROM guiasempresariales '.$condicion.' ) limit '.$st.','.$pp,$link);		


			while($row=@mysql_fetch_array($get)){


			?>


				<tr >


       <td width="128" class="Tablas" >


<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[id];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[id];?></span></td>


            <td width="303" class="Tablas"><?=$row[fecha]; ?></td>


            <td width="51"></td>


          </tr>	


		<?	}


		


		?>





      </table></td>


    </tr>


    <tr>


      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarGuiasEmpresariales_VentanillaGen.php?funcion=$_GET[funcion]&tipo=$_GET[tipo]&st="); ?></font></td>


    </tr>


  </table> 


</form>


</body>


</html>


<? //} ?>