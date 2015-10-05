<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../../Conectar.php');	
	$l = Conectarse('webpmm');
		
	if($_GET[accion]==1){	
		$s = "INSERT INTO catalogosucursal SET prefijo=UCASE('".$_GET[prefijo]."'),
		idsucursal=UCASE('".$_GET[idsucursal]."'), descripcion=UCASE('$_GET[descripcion]'),
		monitoreo='$_GET[monitoreo]', concesion=$_GET[concesion], comision='$_GET[comision]',
		ventas='$_GET[ventas]', recibido='$_GET[recibido]', porcead='$_GET[porcead]', 
		porcrecoleccion='$_GET[porcrecoleccion]', lectores='$_GET[lectores]', iva='$_GET[iva]', 
		bascula='$_GET[bascula]', cajachica='$_GET[cajachica]',
		horariolimiterecoleccion='$_GET[horariolimiterecoleccion]', calle=UCASE('$_GET[calle]'), 
		numero=UCASE('$_GET[numero]'), crucecalles=UCASE('$_GET[crucecalles]'), cp='$_GET[cp]',
		colonia=UCASE('$_GET[colonia]'), poblacion=UCASE('$_GET[poblacion]'), 
		municipio=UCASE('$_GET[municipio]'), estado=UCASE('$_GET[estado]'),
		pais=UCASE('$_GET[pais]'), telefono='$_GET[telefono]',fax='$_GET[fax]', frontera='$_GET[frontera]',
		usuario='$_SESSION[NOMBREUSUARIO]', fecha=current_timestamp(),
		fleterecibido = '".$_GET[fleterecibido]."', fleteenviado = '".$_GET[fleteenviado]."',
		sobrepeso = '".$_GET[sobrepeso]."', zonahoraria='$_GET[zonahoraria]'";
		$r = mysql_query($s,$l) or die($s);
		$codigo=mysql_insert_id();
		
		echo "guardo,".$codigo;
		
	}else if($_GET[accion]==2){
		$s = "UPDATE catalogosucursal SET prefijo=UCASE('".$_GET[prefijo]."'),
		idsucursal=UCASE('".$_GET[idsucursal]."'), descripcion=UCASE('$_GET[descripcion]'),
		monitoreo='$_GET[monitoreo]', concesion=$_GET[concesion], comision='$_GET[comision]',
		ventas='$_GET[ventas]', recibido='$_GET[recibido]', porcead='$_GET[porcead]', 
		porcrecoleccion='$_GET[porcrecoleccion]', lectores='$_GET[lectores]', iva='$_GET[iva]', 
		bascula='$_GET[bascula]', cajachica='$_GET[cajachica]',
		horariolimiterecoleccion='$_GET[horariolimiterecoleccion]', calle=UCASE('$_GET[calle]'), 
		numero=UCASE('$_GET[numero]'), crucecalles=UCASE('$_GET[crucecalles]'), cp='$_GET[cp]',
		colonia=UCASE('$_GET[colonia]'), poblacion=UCASE('$_GET[poblacion]'), 
		municipio=UCASE('$_GET[municipio]'), estado=UCASE('$_GET[estado]'),
		pais=UCASE('$_GET[pais]'), telefono='$_GET[telefono]',fax='$_GET[fax]', frontera='$_GET[frontera]',
		usuario='$_SESSION[NOMBREUSUARIO]', fecha=current_timestamp(),
		fleterecibido = '".$_GET[fleterecibido]."', fleteenviado = '".$_GET[fleteenviado]."',
		sobrepeso = '".$_GET[sobrepeso]."', zonahoraria='$_GET[zonahoraria]'
		WHERE id='$_GET[codigo]'";
		mysql_query($s,$l) or die($s);
		
		echo "modifico";
		
	}else if($_GET[accion]==3){
		$row	=	folio('catalogosucursal','webpmm');
		$codigo	=	$row[0];
		
		echo $codigo;
		
	}else if($_GET[accion] == 4){
		$s = "SELECT CPO.id AS id_poblacion,CPO.descripcion AS poblacion,
		CM.id AS id_municipio,CM.descripcion AS municipio, CE.id AS id_estado,
		CE.descripcion AS estado FROM catalogopoblacion AS CPO 
		INNER JOIN catalogomunicipio AS CM ON CPO.municipio=CM.id 
		INNER JOIN catalogoestado AS CE ON CM.estado=CE.id 
		WHERE CPO.id='".$_GET[poblacion]."'";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->poblacion = cambio_texto($f->poblacion);
				$f->municipio = cambio_texto($f->municipio);
				$f->estado = cambio_texto($f->estado);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}
?>