<?
	session_start();
	header('Content-type: text/xml');
	include('../../Conectar.php');
	$link=Conectarse('webpmm');
	$usuario  = $_SESSION[NOMBREUSUARIO];
	$id       = $_GET['id'];
	$idfila   = $_GET['idfila'];
	$rtipo    = $_GET['rtipo'];
	$semana   = $_GET['semana'];
	$sucursal = $_GET['sucursal'];
	$sucursalb= $_GET['sucursalb'];
	$llegada  = $_GET['llegada'];
	$descarga = $_GET['descarga'];
	$carga    = $_GET['carga'];
	$salida   = $_GET['salida'];
	$ttss     = $_GET['ttss'];
	$tipo     = $_GET['tipo'];
	$fecha    = $_GET['fecha'];
	$ruta	  = $_GET['ruta'];
	$transbordo=$_GET['transbordo'];
	$sucursalestransbordo=substr($_GET['hidensucursal2'],0,-1);
	$idusuario=$_SESSION['IDUSUARIO'];
	
if($tipo==1){
	//INSERTA LOS DATOS EN LA GRID TEMPORAL "catalogorutadetalletmp"
		$sql  = "INSERT INTO catalogorutadetalletmp (id, tipo, diasalidas, sucursal, horasllegada, tiempodescarga, tiempocarga, horasalida, trayectosucursal,transbordo,sucursalestransbordo, idusuario,usuario, fecha) VALUES (null,'$rtipo','$semana','$sucursal', '$llegada', '$descarga', '$carga', '$salida', '$ttss','$transbordo','$sucursalestransbordo','$idusuario', '$usuario', '$fecha')";
		$rest = mysql_query($sql,$link) ;
	 	$id   = mysql_insert_id();
		$xml  = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.= "<id>$id</id>";
		$xml.= "<rtipo>$rtipo</rtipo>";
		if($semana!=""){
		$xml.= "<semana>$semana</semana>";
		}else{
		$xml.= "<semana>0</semana>";
		}
		$xml.= "<sucursal>$sucursal</sucursal>";
		$xml.= "<sucursalb>$sucursalb</sucursalb>";
		if($llegada!=""){
		$xml.= "<llegada>$llegada</llegada>";
		}else{
		$xml.= "<llegada>0</llegada>";
		}
		$xml.= "<descarga>$descarga</descarga>";
		$xml.= "<carga>$carga</carga>";
		if($salida!=""){
		$xml.= "<salida>$salida</salida>";
		}else{
		$xml.= "<salida>0</salida>";
		}
		$xml.= "<ttss>$ttss</ttss>";
		$xml.= "<transbordo>".$transbordo."</transbordo>";
		$xml.= "</datos>
					</xml>";
		echo $xml;
	
}else if($tipo==2){
	//MUSTRAR LOS DATOS CAPTURADOS EN LA GRID TEMPORAL "catalogorutadetalletmp"
	$sql = "SELECT RU.id,RU.tipo,RU.diasalidas,RU.sucursal,SU.prefijo,RU.horasllegada,RU.tiempodescarga,RU.tiempocarga,RU.horasalida,
RU.trayectosucursal,RU.transbordo,RU.sucursalestransbordo FROM catalogorutadetalletmp  AS RU INNER JOIN catalogosucursal AS SU ON SU.id=RU.sucursal
WHERE RU.id='$id'"; 
	$rest = mysql_query($sql,$link);
	$row = mysql_fetch_array($rest);
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.= "<id>$row[id]</id>";
		$xml.= "<idfila>$idfila</idfila>";
		$xml.= "<rtipo>$row[tipo]</rtipo>";
		if($row[2]!=""){
		$xml.= "<semana>$row[diasalidas]</semana>";
		}else{
		$xml.= "<semana>0</semana>";
		}
		$xml.= "<sucursal>$row[sucursal]</sucursal>";
		$xml.= "<sucursalb>$row[prefijo]</sucursalb>";
		$xml.= "<llegada>$row[horasllegada]</llegada>";
		$xml.= "<descarga>$row[tiempodescarga]</descarga>";
		$xml.= "<carga>$row[tiempocarga]</carga>";
		$xml.= "<salida>$row[horasalida]</salida>";
		$xml.= "<ttss>$row[trayectosucursal]</ttss>";
		$xml.= "<transbordo>$row[transbordo]</transbordo>";
		$xml.= "<hidensucursal2>$row[sucursalestransbordo]</hidensucursal2>";
		$xml.= "</datos>
					</xml>";
		echo $xml;
	
}else if($tipo==3){
	//MODIFICAR EN LA TABLA TAMPORAL DE LA GRID TEMPORAL "catalogorutadetalletmp"
	$sqlupdate = "UPDATE catalogorutadetalletmp SET tipo='$rtipo',diasalidas='$semana', sucursal='$sucursal', horasllegada='$llegada', tiempodescarga='$descarga', tiempocarga='$carga',horasalida='$salida', trayectosucursal='$ttss', 
transbordo='$transbordo',sucursalestransbordo='$sucursalestransbordo',idusuario='$idusuario',usuario='$usuario', fecha='$fecha' WHERE id='$id'";
	$f= mysql_query($sqlupdate,$link);
	$sql="SELECT * FROM catalogorutadetalletmp WHERE id='$id' ";
	$rest=mysql_query($sql,$link);
	$row = mysql_fetch_array($rest);
		$xml ="<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<id>$row[id]</id>";
		$xml.="<rtipo>$row[tipo]</rtipo>";
		if($row['diasalidas']!=""){
		$xml.="<semana>$row[diasalidas]</semana>";
		}else{
		$xml.="<semana>0</semana>";
		}
		$xml.="<sucursal>$row[sucursal]</sucursal>";
		$xml.= "<sucursalb>$sucursalb</sucursalb>";
		$xml.="<llegada>".substr($row[horasllegada],0,5)."</llegada>";
		$xml.="<descarga>".substr($row[tiempodescarga],0,5)."</descarga>";
		$xml.="<carga>".substr($row[tiempocarga],0,5)."</carga>";
		$xml.="<salida>".substr($row[horasalida],0,5)."</salida>";
		$xml.="<ttss>".substr($row[trayectosucursal],0,5)."</ttss>";
		$xml.= "<transbordo>$row[transbordo]</transbordo>";
		$xml.="</datos>
					</xml>";
		echo $xml;
}else if($tipo==4){
	//ELIMINAR EN LA GRID TEMPORAL "catalogorutadetalletmp"
		$sql = mysql_query("DELETE FROM catalogorutadetalletmp WHERE id='$id'",$link);
		$xml ="<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<idfila>$idfila</idfila>";
		$xml.="</datos>
					</xml>";
		echo $xml;
}else if($tipo==5){
	//MUSTRAR LOS DATOS CAPTURADOS EN LA GRID  "catalogorutadetalle"
	$sql = "SELECT  RU.id,RU.tipo,RU.diasalidas,RU.sucursal,SU.prefijo,RU.horasllegada, RU.tiempodescarga,RU.tiempocarga,RU.horasalida,RU.trayectosucursal,RU.transbordo  FROM catalogorutadetalle  AS RU INNER JOIN catalogosucursal AS SU ON SU.id=RU.sucursal WHERE ru.id='$id'";
	$rest = mysql_query($sql,$link);
	$row = mysql_fetch_array($rest);
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.= "<id>$row[id]</id>";
		$xml.= "<idfila>$idfila</idfila>";
		$xml.= "<rtipo>$row[tipo]</rtipo>";
		if($row[2]!=""){
		$xml.= "<semana>$row[diasalidas]</semana>";
		}else{
		$xml.= "<semana>0</semana>";
		}
		$xml.= "<sucursal>$row[sucursal]</sucursal>";
		$xml.= "<sucursalb>$row[prefijo]</sucursalb>";
		$xml.= "<llegada>$row[horasllegada]</llegada>";
		$xml.= "<descarga>$row[tiempodescarga]</descarga>";
		$xml.= "<carga>$row[tiempocarga]</carga>";
		$xml.= "<salida>$row[horasalida]</salida>";
		$xml.= "<ttss>$row[trayectosucursal]</ttss>";
		$xml.= "<transbordo>$row[transbordo]</transbordo>";
		$xml.= "</datos>
					</xml>";
		echo $xml;
}else if($tipo==6){
	//ELIMINAR EN LA GRID  "catalogorutadetalle"
		$sql = mysql_query("DELETE FROM catalogorutadetalle WHERE id='$id'",$link);
		$xml ="<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<idfila>$idfila</idfila>";
		$xml.="</datos>
					</xml>";
		echo $xml;
	
}else if($tipo==7){
	//MODIFICAR EN LA TABLA  DE LA GRID  "catalogorutadetalle"
	$sqlupdate = "UPDATE catalogorutadetalle SET tipo='$rtipo',diasalidas='$semana', sucursal='$sucursal', horasllegada='$llegada', 
			tiempodescarga='$descarga', tiempocarga='$carga',horasalida='$salida', 
			trayectosucursal='$ttss',transbordo='$transbordo',idusuario='$idusuario',
			usuario='$usuario', fecha='$fecha' WHERE id='$id'";
	$f= mysql_query($sqlupdate,$link);
	$sql="SELECT * FROM catalogorutadetalle WHERE id='$id' ";
	$rest=mysql_query($sql,$link);
	$row = mysql_fetch_array($rest);
		$xml ="<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<id>$row[0]</id>";
		$xml.="<rtipo>$row[tipo]</rtipo>";
		if($row[3]!=""){
		$xml.="<semana>$row[3]</semana>";
		}else{
		$xml.="<semana>0</semana>";
		}
		$xml.="<sucursal>$row[4]</sucursal>";
		$xml.="<sucursalb>$sucursalb</sucursalb>";
		$xml.="<llegada>$row[5]</llegada>";
		$xml.="<descarga>$row[6]</descarga>";
		$xml.="<carga>$row[7]</carga>";
		$xml.="<salida>$row[8]</salida>";
		$xml.="<ttss>$row[9]</ttss>";
		$xml.="</datos>
					</xml>";
		echo $xml;
	
}else if($tipo==8){
	//INSERTA LOS DATOS EN LA GRID TEMPORAL "catalogorutadetalletmp"
		$sql = "INSERT INTO catalogorutadetalletmp (id, tipo, diasalidas, sucursal, horasllegada, tiempodescarga, tiempocarga, horasalida, trayectosucursal,transbordo,idusuario, usuario, fecha) VALUES (null,'$rtipo','$semana','$sucursal', '$llegada', '$descarga', '$carga', '$salida', '$ttss','$transbordo','$idusuario' ,'$usuario', '$fecha')"; 
		$rest = @mysql_query($sql,$link);
	 	$id   = mysql_insert_id();
		$xml  = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.= "<id>$id</id>";
		if($semana!=""){
		$xml.= "<semana>$semana</semana>";
		}else{
		$xml.= "<semana>0</semana>";
		}
		$xml.= "<sucursal>$sucursal</sucursal>";
		$xml.= "<sucursalb>$sucursalb</sucursalb>";
		if($llegada!=""){
		$xml.= "<llegada>$llegada</llegada>";
		}else{
		$xml.= "<llegada>0</llegada>";
		}
		$xml.= "<descarga>$descarga</descarga>";
		$xml.= "<carga>$carga</carga>";
		if($salida!=""){
		$xml.= "<salida>$salida</salida>";
		}else{
		$xml.= "<salida>0</salida>";
		}
		$xml.= "<ttss>$ttss</ttss>";
		$xml.= "</datos>
					</xml>";
		echo $xml;
	
}else if($_GET[accion]==9){
	$sql_del="DELETE FROM catalogorutadetalletmp WHERE idusuario='".$_SESSION[IDUSUARIO]."'";
	mysql_query($sql_del,$link) or die(mysql_error($link));
}
	

?>