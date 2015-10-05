<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}	
	
	if($_GET[accion] == 2){
		require_once("../Conectar.php");
		$l = Conectarse("webpmm");
		$s = "INSERT INTO entregasocurrealmacen SET 
		folio = obtenerFolio('entregasocurrealmacen',".$_SESSION[IDSUCURSAL]."),
		folioentregasocurre='$_GET[folio]', sucursal='$_SESSION[IDSUCURSAL]', 
		usuario='$_SESSION[NOMBREUSUARIO]', idusuario='$_SESSION[IDUSUARIO]', fecha=CURRENT_DATE";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		$folios = "'".str_replace(",","','",$_GET[folios])."'";
		$idfolioentregaocurre = mysql_insert_id($l);
		
		$s = "SELECT folio FROM entregasocurrealmacen WHERE id = ".$idfolioentregaocurre;
		$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);
		
		$s = "INSERT INTO entregasocurrealmacen_detalle
		SELECT $fo->folio, entregasocurrealmacen_tmp.*
		FROM entregasocurrealmacen_tmp WHERE idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		$s = "UPDATE entregasocurrealmacen_detalle 
		SET entregada = 0 where noguia IN($folios) AND identrega='$fo->folio' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		
		$s = "UPDATE guiasventanilla SET estado = 'ENTREGADA', fechaentrega=current_date,
		recibio=(select personaquerecibe from entregasocurre where folio = '$_GET[folio]')
		WHERE id IN($folios)";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		$s = "UPDATE guiasempresariales SET estado = 'ENTREGADA', fechaentrega=current_date,
		recibio=(select personaquerecibe from entregasocurre where folio = '$_GET[folio]') 
		WHERE id IN($folios)";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		$s = "UPDATE entregasocurre_detalle SET entregada = 1 WHERE guia IN($folios) and entregaocurre=$_GET[folio]";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		$s = "select * from entregasocurre_detalle where entregada=0 and entregaocurre=$_GET[folio]";
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		if(mysql_num_rows($r)<1){		
			$s = "UPDATE entregasocurre SET entregadas = 1 
			where folio = $_GET[folio] ";
			mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		}
		
		$paraciclo = split(',',$folios);
		for($i=0; $i<count($paraciclo); $i++){
			$s = "insert into seguimiento_guias
			set guia = ".$paraciclo[$i].", ubicacion='$_SESSION[IDSUCURSAL]', unidad='', estado='ENTREGADA', fecha=current_date,
			hora = current_time, usuario = '$_SESSION[IDUSUARIO]'";
			mysql_query($s,$l) or die($s);
		}
		
		echo "correcto,$_GET[folio],$fo->folio";
	}
	
	if($_GET[accion] == 3){
		require_once("../Conectar.php");
		$l = Conectarse("webpmm");
		$s = "SELECT obtenerFolio('entregasocurrealmacen',".$_SESSION[IDSUCURSAL].") AS folio";
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		$f = mysql_fetch_object($r);
		
		echo "correcto,$f->folio";
	}
	
	if($_GET[accion] == 4){
		header('Content-type: text/xml');
		require_once("../Conectar.php");
		$l = Conectarse("webpmm");	
		
		$s = "SELECT * FROM 
		entregasocurrealmacen_detalle where identrega=$_GET[folio] AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$enc = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			while($f = mysql_fetch_object($r)){
				$xml .= "
				<identregaalmacen>".$_GET[folio]."</identregaalmacen>
				<noguia>".cambio_texto($f->noguia)."</noguia>
				<tipoguia>Normal</tipoguia>
				<fecha>".cambio_texto(cambiaf_a_normal($f->fecha))."</fecha>
				<remitente>".cambio_texto($f->remitente)."</remitente>
				<destinatario>".cambio_texto($f->destinatario)."</destinatario>
				<importe>".cambio_texto($f->importe)."</importe>
				<entrega>".$f->entregada."</entrega>";
			}
			$xml .= "
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			</datos>
			</xml>";
		}
		echo $xml;
	}
?>
