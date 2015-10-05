<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if(isset($_SESSION['gvalidar'])!=100){echo "<script language='javascript' type='text/javascript'>document.location.href='../../index.php';

</script>";}else{*/

		header('Content-type: text/xml');

		require_once('../../Conectar.php');		

		$link=Conectarse('webpmm');

		$usuario=$_SESSION[NOMBREUSUARIO];

		$tipo=$_GET['tipo'];

		$poblacion=$_GET['poblacion'];

		$destino=$_GET['destino'];

if($_GET['accion']==1){

		$sql=mysql_query("SELECT CPO.id AS id_poblacion,CPO.descripcion AS poblacion, CM.id AS id_municipio,CM.descripcion AS municipio, CE.id AS id_estado,CE.descripcion AS estado FROM catalogopoblacion AS CPO INNER JOIN catalogomunicipio AS CM ON CPO.municipio=CM.id INNER JOIN catalogoestado AS CE ON CM.estado=CE.id WHERE CPO.id='$poblacion'",$link);

	if(mysql_num_rows($sql)>0){

		$cant = mysql_num_rows($sql);

		$row=@mysql_fetch_array($sql);

		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 

			<datos>";

		$xml.="<municipio>".cambio_texto($row[3])."</municipio>";

		$xml.="<estado>".cambio_texto($row[5])."</estado>";

		$xml.="<poblacion>".cambio_texto($row[1])."</poblacion>";

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

	

if($tipo=="destino"){
		$s = "SELECT d.descripcion, d.sucursal, d.poblacion AS idpoblacion, p.descripcion AS poblacion, 
		d.costoead, d.costorecoleccion, d.restringiread,d.restringireadapfsinconvenio, d.restringirrecoleccion, 
		d.restringirporcobrar, d.deshabilitarconvenio,d.subdestinos, d.todasemana, d.lunes, d.martes, 
		d.miercoles, d.jueves, d.viernes, d.sabado, m.descripcion AS municipio, es.descripcion AS estado, notificacion, notificaciones 
		FROM catalogodestino d 
		LEFT JOIN catalogopoblacion p ON d.poblacion=p.id 
		LEFT JOIN  catalogomunicipio m ON p.municipio=m.id 
		LEFT JOIN catalogoestado es ON m.estado=es.id WHERE d.id='$destino'";
		$sql=mysql_query($s,$link);		

		$row=@mysql_fetch_array($sql);

		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 

			<datos>";

		$xml.="<codigo>".$destino."</codigo>";

		$xml.="<sucursal>".$row['sucursal']."</sucursal>";

		$xml.="<descripcion>".cambio_texto($row['descripcion'])."</descripcion>";

		$xml.="<idpoblacion>".$row['idpoblacion']."</idpoblacion>";

		$xml.="<poblacion>".cambio_texto($row['poblacion'])."</poblacion>";

		$xml.="<municipio>".cambio_texto($row['municipio'])."</municipio>";

		$xml.="<estado>".cambio_texto($row['estado'])."</estado>";

		$xml.="<costoead>".$row['costoead']."</costoead>";

		$xml.="<costorecoleccion>".$row['costorecoleccion']."</costorecoleccion>";

		$xml.="<restringiread>".$row['restringiread']."</restringiread>";

		$xml.="<restringireadapf>".$row['restringireadapfsinconvenio']."</restringireadapf>";

		$xml.="<restringirrecoleccion>".$row['restringirrecoleccion']."</restringirrecoleccion>";		

		$xml.="<restringircobrar>".$row['restringirporcobrar']."</restringircobrar>";		

		$xml.="<deshabilitarconvenio>".$row['deshabilitarconvenio']."</deshabilitarconvenio>";		

		$xml.="<subdestinos>".$row['subdestinos']."</subdestinos>";	

		$xml.="<todasemana>".$row['todasemana']."</todasemana>";

		$xml.="<lunes>".$row['lunes']."</lunes>";		

		$xml.="<martes>".$row['martes']."</martes>";

		$xml.="<miercoles>".$row['miercoles']."</miercoles>";

		$xml.="<jueves>".$row['jueves']."</jueves>";

		$xml.="<viernes>".$row['viernes']."</viernes>";

		$xml.="<sabado>".$row['sabado']."</sabado>";

		$xml.="<notificacion>".$row['notificacion']."</notificacion>";

		$xml.="<notificaciones>".$row['notificaciones']."</notificaciones>";

		$xml.="<accion>modificar</accion>";

		$xml.="</datos>

					</xml>";

		echo $xml;

}	

?>



<?  //} ?>