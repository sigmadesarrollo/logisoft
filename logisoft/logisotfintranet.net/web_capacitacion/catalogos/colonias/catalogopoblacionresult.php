<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if ( isset ( $_SESSION['gvalidar'] )!=100 ){
	 echo "<script language='javascript' type='text/javascript'>
						document.location.href='../../../index.php';
					</script>";
	}else{*/
		header('Content-type: text/xml');
require_once('../../Conectar.php');	 $link=Conectarse('webpmm');
$codigo=$_GET['id']; $descripcion=$_GET['descripcion'];
$tipo=$_GET['tipo']; $poblacion=$_GET['poblacion']; 
$municipio=$_GET['municipio'];

if($tipo==1){
	//MOSTRAR EN CATALOGOPOBLACION DESCRIPCION,MUNICIPIO+ESTADO+PAIS
	$sql="select CP.id,UCASE(CP.descripcion),CM.id as municipio,UCASE(CM.descripcion) as descripcionmunicipio, UCASE(CE.descripcion) as estado,UCASE(CPA.descripcion) as pais 
from catalogopoblacion CP  inner join catalogomunicipio CM ON CP.municipio=CM.id 
inner join catalogoestado CE ON CM.estado=CE.id  inner join catalogopais CPA ON CE.pais=CPA.id  WHERE CP.id='$codigo' ";
	$get=mysql_query($sql,$link);
	if(mysql_num_rows($get)>0){
		$cant = mysql_num_rows($get);
		$row=mysql_fetch_array($get);
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<codigo>".$row['0']."</codigo>";
		$xml.="<descripcion>".$row['1']."</descripcion>";
		$xml.="<municipio>".$row['2']."</municipio>";
		$xml.="<descripcionmunicipio>".$row['3']."</descripcionmunicipio>";
		$xml.="<estado>".$row['4']."</estado>";
		$xml.="<pais>".$row['5']."</pais>";
		$xml.="<accion>modificar</accion>";
		$xml.="<encontro>$cant</encontro>";
		$xml.="</datos>
					</xml>";
	}else{
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
		<datos>
		<codigo>$codigo</codigo>
		<descripcion>$poblacion</descripcion>
		<encontro>0</encontro>
		</datos>
		</xml>";
	}
	echo $xml;
	
}

?>






<? if($tipo==2){
//FILTRO POBLACION CATALOGOPOBLACION
?>
<table border="0" width="96%">
<? 
	$get = mysql_query("select * from catalogopoblacion where descripcion LIKE '$poblacion%'",$link);	
	while($row=mysql_fetch_array($get)){
?>
  <tr style="cursor:pointer" onclick="parent.obtener('<?=$row['id'];?>','<?=$row['descripcion'] ?>'); parent.VentanaModal.cerrar();">
    <td width="11%"><?=$row[0]?></td>
    <td width="89%"><?=$row[1]?></td>
  </tr>
	<? }?>
</table>
<? } ?>


<? if($tipo==3){?>
<table border="0" width="96%">
  <? 
	$get = mysql_query("SELECT CM.id AS id_municipio,UCASE(CM.descripcion) AS municipio_descripcion,  CE.id AS id_estado, UCASE(CE.descripcion) as estado_descripcion,
UCASE(CPA.descripcion) as pais_descripcion  from catalogomunicipio AS CM   INNER JOIN catalogoestado AS CE   INNER JOIN catalogopais AS CPA   ON CM.estado=CE.id && CE.pais=CPA.defaul where CM.descripcion LIKE '$municipio%'",$link);	
	while($row=mysql_fetch_array($get)){
?>
  <tr style="cursor:pointer" onclick="parent.obtenerMunicipio('<?=$row['0'];?>','<?=$row['1'] ?>','<?=$row['3'] ?>','<?=$row['4'] ?>'); parent.VentanaModal.cerrar();">
    <td width="11%"><?=$row[0]?></td>
    <td width="89%"><?=$row[1]?></td>
  </tr>
  <? }?>
</table>
<? } ?>