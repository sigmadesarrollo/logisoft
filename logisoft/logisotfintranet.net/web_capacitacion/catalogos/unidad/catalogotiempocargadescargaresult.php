<? 
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	include('../../Conectar.php');	
	$link=Conectarse('webpmm');
	$unidad=$_GET['unidad'];
	$descripcion=$_GET['descripcion'];
	
	
	
	$sql=mysql_query("SELECT cc.unidad,ct.descripcion,cc.tcarga,cc.tdescarga FROM catalogocargadescarga cc INNER JOIN catalogotipounidad ct ON cc.unidad=ct.id  WHERE cc.unidad='$unidad'",$link);
	
	if(mysql_num_rows($sql)>0){
		$cant = mysql_num_rows($sql);
		$row=mysql_fetch_array($sql);
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<codigo>".$row['0']."</codigo>";
		$xml.="<descripcion>".$row['1']."</descripcion>";
		$xml.="<tcarga>".$row['2']."</tcarga>";
		$xml.="<tdescarga>".$row['3']."</tdescarga>";
		$xml.="<accion>modificar</accion>";
		$xml.="<encontro>$cant</encontro>";
		$xml.="</datos>
					</xml>";
	}else{
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
		<datos>
		<codigo>$unidad</codigo>;
		<descripcion>$descripcion</descripcion>
		<encontro>0</encontro>
		</datos>
		</xml>";
	}
	
	echo  $xml;
	

?>
