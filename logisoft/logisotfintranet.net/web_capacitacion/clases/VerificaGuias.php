<?
	session_start();
	require_once("Conectar.php");
	
	###############################################################
	# CLASE PARA VERIFICAR LOS TOTALES DE LAS GUIAS Y CORREGIRLOS #
	# DESPUES DE GUARDADOS Y AL FINAL ASIGNAR LOS VALORES A LAS   #
	# VARIABLES...												  #
	###############################################################
	class VerificaGuias{
		public function VerificaGuias(){
		}
		
		public function verificaGuiaVentanilla($folio){
			$cnx = new Conectar("webpmm");
			$l = $cnx->iniciar();
			
			$s = "SELECT subtotal-(tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible) diferencia,
			total-(subtotal+tiva-ivaretenido) diferencia2
			FROM guiasventanilla 
			WHERE id = '$folio'
			HAVING diferencia < -0.1 OR diferencia > 0.1 OR diferencia2 > 0.1 OR diferencia2 < -0.1";
			
			$r = mysql_query($s,$l);
			if(mysql_num_rows($r)>0){
				$s = "SELECT (SELECT iva
				FROM catalogosucursal WHERE id = 
					(SELECT idsucursalorigen FROM guiasventanilla WHERE id = '$folio')
				)/100 AS iva, (SELECT ivaretenido
				FROM configuradorgeneral)/100 AS ivar";
				$res_ivas = mysql_query($s,$l);
				$f_ivas = mysql_fetch_object($res_ivas);
				
				$s = "SELECT personamoral FROM catalogocliente WHERE id = (
					SELECT IF(tipoflete=0,idremitente,iddestinatario)
					FROM guiasventanilla WHERE id = '$folio'
				);";
				$r = mysql_query($s,$l);
				$f = mysql_fetch_object($r);
				$personamoral = $f->personamoral;
				
				$s = "UPDATE guiasventanilla ge
				SET subtotal = tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible,
				tiva = (tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible)*$f_ivas->iva,
				ivaretenido = if('$personamoral'='SI',(tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible)*$f_ivas->ivar,0)
				WHERE id = '$folio';";
				mysql_query($s,$l);
				
				$s = "UPDATE guiasventanilla ge
				SET total = subtotal + tiva - ivaretenido
				WHERE id = '$folio';";
				mysql_query($s,$l);
				
				$s = "select * from guiasventanilla where id = '$folio'";
				$r = mysql_query($s,$l);
				$f = mysql_fetch_object($r);
				
				$_GET[tflete] = $f->tflete;
				$_GET[tdescuento] = $f->tdescuento;
				$_GET[ttotaldescuento] = $f->ttotaldescuento;
				$_GET[tcostoead] = $f->tcostoead;
				$_GET[trecoleccion] = $f->trecoleccion;
				$_GET[tseguro] = $f->tseguro;
				$_GET[totros] = $f->totros;
				$_GET[texcedente] = $f->texcedente;
				$_GET[tcombustible] = $f->tcombustible;
				$_GET[subtotal] = $f->subtotal;
				$_GET[tiva] = $f->tiva;
				$_GET[ivaretenido] = $f->ivaretenido;
				$_GET[total] = $f->total;
			}
		}
		
		public function verificaGuiaVentanillaCS($folio){
			$cnx = new Conectar("webpmm");
			$l = $cnx->iniciar();
			
			$s = "SELECT subtotal-(tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible) diferencia,
			total-(subtotal+tiva-ivaretenido) diferencia2
			FROM guiasventanilla_cs
			WHERE id = '$folio'
			HAVING diferencia < -0.1 OR diferencia > 0.1 OR diferencia2 > 0.1 OR diferencia2 < -0.1";
			
			$r = mysql_query($s,$l);
			if(mysql_num_rows($r)>0){
				$s = "SELECT (SELECT iva
				FROM catalogosucursal WHERE id = 
					(SELECT idsucursalorigen FROM guiasventanilla_cs WHERE id = '$folio')
				)/100 AS iva, (SELECT ivaretenido
				FROM configuradorgeneral)/100 AS ivar";
				$res_ivas = mysql_query($s,$l);
				$f_ivas = mysql_fetch_object($res_ivas);
				
				$s = "SELECT personamoral FROM catalogocliente WHERE id = (
					SELECT IF(tipoflete=0,idremitente,iddestinatario)
					FROM guiasventanilla_cs WHERE id = '$folio'
				);";
				$r = mysql_query($s,$l);
				$f = mysql_fetch_object($r);
				$personamoral = $f->personamoral;
				
				$s = "UPDATE guiasventanilla_cs ge
				SET subtotal = tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible,
				tiva = (tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible)*$f_ivas->iva,
				ivaretenido = if('$personamoral'='SI',(tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible)*$f_ivas->ivar,0)
				WHERE id = '$folio';";
				mysql_query($s,$l);
				
				$s = "UPDATE guiasventanilla_cs ge
				SET total = subtotal + tiva - ivaretenido
				WHERE id = '$folio';";
				mysql_query($s,$l);
				
				$s = "select * from guiasventanilla_cs where id = '$folio'";
				$r = mysql_query($s,$l);
				$f = mysql_fetch_object($r);
				
				$_GET[tflete] = $f->tflete;
				$_GET[tdescuento] = $f->tdescuento;
				$_GET[ttotaldescuento] = $f->ttotaldescuento;
				$_GET[tcostoead] = $f->tcostoead;
				$_GET[trecoleccion] = $f->trecoleccion;
				$_GET[tseguro] = $f->tseguro;
				$_GET[totros] = $f->totros;
				$_GET[texcedente] = $f->texcedente;
				$_GET[tcombustible] = $f->tcombustible;
				$_GET[subtotal] = $f->subtotal;
				$_GET[tiva] = $f->tiva;
				$_GET[ivaretenido] = $f->ivaretenido;
				$_GET[total] = $f->total;
			}
		}
		
		public function verificaGuiaEmpresarial($folio){
			$cnx = new Conectar("webpmm");
			$l = $cnx->iniciar();
			
			$s = "SELECT subtotal-(tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible) diferencia,
			total-(subtotal+tiva-ivaretenido) diferencia2
			FROM guiasempresariales 
			WHERE id = '$folio'
			HAVING diferencia < -0.1 OR diferencia > 0.1 OR diferencia2 > 0.1 OR diferencia2 < -0.1";
			
			$r = mysql_query($s,$l);
			if(mysql_num_rows($r)>0){
				$s = "SELECT (SELECT iva
				FROM catalogosucursal WHERE id = 
					(SELECT idsucursalorigen FROM guiasempresariales WHERE id = '$folio')
				)/100 AS iva, (SELECT ivaretenido
				FROM configuradorgeneral)/100 AS ivar";
				$res_ivas = mysql_query($s,$l);
				$f_ivas = mysql_fetch_object($res_ivas);
				
				$s = "SELECT personamoral FROM catalogocliente WHERE id = (
					SELECT IF(tipoflete='PAGADA',idremitente,iddestinatario)
					FROM guiasempresariales WHERE id = '$folio'
				);";
				$r = mysql_query($s,$l);
				$f = mysql_fetch_object($r);
				$personamoral = $f->personamoral;
				
				$s = "UPDATE guiasempresariales ge
				SET subtotal = tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible,
				tiva = (tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible)*$f_ivas->iva,
				ivaretenido = if('$personamoral'='SI',(tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible)*$f_ivas->ivar,0)
				WHERE id = '$folio';";
				mysql_query($s,$l);
				
				$s = "UPDATE guiasempresariales ge
				SET total = subtotal + tiva - ivaretenido
				WHERE id = '$folio';";
				mysql_query($s,$l);
				
				$s = "select * from guiasempresariales where id = '$folio'";
				$r = mysql_query($s,$l);
				$f = mysql_fetch_object($r);
				
				$_GET[tflete] = $f->tflete;
				$_GET[tdescuento] = $f->tdescuento;
				$_GET[ttotaldescuento] = $f->ttotaldescuento;
				$_GET[tcostoead] = $f->tcostoead;
				$_GET[trecoleccion] = $f->trecoleccion;
				$_GET[tseguro] = $f->tseguro;
				$_GET[totros] = $f->totros;
				$_GET[texcedente] = $f->texcedente;
				$_GET[tcombustible] = $f->tcombustible;
				$_GET[subtotal] = $f->subtotal;
				$_GET[tiva] = $f->tiva;
				$_GET[ivaretenido] = $f->ivaretenido;
				$_GET[total] = $f->total;
			}
		}
		
		#FINAL DE LA CLASE
	}
?>