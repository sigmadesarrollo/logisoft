<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once('../Conectar.php');	
	$link=Conectarse('webpmm');	
	$evaluacion=$_GET['evaluacion'];
	if($_GET['accion']==1){
	$s = "SELECT e.fechaevaluacion, e.estado, e.guiaempresarial, e.recoleccion, e.destino, cd.descripcion As descripciondestino, e.sucursaldestino, e.bolsaempaque, e.cantidadbolsa, e.totalbolsaempaque, e.emplaye, e.totalemplaye FROM evaluacionmercancia e
INNER JOIN catalogodestino cd ON e.destino=cd.id WHERE e.folio='$evaluacion'";	
	$sql =@mysql_query("SELECT e.id, e.evaluacion, e.cantidad, e.descripcion, cd.descripcion As catdes, e.contenido, e.peso, e.largo, e.ancho, e.alto, e.volumen, e.pesototal, e.pesounit FROM evaluacionmercanciadetalle e
INNER JOIN catalogodescripcion cd ON e.descripcion=cd.id WHERE e.evaluacion='$evaluacion'",$link);

	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$to=@mysql_num_rows($sql);
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);	
			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
	$xml.="<fechaevaluacion>$f->fechaevaluacion</fechaevaluacion>";
	$xml.="<estado>".cambio_texto($f->estado)."</estado>";
	$xml.="<guiaempresarial>$f->guiaempresarial</guiaempresarial>";
	$xml.="<recoleccion>$f->recoleccion</recoleccion>";
	$xml.="<destino>$f->destino</destino>";	
	$xml.="<descripciondestino>".cambio_texto($f->descripciondestino)."</descripciondestino>";
	$xml.="<sucursaldestino>".cambio_texto($f->sucursaldestino)."</sucursaldestino>";
	$xml.="<bolsaempaque>$f->bolsaempaque</bolsaempaque>";
	$xml.="<cantidadbolsa>$f->cantidadbolsa</cantidadbolsa>";
	$xml.="<totalbolsaempaque>$f->totalbolsaempaque</totalbolsaempaque>";
	$xml.="<emplaye>$f->emplaye</emplaye>";
	$xml.="<totalemplaye>$f->totalemplaye</totalemplaye>";	
	$xml.="<encontro>$cant</encontro>";	
	$xml.="<total>".$to."</total>";		
		if($to>0){				
			while($row=mysql_fetch_array($sql)){
			$xml.="<id>".$row['id']."</id>";
			$xml.="<evaluacion>".$row['evaluacion']."</evaluacion>";
			$xml.="<cantidad>".$row['cantidad']."</cantidad>";
			$xml.="<descripcion>".$row['descripcion']."</descripcion>";
			$xml.="<catdes>".cambio_texto($row['catdes'])."</catdes>";
			$xml.="<contenido>".cambio_texto($row['contenido'])."</contenido>";
			$xml.="<peso>".$row['peso']."</peso>";
			$xml.="<largo>".$row['largo']."</largo>";
			$xml.="<ancho>".$row['ancho']."</ancho>";
			$xml.="<alto>".$row['alto']."</alto>";
			$xml.="<volumen>".$row['volumen']."</volumen>";
			$xml.="<pesototal>".$row['pesototal']."</pesototal>";
			$xml.="<pesounit>".cambio_texto($row['pesounit'])."</pesounit>";			
			}
		}
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
?>