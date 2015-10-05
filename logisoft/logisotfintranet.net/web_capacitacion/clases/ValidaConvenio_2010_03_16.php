<?
	session_start();
	include("Conectar.php");
	
	class ValidaConvenio{
		var $idremitente				= 0;
		var $iddestinatario				= 0;
		var $tipoenvio 					= 0;
		var $ocurre 					= 0;
		var $iddestino 					= 0;
		var $idsucdestino 				= 0;
		var $datosdestino 				= 0;
		var $gv_remitente_credito		= false;
		var $gv_remitente_convenio		= false;
		var $gv_remitente_descripciones = "";
		var $gv_remitente_persmor		= "";
		var $gv_destinatario_credito 	= false;
		var $gv_destinatario_convenio	= false;
		var $gv_destinatario_descripciones = "";
		var $gv_destinatario_persmor	= "";
		var $gv_remitente_servrest		= "";
		var $gv_destinatario_servrest	= "";
		var $gv_remitente_oserv			= "";
		var $gv_destinatario_oserv		= "";
		var $gv_remitente_sucur			= "";
		var $gv_destinatario_sucur		= "";
		
		var $gv_sucdestino_servicioead 	= false;
		var $gv_remitente_servdest 		= "";
		var $gv_destinatario_servdest 	= "";
		
		
		function ValidaConvenio($idremitente, $iddestinatario, $iddestino, $idsucdestino){	
			$this->idremitente 		= $idremitente;
			$this->iddestinatario	= $iddestinatario;
			$this->iddestino		= $iddestino;
			$this->idsucdestino		= $idsucdestino;
						
			$cnx = new Conectar("webpmm");
			$l = $cnx->iniciar();
			
			if($this->idremitente!=""){
				$s = "SELECT vendedor, nvendedor, precioporkg, precioporcaja, descuentosobreflete, cantidaddescuento,
				prepagadas, limitekg, costo, preciokgexcedente, consignaciondescantidad
				FROM generacionconvenio WHERE idcliente = $this->idremitente AND 
				vigencia > CURRENT_DATE AND generacionconvenio.estadoconvenio = 'ACTIVADO'";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)>0){
					$f = mysql_fetch_object($r);
					$this->rem_idvendedor 	= $f->vendedor;
					$this->rem_nvendedor 	= $f->nvendedor;
					
					$this->rem_precioporkg 	= $f->precioporkg;
					$this->rem_precioporcaja 	= $f->precioporcaja;
					$this->rem_descuentosobreflete 	= $f->descuentosobreflete;
					$this->rem_cantidaddescuento = $f->cantidaddescuento;
					$this->rem_consignaciondescuento = $f->consignaciondescantidad;
					
					$this->rem_prepagadas = $f->prepagadas;
					$this->rem_limitekg = $f->limitekg;
					$this->rem_costo = $f->costo;
					$this->rem_preciokgexcedente = $f->preciokgexcedente;
				}else{
					$this->rem_idvendedor 	= 0;
					$this->rem_nvendedor 	= 0;
					
					$this->rem_precioporkg 	= 0;
					$this->rem_precioporcaja 	= 0;
					$this->rem_descuentosobreflete 	= 0;
					$this->rem_cantidaddescuento = 0;
					
					$this->rem_prepagadas = 0;
					$this->rem_limitekg = 0;
					$this->rem_costo = 0;
					$this->rem_preciokgexcedente = 0;
				}
			}
			
			if($this->iddestinatario!=""){	
				$s = "SELECT vendedor, nvendedor, precioporkg, precioporcaja, descuentosobreflete, cantidaddescuento,
				prepagadas, limitekg, costo, preciokgexcedente 
				FROM generacionconvenio WHERE idcliente = $this->iddestinatario AND vigencia > CURRENT_DATE AND generacionconvenio.estadoconvenio = 'ACTIVADO'";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)>0){
					$f = mysql_fetch_object($r);
					$this->des_idvendedor 	= $f->vendedor;
					$this->des_nvendedor 	= $f->nvendedor;
					
					$this->des_precioporkg 	= $f->precioporkg;
					$this->des_precioporcaja 	= $f->precioporcaja;
					$this->des_descuentosobreflete 	= $f->descuentosobreflete;
					$this->des_cantidaddescuento = $f->cantidaddescuento;
					
					$this->des_prepagadas = $f->prepagadas;
					$this->des_limitekg = $f->limitekg;
					$this->des_costo = $f->costo;
					$this->des_preciokgexcedente = $f->preciokgexcedente;
				}else{
					$this->des_idvendedor 	= 0;
					$this->des_nvendedor 	= 0;
					
					$this->des_precioporkg 	= 0;
					$this->des_precioporcaja 	= 0;
					$this->des_descuentosobreflete 	= 0;
					$this->des_cantidaddescuento = 0;
					
					$this->des_prepagadas = 0;
					$this->des_limitekg = 0;
					$this->des_costo = 0;
					$this->des_preciokgexcedente = 0;
				}
			}
			
			//CONVENIO
			if($this->idremitente!=""){
				$s = "SELECT foliocredito, personamoral, activado FROM catalogocliente WHERE id = $this->idremitente";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
				//echo "<br>$f->foliocredito!='' && $f->foliocredito!='0'<br>$s<br>";
				if($f->foliocredito!="" && $f->foliocredito!="0"){
					$this->gv_remitente_credito = true;
					$this->gv_remitente_creditoactivado = $f->activado;
				}
				$this->gv_remitente_persmor = $f->personamoral;
				
				$s = "SELECT gc.folio 
				FROM generacionconvenio AS gc
				WHERE gc.idcliente = $this->idremitente AND CURRENT_DATE < vigencia AND gc.estadoconvenio = 'ACTIVADO'";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$f = mysql_fetch_object($r);
					$this->gv_remitente_convenio = $f->folio;
				}else{
					$this->gv_remitente_convenio = 0;
				}
				
				$s = "SELECT gc.folio, css.clave, css.nombre 
				FROM generacionconvenio AS gc
				INNER JOIN cconvenio_servicios_sucursales AS css ON css.idconvenio = gc.folio
				WHERE gc.idcliente = $this->idremitente AND CURRENT_DATE < vigencia AND tipo = 'SRCONVENIO' AND gc.estadoconvenio = 'ACTIVADO'
				GROUP BY css.clave";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$arre[] = $f;
					}
					$this->gv_remitente_servrest = str_replace("null",'""',json_encode($arre));					
				}else{
					$this->gv_remitente_servrest = "''";
				}
				
				$s = "SELECT css.clave, css.nombre 
				FROM generacionconvenio AS gc
				INNER JOIN cconvenio_servicios_sucursales AS css ON css.idconvenio = gc.folio
				WHERE gc.idcliente = $this->idremitente AND CURRENT_DATE < vigencia AND tipo = 'SUCONVENIO' AND gc.estadoconvenio = 'ACTIVADO'
				GROUP BY css.clave";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$arre[] = $f;
					}
					$this->gv_remitente_sucur = str_replace("null",'""',json_encode($arre));
				}else{
					$this->gv_remitente_sucur = "''";
				}
				
				$s = "SELECT css.idservicio, css.servicio 
				FROM generacionconvenio AS gc
				INNER JOIN cconvenio_servicios AS css ON css.idconvenio = gc.folio
				WHERE gc.idcliente = $this->idremitente AND CURRENT_DATE < vigencia AND tipo = 'CONVENIO' AND gc.estadoconvenio = 'ACTIVADO'
				GROUP BY css.idservicio";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$arre[] = $f;
					}
					$this->gv_remitente_oserv = str_replace("null",'""',json_encode($arre));
				}else{
					$this->gv_remitente_oserv = "''";
				}
				
				$s = "SELECT cd.id,t1.descripcion FROM (
					SELECT descripcion
					FROM generacionconvenio AS gc
					INNER JOIN cconvenio_configurador_caja ON gc.folio = cconvenio_configurador_caja.idconvenio
					WHERE gc.idcliente = $this->idremitente and cconvenio_configurador_caja.tipo = 'CONVENIO' AND gc.estadoconvenio = 'ACTIVADO'
					GROUP BY descripcion
				) AS t1
				INNER JOIN catalogodescripcion AS cd ON t1.descripcion = cd.descripcion";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$f->descripcion = $this->cambiartexto($f->descripcion);
						$arre[] = $f;
					}
					$this->gv_remitente_descripciones = str_replace("null",'""',json_encode($arre));
				}else{
					$this->gv_remitente_descripciones = "''";
				}
			}
			if($this->iddestinatario!=""){
				$s = "SELECT foliocredito, personamoral, activado FROM catalogocliente WHERE id = $this->iddestinatario";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
				//echo "<br>$f->foliocredito!='' && $f->foliocredito!='0'<br>$s<br>";
				if($f->foliocredito!="" && $f->foliocredito!="0"){
					$this->gv_destinatario_credito = true;
					$this->gv_destinatario_creditoactivado = $f->activado;
				}
				$this->gv_destinatario_persmor = $f->personamoral;
					
					
				$s = "SELECT gc.folio 
				FROM generacionconvenio AS gc
				WHERE gc.idcliente = $this->iddestinatario AND CURRENT_DATE < vigencia AND gc.estadoconvenio = 'ACTIVADO'";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$f = mysql_fetch_object($r);
					$this->gv_destinatario_convenio = $f->folio;
				}else{
					$this->gv_destinatario_convenio = 0;
				}
				
				$s = "SELECT gc.folio, css.clave, css.nombre 
				FROM generacionconvenio AS gc
				INNER JOIN cconvenio_servicios_sucursales AS css ON css.idconvenio = gc.folio
				WHERE gc.idcliente = $this->iddestinatario AND CURRENT_DATE < vigencia AND tipo = 'SRCONVENIO' AND gc.estadoconvenio = 'ACTIVADO'
				GROUP BY css.clave";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$arre[] = $f;
					}
					$this->gv_destinatario_servrest = str_replace("null",'""',json_encode($arre));
				}else{
					$this->gv_destinatario_servrest = "''";
				}
				
				$s = "SELECT css.clave, css.nombre 
				FROM generacionconvenio AS gc
				INNER JOIN cconvenio_servicios_sucursales AS css ON css.idconvenio = gc.folio
				WHERE gc.idcliente = $this->iddestinatario AND CURRENT_DATE < vigencia AND tipo = 'SUCONVENIO' AND gc.estadoconvenio = 'ACTIVADO'
				GROUP BY css.clave";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$arre[] = $f;
					}
					$this->gv_destinatario_sucur = str_replace("null",'""',json_encode($arre));
				}else{
					$this->gv_destinatario_sucur = "''";
				}
				
				$s = "SELECT css.idservicio, css.servicio 
				FROM generacionconvenio AS gc
				INNER JOIN cconvenio_servicios AS css ON css.idconvenio = gc.folio
				WHERE gc.idcliente = $this->iddestinatario AND CURRENT_DATE < vigencia AND tipo = 'CONVENIO' AND gc.estadoconvenio = 'ACTIVADO'
				GROUP BY css.idservicio";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$arre[] = $f;
					}
					$this->gv_destinatario_oserv = str_replace("null",'""',json_encode($arre));
				}else{
					$this->gv_destinatario_oserv = "''";
				}
				
				$s = "SELECT cd.id,t1.descripcion FROM (
					SELECT descripcion
					FROM generacionconvenio AS gc
					INNER JOIN cconvenio_configurador_caja ON gc.folio = cconvenio_configurador_caja.idconvenio
					WHERE gc.idcliente = $this->iddestinatario and cconvenio_configurador_caja.tipo = 'CONVENIO' AND gc.estadoconvenio = 'ACTIVADO'
					GROUP BY descripcion
				) AS t1
				INNER JOIN catalogodescripcion AS cd ON t1.descripcion = cd.descripcion";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$f->descripcion = $this->cambiartexto($f->descripcion);
						$arre[] = $f;
					}
					$this->gv_destinatario_descripciones = str_replace("null",'""',json_encode($arre));
				}else{
					$this->gv_destinatario_descripciones = "''";
				}
			}
			
			//CONSIGNACION
			if($this->idremitente!=""){				
				$s = "SELECT gc.folio, css.clave, css.nombre 
				FROM generacionconvenio AS gc
				INNER JOIN cconvenio_servicios_sucursales AS css ON css.idconvenio = gc.folio
				WHERE gc.idcliente = $this->idremitente AND CURRENT_DATE < vigencia AND tipo = 'SRCONSIGNACION' AND gc.estadoconvenio = 'ACTIVADO'
				GROUP BY css.clave";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$arre[] = $f;
					}
					$this->ge_remitente_servrest = str_replace("null",'""',json_encode($arre));					
				}else{
					$this->ge_remitente_servrest = "''";
				}
				
				$s = "SELECT css.clave, css.nombre 
				FROM generacionconvenio AS gc
				INNER JOIN cconvenio_servicios_sucursales AS css ON css.idconvenio = gc.folio
				WHERE gc.idcliente = $this->idremitente AND CURRENT_DATE < vigencia AND tipo = 'SUCONSIGNACION2' AND gc.estadoconvenio = 'ACTIVADO'
				GROUP BY css.clave";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$arre[] = $f;
					}
					$this->ge_remitente_sucur = str_replace("null",'""',json_encode($arre));
				}else{
					$this->ge_remitente_sucur = "''";
				}
				
				$s = "SELECT css.idservicio, css.servicio 
				FROM generacionconvenio AS gc
				INNER JOIN cconvenio_servicios AS css ON css.idconvenio = gc.folio
				WHERE gc.idcliente = $this->idremitente AND CURRENT_DATE < vigencia AND tipo = 'CONSIGNACION' AND gc.estadoconvenio = 'ACTIVADO'
				GROUP BY css.idservicio";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$arre[] = $f;
					}
					$this->ge_remitente_oserv = str_replace("null",'""',json_encode($arre));
				}else{
					$this->ge_remitente_oserv = "''";
				}
				
				$s = "SELECT cd.id,t1.descripcion FROM (
					SELECT descripcion
					FROM generacionconvenio AS gc
					INNER JOIN cconvenio_configurador_caja ON gc.folio = cconvenio_configurador_caja.idconvenio
					WHERE gc.idcliente = $this->idremitente and cconvenio_configurador_caja.tipo = 'CONSIGNACION' AND gc.estadoconvenio = 'ACTIVADO'
					GROUP BY descripcion
				) AS t1
				INNER JOIN catalogodescripcion AS cd ON t1.descripcion = cd.descripcion";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$f->descripcion = $this->cambiartexto($f->descripcion);
						$arre[] = $f;
					}
					$this->ge_remitente_descripciones = str_replace("null",'""',json_encode($arre));
				}else{
					$this->ge_remitente_descripciones = "''";
				}
			}
			if($this->iddestinatario!=""){
				
				$s = "SELECT gc.folio, css.clave, css.nombre 
				FROM generacionconvenio AS gc
				INNER JOIN cconvenio_servicios_sucursales AS css ON css.idconvenio = gc.folio
				WHERE gc.idcliente = $this->iddestinatario AND CURRENT_DATE < vigencia AND tipo = 'SRCONSIGNACION' AND gc.estadoconvenio = 'ACTIVADO'
				GROUP BY css.clave";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$arre[] = $f;
					}
					$this->ge_destinatario_servrest = str_replace("null",'""',json_encode($arre));
				}else{
					$this->ge_destinatario_servrest = "''";
				}
				
				$s = "SELECT css.clave, css.nombre 
				FROM generacionconvenio AS gc
				INNER JOIN cconvenio_servicios_sucursales AS css ON css.idconvenio = gc.folio
				WHERE gc.idcliente = $this->iddestinatario AND CURRENT_DATE < vigencia AND tipo = 'SUCONSIGNACION' AND gc.estadoconvenio = 'ACTIVADO'
				GROUP BY css.clave";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$arre[] = $f;
					}
					$this->ge_destinatario_sucur = str_replace("null",'""',json_encode($arre));
				}else{
					$this->ge_destinatario_sucur = "''";
				}
				
				$s = "SELECT css.idservicio, css.servicio 
				FROM generacionconvenio AS gc
				INNER JOIN cconvenio_servicios AS css ON css.idconvenio = gc.folio
				WHERE gc.idcliente = $this->iddestinatario AND CURRENT_DATE < vigencia AND tipo = 'CONSIGNACION' AND gc.estadoconvenio = 'ACTIVADO'
				GROUP BY css.idservicio";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$arre[] = $f;
					}
					$this->ge_destinatario_oserv = str_replace("null",'""',json_encode($arre));
				}else{
					$this->ge_destinatario_oserv = "''";
				}
				
				$s = "SELECT cd.id,t1.descripcion FROM (
					SELECT descripcion
					FROM generacionconvenio AS gc
					INNER JOIN cconvenio_configurador_caja ON gc.folio = cconvenio_configurador_caja.idconvenio
					WHERE gc.idcliente = $this->iddestinatario and cconvenio_configurador_caja.tipo = 'CONSIGNACION' AND gc.estadoconvenio = 'ACTIVADO'
					GROUP BY descripcion
				) AS t1
				INNER JOIN catalogodescripcion AS cd ON t1.descripcion = cd.descripcion";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)){
					$arre = array();
					while($f = mysql_fetch_object($r)){
						$f->descripcion = $this->cambiartexto($f->descripcion);
						$arre[] = $f;
					}
					$this->ge_destinatario_descripciones = str_replace("null",'""',json_encode($arre));
				}else{
					$this->ge_destinatario_descripciones = "''";
				}
			}
			
			if($this->iddestino!=""){
				$s = "SELECT costo FROM configuraciondetalles WHERE 
				(SELECT IFNULL(SUM(distancia),0) AS distancia 
				FROM catalogodistancias WHERE (idorigen=".$_SESSION[IDSUCURSAL]." AND iddestino=".$this->idsucdestino.") 
				OR (iddestino=".$_SESSION[IDSUCURSAL]." AND idorigen=".$this->idsucdestino.")) BETWEEN zoi AND zof
				AND 1 BETWEEN kgi AND kgf";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)>0){
					$f = mysql_fetch_object($r);
					$this->tarifaminima = $f->costo;
				}else{
					$this->tarifaminima = 0;
				}
				
				$s = "SELECT id,descripcion,sucursal,poblacion,costoead,costorecoleccion,restringiread,
				restringireadapfsinconvenio,restringirrecoleccion,restringirporcobrar,
				deshabilitarconvenio,todasemana,lunes,martes,miercoles,jueves,viernes
				FROM catalogodestino WHERE id = $this->iddestino";
				$r = mysql_query($s,$l) or die($s);
				$arre = array();
				if(mysql_num_rows($r)>0){
					$f 						= mysql_fetch_object($r);
					$arre[] = $f;
					$this->datosdestino 	= str_replace("null","''",json_encode($arre));
				}else{
					$this->datosdestino 	= "''";
				}
			}
			
			//TARIFA MINIMA PERMITIDA
			$s = "SELECT tarifaminimakg FROM configuradorgeneral";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)>0){
					$f = mysql_fetch_object($r);
					$this->tarifaminimapermitida 	= $f->tarifaminimakg;
				}else{
					$this->tarifaminimapermitida 	= 0;
				}
		}
		function getJsonDataVentanilla(){
			$res = "[{";
			if( $this->idremitente !=""){
				$res .= "datosremitente:{
					   idremitente:'$this->idremitente',
					   convenio:'$this->gv_remitente_convenio',
					   tarifaminima:'$this->tarifaminimapermitida',
					   precioporkg:'$this->rem_precioporkg',
					   precioporcaja:'$this->rem_precioporcaja',
					   descuentosobreflete:'$this->rem_descuentosobreflete',
					   cantidaddescuento:'$this->rem_cantidaddescuento',
					   consignaciondescuento:'$this->rem_consignaciondescuento',
					   vendedorconvenio:'$this->rem_nvendedor',
					   
					   prepagadas:'$this->rem_prepagadas',
					   limitekg:'$this->rem_limitekg',
					   costo:'$this->rem_costo',
					   preciokgexcedente:'$this->rem_preciokgexcedente',
					   
					   idvendedorconvenio:'$this->rem_idvendedor',
					   descripciones:$this->gv_remitente_descripciones,
					   credito:'$this->gv_remitente_credito',
					   creditoactivado:'$this->gv_remitente_creditoactivado',
					   personamoral:'$this->gv_remitente_persmor',
					   serviciosrestringidos:$this->gv_remitente_servrest,
					   sucursales:$this->gv_remitente_sucur,
					   otrosservicios:$this->gv_remitente_oserv,
					   descripcionese:$this->ge_remitente_descripciones,
					   serviciosrestringidose:$this->ge_remitente_servrest,
					   sucursalese:$this->ge_remitente_sucur,
					   otrosserviciose:$this->ge_remitente_oserv
				  }";
			}
			if( $this->iddestinatario !=""){
				$res .= ",
				  datosdestinatario:{
					   iddestinatario:'$this->iddestinatario',
					   convenio:'$this->gv_destinatario_convenio',
					   tarifaminima:'$this->tarifaminimapermitida',
					   precioporkg:'$this->des_precioporkg',
					   precioporcaja:'$this->des_precioporcaja',
					   descuentosobreflete:'$this->des_descuentosobreflete',
					   cantidaddescuento:'$this->des_cantidaddescuento',
					   vendedorconvenio:'$this->des_nvendedor',
					   
					   prepagadas:'$this->des_prepagadas',
					   limitekg:'$this->des_limitekg',
					   costo:'$this->des_costo',
					   preciokgexcedente:'$this->des_preciokgexcedente',
					   
					   idvendedorconvenio:'$this->des_idvendedor',
					   descripciones:$this->gv_destinatario_descripciones,
					   credito:'$this->gv_destinatario_credito',
					   creditoactivado:'$this->gv_destinatario_creditoactivado',
					   personamoral:'$this->gv_destinatario_persmor',
					   serviciosrestringidos:$this->gv_destinatario_servrest,
					   sucursales:$this->gv_destinatario_sucur,
					   otrosservicios:$this->gv_destinatario_oserv,
					   descripcionese:$this->ge_destinatario_descripciones,
					   serviciosrestringidose:$this->ge_destinatario_servrest,
					   sucursalese:$this->ge_destinatario_sucur,
					   otrosserviciose:$this->ge_destinatario_oserv
				  }";
			}
			
			if($this->iddestino!=""){
				$res .= ",
					destino:$this->datosdestino,
					tarifaminima:$this->tarifaminima
				";
			}
			$res .= "}]";
			
			return $res;
		}
		
		function obtenerExcedente($convenio, $idorigen, $iddestino, $descripcion, $peso, $cant_merc){
			$this->convenio 		= $convenio;
			$this->idorigen			= $idorigen;
			$this->iddestino		= $iddestino;
			$this->descripcion		= $descripcion;
			$this->peso				= $peso;
			$this->cant_merc 		= $cant_merc;
						
			$cnx = new Conectar("webpmm");
			$l = $cnx->iniciar();		
			
			if($this->convenio!=0){
			$s = "SELECT precioporkg, precioporcaja, descuentosobreflete, cantidaddescuento FROM generacionconvenio WHERE folio=".$this->convenio." AND generacionconvenio.estadoconvenio = 'ACTIVADO'";
			$rf = mysql_query($s,$l) or die($s);
			$fs = mysql_fetch_object($rf); $excedente = 0;			
				if($fs->precioporcaja == 1){
					$s = "SELECT precio, pesolimite, preciokgexcedente FROM cconvenio_configurador_caja
					WHERE idconvenio=".$this->convenio." AND descripcion='".$this->descripcion."'
					AND tipo='CONVENIO' AND
					(SELECT IFNULL(SUM(distancia),0) AS distancia FROM catalogodistancias WHERE 
					(idorigen=".$this->idorigen." AND iddestino=".$this->iddestino.") or 
					(iddestino=".$this->idorigen." AND idorigen=".$this->iddestino.")) BETWEEN kmi AND kmf
					GROUP BY zona";
					$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
					$excedekg = 0;
					if(mysql_num_rows($r)>0){
						if($this->peso > ($f->pesolimite*$this->cant_merc)){
							$excedekg = $this->peso - ($f->pesolimite*$this->cant_merc);
						}
					}
					return $excedekg;
				}else{					
					return "0";
				}
			}else{
				return "0";
			}
		}
		function obtenerExcedenteEmp($convenio, $idorigen, $iddestino, $descripcion, $peso, $cant_merc){
			$this->convenio 		= $convenio;
			$this->idorigen			= $idorigen;
			$this->iddestino		= $iddestino;
			$this->descripcion		= $descripcion;
			$this->peso				= $peso;
			$this->cant_merc2 		= $cant_merc;
						
			$cnx = new Conectar("webpmm");
			$l = $cnx->iniciar();		
			
			if($this->convenio!=0){
			$s = "SELECT prepagadas, costo, consignacionkg, consignacioncaja,consignaciondescuento, consignaciondescantidad, limitekg 
			FROM generacionconvenio WHERE folio=".$this->convenio." AND generacionconvenio.estadoconvenio = 'ACTIVADO'";
			$rf = mysql_query($s,$l) or die($s);
			$fs = mysql_fetch_object($rf); 
			$excedente = 0;	
				if($fs->prepagadas == 1){
					$excedekg=0;
					//se borro no es asi
					//if($this->peso > ($fs->limitekg*$this->cant_merc2)){
					//	$excedekg = $this->peso - ($fs->limitekg*$this->cant_merc2);
					//}
					return 0;
					//return $excedekg;
				}elseif($fs->consignacioncaja == 1){
					$s = "SELECT precio, pesolimite, preciokgexcedente FROM cconvenio_configurador_caja
					WHERE idconvenio=".$this->convenio." AND descripcion='".$this->descripcion."'
					AND tipo='CONSIGNACION' AND
					(SELECT IFNULL(SUM(distancia),0) AS distancia FROM catalogodistancias WHERE 
					(idorigen=".$this->idorigen." AND iddestino=".$this->iddestino.") or 
					(iddestino=".$this->idorigen." AND idorigen=".$this->iddestino.")) BETWEEN kmi AND kmf
					GROUP BY zona";
					$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
					$excedekg = 0;
					if(mysql_num_rows($r)>0){
						if($this->peso > ($f->pesolimite*$this->cant_merc2)){
							$excedekg = $this->peso - ($f->pesolimite*$this->cant_merc2);
						}
					}
					return $excedekg;
				}else{
					return 0;
				}			
			}else{
				return 0;
			}
		}
		
		function ObtenerFlete($convenio, $idorigen, $iddestino, $descripcion, $peso, $cant_merc){
			$this->convenio 		= $convenio;
			$this->idorigen			= $idorigen;
			$this->iddestino		= $iddestino;
			$this->descripcion		= $descripcion;
			$this->peso				= $peso;
			$this->cant_merc 		= $cant_merc;
						
			$cnx = new Conectar("webpmm");
			$l = $cnx->iniciar();		
			
			if($this->convenio!=0){
			$s = "SELECT precioporkg, precioporcaja, descuentosobreflete, cantidaddescuento FROM generacionconvenio WHERE folio=".$this->convenio." AND generacionconvenio.estadoconvenio = 'ACTIVADO'";
			$rf = mysql_query($s,$l) or die($s);
			$fs = mysql_fetch_object($rf); $excedente = 0;			
				if($fs->precioporcaja == 1){
					$s = "SELECT precio, pesolimite, preciokgexcedente FROM cconvenio_configurador_caja
					WHERE idconvenio=".$this->convenio." AND descripcion='".$this->descripcion."'
					AND tipo='CONVENIO' AND
					(SELECT IFNULL(SUM(distancia),0) AS distancia FROM catalogodistancias WHERE 
					(idorigen=".$this->idorigen." AND iddestino=".$this->iddestino.") or 
					(iddestino=".$this->idorigen." AND idorigen=".$this->iddestino.")) BETWEEN kmi AND kmf
					GROUP BY zona";
					$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
					if(mysql_num_rows($r)>0){
						if($this->peso > ($f->pesolimite*$this->cant_merc)){
							$excedekg = $this->peso - ($f->pesolimite*$this->cant_merc);
							$excedente = $f->preciokgexcedente * $excedekg;
						}
						if($excedente=="")
							$excedente="0";
						$array = ($f->precio*$this->cant_merc).','.$excedente;
						return $array;
					}else{
						$cantidad = $this->getFleteSC($this->descripcion, $this->iddestino, $this->idorigen, $this->peso);
						$array = ($cantidad-($cantidad*($fs->cantidaddescuento/100))).',0';
						return $array;
					}
					
				}else if($fs->precioporkg == 1){					
					$s = "SELECT valor FROM cconvenio_configurador_preciokg
					WHERE idconvenio=".$this->convenio." AND tipo='CONVENIO' AND
					(SELECT IFNULL(SUM(distancia),0) AS distancia FROM catalogodistancias WHERE 
					(idorigen=".$this->idorigen." AND iddestino=".$this->iddestino.") or 
					(iddestino=".$this->idorigen." AND idorigen=".$this->iddestino.")) BETWEEN kmi AND kmf
					GROUP BY zona";				
					$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
					
						if($excedente=="")
							$excedente="0";
						$array = ($peso*$f->valor).','.$excedente;
					return $array;
					//return $f->valor;		
				
				}else if($fs->descuentosobreflete == 1){
					$cantidad = $this->getFleteSC($this->descripcion, $this->iddestino, $this->idorigen, $this->peso);
					$array = ($cantidad-($cantidad*($fs->cantidaddescuento/100))).',0';
					return $array;
				}else{
					return $this->getFleteSC($this->descripcion, $this->iddestino, $this->idorigen, $this->peso).",0";
				}
			}else{
				return $this->getFleteSC($this->descripcion, $this->iddestino, $this->idorigen, $this->peso).",0";
			}
		}
		function ObtenerFleteEmp($convenio, $idorigen, $iddestino, $descripcion, $peso, $cant_merc){
			$this->convenio 		= $convenio;
			$this->idorigen			= $idorigen;
			$this->iddestino		= $iddestino;
			$this->descripcion		= $descripcion;
			$this->peso				= $peso;
			$this->cant_merc2 		= $cant_merc;
						
			$cnx = new Conectar("webpmm");
			$l = $cnx->iniciar();		
			
			if($this->convenio!=0){
			$s = "SELECT prepagadas, costo, consignacionkg, consignacioncaja,consignaciondescuento, consignaciondescantidad, limitekg, preciokgexcedente
			FROM generacionconvenio WHERE folio=".$this->convenio." AND generacionconvenio.estadoconvenio = 'ACTIVADO'";
			$rf = mysql_query($s,$l) or die($s);
			$fs = mysql_fetch_object($rf); 
			$excedente = 0;	
			$seexcedio = 0;
				if($fs->prepagadas == 1){
					//se cambio no era asi
					//if($this->peso > ($fs->limitekg*$this->cant_merc2)){
					//	$excedekg = $this->peso - ($fs->limitekg*$this->cant_merc2);
					//	$seexcedio = $excedekg*$fs->preciokgexcedente;
					//}
					//return "$fs->costo,$seexcedio";	
					return "$fs->costo,0";	
				}elseif($fs->consignacioncaja == 1){
					$s = "SELECT precio, pesolimite, preciokgexcedente FROM cconvenio_configurador_caja
					WHERE idconvenio=".$this->convenio." AND descripcion='".$this->descripcion."'
					AND tipo='CONSIGNACION' AND
					(SELECT IFNULL(SUM(distancia),0) AS distancia FROM catalogodistancias WHERE 
					(idorigen=".$this->idorigen." AND iddestino=".$this->iddestino.") or 
					(iddestino=".$this->idorigen." AND idorigen=".$this->iddestino.")) BETWEEN kmi AND kmf
					GROUP BY zona";
					$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
					if(mysql_num_rows($r)>0){
						if($this->peso > ($f->pesolimite*$this->cant_merc2)){
							$excedekg = $this->peso - ($f->pesolimite*$this->cant_merc2);
							$excedente = $f->preciokgexcedente * $excedekg;
						}
						if($excedente=="")
							$excedente="0";
						$array = ($f->precio*$this->cant_merc2).','.$excedente;
						return $array;
					}else{
						$cantidad = $this->getFleteSC($this->descripcion, $this->iddestino, $this->idorigen, $this->peso);
						$array = ($cantidad-($cantidad*($fs->consignaciondescantidad/100))).',0';
						return $array;
					}
					
				}else if($fs->consignacionkg == 1){					
					$s = "SELECT valor FROM cconvenio_configurador_preciokg
					WHERE idconvenio=".$this->convenio." AND tipo='CONSIGNACION' AND
					(SELECT IFNULL(SUM(distancia),0) AS distancia FROM catalogodistancias WHERE 
					(idorigen=".$this->idorigen." AND iddestino=".$this->iddestino.") or 
					(iddestino=".$this->idorigen." AND idorigen=".$this->iddestino.")) BETWEEN kmi AND kmf
					GROUP BY zona";				
					$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
					
						if($excedente=="")
							$excedente="0";
					$array = ($peso*$f->valor).','.$excedente;
					return $array;
						//return $f->valor;	
				}else if($fs->consignaciondescuento == 1){
					$cantidad = $this->getFleteSC($this->descripcion, $this->iddestino, $this->idorigen, $this->peso);
					$array = ($cantidad-($cantidad*($fs->consignaciondescantidad/100))).',0';
					return $array;
				}else{
					return $this->getFleteSC($this->descripcion, $this->iddestino, $this->idorigen, $this->peso).",0";
				}			
			}else{
				return $this->getFleteSC($this->descripcion, $this->iddestino, $this->idorigen, $this->peso).",0";
			}
		}
		
		function getFleteSC($descripcion, $iddestino, $idorigen, $peso){
			$cnx = new Conectar("webpmm");
			$l = $cnx->iniciar();	
			
			if($descripcion=="ENVASE(S)"){
				$s = "SELECT costo from configuraciondetalles where 
				(SELECT IFNULL(SUM(distancia),0) AS distancia 
				from catalogodistancias where (idorigen=".$idorigen." AND 
				iddestino=".$iddestino.") or (iddestino=".$idorigen." 
				AND idorigen=".$iddestino.")) between zoi and zof and kgi = -1";	
				$rb = mysql_query($s,$l) or die($s); 
				$fb = mysql_fetch_object($rb);
				$dato = round($fb->costo,2);
				return $dato;
				//return round($fb->costo,2);
				
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
						$costo = round($fb->costo*$this->peso,2);
						return $costo;
					}else{
						$costo = round($fb->costo,2);
						return $costo;
					}						
			}
		}
		
		function cambiartexto($texto){
			if($texto == " ")
				$texto = "";
			if($texto!=""){
				$n_texto=ereg_replace("á","&#224;",$texto);
				$n_texto=ereg_replace("é","&#233;",$n_texto);
				$n_texto=ereg_replace("í","&#237;",$n_texto);
				$n_texto=ereg_replace("ó","&#243;",$n_texto);
				$n_texto=ereg_replace("ú","&#250;",$n_texto);
				
				$n_texto=ereg_replace("Á","&#193;",$n_texto);
				$n_texto=ereg_replace("É","&#201;",$n_texto);
				$n_texto=ereg_replace("Í","&#205;",$n_texto);
				$n_texto=ereg_replace("Ó","&#211;",$n_texto);
				$n_texto=ereg_replace("Ú","&#218;",$n_texto);
				
				$n_texto=ereg_replace("ñ", "&#241;", $n_texto);
				$n_texto=ereg_replace("Ñ", "&#209;", $n_texto);
				$n_texto=ereg_replace("¿", "&#191;", $n_texto);
				return $n_texto;
			}else{
				return "&#32;";
			}
		}
	}
?>