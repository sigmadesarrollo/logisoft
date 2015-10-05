<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	header('Content-type: text/xml');
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
if($_GET[accion]==1){
	//traspaso mercancia.php
		$s="(SELECT gv.id,gv.evaluacion,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,gv.idsucursaldestino,cs.descripcion as sucursaldestino,
			gv.idremitente,gv.iddireccionremitente,
			gv.iddestinatario,gv.iddirecciondestinatario,
			concat(remitente.nombre,' ',remitente.paterno,' ',remitente.materno)as nombre_remitente,remitente.rfc as rfc_remitente,
			concat(destinatario.nombre,' ',destinatario.paterno,' ',destinatario.materno)as nombre_destinatario,destinatario.rfc as rfc_destinatario,
			dirremitente.calle as calle_remitente,dirremitente.numero as numero_remitente,dirremitente.cp as cp_remitente,dirremitente.colonia as colonia_remitente,dirremitente.poblacion as poblacion_remitente,dirremitente.telefono as telefono_remitente,
			dirdestinatario.calle as calle_destinatario,dirdestinatario.numero as numero_destinatario,dirdestinatario.cp as cp_destinatario,dirdestinatario.colonia as colonia_destinatario,dirdestinatario.poblacion as poblacion_destinatario,dirdestinatario.telefono as telefono_destinatario
			FROM guiasventanilla as gv
			INNER JOIN catalogosucursal as cs ON cs.id=gv.idsucursaldestino
			INNER JOIN catalogocliente as remitente ON remitente.id=gv.idremitente
			INNER JOIN catalogocliente as destinatario ON destinatario.id=gv.iddestinatario
			INNER JOIN direccion as dirremitente ON dirremitente.id=gv.iddireccionremitente
			INNER JOIN direccion as dirdestinatario ON dirdestinatario.id=gv.iddireccionremitente
			WHERE gv.id='".$_GET['folio']."')
			UNION 
			(SELECT gv.id,gv.evaluacion,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,gv.idsucursaldestino,cs.descripcion as sucursaldestino,
			gv.idremitente,gv.iddireccionremitente,
			gv.iddestinatario,gv.iddirecciondestinatario,
			concat(remitente.nombre,' ',remitente.paterno,' ',remitente.materno)as nombre_remitente,remitente.rfc as rfc_remitente,
			concat(destinatario.nombre,' ',destinatario.paterno,' ',destinatario.materno)as nombre_destinatario,destinatario.rfc as rfc_destinatario,
			dirremitente.calle as calle_remitente,dirremitente.numero as numero_remitente,dirremitente.cp as cp_remitente,dirremitente.colonia as colonia_remitente,dirremitente.poblacion as poblacion_remitente,dirremitente.telefono as telefono_remitente,
			dirdestinatario.calle as calle_destinatario,dirdestinatario.numero as numero_destinatario,dirdestinatario.cp as cp_destinatario,dirdestinatario.colonia as colonia_destinatario,dirdestinatario.poblacion as poblacion_destinatario,dirdestinatario.telefono as telefono_destinatario
			FROM guiasempresariales as gv
			INNER JOIN catalogosucursal as cs ON cs.id=gv.idsucursaldestino
			INNER JOIN catalogocliente as remitente ON remitente.id=gv.idremitente
			INNER JOIN catalogocliente as destinatario ON destinatario.id=gv.iddestinatario
			INNER JOIN direccion as dirremitente ON dirremitente.id=gv.iddireccionremitente
			INNER JOIN direccion as dirdestinatario ON dirdestinatario.id=gv.iddireccionremitente
			WHERE gv.id='".$_GET['folio']."')";	
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__.mysql_error($link));
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> ";
			$xml.="<datosX>";
			$xml.="<folio>".cambio_texto($f->id)."</folio>";
			$xml.="<fecha2>".cambio_texto($f->fecha)."</fecha2>";
			$xml.="<sucdestino>".cambio_texto($f->sucursaldestino)."</sucdestino>";
			$xml.="<id_remitente>".cambio_texto($f->idremitente)."</id_remitente>";
			$xml.="<rfc_remitente>".cambio_texto($f->rfc_remitente)."</rfc_remitente>";
			$xml.="<cliente_remitente>".cambio_texto($f->nombre_remitente)."</cliente_remitente>";
			$xml.="<calle_remitente>".cambio_texto($f->calle_remitente)."</calle_remitente>";
			$xml.="<numero_remitente>".cambio_texto($f->numero_remitente)."</numero_remitente>";
			$xml.="<cp_remitente>".cambio_texto($f->cp_destinatario)."</cp_remitente>";
			$xml.="<colonia_remitente>".cambio_texto($f->colonia_destinatario)."</colonia_remitente>";
			$xml.="<poblacion_remitente>".cambio_texto($f->poblacion_remitente)."</poblacion_remitente>";
			$xml.="<telefono_remitente>".cambio_texto($f->telefono_remitente)."</telefono_remitente>";
			$xml.="<id_destinatario>".cambio_texto($f->iddestinatario)."</id_destinatario>";
			$xml.="<rfc_destinatario>".cambio_texto($f->rfc_destinatario)."</rfc_destinatario>";
			$xml.="<cliente_destinatario>".cambio_texto($f->nombre_destinatario)."</cliente_destinatario>";
			$xml.="<calle_destinatario>".cambio_texto($f->calle_destinatario)."</calle_destinatario>";
			$xml.="<numero_destinatario>".cambio_texto($f->numero_destinatario)."</numero_destinatario>";
			$xml.="<cp_destinatario>".cambio_texto($f->cp_destinatario)."</cp_destinatario>";
			$xml.="<colonia_destinatario>".cambio_texto($f->colonia_destinatario)."</colonia_destinatario>";
			$xml.="<poblacion_destinatario>".cambio_texto($f->poblacion_destinatario)."</poblacion_destinatario>";
			$xml.="<telefono_destinatario>".cambio_texto($f->telefono_destinatario)."</telefono_destinatario>";
			$xml.="<encontro>".$cant."</encontro>";
			$xml.="</datosX>";
			$e="(SELECT cantidad, descripcion, contenido, peso, volumen, importe 
				FROM guiaventanilla_detalle WHERE idguia='".$_GET['folio']."')
				UNION
				(SELECT cantidad, descripcion, contenido, peso, volumen,importe 
				FROM guiasempresariales_detalle  WHERE id='".$_GET['folio']."')";
			$e_query=mysql_query($e,$link) or die("Error en la linea ".__LINE__);
			$xml.="<datos>";
			while($row=mysql_fetch_array($e_query)){
			$xml.="<idmercancia>".cambio_texto($f->evaluacion)."</idmercancia>";
			$xml.="<cantidad>".cambio_texto($row[cantidad])."</cantidad>";
			$xml.="<descripcion>".cambio_texto($row[descripcion])."</descripcion>";
			$xml.="<contenido>".cambio_texto($row[contenido])."</contenido>";
			$xml.="<peso>".cambio_texto($row[peso])."</peso>";
			$xml.="<volumen>".cambio_texto($row[volumen])."</volumen>";
			$xml.="<importe>".cambio_texto($row[importe])."</importe>";
			}
			$xml.="</datos>";
			$xml.="</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
}else if($_GET[accion]==2){
	//mercanciadetraspasopendienteporrecibir.php
	$s="(select traspasarmercancia.folio,traspasarmercancia.remitente,traspasarmercancia.destinatario,traspasarmercancia.sucursaldestino ,
	guiaventanilla_detalle.cantidad, guiaventanilla_detalle.descripcion, guiaventanilla_detalle.contenido 
	from traspasarmercancia inner join  guiaventanilla_detalle ON guiaventanilla_detalle.idguia=traspasarmercancia.folio 
	WHERE traspasarmercancia.status is null)
