<? session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/

	require_once('../../Conectar.php');	
	$link=Conectarse('webpmm');
	$estado=$_GET['estado'];	
	$tipo=$_GET['tipo'];
	$municipio=$_GET['municipio'];
?>

<? if($tipo==1){?>

<table border="0" width="96%">

  <? 

	$get = mysql_query("SELECT CM.id AS id_municipio,UCASE(CM.descripcion) AS municipio_descripcion,  CE.id AS id_estado, UCASE(CE.descripcion) as estado_descripcion, UCASE(CPA.descripcion) as pais_descripcion  from catalogomunicipio AS CM   INNER JOIN catalogoestado AS CE   INNER JOIN catalogopais AS CPA   ON CM.estado=CE.id && CE.pais=CPA.defaul where CM.descripcion LIKE '$municipio%'",$link);		

	while($row=mysql_fetch_array($get)){

?>

  <tr>

    <td width="11%"><span onclick="window.parent.obtenerEstado('<?=$row[0];?>','<?=$row[1];?>','<?=$row[2];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[0]?></span></td>

    <td width="89%"><?=$row[1]?></td>

  </tr>

  <? }?>

</table>

<? } ?>





<? if($tipo==2){

//FILTRO POBLACION CATALOGOPOBLACION

?>

<table border="0" width="96%">

<? 

	$get = mysql_query("SELECT ES.id,UCASE(ES.descripcion) AS descripcion_estado,UCASE(PA.descripcion) AS descripcion_pais FROM catalogoestado  AS ES

INNER JOIN catalogopais AS PA ON ES.pais=PA.id where ES.descripcion LIKE  '$estado%'",$link);	

	while($row=mysql_fetch_array($get)){

?>

  <tr >

    <td width="11%"><span onclick="window.parent.obtenerMunicipio('<?=$row[0]?>','<?=$row[1]?>','<?=$row[2]?>','<?=$row[3]?>','<?=$row[4]?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[0]?></span></td>

    <td width="89%"><?=$row[1]?></td>

  </tr>

	<? }?>

</table>

<? } ?>

 

