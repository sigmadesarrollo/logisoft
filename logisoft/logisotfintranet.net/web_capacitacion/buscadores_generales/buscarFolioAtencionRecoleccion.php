<? session_start();


	if(!$_SESSION[IDUSUARIO]!=""){


		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");


	}


	require_once('../Conectar.php');


	$link = Conectarse('webpmm');	


	$get = @mysql_query("SELECT COUNT(*) FROM recoleccion


			WHERE sucursal=$_GET[sucursal] AND (realizo='NO' OR realizo IS NULL)");


	


	$total = mysql_result($get,0);


	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }


	$pp = 20;


?>


<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />


<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />


<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


  <tr> 


    <td width="15%" class="FondoTabla">Folio</td>


    <td width="85%" class="FondoTabla">Fecha Recolección</td>


  </tr>


  <tr> 


    <td colspan="5" class="Tablas" height="300px" valign="top"> 


        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">


          <?			


	$sql = @mysql_query("SELECT folio, DATE_FORMAT(fecharecoleccion,'%d/%m/%Y') AS fecha


		FROM recoleccion 


		WHERE sucursal=$_GET[sucursal] AND (realizo='NO' OR realizo IS NULL) limit $st,$pp",$link);		  	


		


		while($row=@mysql_fetch_array($sql)){


	?>


          <tr> 


            <td width="43" class="Tablas"><span style="cursor:pointer;color:#0000FF" onclick="parent.<?=$_GET['funcion']?>('<?=$row[0]?>'); parent.VentanaModal.cerrar();"> 


              <?= $row[0];?>


              </span></td>


            <td width="205" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row[1]?>" readonly="true" style="width:200px; border:none; cursor:default"></td>


            <td width="42"></td>


          </tr>


      <? } ?>


        </table>


        <p class="Tablas">&nbsp;</p>


   </td>


  </tr>


  <tr> 


    <td colspan="5" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarFolioAtencionRecoleccion.php?sucursal=$_GET[sucursal]&st="); ?></font></td>


  </tr>


</table>