UNION
(select traspasarmercancia.folio,traspasarmercancia.remitente,traspasarmercancia.destinatario,traspasarmercancia.sucursaldestino ,guiasempresariales_detalle.cantidad, guiasempresariales_detalle.descripcion, guiasempresariales_detalle.contenido 
	from traspasarmercancia inner join  guiasempresariales_detalle ON guiasempresariales_detalle.id=traspasarmercancia.folio
	WHERE traspasarmercancia.status is null)";
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> ";
			$e_query=mysql_query($s,$link) or die("Error en la linea ".__LINE__);
			$xml.="<datos>";
			while($row=mysql_fetch_array($e_query)){
			$xml.="<noguia>".cambio_texto($row[folio])."</noguia>";
			$xml.="<cantidad>".cambio_texto($row[cantidad])."</cantidad>";
			$xml.="<descripcion>".cambio_texto($row[descripcion])."</descripcion>";
			$xml.="<contenido>".cambio_texto($row[contenido])."</contenido>";
			$xml.="<remitente>".cambio_texto($row[remitente])."</remitente>";
			$xml.="<destinatario>".cambio_texto($row[destinatario])."</destinatario>";
			$xml.="<sucursal>".cambio_texto($row[sucursaldestino])."</sucursal>";
			$xml.="<s>1</s>";
			}
			$xml.="</datos>";
			$xml.="</xml>";		
		echo $xml;
}else if($_GET[accion]==3){
	//mercanciadetraspasopendienteporrecibir.php
	$f=$_GET[folios];
	$folios=split(",",$f);
	for($i=0;$i<strlen($folios);$i++){
		$s="UPDATE traspasarmercancia SET status='TRASPASO MERCANCIA',idusuario='".$_SESSION[NOMBREUSUARIO]."',
usuario='".$_SESSION[IDUSUARIO]."',fecha=CURRENT_DATE WHERE  folio='$folios[$i]'";
		mysql_query($s,$link) or die(mysql_error($link));
	}
	$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> ";
	$xml.="<datos>";
	$xml.="<guardado>1</guardado>";
	$xml.="</datos>";
	$xml.="</xml>";		
	echo $xml;
}
?>