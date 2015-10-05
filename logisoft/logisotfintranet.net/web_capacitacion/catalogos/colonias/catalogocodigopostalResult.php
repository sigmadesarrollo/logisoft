<?
 		include('../../Conectar.php');	
		$link=Conectarse('webpmm');
		$cp=$_GET['cp'];
		$tipo=$_GET['tipo'];
		
?>
<link href="../../css/Tablas.css" rel="stylesheet" type="text/css" />


<? if($_GET[tipo]=='1'){
//MOSTRAR CODIGO POSTAL
?>	
<table width="95%" border="0" cellpadding="1" class="Tablas">

	<? $sql="SELECT CP.codigopostal, CC.descripcion AS colonia, CPO.descripcion AS poblacion, CM.descripcion AS municipio, CE.descripcion AS estado, P.descripcion AS pais
FROM catalogocodigopostal AS CP  INNER JOIN catalogocolonia AS CC ON CC.cp = CP.codigopostal INNER JOIN catalogopoblacion AS CPO ON CPO.id = CC.poblacion INNER JOIN catalogomunicipio AS CM ON CM.id = CPO.municipio INNER JOIN catalogoestado AS CE ON CE.id = CM.estado INNER JOIN catalogopais AS P ON P.id = CE.pais WHERE CP.codigopostal='$cp'"; 	$result=mysql_query($sql,$link); 
	while($row=mysql_fetch_array($result)){
	?>
  <tr>
    <td width="19%" class="Tablas"><?=$row[1]?></td>
    <td width="25%" class="Tablas"><?=$row[2]?></td>
    <td width="26%" class="Tablas"><?=$row[3]?></td>
    <td width="19%" class="Tablas"><?=$row[4]?></td>
    <td width="11%" class="Tablas"><?=$row[5]?></td>
  </tr>
  <? } ?>
</table>
<? } ?>







