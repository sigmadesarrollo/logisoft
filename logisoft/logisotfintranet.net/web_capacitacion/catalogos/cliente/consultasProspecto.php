<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}

	header('Content-type: text/xml');

	require_once('../../Conectar.php');	

	$link=Conectarse('webpmm');	

	$cp=$_GET['cp'];	

	
	if($cp!=""){	

	$s = "SELECT cpo.codigopostal, cc.id as idcol, 

		cc.descripcion As colonia, cp.id as idpob, cp.descripcion as poblacion,

		cm.id as idmun, cm.descripcion as municipio, ce.id as idest, 

		ce.descripcion as estado, cpa.id as idpais, cpa.descripcion as pais FROM 

		catalogocolonia cc

		INNER JOIN catalogocodigopostal cpo ON cc.cp=cpo.codigopostal

		INNER JOIN catalogopoblacion cp ON cc.poblacion=cp.id

		INNER JOIN catalogomunicipio cm ON cp.municipio=cm.id

		INNER JOIN catalogoestado ce ON cm.estado=ce.id

		INNER JOIN catalogopais cpa ON ce.pais=cpa.id 

		WHERE cpo.codigopostal='$cp'";

	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);

		if(mysql_num_rows($r)>0){

			$to=mysql_num_rows($r);

			$f = mysql_fetch_object($r);

			$cant = mysql_num_rows($r);	

			

			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 

			<datos>";

			$xml.="<cp>".$f->codigopostal."</cp>";

			$xml.="<poblacion>".cambio_texto($f->poblacion)."</poblacion>";

			$xml.="<municipio>".cambio_texto($f->municipio)."</municipio>";

			$xml.="<estado>".cambio_texto($f->estado)."</estado>";

			$xml.="<pais>".cambio_texto($f->pais)."</pais>";

			$xml.="<total>".$to."</total>";		

			if($to>1){

				$sql=mysql_query($s,$link);

			while($row=mysql_fetch_array($sql)){

			$xml.="<colonia>".cambio_texto($row['colonia'])."</colonia>";

			}

			}else{

			$xml.="<colonia>".cambio_texto($f->colonia)."</colonia>";

			}

			$xml.="<encontro>$cant</encontro>";

			$xml.="</datos>

					</xml>";			

		}else{

			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 

			<datos>

			<encontro>0</encontro>

			</datos>

			</xml>";

		}echo $xml;	

	}
	
	if($_GET['tipo']=="prospecto"){
	$sql=mysql_query("SELECT CP.*,DI.* From catalogoprospecto as CP INNER JOIN  direccion as DI ON CP.id=DI.codigo && DI.origen = 'pro' Where CP.id='".$_GET['prospecto']."'",$link) or die('aki');
		if(mysql_num_rows($sql)>0){
			$cant = mysql_num_rows($sql);
			$row=mysql_fetch_array($sql);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml.="<codigo>".$_GET['prospecto']."</codigo>";
			$xml.="<rdmoral>".$row['personamoral']."</rdmoral>";
			$xml.="<nombre>".$row['nombre']."</nombre>";
			if($row['paterno']!=""){
			$xml.="<paterno>".$row['paterno']."</paterno>";
			}else{
			$xml.="<paterno>0</paterno>";
			}
			if($row['materno']!=""){
			$xml.="<materno>".$row['materno']."</materno>";
			}else{
			$xml.="<materno>0</materno>";
			}
			$xml.="<rfc>".$row['rfc']."</rfc>";
			if($row['email']!=""){
			$xml.="<email>".$row['email']."</email>";
			}else{
			$xml.="<email>0</email>";
			}
			if($row['celular']!=""){
			$xml.="<celular>".$row['celular']."</celular>";
			}else{
			$xml.="<celular>0</celular>";
			}
			if($row['web']!=""){
			$xml.="<web>".$row['web']."</web>";
			}else{
			$xml.="<web>0</web>";
			}
			$xml.="<calle>".$row['calle']."</calle>";
			$xml.="<cp>".$row['cp']."</cp>";
			if($row['crucecalles']!=""){
			$xml.="<entrecalles>".$row['crucecalles']."</entrecalles>";
			}else{
			$xml.="<entrecalles>0</entrecalles>";
			}
			$xml.="<colonia>".$row['colonia']."</colonia>";
			$xml.="<numero>".$row['numero']."</numero>";
			$xml.="<poblacion>".$row['poblacion']."</poblacion>";
			$xml.="<municipio>".$row['municipio']."</municipio>";
			$xml.="<estado>".$row['estado']."</estado>";
			$xml.="<pais>".$row['pais']."</pais>";
			$xml.="<telefono>".$row['telefono']."</telefono>";
			if($row['fax']!=""){
			$xml.="<fax>".$row['fax']."</fax>";
			}else{
			$xml.="<fax>0</fax>";
			}
			$con=mysql_query("SELECT * FROM catalogoprospectonick WHERE prospecto='".$_GET['prospecto']."'",$link) or die('Error en la linea '._LINE_);
			$list="";
			$xml.="<listnick>";
			while($row=mysql_fetch_array($con)){
				$list.= $row['nick'] . ",";
			}
			$list=substr($list,0,-1);
			$xml.=$list;
			$xml.="</listnick>";
			$xml.="<accion>modificar</accion>";
			$xml.="<encontro>$cant</encontro>";
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
	

?>