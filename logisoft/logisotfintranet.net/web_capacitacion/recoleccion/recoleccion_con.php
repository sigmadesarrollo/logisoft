<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$s = "SELECT CONCAT(nombre,' ',paterno,' ', materno) AS nombre
		FROM catalogocliente WHERE id=".$_GET[cliente]."";		
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		
		$d = "SELECT id, calle, numero, cp, colonia, crucecalles,
		poblacion, municipio, telefono, facturacion FROM direccion
		WHERE codigo =".$_GET['cliente']." AND origen = 'cl'";
		
	$sd = mysql_query($d,$link) or die("error en linea ".__LINE__);	
	$rd = mysql_num_rows($sd); 		

	if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);	
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml.="<nombre>".cambio_texto($f->nombre)."</nombre>";				
		if($rd > 1){
			while($row=mysql_fetch_object($sd)){
			$xml.="<id>".cambio_texto($row->id)."</id>";
			$xml.="<calle>".cambio_texto($row->calle)."</calle>";
			$xml.="<numero>".cambio_texto($row->numero)."</numero>";
			$xml.="<cp>".cambio_texto($row->cp)."</cp>";
			$xml.="<colonia>".cambio_texto($row->colonia)."</colonia>";
			$xml.="<poblacion>".cambio_texto($row->poblacion)."</poblacion>";
			$xml.="<municipio>".cambio_texto($row->municipio)."</municipio>";
			$xml.="<crucecalles>".cambio_texto($row->crucecalles)."</crucecalles>";			
			$xml.="<telefono>".cambio_texto($row->telefono)."</telefono>";
			$xml.="<facturacion>".cambio_texto($row->facturacion)."</facturacion>";			
			}
		}else{
			$drow = mysql_fetch_object($sd);
			$xml.="<id>".cambio_texto($drow->id)."</id>";
			$xml.="<calle>".cambio_texto($drow->calle)."</calle>";
			$xml.="<numero>".cambio_texto($drow->numero)."</numero>";
			$xml.="<cp>".cambio_texto($drow->cp)."</cp>";
			$xml.="<colonia>".cambio_texto($drow->colonia)."</colonia>";
			$xml.="<poblacion>".cambio_texto($drow->poblacion)."</poblacion>";
			$xml.="<municipio>".cambio_texto($drow->municipio)."</municipio>";
			$xml.="<crucecalles>".cambio_texto($drow->crucecalles)."</crucecalles>";			
			$xml.="<telefono>".cambio_texto($drow->telefono)."</telefono>";
			$xml.="<facturacion>".cambio_texto($drow->facturacion)."</facturacion>";
		}
		$xml.="<dir>".$rd."</dir>";
		$xml.="<encontro>".$cant."</encontro>";
		$xml.="</datos>
				</xml>";
		}else{
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;

	}else if($_GET[accion]==2){
		$s = "SELECT horariolimiterecoleccion AS horario FROM catalogosucursal WHERE id=".$_GET['sucursal']."";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		$f = mysql_fetch_object($r);
		$ge	= "<horario>".cambio_texto($f->horario)."</horario>";
		
		$s	= "SELECT DATE_FORMAT(ADDDATE(CURRENT_DATE,INTERVAL 1 DAY),'%d-%m-%Y') AS fecha";
		$fe	= mysql_query($s,$link) or die("error en linea ".__LINE__);
		$fec= mysql_fetch_object($fe);
		$fecha="<fecha>".$fec->fecha."</fecha>";
			 
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.=$ge;
		$xml.=$fecha;
		$xml.="</datos>
				</xml>";	
		echo $xml;
	}

?>
