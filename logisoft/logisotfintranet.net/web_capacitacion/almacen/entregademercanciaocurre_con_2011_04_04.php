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
		$guiasfaltantes = "'".str_replace(",","','",$_GET[guiasfaltantes])."'";
		$idfolioentregaocurre = mysql_insert_id($l);
		
		$s = "SELECT tipodeidentificacion AS ti, numeroidentificacion AS ni, personaquerecibe AS pr
		FROM entregasocurre WHERE folio = ".$_GET[folio]." AND idsucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s); 
		$f = mysql_fetch_object($r);
		$de_ti = $f->ti;
		$de_ni = $f->ni;
		$de_pr = $f->pr;
		
		$s = "SELECT folio FROM entregasocurrealmacen WHERE id = ".$idfolioentregaocurre;
		$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);
		
		$s = "UPDATE reportedanosfaltanteocurre SET folioentrega = ".$fo->folio." 
		WHERE folioentrega is null AND idusuario='".$_SESSION[IDUSUARIO]."'";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "INSERT INTO entregasocurrealmacen_detalle
		SELECT $fo->folio, entregasocurrealmacen_tmp.*
		FROM entregasocurrealmacen_tmp WHERE idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		$s = "UPDATE entregasocurrealmacen_detalle 
		SET entregada = 0 where noguia IN($folios) AND identrega='$fo->folio' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		$s = "UPDATE guiasventanilla SET estado = 'ENTREGADA',
		recibio='$de_pr', tipoidentificacion='$de_ti', numeroidentificacion='$de_ni'
		WHERE id IN($folios)";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		$s = "UPDATE guiasempresariales SET estado = 'ENTREGADA',
		recibio='$de_pr', tipoidentificacion='$de_ti', numeroidentificacion='$de_ni'
		WHERE id IN($folios)";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		if(!empty($_GET[guiasfaltantes])){		
			$s = "UPDATE guiasventanilla SET estado = 'ENTREGADA',
			recibio='$de_pr', tipoidentificacion='$de_ti', numeroidentificacion='$de_ni'
			WHERE id IN($guiasfaltantes)";
			mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
			
			$s = "UPDATE guiasempresariales SET estado = 'ENTREGADA',
			recibio='$de_pr', tipoidentificacion='$de_ti', numeroidentificacion='$de_ni'
			WHERE id IN($guiasfaltantes)";
			mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		}
		
		$s = "UPDATE entregasocurre_detalle SET entregada = 1 WHERE guia IN($folios) and entregaocurre=$_GET[folio]";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		$s = "UPDATE guiasventanilla SET firma = (SELECT firma FROM entregasocurre
		WHERE folio = ".$_GET[folio]." AND idsucursal = ".$_SESSION[IDSUCURSAL]."),
		recibio='$de_pr', tipoidentificacion='$de_ti', numeroidentificacion='$de_ni'
		WHERE id IN ($folios)";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		$s = "UPDATE guiasempresariales SET firma = (SELECT firma FROM entregasocurre
		WHERE folio = ".$_GET[folio]." AND idsucursal = ".$_SESSION[IDSUCURSAL]."),
		recibio='$de_pr', tipoidentificacion='$de_ti', numeroidentificacion='$de_ni' 
		WHERE id IN ($folios)";
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
			
			$s = "SELECT incompleta, danos FROM guiasventanilla WHERE id = ".$paraciclo[$i]."
			UNION
			SELECT incompleta, danos FROM guiasempresariales WHERE id = ".$paraciclo[$i];
			$rf = mysql_query($s,$l) or die($s);
			$ff = mysql_fetch_object($rf);
			
			if(substr($paraciclo[$i],1,3)=="777"){
				$c = "SELECT guia FROM devolucionguia WHERE nuevaguia = ".$paraciclo[$i];
				$cr = mysql_query($c,$l) or die($c);
				$cf = mysql_fetch_object($cr);
				$folioCancelar = $cf->guia;
				
				$s = "insert into historial_cancelacionysustitucion
				set guia = '$folioCancelar', accion='CANCELADO', tipo='DEVOLUCION', 
				sucursal='$_SESSION[IDSUCURSAL]', fecha=current_date,
				hora=current_time, usuario = '$_SESSION[IDUSUARIO]';";
				mysql_query($s,$l) or die($s);
				
				$s = "call proc_RegistroAuditorias('GC','$folioCancelar',$_SESSION[IDSUCURSAL])";
				mysql_query($s, $l) or die($s);
				
				$s = "UPDATE pagoguias SET pagado = 'C', fechacancelacion=current_date WHERE guia = '$folioCancelar'";
				mysql_query($s,$l) or die($s);
				
				$s = "UPDATE formapago SET fechacancelacion = current_date where procedencia='G' and guia='$folioCancelar'";
				mysql_query($s,$l) or die($s);
				
				$s = "update guiasventanilla set estado = 'CANCELADO', 
				idusuario='".$_SESSION[IDUSUARIO]."', usuario='".$_SESSION[NOMBREUSUARIO]."',
				observaciones = 'CANCELADA POR ENTREGA DE GUIA DEV ".str_replace("'","",$paraciclo[$i])."'
				where id='$folioCancelar'";
				mysql_query($s,$l) or die($s);
			}
			$s = "insert into seguimiento_guias
			set guia = ".$paraciclo[$i].", ubicacion='$_SESSION[IDSUCURSAL]', unidad='', 
			estado=CONCAT('ENTREGADA',IF('$ff->incompleta'='S',' INCOM',''),IF('$ff->dano'='S',' DAÑO',''))
			, fecha=current_date,
			hora = current_time, usuario = '$_SESSION[IDUSUARIO]'";
			mysql_query($s,$l) or die($s);
		}
		
		$s = "UPDATE actividadusuario SET estado = 1 WHERE tipo = 'inventario' AND referencia IN($folios)";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
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
