<? session_start();


	/*if(!$_SESSION[IDUSUARIO]!=""){


		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");


	}*/





	include('../Conectar.php');


	$link=Conectarse('webpmm');


	$get=@mysql_query('SELECT COUNT(*) FROM devolucionguia '.(($_SESSION[IDSUCURSAL]==1)?'':" WHERE sucursal = $_SESSION[IDSUCURSAL]"));


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


      <td width="18%" class="FondoTabla">Folio</td>


      <td width="37%" class="FondoTabla">Guia</td>


      <td width="45%" class="FondoTabla">&nbsp;</td>


	  


    </tr>


    <tr >


      <td colspan="3" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" cellspacing="0" class="Tablas">


          <?		 


		$get =@mysql_query('SELECT folio, guia FROM devolucionguia 
						   '.(
							  ($_SESSION[IDSUCURSAL]==1)?"":" WHERE sucursal = $_SESSION[IDSUCURSAL] "
						   ).'
						   limit '.$st.','.$pp,$link);		


			while($row=@mysql_fetch_array($get)){


			?>


				<tr >


       <td width="82" class="Tablas" >


<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[0];?></span></td>


            <td width="344" class="Tablas"><?=$row[1]; ?></td>


			<td width="56"></td>


          </tr>	


		<?	}


		


		?>





      </table>


      </td>


    </tr>


    <tr>


      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarDevolucionGuiasGen.php?funcion=$_GET[funcion]&st="); ?></font></td>


    </tr>


  </table> 


</form>


</body>


</html>


<? //} ?>