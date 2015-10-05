<?
	require_once("../web_capacitacion/Conectar.php");
	require_once("../web_capacitacion/clases/ValidaConvenio.php");
	$l = Conectarse("webpmm");
	$vc = new validaConvenio('','','','');
	if($_GET[tipo]==1){//GUIAS DE VENTANILLA
		$s = "SELECT r.folio, r.estado, r.sucursal, DATE_FORMAT( r.fecharegistro, '%d/%m/%Y' ) AS fecharegistro, 
		      DATE_FORMAT( r.fecharecoleccion, '%d/%m/%Y' ) AS fecharecoleccion, r.origen, r.destino, r.npedidos, 
			  r.dirigido, r.chnombre, r.llama, r.telefono, r.comentarios, r.cliente, r.calle, r.numero, 
			  r.crucecalles, r.cp, r.colonia, r.poblacion, r.municipio, r.telefono2, r.horario, 
			  r.sector, r.unidad, r.transmitida, r.realizo, CONCAT( c.nombre, ' ', c.paterno, ' ', c.materno ) AS ncliente, 
			  s.descripcion AS dessuc, o.descripcion AS desori, r.multiple, r.horario2, r.hrcomida, r.hrcomida2, cp.Folio, cp.Fecha,
			   cp.ValorUnitario, cp.ValorDeclarado, cp.TipoViaje, cp.CondicionesPago, cp.CuotaTonelada,c.rfc
				FROM recoleccion r
				INNER JOIN catalogocliente c ON r.cliente = c.id
				INNER JOIN catalogosucursal s ON r.sucursal = s.id
				INNER JOIN cartaporte cp ON cp.idrecoleccion = r.folio
				LEFT JOIN catalogodestino o ON r.origen = o.id
				LEFT JOIN catalogodestino d ON r.destino = d.id
				WHERE r.folio='".$_GET[folio2]."'";
		
		//echo $s;
		$r = mysql_query($s, $l) or die($s);
		$frd = mysql_fetch_object($r);
		
	}
	function getSucursal($idsucursal,$l){
		$s = "SELECT descripcion FROM catalogosucursal WHERE id = $idsucursal";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		return $f->descripcion;
	}
	
	function getDestinos($iddestino,$l){
		$s = "SELECT descripcion FROM catalogodestino WHERE id = $iddestino";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		return $f->descripcion;
	}
	
	function getidSucursal($idsucursal,$l){
		$s = "SELECT sucursal FROM catalogodestino WHERE id = $idsucursal";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		return $f->sucursal;
	}
	
	$cantidad		= 0;
	$iddorigen		= $_GET[desde];
	$idddestino		= $_GET[hasta];
	$idorigen 		= getidSucursal($_GET[desde],$l);
	$iddestino 		= getidSucursal($_GET[hasta],$l);	
	
	$peso			= 0;
	$tipopaquete 	= $_GET[tipopaquete];
	$seguro 		= 0;
	$costoead		= 0;
	$costorec		= 0;
	$volumen		= 0;
	
	$array = count($_GET['detalle_CANT']);
	for($i = 0; $i < $array; $i++){
		if($_GET['detalle_DESCRIPCION'][$i]<>""){
			$peso += $_GET['detalle_P_TOTAL'][$i];
			$volumen += $_GET['detalle_P_VOLU'][$i];
			$cantidad += $_GET['detalle_CANT'][$i];
			$descripcionDet = $_GET['detalle_DESCRIPCION'][$i];
		}
	}
	
