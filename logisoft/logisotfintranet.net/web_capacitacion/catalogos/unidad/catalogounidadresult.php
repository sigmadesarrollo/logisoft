<? //session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	header('Content-type: text/xml');
	include('../../Conectar.php');	
	$link=Conectarse('webpmm');		
	$tipo=$_GET['tipo'];
	$idunidad=$_GET['unidad'];
	$economico=$_GET['economico'];	
if($tipo=="unidad"){
		$sql=mysql_query("SELECT cu.numeroeconomico, cu.tipounidad, ctu.descripcion,cu.tarjetacirculacion, cu.cvolumen, cu.ckilos,cu.tiporuta,cu.sucursal, cu.celular,cu.placas,cu.fueradeservicio,cu.desservicio FROM catalogounidad cu INNER JOIN catalogotipounidad ctu ON cu.tipounidad=ctu.id WHERE cu.numeroeconomico='$economico'",$link);			
	if(mysql_num_rows($sql)>0){
		$cant = mysql_num_rows($sql);
		$row=mysql_fetch_array($sql);		
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<numeroeconomico>".cambio_texto($row['numeroeconomico'])."</numeroeconomico>";
		$xml.="<unidad>".cambio_texto($row['tipounidad'])."</unidad>";
		$xml.="<descripcion>".cambio_texto($row['descripcion'])."</descripcion>";
		$xml.="<tarjetacirculacion>".cambio_texto($row['tarjetacirculacion'])."</tarjetacirculacion>";
		$xml.="<cvolumen>".cambio_texto($row['cvolumen'])."</cvolumen>";
		$xml.="<ckilos>".cambio_texto($row['ckilos'])."</ckilos>";
		$xml.="<tiporuta>".cambio_texto($row['tiporuta'])."</tiporuta>";
		$xml.="<sucursal>".cambio_texto($row['sucursal'])."</sucursal>";
		$xml.="<celular>".cambio_texto($row['celular'])."</celular>";
		$xml.="<placas>".cambio_texto($row['placas'])."</placas>";
		$xml.="<servicio>".$row['fueradeservicio']."</servicio>";
		$xml.="<des_servicio>".$row['desservicio']."</des_servicio>";
		$xml.="<accion>modificar</accion>";
		$xml .= "<encontro>$cant</encontro>";
		$xml.="</datos>
					</xml>";
	}else{
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		
	}
		echo $xml;
		
}

if($tipo=="tipounidad1"){
	$sql=mysql_query("SELECT * FROM catalogotipounidad WHERE id='$idunidad'",$link);
	if(mysql_num_rows($sql)>0){
		$cant = mysql_num_rows($sql);
		$row=mysql_fetch_array($sql);
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<unidad>".$row['id']."</unidad>";
		$xml.="<descripcion>".$row['descripcion']."</descripcion>";
		$xml .= "<encontro>$cant</encontro>";
		$xml.="</datos>
					</xml>";
	}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
	}
		echo $xml;
}?>

