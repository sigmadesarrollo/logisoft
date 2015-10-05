<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "SELECT CONCAT_WS(' ', nombre, apellidopaterno, apellidomaterno) AS nempleado
		FROM catalogoempleado WHERE id = $_GET[valor] and (sucursal = $_GET[sucursal] and (puesto = 29 or id = 0) or $_GET[valor]=0)";	
		
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo $f->nempleado;
	}
	
	if($_GET[accion]==2){
		$s = "SELECT cp.id, cpn.nick, cp.rfc, cp.nombre, cp.paterno, cp.materno,
		d.calle, d.numero, d.colonia, d.cp, d.poblacion, d.municipio,
		d.estado, d.pais, cp.celular, d.telefono, cp.email, cp.personamoral
		FROM catalogoprospecto AS cp
		left JOIN catalogoprospectonick AS cpn ON cp.id = cpn.prospecto
		left JOIN direccion AS d ON cp.id = d.codigo AND d.origen = 'pro'
		WHERE cp.id = $_GET[valor]
		group by cp.id";	
		
		#se quito y se dejo solo de manera informativa
		//and cp.personamoral = '$_GET[personamoral]'
		
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$registros[] = $f;
		}
		
		echo str_replace("null",'""',json_encode($registros));
	}
	
	//guardar servicios
	if($_GET[accion]==3){
		if($_GET[valor]=="1"){
			$s = "select * from convenio_servicios 
			where idusuario = $_SESSION[IDUSUARIO] and isnull(idconvenio) and idservicio = $_GET[idservicio] and tipo='$_GET[tipo]'
			and idservicio = $_GET[idservicio]";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)<1){
				$s = "INSERT INTO convenio_servicios SET servicio = '$_GET[servicio]',
				idservicio = '$_GET[idservicio]', cobro = '$_GET[cobro]', precio = '$_GET[precio]',
				tipo = '$_GET[tipo]', usuario = '$_SESSION[NOMBREUSUARIO]', idusuario = '$_SESSION[IDUSUARIO]',
				fecha = CURRENT_DATE";
				mysql_query(str_replace("''","null",$s),$l) or die($s);
			}
			echo "guardo";
		}
		if($_GET[valor]=="2"){
			$s = "update convenio_servicios SET servicio = '$_GET[servicio]',
			idservicio = '$_GET[idservicio]', cobro = '$_GET[cobro]', precio = '$_GET[precio]',
			tipo = '$_GET[tipo]', usuario = '$_SESSION[NOMBREUSUARIO]', idusuario = '$_SESSION[IDUSUARIO]',
			fecha = CURRENT_DATE where idusuario = $_SESSION[IDUSUARIO] and isnull(idconvenio) and idservicio = $_GET[idservicio] and tipo='$_GET[tipo]'";
			mysql_query(str_replace("''","null",$s),$l) or die($s);
			echo "modifico";
		}
		if($_GET[valor]=="3"){
			$s = "delete from convenio_servicios 
			where idusuario = $_SESSION[IDUSUARIO] and isnull(idconvenio) and servicio = '$_GET[servicio]' and tipo='$_GET[tipo]'";
			mysql_query($s,$l) or die($s);
			echo "elimino";
		}
	}
	
	if($_GET[accion]==4){
		if($_GET[limpiar]=="1"){
			$s = "delete from convenio_servicios_sucursales 
			where idusuario = $_SESSION[IDUSUARIO] and isnull(idconvenio) and tipo='$_GET[tipo]'";
			mysql_query($s,$l) or die($s);
		}
		if($_GET[valor]=="1"){
			$s = "INSERT INTO convenio_servicios_sucursales SET clave = '$_GET[idservicio]',
			nombre = '$_GET[servicio]', tipo = '$_GET[tipo]',
			usuario = '$_SESSION[NOMBREUSUARIO]', idusuario = '$_SESSION[IDUSUARIO]', fecha = CURRENT_DATE";
			mysql_query($s,$l) or die($s);
			echo "bien";
		}
		
		if($_GET[valor]=="2"){
			$s = "delete from convenio_servicios_sucursales 
			where idusuario = $_SESSION[IDUSUARIO] and isnull(idconvenio) and clave = $_GET[idservicio] and tipo='$_GET[tipo]'";
			mysql_query($s,$l) or die($s);
			echo "bien";
		}
		
		if($_GET[valor]=="3"){
			$s = "delete from convenio_servicios_sucursales 
			where idusuario = $_SESSION[IDUSUARIO] and isnull(idconvenio) and tipo='$_GET[tipo]'";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO convenio_servicios_sucursales SET clave = 0,
			nombre = 'TODOS', tipo = '$_GET[tipo]',
			usuario = '$_SESSION[NOMBREUSUARIO]', idusuario = '$_SESSION[IDUSUARIO]', fecha = CURRENT_DATE";
			mysql_query($s,$l) or die($s);
			echo "bien";
		}
		
		if($_GET[valor]=="4"){
			$s = "delete from convenio_servicios_sucursales 
			where idusuario = $_SESSION[IDUSUARIO] and isnull(idconvenio) and tipo='$_GET[tipo]'";
			mysql_query($s,$l) or die($s);
			echo "bien";
		}
	}
	
	
	//para guardar convenios
	if($_GET[accion]==5){
		if($_GET[tipoc]=='PRO'){
			$s = "SELECT * FROM catalogoprospecto WHERE id = $_GET[idprospecto]";
			$r = mysql_query(str_replace("''","null",$s),$l) or die($s);
			if(mysql_num_rows($r)<1){
				die("El prospecto no existe, o ya fue convertido a cliente \npor favor seleccione otro");
			}
		}
		
		$s = "INSERT INTO propuestaconvenio
		SELECT NULL, ".(($_GET[provienedefolio]=="")?"NULL":$_GET[provienedefolio]).", 
		current_date, '$_GET[estado]', '$_GET[estado]', adddate(current_date, interval 15 day), 
		'$_GET[sucursal]', '$_GET[vendedor]', '$_GET[nvendedor]', 
		'$_GET[personamoral]', cp.id, '$_GET[tipoc]', cpn.nick, cp.rfc, cp.nombre, cp.paterno, cp.materno,
		'$_GET[calle]', '$_GET[numero]', '$_GET[colonia]', '$_GET[cp]', '$_GET[poblacion]', '$_GET[municipio]',
		'$_GET[estadodir]', '$_GET[pais]', '$_GET[celular]', '$_GET[telefono]', '$_GET[email]', '$_GET[precioporkg]', 
		'$_GET[precioporcaja]', '$_GET[descuentosobreflete]', 
		'$_GET[cantidaddescuento]', '$_GET[limitekg]', '$_GET[costo]', '$_GET[preciokgexcedente]',
		'$_GET[prepagadas]', '$_GET[consignacionkg]', 
		'$_GET[consignacioncaja]', '$_GET[consignaciondescuento]', 
		'$_GET[consignaciondescantidad]', '$_SESSION[NOMBREUSUARIO]', '$_SESSION[IDUSUARIO]',
		'$_GET[valordeclarado]', '$_GET[limite]', '$_GET[porcada]','$_GET[costoextra]','$_GET[legal]'";
		
		
		if($_GET[tipoc]=='PRO'){
			$s .= " FROM catalogoprospecto AS cp 
			LEFT JOIN catalogoprospectonick AS cpn ON cp.id = cpn.prospecto
			LEFT JOIN direccion AS d ON cp.id = d.codigo AND d.origen = 'pro'
			WHERE cp.id = '$_GET[idprospecto]'
			GROUP BY cp.id";
		}else{
			$s .= " FROM catalogocliente AS cp 
			left JOIN catalogoclientenick AS cpn ON cp.id = cpn.cliente
			left JOIN direccion AS d ON cp.id = d.codigo AND d.origen = 'cl'
			WHERE cp.id = '$_GET[idprospecto]'
			group by cp.id";
		}
		
		mysql_query($s,$l) or die(mysql_error($l).$s);
		$idconvenio = mysql_insert_id($l);
		
		
		$s = "UPDATE lasalertas SET prpeau=prpeau+1;";
		mysql_query($s,$l) or die(mysql_error($l).$s);
		
		$s = "INSERT INTO historialmovimientos(modulo,folio,estado,idusuario,fechamodificacion) 
		VALUES('propuestaconvenio',$idconvenio,'$_GET[estado]',$_SESSION[IDUSUARIO],CURRENT_TIMESTAMP);";
		mysql_query($s,$l) or die(mysql_error($l).$s);
			
		if($_GET[esrenovacion]=="SI"){
			$s = "CALL proc_propuestaBitacora($idconvenio)";
			mysql_query($s,$l) or die(mysql_error($l).$s);
		}
		
		//convenio grids
		if($_GET[precioporkg]==1){
		$s = "UPDATE convenio_configurador_preciokg SET idconvenio = $idconvenio WHERE idusuario = $_SESSION[IDUSUARIO] AND ISNULL(idconvenio) AND tipo = 'CONVENIO'";
		mysql_query($s,$l) or die($s);
		}
		
		if($_GET[precioporcaja]==1){
		$s = "UPDATE convenio_configurador_caja SET idconvenio = $idconvenio WHERE idusuario = $_SESSION[IDUSUARIO] AND ISNULL(idconvenio) AND tipo = 'CONVENIO'";
		mysql_query($s,$l) or die($s);
		}
		//consignacion grids
		if($_GET[consignacionkg]==1){
		$s = "UPDATE convenio_configurador_preciokg SET idconvenio = $idconvenio WHERE idusuario = $_SESSION[IDUSUARIO] AND ISNULL(idconvenio) 
		AND tipo = 'CONSIGNACION'";
		mysql_query($s,$l) or die($s);
		}
		
		if($_GET[consignacioncaja]==1){
		$s = "UPDATE convenio_configurador_caja SET idconvenio = $idconvenio WHERE idusuario = $_SESSION[IDUSUARIO] AND ISNULL(idconvenio) and tipo = 'CONSIGNACION'";
		mysql_query($s,$l) or die($s);
		}
		//servicios
		$s = "UPDATE convenio_servicios SET idconvenio = $idconvenio WHERE idusuario = $_SESSION[IDUSUARIO] AND ISNULL(idconvenio) and tipo = 'CONVENIO'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE convenio_servicios SET idconvenio = $idconvenio WHERE idusuario = $_SESSION[IDUSUARIO] AND ISNULL(idconvenio) and tipo = 'CONSIGNACION'";
		mysql_query($s,$l) or die($s);
		
		//selects convenio
		$s = "UPDATE convenio_servicios_sucursales SET idconvenio = $idconvenio WHERE idusuario = $_SESSION[IDUSUARIO] 
		AND ISNULL(idconvenio) and tipo = 'SRCONVENIO'";
		mysql_query($s,$l) or die($s);
		$s = "UPDATE convenio_servicios_sucursales SET idconvenio = $idconvenio WHERE idusuario = $_SESSION[IDUSUARIO] 
		AND ISNULL(idconvenio) and tipo = 'SUCONVENIO'";
		mysql_query($s,$l) or die($s);
		$s = "UPDATE convenio_servicios_sucursales SET idconvenio = $idconvenio WHERE idusuario = $_SESSION[IDUSUARIO] 
		AND ISNULL(idconvenio) and tipo = 'SUCONSIGNACION2'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE convenio_servicios_sucursales SET idconvenio = $idconvenio WHERE idusuario = $_SESSION[IDUSUARIO] 
		AND ISNULL(idconvenio) and tipo = 'SRCONSIGNACION'";
		mysql_query($s,$l) or die($s);
		$s = "UPDATE convenio_servicios_sucursales SET idconvenio = $idconvenio WHERE idusuario = $_SESSION[IDUSUARIO] 
		AND ISNULL(idconvenio) and tipo = 'SUCONSIGNACION1'";
		mysql_query($s,$l) or die($s);
		
		
		echo "guardo,$idconvenio,$_GET[estado]";
	}
	
	
	//para cargar convenio
	if($_GET[accion]==6){
		//propuesta
		
		$propuesta = "";
		
		$s = "SELECT * FROM propuestaconvenio_motivos WHERE folio = '$_GET[valor]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$motivosnoautorizacion = $f->motivo;
		
		$s = "SELECT folio,renovacionde,DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha,estadopropuesta,tipoautorizacion,
		DATE_FORMAT(vigencia,'%d/%m/%Y') AS vigencia,sucursal,vendedor,nvendedor,personamoral,idprospecto,
		tipo,nick, rfc, nombre, apaterno, amaterno, calle, 
		numero, colonia, cp, poblacion, municipio, estado, pais, celular, telefono, email, 
		precioporkg, precioporcaja, descuentosobreflete, cantidaddescuento, limitekg, costo, preciokgexcedente,
		prepagadas, consignacionkg, consignacioncaja, consignaciondescuento, consignaciondescantidad, 
		usuario, idusuario, valordeclarado, limite, porcada, costoextra,legal FROM propuestaconvenio WHERE folio = $_GET[valor]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->estadopropuesta = cambio_texto($f->estadopropuesta);
		$f->motivo = cambio_texto($motivosnoautorizacion);
		$f->tipoautorizacion = cambio_texto($f->tipoautorizacion);
		$f->nvendedor = cambio_texto($f->nvendedor);
		$f->nick = cambio_texto($f->nick);
		$f->rfc = cambio_texto($f->rfc);
		$f->nombre = cambio_texto($f->nombre);
		$f->apaterno = cambio_texto($f->apaterno);
		$f->amaterno = cambio_texto($f->amaterno);
		$f->calle = cambio_texto($f->calle);
		$f->numero = cambio_texto($f->numero);
		$f->colonia = cambio_texto($f->colonia);
		$f->poblacion = cambio_texto($f->poblacion);
		$f->municipio = cambio_texto($f->municipio);		
		$f->estado = cambio_texto($f->estado);
		$f->pais = cambio_texto($f->pais);
		$f->email = cambio_texto($f->email);
		
		$estado = $f->estadopropuesta;
		$pro = array();
		$pro[] = $f;
		$propuesta = str_replace("null",'""',json_encode($pro));
		
		//if($estado == "PROPUESTA"){
			$s = "DELETE FROM convenio_configurador_caja where isnull(idconvenio) and idusuario = $_SESSION[IDUSUARIO]";
			mysql_query($s,$l) or die($s);
			$s = "DELETE FROM convenio_configurador_preciokg where isnull(idconvenio) and idusuario = $_SESSION[IDUSUARIO]";
			mysql_query($s,$l) or die($s);
			$s = "DELETE FROM convenio_servicios where isnull(idconvenio) and idusuario = $_SESSION[IDUSUARIO]";
			mysql_query($s,$l) or die($s);
			$s = "DELETE FROM convenio_servicios_sucursales where isnull(idconvenio) and idusuario = $_SESSION[IDUSUARIO]";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO convenio_configurador_caja
			SELECT null, descripcion,zona,kmi,kmf,tipo,precio,
			'$_SESSION[NOMBREUSUARIO]','$_SESSION[IDUSUARIO]',CURRENT_DATE,pesolimite,preciokgexcedente
			FROM convenio_configurador_caja
			WHERE idconvenio = '$_GET[valor]'";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO convenio_configurador_preciokg
			SELECT null,zona,kmi,kmf,valor,tipo,'$_SESSION[NOMBREUSUARIO]','$_SESSION[IDUSUARIO]',CURRENT_DATE
			FROM convenio_configurador_preciokg
			WHERE idconvenio = $_GET[valor]";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO convenio_servicios
			SELECT null,idservicio,servicio,cobro,precio,tipo,'$_SESSION[NOMBREUSUARIO]','$_SESSION[IDUSUARIO]',CURRENT_DATE
			FROM convenio_servicios
			WHERE idconvenio = $_GET[valor]";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO convenio_servicios_sucursales
			SELECT null,clave,nombre,tipo,'$_SESSION[NOMBREUSUARIO]','$_SESSION[IDUSUARIO]',CURRENT_DATE
			FROM convenio_servicios_sucursales
			WHERE idconvenio = $_GET[valor]";
			mysql_query($s,$l) or die($s);	
		//}
		
		//servicios grid
		$servicioscobro = "";
		$s = "SELECT servicio, cobro, precio FROM convenio_servicios WHERE idconvenio = $_GET[valor] and tipo = 'CONVENIO'";
		$r = mysql_query($s,$l) or die($s);
		$ser = array();
		while($f = mysql_fetch_object($r)){
			$f->servicio = cambio_texto($f->servicio);
			$ser[] = $f;
		}
		$servicioscobro = str_replace("null",'""',json_encode($ser));
		
		$servicioscobro2 = "";
		$s = "SELECT servicio, cobro, precio FROM convenio_servicios WHERE idconvenio = $_GET[valor] and tipo = 'CONSIGNACION'";
		$r = mysql_query($s,$l) or die($s);
		$ser2 = array();
		while($f = mysql_fetch_object($r)){
			$f->servicio = cambio_texto($f->servicio);
			$ser2[] = $f;
		}
		$servicioscobro2 = str_replace("null",'""',json_encode($ser2));
		
		//servicios combo
		$servicioscombo1 = "";
		$s = "SELECT clave,nombre,tipo FROM convenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SRCONVENIO'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
			$arre[] = $f;
		}
		$servicioscombo1 = str_replace("null",'""',json_encode($arre));
		
		$servicioscombo2 = "";
		$s = "SELECT clave,nombre,tipo FROM convenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SUCONVENIO'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
			$arre[] = $f;
		}
		$servicioscombo2 = str_replace("null",'""',json_encode($arre));
		
		
		$servicioscombo5 = "";
		$s = "SELECT clave,nombre,tipo FROM convenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SUCONSIGNACION2'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
			$arre[] = $f;
		}
		$servicioscombo5 = str_replace("null",'""',json_encode($arre));
		
		// combos de prepagadas
		$servicioscombo3 = "";
		$s = "SELECT clave,nombre,tipo FROM convenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SRCONSIGNACION'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
			$arre[] = $f;
		}
		$servicioscombo3 = str_replace("null",'""',json_encode($arre));
		
		$servicioscombo4 = "";
		$s = "SELECT clave,nombre,tipo FROM convenio_servicios_sucursales WHERE idconvenio = $_GET[valor] and tipo = 'SUCONSIGNACION1'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
			$arre[] = $f;
		}
		$servicioscombo4 = str_replace("null",'""',json_encode($arre));
		echo "[{propuesta:$propuesta, 
				serviciogrid1:$servicioscobro, 
				serviciogrid2:$servicioscobro2, 
				serviciocombo1:$servicioscombo1, 
				serviciocombo2:$servicioscombo2, 
				serviciocombo3:$servicioscombo3, 
				serviciocombo4:$servicioscombo4, 
				serviciocombo5:$servicioscombo5}]";
	}
	
	if($_GET[accion]==7){
		if($_GET[valor]==1){
				$s = "SELECT * FROM convenio_configurador_preciokg 
						where tipo = 'CONVENIO' and idconvenio = $_GET[idconvenio]
						GROUP BY zona";
				$r = mysql_query($s,$l) or die($s);
				$sihay = (mysql_num_rows($r)>0)?1:0;
						if($sihay==1){
							$s = "SELECT * FROM convenio_configurador_preciokg 
							where tipo = 'CONVENIO' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
              <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+55?>">
                <tr>
                  <td height="16" width="55"  class="formato_columnasg">&nbsp;</td>
                  <?
						if($sihay==1){
							$s = "SELECT convenio_configurador_preciokg.*, kmi as zoi, kmf as zof FROM convenio_configurador_preciokg 
							where tipo = 'CONVENIO' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$zona = 1;
						while($f = mysql_fetch_object($r)){
					?>
                  <td height="16" class="formato_columnasg" width="55px" align="center" >Zona
                    <?=$zona?>
                    <br>
                    <?=$f->zoi?>
                    -
                    <?=$f->zof?></td>
                  <?
							$zona++;
						}
					?>
                </tr>
                <tr>
                  <td  class="formato_columnasg" height="16" >Precio KG</td>
                  <?
				  		if($sihay==1){
							$s = "SELECT * FROM convenio_configurador_preciokg 
							where tipo = 'CONVENIO' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$zona = 1;
						while($f = mysql_fetch_object($r)){
					?>
                  <td height="16" ><input type="text" readonly class="estilo_cajadesseleccion"
                  style="width:55; text-align:right" name="caja<?=$zona?>" value="<?=$f->valor?>"
                  onDblClick="seleccionar('caja<?=$zona?>')"></td>
                  <?	
				  		$zona++;
						}
					?>
                </tr>
              </table>
			<?
		}
		
		if($_GET[valor]==2){
			$s = "SELECT * FROM convenio_configurador_caja 
					where tipo = 'CONVENIO' and idconvenio = $_GET[idconvenio]
					GROUP BY zona";
			$r = mysql_query($s,$l) or die($s);
			$sihay = (mysql_num_rows($r)>0)?1:0;
			
						if($sihay==1){
							$s = "SELECT * FROM convenio_configurador_caja 
							where tipo = 'CONVENIO' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
              <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+265?>" id="tablaconveniopreciocaja">
                <tr>
                  <td height="16" width="25"  class="formato_columnasg">
                  	
                    </td>
                  <td height="16" width="80"  class="formato_columnasg">Descripcion</td>
                  <?
						if($sihay==1){
							$s = "SELECT convenio_configurador_caja.*, kmi as zoi, kmf as zof FROM convenio_configurador_caja 
							where tipo = 'CONVENIO' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$zona = 1;
						while($f = mysql_fetch_object($r)){
					?>
                  <td height="16" class="formato_columnasg" width="55px" align="center" >Zona
                    <?=$zona?>
                    <br>
                    <?=$f->zoi?> - <?=$f->zof?></td>
                  <?
							$zona++;
						}
					?>
				  <td height="16" width="80"  class="formato_columnasg" align="right">Peso Limite</td>
				  <td height="16" width="80"  class="formato_columnasg" align="right">Precio Excedente</td>
                </tr>
                <?
					$s = "SELECT * FROM convenio_configurador_caja WHERE tipo='CONVENIO' and idconvenio = $_GET[idconvenio] GROUP BY descripcion";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_array($r)){
				?>
                <tr>
                	<td>&nbsp;</td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='descripcion[]' id='descripcion' value='<?=$f[descripcion]?>'  
					  onDblClick='seleccionar(obtenerIndice(this,"descripcion"),"zona1")'></td>
                    <? 
					$s = "SELECT * FROM convenio_configurador_caja WHERE tipo='CONVENIO' and idconvenio = $_GET[idconvenio] and descripcion = '$f[descripcion]' order by zona";
					$rx = mysql_query($s,$l) or die($s);
					while($fx = mysql_fetch_object($rx)){
					?>
                    <td>
                      <input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:55; text-align:right' name='zona<?=$fx->zona?>[]' id='zona<?=$fx->zona?>' value='<?=$fx->precio?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"zona<?=$fx->zona?>"),"zona<?=$fx->zona?>")'>
                    </td>
                    <? }?>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='pesolimite[]' id='pesolimite' value='<?=$f["pesolimite"]?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"pesolimite"),"zona1")'></td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion' 
					  style='width:80; text-align:right' name='precioexcedente[]' id='precioexcedente' value='<?=$f["preciokgexcedente"]?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"precioexcedente"),"zona1")'></td>
                </tr>
                <?
					}
				?>
              </table>
		<?
		}
		
		if($_GET[valor]==3){
				$s = "SELECT * FROM convenio_configurador_preciokg 
						where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
						GROUP BY zona";
				$r = mysql_query($s,$l) or die($s);
				$sihay = (mysql_num_rows($r)>0)?1:0;
				
						if($sihay==1){
							$s = "SELECT * FROM convenio_configurador_preciokg 
							where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
              <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+55?>">
                <tr>
                  <td height="16" width="55"  class="formato_columnasg">&nbsp;</td>
                  <?
						if($sihay==1){
							$s = "SELECT convenio_configurador_preciokg.*, kmi as zoi, kmf as zof FROM convenio_configurador_preciokg 
							where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$zona = 1;
						while($f = mysql_fetch_object($r)){
					?>
                  <td height="16" class="formato_columnasg" width="55px" align="center" >Zona
                    <?=$zona?>
                    <br>
                    <?=$f->zoi?>
                    -
                    <?=$f->zof?></td>
                  <?
							$zona++;
						}
					?>
                </tr>
                <tr>
                  <td  class="formato_columnasg" height="16" >Precio KG</td>
                  <?
				  		if($sihay==1){
							$s = "SELECT * FROM convenio_configurador_preciokg 
							where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$zona = 1;
						while($f = mysql_fetch_object($r)){
					?>
                  <td height="16" ><input type="text" readonly class="estilo_cajadesseleccion"
                  style="width:55; text-align:right" name="caja<?=$zona?>" value="<?=$f->valor?>"
                  onDblClick="seleccionar('caja<?=$zona?>')"></td>
                  <?	
				  		$zona++;
						}
					?>
                </tr>
              </table>
			<?
		}
		
		if($_GET[valor]==4){
			$s = "SELECT * FROM convenio_configurador_caja 
					where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
					GROUP BY zona";
			$r = mysql_query($s,$l) or die($s);
			$sihay = (mysql_num_rows($r)>0)?1:0;
			
						if($sihay==1){
							$s = "SELECT * FROM convenio_configurador_caja 
							where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
              <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+265?>" id="tablaconveniopreciocaja">
                <tr>
                  <td height="16" width="25"  class="formato_columnasg">
                  	
                    </td>
                  <td height="16" width="80"  class="formato_columnasg">Descripcion</td>
                  <?
						if($sihay==1){
							$s = "SELECT convenio_configurador_caja.*, kmi as zoi, kmf as zof FROM convenio_configurador_caja 
							where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
							GROUP BY zona";
						}else{
							$s = "SELECT configuraciondetalles.*, columna as zona FROM configuraciondetalles 
							GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$zona = 1;
						while($f = mysql_fetch_object($r)){
					?>
                  <td height="16" class="formato_columnasg" width="55px" align="center" >Zona
                    <?=$zona?>
                    <br>
                    <?=$f->zoi?> - <?=$f->zof?></td>
                  <?
							$zona++;
						}
					?>
				  <td height="16" width="80"  class="formato_columnasg" align="right">Peso Limite</td>
				  <td height="16" width="80"  class="formato_columnasg" align="right">Precio Excedente</td>
                </tr>
                <?
					$s = "SELECT * FROM convenio_configurador_caja WHERE tipo='CONSIGNACION' and idconvenio = $_GET[idconvenio] GROUP BY descripcion";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_array($r)){
				?>
                <tr>
                	<td>&nbsp;</td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='descripcion[]' id='descripcion' value='<?=$f[descripcion]?>'  
					  onDblClick='seleccionar(obtenerIndice(this,"descripcion"),"zona1")'></td>
                    <? 
					$s = "SELECT * FROM convenio_configurador_caja WHERE tipo='CONSIGNACION' and idconvenio = $_GET[idconvenio] and descripcion = '$f[descripcion]' order by zona";
					$rx = mysql_query($s,$l) or die($s);
					while($fx = mysql_fetch_object($rx)){
					?>
                    <td>
                      <input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:55; text-align:right' name='zona<?=$fx->zona?>[]' id='zona<?=$fx->zona?>' value='<?=$fx->precio?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"zona<?=$fx->zona?>"),"zona<?=$fx->zona?>")'>
                    </td>
                    <? }?>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='pesolimite[]' id='pesolimite' value='<?=$f["pesolimite"]?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"pesolimite"),"zona1")'></td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion' 
					  style='width:80; text-align:right' name='precioexcedente[]' id='precioexcedente' value='<?=$f["preciokgexcedente"]?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"precioexcedente"),"zona1")'></td>
                </tr>
                <?
					}
				?>
              </table>
		<?
		}
	}
	
	//autorizar o no autorizar propuesta
	if($_GET[accion]==8){		
		$s = "update propuestaconvenio 
		set estadopropuesta  = '$_GET[estado]' ,
		calle='$_GET[calle]', numero='$_GET[numero]', colonia='$_GET[colonia]', cp='$_GET[cp]', poblacion='$_GET[poblacion]', municipio='$_GET[municipio]',
		estado='$_GET[estadodir]', pais='$_GET[pais]', celular='$_GET[celular]', telefono='$_GET[telefono]', email='$_GET[email]'
		where folio = $_GET[folio]";
		mysql_query($s,$l) or die($s);
		echo "modificada,$_GET[estado]";
		
		if($_GET[estado]=='AUTORIZADA'){
			$s = "UPDATE lasalertas SET prau=prau+1;";
			mysql_query($s,$l) OR die(mysql_error($l).$s);
			$s = "UPDATE lasalertas SET prauco=prauco+1;";
			mysql_query($s,$l) OR die(mysql_error($l).$s);
		}

		$s = "UPDATE lasalertas SET prpeau=prpeau-1;";
		mysql_query($s,$l) OR die(mysql_error($l).$s);
		
		
		if($_GET[motivonoautorizar]){
			$s = "INSERT INTO propuestaconvenio_motivos SET folio = '$_GET[folio]', motivo = '$_GET[motivonoautorizar]'";
			mysql_query($s,$l);
		}
		
	}
	
	//insertar tipo propuesta
	if($_GET[accion]==9){
		$s = "insert into propuestaconvenio
		SELECT null,$_GET[folio],current_date,'PROPUESTA', '', vigencia,sucursal,vendedor,nvendedor,
		personamoral,idprospecto,nick,rfc,nombre,apaterno,amaterno,calle,numero,colonia,cp,
		poblacion,municipio,estado,pais,celular,telefono,email,precioporkg,precioporcaja,
		descuentosobreflete,cantidaddescuento,limitekg,costo,preciokgexcedente,prepagadas,consignacionkg,
		consignacioncaja,consignaciondescuento,consignaciondescantidad,'$_SESSION[NOMBREUSUARIO]','$_SESSION[IDUSUARIO]'
		,valordeclarado,limite,porcada,costoextra,legal FROM propuestaconvenio
		where folio = '$_GET[folio]'";
		mysql_query($s,$l) or die($s);		
		
		$nid = mysql_insert_id($l);
		
		$s = "INSERT INTO convenio_configurador_caja
		SELECT $nid, descripcion,zona,kmi,kmf,tipo,precio,
		'$_SESSION[NOMBREUSUARIO]','$_SESSION[IDUSUARIO]',CURRENT_DATE,pesolimite,preciokgexcedente
		FROM convenio_configurador_caja
		WHERE idconvenio = '$_GET[folio]'";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO convenio_configurador_preciokg
		SELECT $nid,zona,kmi,kmf,valor,tipo,'$_SESSION[NOMBREUSUARIO]','$_SESSION[IDUSUARIO]',CURRENT_DATE
		FROM convenio_configurador_preciokg
		WHERE idconvenio = $_GET[folio]";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO convenio_servicios
		SELECT $nid,idservicio,servicio,cobro,precio,tipo,'$_SESSION[NOMBREUSUARIO]','$_SESSION[IDUSUARIO]',CURRENT_DATE
		FROM convenio_servicios
		WHERE idconvenio = $_GET[folio]";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO convenio_servicios_sucursales
		SELECT '$nid',clave,nombre,tipo,'$_SESSION[NOMBREUSUARIO]','$_SESSION[IDUSUARIO]',CURRENT_DATE
		FROM convenio_servicios_sucursales
		WHERE idconvenio = $_GET[folio]";
		mysql_query($s,$l) or die($s);	
		
		echo "renovada,$_GET[estado], $_GET[folio]";
	}
	
	if($_GET[accion]==10){
		$s = "update propuestaconvenio set estadopropuesta = 'EN AUTORIZACION (-)' where folio=$_GET[folio]";
		mysql_query($s,$l) or die($s);
		
		$s = "delete from convenio_configurador_caja where idconvenio=$_GET[folio]";
		mysql_query($s,$l) or die($s);
		$s = "delete from convenio_configurador_preciokg where idconvenio=$_GET[folio]";
		mysql_query($s,$l) or die($s);
		$s = "delete from convenio_servicios where idconvenio and idconvenio=$_GET[folio]";
		mysql_query($s,$l) or die($s);
		$s = "delete from convenio_servicios_sucursales where idconvenio=$_GET[folio]";
		mysql_query($s,$l) or die($s);
		
		//convenio grids
		if($_GET[precioporkg]==1){
		$s = "UPDATE convenio_configurador_preciokg SET idconvenio = $_GET[folio] WHERE idusuario = $_SESSION[IDUSUARIO] AND ISNULL(idconvenio) and tipo = 'CONVENIO'";
		mysql_query($s,$l) or die($s);
		}
		
		if($_GET[precioporcaja]==1){
		$s = "UPDATE convenio_configurador_caja SET idconvenio = $_GET[folio] WHERE idusuario = $_SESSION[IDUSUARIO] AND ISNULL(idconvenio) and tipo = 'CONVENIO'";
		mysql_query($s,$l) or die($s);
		}
		//consignacion grids
		if($_GET[consignacionkg]==1){
		$s = "UPDATE convenio_configurador_preciokg SET idconvenio = $_GET[folio] WHERE idusuario = $_SESSION[IDUSUARIO] AND ISNULL(idconvenio) and tipo = 'CONSIGNACION'";
		mysql_query($s,$l) or die($s);
		}
		
		if($_GET[consignacioncaja]==1){
		$s = "UPDATE convenio_configurador_caja SET idconvenio = $_GET[folio] WHERE idusuario = $_SESSION[IDUSUARIO] AND ISNULL(idconvenio) and tipo = 'CONSIGNACION'";
		mysql_query($s,$l) or die($s);
		}
		//servicios
		$s = "UPDATE convenio_servicios SET idconvenio = $_GET[folio] WHERE idusuario = $_SESSION[IDUSUARIO] AND ISNULL(idconvenio)";
		mysql_query($s,$l) or die($s);
		
		//selects convenio
		$s = "UPDATE convenio_servicios_sucursales SET idconvenio = $_GET[folio] WHERE idusuario = $_SESSION[IDUSUARIO] 
		AND ISNULL(idconvenio)";
		mysql_query($s,$l) or die($s);
		
		
		echo "guardo,EN AUTORIZACION";
	}
	
	if($_GET[accion]==11){
		$s = "(SELECT cp.id, sc.folio, cpn.nick, cp.rfc, cp.nombre, cp.paterno, cp.materno,
		d.calle, d.numero, d.colonia, d.cp, d.poblacion, d.municipio,
		d.estado, d.pais, cp.celular, d.telefono, cp.email, cp.personamoral
		FROM catalogocliente AS cp
		LEFT JOIN catalogoclientenick AS cpn ON cp.id = cpn.cliente
		LEFT JOIN direccion AS d ON cp.id = d.codigo AND d.origen = 'cl'
		LEFT JOIN solicitudcredito as sc on cp.id = sc.cliente
		WHERE cp.id = $_GET[valor] and d.facturacion = 'SI' 
		group by cp.id)
		UNION
		(SELECT cp.id, sc.folio, cpn.nick, cp.rfc, cp.nombre, cp.paterno, cp.materno,
		d.calle, d.numero, d.colonia, d.cp, d.poblacion, d.municipio,
		d.estado, d.pais, cp.celular, d.telefono, cp.email, cp.personamoral
		FROM catalogocliente AS cp
		LEFT JOIN catalogoclientenick AS cpn ON cp.id = cpn.cliente
		LEFT JOIN direccion AS d ON cp.id = d.codigo AND d.origen = 'cl'
		LEFT JOIN solicitudcredito as sc on cp.id = sc.cliente
		WHERE cp.id = $_GET[valor] and d.facturacion <> 'SI' 
		group by cp.id)";	
		
		#se dejo como informativo
		#and cp.personamoral = '$_GET[personamoral]'
		
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
			$f->paterno = cambio_texto($f->paterno);
			$f->materno = cambio_texto($f->materno);
			$f->nick = cambio_texto($f->nick);
			$f->rfc = cambio_texto($f->rfc);
			$f->calle = cambio_texto($f->calle);
			$f->numero = cambio_texto($f->numero);
			$f->colonia = cambio_texto($f->colonia);
			$f->poblacion = cambio_texto($f->poblacion);
			$f->municipio = cambio_texto($f->municipio);
			$f->estado = cambio_texto($f->estado);
			$f->pais = cambio_texto($f->pais);
			$f->email = cambio_texto($f->email);
			$f->personamoral = cambio_texto($f->personamoral);
			$registros[] = $f;
		}
		
		echo str_replace("null",'""',json_encode($registros));
	}
	
	if($_GET[accion]==12){
		$s = "delete from convenio_configurador_caja where isnull(idconvenio) and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		$s = "delete from convenio_configurador_preciokg where isnull(idconvenio) and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		$s = "delete from convenio_servicios where isnull(idconvenio) and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		$s = "delete from convenio_servicios_sucursales where isnull(idconvenio) and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT IFNULL((SELECT MAX(folio)+1 FROM propuestaconvenio),1) AS folio, DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fecha,
		DATE_FORMAT(ADDDATE(CURRENT_DATE, INTERVAL 15 DAY), '%d/%m/%Y') AS fechalimite";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo "({'folio':'$f->folio','fecha':'$f->fecha','fechalimite':'$f->fechalimite'})";
	}
?>