//	die($peso." ".$volumen);
	
	if($_GET[valordeclarado]=="")
		$_GET[valordeclarado] = 5;
	if($_GET[valordeclarado]!=""){
		$s = "SELECT (IF($_GET[valordeclarado]>limite,CEILING(limite/porcada),CEILING($_GET[valordeclarado]/porcada))*costo) +
		(IF($_GET[valordeclarado]>limite,CEILING(($_GET[valordeclarado]-limite)/porcada),0)*costoextra) as vd
		FROM configuradorservicios
		WHERE servicio = 6";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$seguro = $f->vd;
	}
	
	if($_GET[chk1]!=""){
		$s = "select cdd.costoead
		from catalogodestino as cdd 
		where cdd.id = $_GET[hasta]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$costoead = $f->costoead;
	}
	
	if($_GET[chk2]!=""){
		$s = "select cdd.costorecoleccion
		from catalogodestino as cdd 
		where cdd.sucursal = $idorigen";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$costorec = $f->costorecoleccion;
	}
	
	
	
	$s = "select catalogotiempodeentregas.*, hour(current_time) as tiempo 
	from catalogotiempodeentregas where idorigen = $idorigen and iddestino = $iddestino";
	$rm = mysql_query($s,$l) or die($s);
	$fm = mysql_fetch_object($rm);
	
	$horasparamensaje = array(0,12,14,16,8,9,10,11,13,15,17);
	
	if($fm->incrementartiempo==1){
		$s = "select if(current_time>'".$horasparamensaje[$fm->siocurre]."',1,0) as validacion";
		$rpr = mysql_query($s,$l) or die($s);
		$fpr = mysql_fetch_object($rpr);
		if($fpr->validacion=="1"){
			$mashoras = $fm->aincrementar;
		}else{
			$mashoras = 0;
		}
	}else{
		$mashoras = 0;
	}
	
	$tiempoead 		= $fm->tentregaad+$mashoras;
	$tiempoocurre 	= $fm->tentrega+$mashoras;
	
	//$x = "http://www.pmmentuempresa.com/axfbt5asd/cotizar.php?tipopaquete=Paquete&desde=2&hasta=2&largo=150&alto=214&ancho=122&cantidad=50&peso=50";
	
	/*if($cantidad!="" && $cantidad!="0" && $cantidad>1){
		$peso = $peso*$cantidad;
	}*/
	
	
	if($volumen > $peso){
		$peso = $volumen;
	}
	
	$nombreorigen	= getSucursal($idorigen,$l);
	$nombredestino	= getSucursal($iddestino,$l);
	$nombredorigen 	= getDestinos($iddorigen,$l);
	$nombreddestino	= getDestinos($idddestino,$l);
	
	$s = "select catalogotiempodeentregas.*, hour(current_time) as tiempo 
	from catalogotiempodeentregas where idorigen = $idorigen and iddestino = $iddestino";
	$rm = mysql_query($s,$l) or die($s);
	$fm = mysql_fetch_object($rm);
	
	$horasparamensaje = array(0,12,14,16,8,9,10,11,13,15,17);
	
	if($fm->incrementartiempo==1){
		$s = "select if(current_time>'".$horasparamensaje[$fm->siocurre]."',1,0) as validacion";
		$rpr = mysql_query($s,$l) or die($s);
		$fpr = mysql_fetch_object($rpr);
		if($fpr->validacion=="1"){
			$restrinccion = "Si documenta despues de las ".$horasparamensaje[$fm->siocurre]." hrs se incrementaran 
			".$fm->aincrementar."hrs";
			$mashoras = $fm->aincrementar;
		}else{
			$restrinccion = "";
			$mashoras = 0;
		}
	}else{
		$restrinccion = "";
		$mashoras = 0;
	}
	
	/*if($_GET[tipopaquete]=="Envase"){
		$s = "SELECT costo from configuraciondetalles where 
		(SELECT IFNULL(SUM(distancia),0) AS distancia 
		from catalogodistancias where (idorigen=".$idorigen." AND 
		iddestino=".$iddestino.") or (iddestino=".$idorigen." 
		AND idorigen=".$iddestino.")) between zoi and zof and kgi = -1";	
		$rb = mysql_query($s,$l) or die($s); 
		$fb = mysql_fetch_object($rb);
		$costo = round($fb->costo,2);
		//echo "<br>$s<br>";
	}else{
		$s = "select costo from configuraciondetalles where 
		(select IFNULL(SUM(distancia),0) AS distancia 
		from catalogodistancias where (idorigen=".$idorigen." and iddestino=".$iddestino.") 
		or (iddestino=".$idorigen." and idorigen=".$iddestino.")) between zoi and zof
		and ".$peso." between kgi and kgf";
		//echo "<br>$s<br>";
		$rb = mysql_query($s,$l) or die($s);
		$fb = mysql_fetch_object($rb);
		if($fb->costo < 10){
			$costo = round($fb->costo*$peso,2);
		}else{
			$costo = round($fb->costo,2);
		}						
	}	*/
	
		$sx = "SELECT (SELECT iva
		FROM catalogosucursal WHERE id = $idorigen) AS iva, (SELECT ivaretenido
		FROM configuradorgeneral) AS ivar";
		$rx = mysql_query($sx,$l) or die($sx);
		$fxpr = mysql_fetch_object($rx);
		$iva = $fxpr->iva;
		$ivar = $fxpr->ivar;
		
		$sy = "select personamoral from catalogocliente where id = '$_GET[idcliente]'";
		$ry = mysql_query($sy,$l) or die($sy);
		$fpm = mysql_fetch_object($ry);
		$personamoral = $fpm->personamoral;
	
	$s = "SELECT folio, descuentosobreflete, cantidaddescuento,precioporcaja  FROM generacionconvenio 
	WHERE estadoconvenio = 'ACTIVADO' AND idcliente = '$_GET[idcliente]'";
	//echo $s;
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$convenio = ($f->folio=="")?0:$f->folio;
	$porcdescuento = $f->cantidaddescuento;
	$cantdescuento = $f->cantidaddescuento;
	if($f->precioporcaja!="1"){	
		//echo "$convenio, $idorigen, $iddestino, '$descripcionDet', $peso, $cantidad";
	
		$res = $vc->ObtenerFlete($convenio, $idorigen, $iddestino, "$descripcionDet", $peso, $cantidad);
		
		//print_r($res);
		
		$res = split(",",$res);
		$costo = $res[0];
	}else{
		$costo = 0;
		$array = count($_GET['detalle_CANT']);
		for($i = 0; $i < $array; $i++){
			if($_GET['detalle_DESCRIPCION'][$i]!=""){
				$cantidadD = $_GET['detalle_CANT'][$i];
				$descripcion = $_GET['detalle_DESCRIPCION'][$i];
				//echo "$convenio, $idorigen, $iddestino, $descripcion, $peso, $cantidadD";
				$res = $vc->ObtenerFlete($convenio, $idorigen, $iddestino, "$descripcion", $peso, $cantidadD);
				$res = split(",",$res);
				//echo "<br>$res[0]<br>";
				$costo += $res[0];
			}
		}
	}
	
	$cobroead = true;
	$cobrorec = true;

	if($convenio!=0 && $convenio!=''){
		
		$s = "SELECT SUM(IF(idservicio=7,1,0)) ead, SUM(IF(idservicio=8,1,0)) recoleccion
		FROM cconvenio_servicios
		WHERE idconvenio = $convenio AND tipo = 'CONVENIO'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		if($f->ead==1){
			$cobroead = false;
			$costoead = 0;
		}
		if($f->recoleccion==1){
			$cobrorec = false;
			$costorec = 0;
		}
	}
	
	$s = "SELECT costo FROM configuraciondetalles WHERE 
		(SELECT IFNULL(SUM(distancia),0) AS distancia 
		FROM catalogodistancias WHERE (idorigen=".$idorigen." AND 
		iddestino=".$iddestino.") OR (iddestino=".$idorigen." 
		AND idorigen=".$iddestino.")) BETWEEN zoi AND zof AND kgi = 0";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$costo = ($costo<$f->costo)?$f->costo:$costo;
	
	if($convenio!=0){
		$s = "SELECT * FROM cconvenio_configurador_caja
		WHERE descripcion = '$tipopaquete(S)' and idconvenio = $convenio";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$porcdescuento = 0;
			$cantdescuento = 0;
			$etiquetadescuento = "";
		}else{
			if($porcdescuento>0){
				$cantdescuento = $costo*($cantdescuento/100);
				$etiquetadescuento = number_format($porcdescuento,2,".","")." % ($ ".number_format($cantdescuento,2,".",",").")";
			}else{
				$etiquetadescuento = "";
				$cantdescuento = 0;
			}
		}
	}
	
	$s = "SELECT $costo*(cargocombustible/100) as combus FROM configuradorgeneral";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$combustible = $f->combus;
	
	$etiquetadescuento = "";
	$cantdescuento = 0;
	
	if($cobroead==true && $_GET[chk1]!=""){
		if($costo*0.10>$costoead){
			$costoead = $costo*0.10;
		}
	}
	
	if($cobrorec==true && $_GET[chk2]!=""){
		if($costo*0.10>$costorec){
			$costorec = $costo*0.10;
		}
	}
	$subtotal = $costo+$combustible+$seguro+$costorec+$costoead-$cantdescuento;
	$iva = $subtotal * ($iva/100);
	$ivar = (($personamoral=="SI")? ($subtotal * ($ivar/100)) : 0);
	$total = $subtotal + $iva - $ivar;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cotizador</title>
