<?	session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

	require_once('../Conectar.php');

	require_once("../clases/ValidaConvenio.php");

	$l = Conectarse('webpmm');

	

	if($_GET[accion]==1){

		$s="SELECT cs.descripcion FROM catalogosucursal cs

			INNER JOIN catalogodestino cd ON cs.id=cd.sucursal

			WHERE cd.id=".$_GET[destino]."";

		$r = mysql_query($s,$l) or die(mysql_error($l).$s);		

		$f = mysql_fetch_object($r);

		

		echo $f->descripcion;

				

	}else if($_GET[accion]==2){

		$arr = split(",",$_GET[arre]);

		$s = "SELECT CURRENT_TIMESTAMP() AS fecha";

		$ds= mysql_query($s, $l) or die($s);

		$f = mysql_fetch_object($ds);

			$s = "INSERT INTO evaluacionmercanciadetalle 

			(cantidad,descripcion,contenido, peso,largo,alto,ancho,volumen,

			pesototal,pesounit,idusuario,usuario,fecha )

			VALUES 

			('".$arr[0]."', '".$arr[1]."', '".$_GET[contenido]."', '".$arr[4]."', '".$arr[5]."', 

			 '".$arr[6]."', '".$arr[7]."', '".$arr[8]."', '".$arr[9]."', '".$arr[10]."', 

			 ".$_SESSION[IDUSUARIO].",'".$_SESSION[NOMBREUSUARIO]."','".$f->fecha."')";



			$r = mysql_query(str_replace("''",'null', $s),$l) or die(mysql_error($l).$s);



			echo "ok,".$f->fecha;



	}else if($_GET[accion]==3){//OBTENER GENERALES	

		$s = "SELECT DATE_FORMAT(CURRENT_DATE(),'%d/%m/%Y') AS fecha,

		(SELECT IFNULL(MAX(folio),0) + 1 AS folio FROM evaluacionmercancia)

		 AS folio,(SELECT UCASE(descripcion) FROM catalogosucursal WHERE id=".$_SESSION[IDSUCURSAL].")

		 AS sucursal"; //OBTENER FOLIO Y FECHA

		$r = mysql_query($s, $l) or die($s);

		$f = mysql_fetch_object($r);

		

		$s = "SELECT con.costo AS bolsaempaque FROM catalogoservicio cs

		INNER JOIN configuradorservicios con ON cs.id=con.servicio

		WHERE cs.id=".$_GET[bolsa]."";//OBTENER COSTO BOLSA EMPAQUE

		$b = mysql_query($s, $l) or die($s);

		$bol = mysql_fetch_object($b);

		

		$s = "SELECT cs.descripcion as servicio, con.condicion,

		con.costo AS emplaye, con.costoextra, con.limite,

		con.porcada FROM catalogoservicio cs

		INNER JOIN configuradorservicios con ON cs.id=con.servicio WHERE cs.id=".$_GET[emplaye]."";

		$e = mysql_query($s, $l) or die($s);//OBTENER COSTO EMPLAYE 

		$emp = mysql_fetch_object($e);

		

		$f->bolsaempaque = $bol->bolsaempaque;

		$f->servicio = $emp->servicio;

		$f->condicion = $emp->condicion;

		$f->emplaye = $emp->emplaye;

		$f->costoextra = $emp->costoextra;

		$f->limite = $emp->limite;

		$f->porcada = $emp->porcada;

		$f->sucursal= cambio_texto($f->sucursal);	

		$datosgenerales = str_replace('null','""', json_encode($f));

		

		echo "({datos:$datosgenerales})";

		

	}else if($_GET[accion]==4){

		$s = "DELETE FROM evaluacionmercanciadetalle WHERE idusuario=".$_SESSION[IDUSUARIO]." AND evaluacion=0";

		$r = mysql_query($s,$l) or die($s);

		

	}else if($_GET[accion]==5){//REGISTRAR EVALUACION

		$arr = split(",",$_GET[arre]);	

		$s = "INSERT INTO evaluacionmercancia 

		(fechaevaluacion, estado, guiaempresarial, recoleccion,

		destino, sucursaldestino, bolsaempaque, cantidadbolsa,

		totalbolsaempaque, emplaye, totalemplaye, sucursal, usuario, fecha)

		VALUES

		(current_date, UCASE('".$arr[0]."'), '".$arr[1]."', '".$arr[2]."',

		".$arr[3].", UCASE('".$arr[4]."'), ".$arr[5].", ".$arr[6].", ".$arr[7].",

		".$arr[8].", ".$arr[9].", ".$arr[10].", '".$_SESSION[NOMBREUSUARIO]."', current_timestamp())";

		$r = mysql_query($s,$l) or die($s);

		$folio = mysql_insert_id();

		

		$s = "INSERT INTO seguimiento_guias 

		SET guia = $folio, ubicacion = $_SESSION[IDSUCURSAL],

		estado = 'EVALUACION', unidad=null, fecha=CURRENT_DATE, hora = CURRENT_TIME,

		usuario = $_SESSION[IDUSUARIO]";

		$r = mysql_query($s,$l) or die($s);

		

		$s = "SELECT * FROM evaluacionmercanciadetalle WHERE idusuario=".$_SESSION[IDUSUARIO]." AND evaluacion=0";

		$sq = mysql_query($s,$l) or die($s);

		while($f = mysql_fetch_object($sq)){

			$s = "UPDATE evaluacionmercanciadetalle SET evaluacion=".$folio." 

			WHERE idusuario=".$_SESSION[IDUSUARIO]." AND evaluacion=0";

			$d = mysql_query($s,$l) or die($s);

		}

		

		echo "guardo";

	

	}else if($_GET[accion]==6){

		$s = "DELETE FROM evaluacionmercanciadetalle WHERE idusuario=".$_SESSION[IDUSUARIO]." AND fecha='".$_GET[fecha]."'";

		$r = mysql_query($s,$l) or die($s);

	

	}else if($_GET[accion]==7){

		$s = "UPDATE evaluacionmercancia SET estado='CANCELAR' WHERE folio=".$_GET[folio]."";

		$r = mysql_query($s,$l) or die($s);

		echo "ok";

	

	}else if($_GET[accion]==8){

		$s = "SELECT e.fechaevaluacion, e.estado, e.guiaempresarial,

		e.recoleccion, e.destino, cd.descripcion As descripciondestino,

		e.sucursaldestino, e.bolsaempaque, e.cantidadbolsa, e.totalbolsaempaque,

		e.emplaye, e.totalemplaye FROM evaluacionmercancia e

		INNER JOIN catalogodestino cd ON e.destino=cd.id

		WHERE e.folio=".$_GET[evaluacion]." AND e.sucursal=".$_SESSION[IDSUCURSAL]."";

		$r = mysql_query($s,$l) or die($s);

		if(mysql_num_rows($r)>0){

		$registros = array();

			while($f = mysql_fetch_object($r)){

				$f->fechaevaluacion = cambiaf_a_normal($f->fechaevaluacion);

				$f->descripciondestino = cambio_texto($f->descripciondestino);

				$f->sucursaldestino = cambio_texto($f->sucursaldestino);

				$f->guiaempresarial = cambio_texto($f->guiaempresarial);

				$registros[] = $f;

			}

		

			echo str_replace('null','""',json_encode($registros));

		}else{

			echo str_replace('null','""',json_encode(0));

		}

		

	}else if($_GET[accion]==9){

		$s = "SELECT e.id, e.evaluacion, e.cantidad, e.descripcion,

		cd.descripcion As catdes, e.contenido, e.peso, e.largo, e.ancho,

		e.alto, e.volumen, e.pesototal, e.pesounit FROM evaluacionmercanciadetalle e

		INNER JOIN catalogodescripcion cd ON e.descripcion=cd.id

		WHERE e.evaluacion=".$_GET[evaluacion]."";

		$r = mysql_query($s,$l) or die($s);

		$registros = array();

			while($f = mysql_fetch_object($r)){

				$f->descripcion = cambio_texto($f->catdes);

				$registros[] = $f;

			}

		

		echo str_replace('null','""',json_encode($registros));

		

	}else if($_GET[accion]==10){	

		$s = "SELECT id from guiasempresariales where id = '$_GET[folio]'";

		$r = mysql_query($s,$l) or die($s);

		if(mysql_num_rows($r)>0){

			echo '({"encontro":"-2"})';

		}else{		

			$s = "SELECT sge.prepagada, gcn.folio, sge.id

			FROM solicitudguiasempresariales AS sge

			INNER JOIN generacionconvenio AS gcn ON sge.idconvenio = gcn.folio AND CURRENT_DATE < gcn.vigencia

			WHERE sge.status = 1 AND

			SUBSTRING('$_GET[folio]',4,9) 

			BETWEEN SUBSTRING(sge.desdefolio,4,9) AND SUBSTRING(sge.hastafolio,4,9)

			AND SUBSTR('$_GET[folio]',1,3) = SUBSTRING(sge.desdefolio,1,3) 

			AND SUBSTRING('$_GET[folio]',13,1) BETWEEN SUBSTRING(sge.desdefolio,13,1) AND SUBSTRING(sge.hastafolio,13,1)";

			$r = mysql_query($s,$l) or die($s);

			if(mysql_num_rows($r)>0){

				$f = mysql_fetch_object($r);

				echo '({"encontro":"1", "idconvenio":"'.$f->folio.'", "prepagadas":"'.$f->prepagada.'"})';

			}elseif($_GET[folio]!=""){

				echo '({"encontro":"0"})';

			}

		}		

	}else if($_GET[accion]==11){

		$s = "SELECT cs.descripcion FROM catalogosucursal cs

		INNER JOIN catalogodestino cd ON cs.id=cd.sucursal

		WHERE cd.id=".$_GET[destino]."";

		$r = mysql_query($s,$l) or die($s);

		$registros = array();

			while($f = mysql_fetch_object($r)){

				$f->descripcion = cambio_texto($f->descripcion);

				$registros[] = $f;

			}

		

		echo str_replace('null','""',json_encode($registros));

		

	}else if($_GET[accion]==12){

		$s = "SELECT * FROM catalogodescripcion WHERE descripcion='".$_GET[descripcion]."'";

		$ss= mysql_query($s,$l) or die($s);		

		if(mysql_num_rows($ss)==0){

			echo "no";

		}else{

			echo "si";

		}

	}else if($_GET[accion]==13){		

		$row = split(",",$_GET[arre]);

		$s = "UPDATE evaluacionmercanciadetalle SET cantidad=".$row[0].", descripcion=".$row[1].",

		contenido='".$_GET[contenido]."', peso=".$row[4].", largo=".$row[5].", ancho=".$row[7].",

		alto=".$row[6].", volumen=".$row[8].", pesototal=".$row[9].",

		pesounit=".$row[10]." WHERE idusuario=".$_SESSION[IDUSUARIO]." AND fecha='".$_GET[fecha]."'";

		//echo $s;

		$r = mysql_query($s,$l) or die($s);

		

		echo "ok";	

	}

?>