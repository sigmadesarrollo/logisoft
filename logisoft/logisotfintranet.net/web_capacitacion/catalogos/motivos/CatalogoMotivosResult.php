<?
session_start();

	header('Content-type: text/xml');
	include('../../Conectar.php');
	$link=Conectarse('webpmm');
	$usuario=$_SESSION[NOMBREUSUARIO];
	$tipo=$_GET['tipo'];
	$id=$_GET['id'];
	
if($_GET['accion']==1){
		$sql="select * from catalogomotivos  where id='$id'";
		$result=mysql_query($sql,$link);
		$row=mysql_fetch_array($result);
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		if($row['id']!=""){
			$xml.="<codigo>".$row['id']."</codigo>";
		}else{
			$xml.="<codigo>0</codigo>";
		}
		$xml.="<descripcion>".cambio_texto($row['descripcion'])."</descripcion>";
		$xml.="<slclasificacion>".$row['clasificacion']."</slclasificacion>";
		$xml.="<slcolor>".$row['color']."</slcolor>";
		$xml.="<autorizacion>".$row['autorizacion']."</autorizacion>";
		$xml.="<accion>modificar</accion>";
		$xml.="</datos>
					</xml>";
		echo $xml;
}
?>