<link href="estiloRastreo.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color:#FFF;
}
-->
</style></head>

<body>
	<table width="765" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td height="60" colspan="2" align="center" valign="middle"><table>
        	  <tr>
        	    <td width="88" rowspan="2">&nbsp;</td>
        	    <td width="450" class="fuenteTitulo">&nbsp;</td>
        	    <td width="120" class="fuenteSubCot">CARTA PORTE</td>
       	      </tr>
        	  <tr>
        	    <td height="24">&nbsp;</td>
        	    <td class="fuenteColumnas" align="center"><?=$frd->Folio?></td>
       	      </tr>
      	  </table></td>
       	</tr>
    	<tr>
    	  <td width="6" height="6" align="center" valign="middle"></td>
    	  <td width="747"></td>
  	  </tr>
      <tr>
    	  <td height="19" align="center" valign="middle" >&nbsp;</td>
    	  <td class="fuenteDatos">Lugar y Fecha:</td>          
  	  </tr>
      <tr>
    	  <td height="19" align="center" valign="middle" >&nbsp;</td>
    	  <td class="fuenteDatos"> <?=$nombreorigen?>,&nbsp; <?=$nombredorigen?>&nbsp; <?=$frd->Fecha?>
          
            	  </span></td>          
  	  </tr>
      
      
      <tr>
            	  <td colspan="6" align="center" class="fuenteSubCot">FACTURAR A:</td>
                  
      </tr>
      <tr>
            	  <td colspan="6" align="LEFT"  class="fuenteDatos">&nbsp;  <?=$frd->ncliente?></td>
      </tr>
    	<tr>
       <tr>
            	  <td colspan="6" align="center" class="fuenteSubCot">DESTINATARIO:</td>
   	  </tr>
  	  
      <tr> 
       
    	  <td height="35" align="center" valign="middle" class="fuenteSubTitulo">&nbsp;</td>
    	  <td>
          	<table width="759">
            	<tr>
        <td width="672" class="texto_bold4"><div  style="overflow:hidden; width:225px; height:11px"><?=$f->ncliente?></div></td>
      </tr>
      <tr>
        <td  class="fuenteDatos" ><div>RFC: <?=$frd->rfc?></div></td>
      </tr>
      <tr>
        <td  class="fuenteDatos" ><div >CALLE:
          <?=$frd->calle?>
          <?=$frd->numero?></div></td>
      </tr>
      <tr>
        <td  class="fuenteDatos" ><div >COL:
          <?=$frd->colonia?>
          C.P.
          <?=$frd->cp?></div></td>
      </tr>
      <tr>
        <td  class="fuenteDatos" ><div>CD:
          <?=$frd->poblacion?></div></td>
      </tr>
      <tr>
        <td class="fuenteDatos"><div>SECTOR:
          <?=$frd->sector?>
          &nbsp;&nbsp;TEL:
          <?=$frd->telefono2?></div></td>
      </tr>

            </table>
          	<table width="755">
           	  <tr>
                  <td  width="121"class="fuenteDatos">
                      VALOR UNITARIO CUOTA CONVENIDA O CARGA FRACCIONADA
                      <?=$frd->ValorUnitario?>
                  </td>
               	  <td width="122"  align="center" class="fuenteDatos">
                  	VALOR DECLARADO
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?=$frd->ValorDeclarado?>
                  </td>
           	      <td width="99" align="center" class="fuenteDatos">
               	    TIPO DE VIAJE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?=$frd->TipoViaje?>           	     
           	      </td>
           	      <td width="121" align="center"  class="fuenteDatos">
                  CONDICIONES DE PAGO
				  <?=$frd->CondicionesPago?> </td>
               	  <td width="121" align="center" class="fuenteDatos">
                  CUOTA POR TONELADA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <?=$frd->CuotaTonelada?>  </td>
       
              </tr>

            </table>
            <table width="752">
           	  <tr>
                  <td  width="57" align="center" class="fuenteDatos">CaNTIDAD</td>
               	  <td width="55"  align="center" class="fuenteDatos">CLASE</td>
           	      <td width="185" align="center" class="fuenteDatos">EL REMITENTE DICE QUE CONTIENE</td>
           	      <td width="38" align="center"  class="fuenteDatos">PESO</td>
                  <td width="110" align="center"  class="fuenteDatos">VOLUMEN</td>
               	  <td width="134" align="center" class="fuenteDatos">CONCEPTO</td>
                  <td width="141" align="center" class="fuenteDatos">IMPORTE</td>
       
              </tr>
            </table>
            <table width="752">
           	  <tr>
                  <td  width="56" align="center" class="fuenteDatos"></td>
               	  <td width="52"  align="center" class="fuenteDatos"></td>
           	      <td width="187" align="center" class="fuenteDatos"></td>
           	      <td width="41" align="center"  class="fuenteDatos"></td>
                  <td width="43" align="center"  class="fuenteDatos">MTS</td>
                  <td width="62" align="center"  class="fuenteDatos">PESO EST.</td>
               	  <td width="136" align="center" class="fuenteDatos"></td>
                  <td width="139" align="center" class="fuenteDatos"></td>
       
              </tr>
          <?
			if($_GET[tipo]==1 || $_GET[tipo]==3){//GUIAS DE VENTANILLA
				$s = "SELECT * FROM recolecciondetalle WHERE recoleccion='".$_GET[folio2]."'";;
				$rd = mysql_query($s,$l) or die($s);
				$fdRX = mysql_fetch_object($rd);
			}
		?>
           	  <tr>
                  <td  width="56" align="center" class="fuenteDatos"><?=$fdRX->cantidad?></td>
               	  <td width="52"  align="center" class="fuenteDatos"><?=$fdRX->descripcion?></td>
           	      <td width="187" align="left" class="fuenteDatos"><?=$fdRX->contenido?></td>
           	      <td width="41" align="center"  class="fuenteDatos"><?=$fdRX->peso?></td>
                  <td width="43" align="center"  class="fuenteDatos"></td>
                  <td width="62" align="center"  class="fuenteDatos"></td>
               	  <td width="136" align="center" class="fuenteDatos"></td>
                  <td width="139" align="center" class="fuenteDatos"></td>
       
              </tr>
               <? $fdRX = mysql_fetch_object($rd); ?>
              <tr>
                  <td  width="56" align="center" class="fuenteDatos"><?=$fdRX->cantidad?></td>
               	  <td width="52"  align="center" class="fuenteDatos"><?=$fdRX->descripcion?></td>
           	      <td width="187" align="left" class="fuenteDatos"><?=$fdRX->contenido?></td>
           	      <td width="41" align="center"  class="fuenteDatos"><?=$fdRX->peso?></td>
                  <td width="43" align="center"  class="fuenteDatos"></td>
                  <td width="62" align="center"  class="fuenteDatos"></td>
               	  <td width="136" align="center" class="fuenteDatos"></td>
                  <td width="139" align="center" class="fuenteDatos"></td>
       
              </tr>
               <? $fdRX = mysql_fetch_object($rd); ?>
              <tr>
                  <td  width="56" align="center" class="fuenteDatos"><?=$fdRX->cantidad?></td>
               	  <td width="52"  align="center" class="fuenteDatos"><?=$fdRX->descripcion?></td>
           	      <td width="187" align="left" class="fuenteDatos"><?=$fdRX->contenido?></td>
           	      <td width="41" align="center"  class="fuenteDatos"><?=$fdRX->peso?></td>
                  <td width="43" align="center"  class="fuenteDatos"></td>
                  <td width="62" align="center"  class="fuenteDatos"></td>
               	  <td width="136" align="center" class="fuenteDatos"></td>
                  <td width="139" align="center" class="fuenteDatos"></td>
       
              </tr>
               <? $fdRX = mysql_fetch_object($rd); ?>
              <tr>
                  <td  width="56" align="center" class="fuenteDatos"><?=$fdRX->cantidad?></td>
               	  <td width="52"  align="center" class="fuenteDatos"><?=$fdRX->descripcion?></td>
           	      <td width="187" align="left" class="fuenteDatos"><?=$fdRX->contenido?></td>
           	      <td width="41" align="center"  class="fuenteDatos"><?=$fdRX->peso?></td>
                  <td width="43" align="center"  class="fuenteDatos"></td>
                  <td width="62" align="center"  class="fuenteDatos"></td>
               	  <td width="136" align="center" class="fuenteDatos"></td>
                  <td width="139" align="center" class="fuenteDatos"></td>
       
              </tr>
               <? $fdRX = mysql_fetch_object($rd); ?>
              <tr>
                  <td  width="56" align="center" class="fuenteDatos"><?=$fdRX->cantidad?></td>
               	  <td width="52"  align="center" class="fuenteDatos"><?=$fdRX->descripcion?></td>
           	      <td width="187" align="left" class="fuenteDatos"><?=$fdRX->contenido?></td>
           	      <td width="41" align="center"  class="fuenteDatos"><?=$fdRX->peso?></td>
                  <td width="43" align="center"  class="fuenteDatos"></td>
                  <td width="62" align="center"  class="fuenteDatos"></td>
               	  <td width="136" align="center" class="fuenteDatos"></td>
                  <td width="139" align="center" class="fuenteDatos"></td>
       
              </tr>
               <? $fdRX = mysql_fetch_object($rd); ?>
              <tr>
                  <td  width="56" align="center" class="fuenteDatos"><?=$fdRX->cantidad?></td>
               	  <td width="52"  align="center" class="fuenteDatos"><?=$fdRX->descripcion?></td>
           	      <td width="187" align="left" class="fuenteDatos"><?=$fdRX->contenido?></td>
           	      <td width="41" align="center"  class="fuenteDatos"><?=$fdRX->peso?></td>
                  <td width="43" align="center"  class="fuenteDatos"></td>
                  <td width="62" align="center"  class="fuenteDatos"></td>
               	  <td width="136" align="center" class="fuenteDatos"></td>
                  <td width="139" align="center" class="fuenteDatos"></td>
       
              </tr>
               <? $fdRX = mysql_fetch_object($rd); ?>
              <tr>
                  <td  width="56" align="center" class="fuenteDatos"><?=$fdRX->cantidad?></td>
               	  <td width="52"  align="center" class="fuenteDatos"><?=$fdRX->descripcion?></td>
           	      <td width="187" align="left" class="fuenteDatos"><?=$fdRX->contenido?></td>
           	      <td width="41" align="center"  class="fuenteDatos"><?=$fdRX->peso?></td>
                  <td width="43" align="center"  class="fuenteDatos"></td>
                  <td width="62" align="center"  class="fuenteDatos"></td>
               	  <td width="136" align="center" class="fuenteDatos"></td>
                  <td width="139" align="center" class="fuenteDatos"></td>
       
              </tr>
               <? $fdRX = mysql_fetch_object($rd); ?>
              <tr>
                  <td  width="56" align="center" class="fuenteDatos"><?=$fdRX->cantidad?></td>
               	  <td width="52"  align="center" class="fuenteDatos"><?=$fdRX->descripcion?></td>
           	      <td width="187" align="left" class="fuenteDatos"><?=$fdRX->contenido?></td>
           	      <td width="41" align="center"  class="fuenteDatos"><?=$fdRX->peso?></td>
                  <td width="43" align="center"  class="fuenteDatos"></td>
                  <td width="62" align="center"  class="fuenteDatos"></td>
               	  <td width="136" align="center" class="fuenteDatos"></td>
                  <td width="139" align="center" class="fuenteDatos"></td>
       
              </tr>
               <? $fdRX = mysql_fetch_object($rd); ?>
              <tr>
                  <td  width="56" align="center" class="fuenteDatos"><?=$fdRX->cantidad?></td>
               	  <td width="52"  align="center" class="fuenteDatos"><?=$fdRX->descripcion?></td>
           	      <td width="187" align="left" class="fuenteDatos"><?=$fdRX->contenido?></td>
           	      <td width="41" align="center"  class="fuenteDatos"><?=$fdRX->peso?></td>
                  <td width="43" align="center"  class="fuenteDatos"></td>
                  <td width="62" align="center"  class="fuenteDatos"></td>
               	  <td width="136" align="center" class="fuenteDatos"></td>
                  <td width="139" align="center" class="fuenteDatos"></td>
       
              </tr>
               <? $fdRX = mysql_fetch_object($rd); ?>
              <tr>
                  <td  width="56" align="center" class="fuenteDatos"><?=$fdRX->cantidad?></td>
               	  <td width="52"  align="center" class="fuenteDatos"><?=$fdRX->descripcion?></td>
           	      <td width="187" align="left" class="fuenteDatos"><?=$fdRX->contenido?></td>
           	      <td width="41" align="center"  class="fuenteDatos"><?=$fdRX->peso?></td>
                  <td width="43" align="center"  class="fuenteDatos"></td>
                  <td width="62" align="center"  class="fuenteDatos"></td>
               	  <td width="136" align="center" class="fuenteDatos"></td>
                  <td width="139" align="center" class="fuenteDatos"></td>
       
              </tr>
               <? $fdRX = mysql_fetch_object($rd); ?> 
               <tr>
                  <td  width="56" align="center" class="fuenteDatos"><?=$fdRX->cantidad?></td>
               	  <td width="52"  align="center" class="fuenteDatos"><?=$fdRX->descripcion?></td>
           	      <td width="187" align="left" class="fuenteDatos"><?=$fdRX->contenido?></td>
           	      <td width="41" align="center"  class="fuenteDatos"><?=$fdRX->peso?></td>
                  <td width="43" align="center"  class="fuenteDatos"></td>
                  <td width="62" align="center"  class="fuenteDatos"></td>
               	  <td width="136" align="center" class="fuenteDatos"></td>
                  <td width="139" align="center" class="fuenteDatos"></td>
       
              </tr>
              

            </table>
          </td>
          
  	  </tr>

    </table>
</body>
</html>