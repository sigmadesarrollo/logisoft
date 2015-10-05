<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if(isset($_POST['enviar'])){
	
	$la_bdatos = "pmm_curso";
	
	ini_set('post_max_size','512M');
	ini_set('upload_max_filesize','512M');
	ini_set('memory_limit','500M');
	ini_set('max_execution_time',600);
	ini_set('limit',-1);
	
	require_once 'Excel/reader.php';
	
	
	function solicitarguias($nombrebase){
		$camposguias = "";
		$insertInicio = "";
		$infoguias = "";
		$contarcampos = 0;
		for($mx=1; $mx<6; $mx++){		
			
			$cajaguias = $nombrebase.$mx;
			
/*			echo $nombrebase."<br>";
			echo $mx."<br>";
			echo $_FILES[$cajaguias]['tmp_name'];
			
			
			echo is_uploaded_file($_FILES[$cajaguias]['tmp_name']);*/
			if (is_uploaded_file($_FILES[$cajaguias]['tmp_name'])) {
				//echo "entro xxx";
				
				$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');
			
				$data->read($_FILES[$cajaguias]['tmp_name']);
				
				error_reporting(E_ALL ^ E_NOTICE);
				
				if($camposguias==""){
					for ($i = 1; $i <= $data->sheets[0]['numCols']; $i++){
						if(str_replace(" ","",$data->sheets[0]['cells'][1][$i])!=""){
							$camposguias .= (($camposguias!="")?",
							":"").$data->sheets[0]['cells'][1][$i]." varchar(255) ";
							$insertInicio .= (($insertInicio!="")?",":"").$data->sheets[0]['cells'][1][$i];
							$contarcampos++;
						}
					}
				}
				
				$datos = "";
				$inicioDatos = "insert into $nombrebase ($insertInicio) values ";
				for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
					$dax = "";
					for ($j = 1; $j <= $contarcampos; $j++) {
						$dax .= (($dax!="")?",":"")."'".str_replace("'","",str_replace("\\","",str_replace('"',"",$data->sheets[0]['cells'][$i][$j])))."'"; 
					}
					if(strlen(str_replace(",","",str_replace("'","",$dax)))>0){
						$datos .= $inicioDatos." ( $dax ); 
						";
					}
				}
				
				$infoguias .= $datos."
				
				";
			}
		
		}
		
		if($camposguias!="" && $infoguias!=""){
			$sqlguias = "CREATE TABLE $nombrebase 
						(
					 $camposguias
					 );
					
					$infoguias
			";
		}else{
			$sqlguias = "";
		}
		return $sqlguias;
	}
	
	
	$nombretablas = array('paralosclientes'.$_SESSION[IDSUCURSAL],'paralasdirecciones'.$_SESSION[IDSUCURSAL],
						  'paralasguias'.$_SESSION[IDSUCURSAL],'paradetalleguia'.$_SESSION[IDSUCURSAL],
						  'paralafacturacion'.$_SESSION[IDSUCURSAL],'paralafacturaciondetalle'.$_SESSION[IDSUCURSAL],'paraloscreditos'.$_SESSION[IDSUCURSAL]);
	$cuerpo = "";
	
	for($lbd = 0; $lbd<count($nombretablas); $lbd++){
		
		$nombrebase = $nombretablas[$lbd];		
		$cuerpoDatos = solicitarguias($nombrebase);
		$cuerpo .= $cuerpoDatos;
		//echo $cuerpoDatos;

	}
	
	$cuerpo .= "
	DELIMITER $$

	USE `".$la_bdatos."`$$
	
	DROP PROCEDURE IF EXISTS `migrarLosDatos".$_SESSION[IDSUCURSAL]."`$$
	
	CREATE PROCEDURE `migrarLosDatos".$_SESSION[IDSUCURSAL]."`()
	BEGIN
	
		ALTER TABLE `".$la_bdatos."`.`paralosclientes".$_SESSION[IDSUCURSAL]."` ADD COLUMN `foliocliente` DOUBLE NULL AFTER `id`;
		ALTER TABLE `".$la_bdatos."`.`paralasdirecciones".$_SESSION[IDSUCURSAL]."` ADD COLUMN `folio` DOUBLE NULL AFTER `codigo`;
		ALTER TABLE `".$la_bdatos."`.`paralasdirecciones".$_SESSION[IDSUCURSAL]."` ADD COLUMN `idfolio` VARCHAR(255) NULL FIRST;
		ALTER TABLE `".$la_bdatos."`.`paralasdirecciones".$_SESSION[IDSUCURSAL]."` ADD COLUMN `ids` DOUBLE NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY(`ids`);
		ALTER TABLE `".$la_bdatos."`.`paralasdirecciones".$_SESSION[IDSUCURSAL]."` ADD INDEX `NewIndex1` (`idfolio`);
		ALTER TABLE `".$la_bdatos."`.`paralasdirecciones".$_SESSION[IDSUCURSAL]."` ADD INDEX `NewIndex2` (`codigo`);
		ALTER TABLE `".$la_bdatos."`.`paralasdirecciones".$_SESSION[IDSUCURSAL]."` ADD INDEX `cp` (`cp`);
		ALTER TABLE `".$la_bdatos."`.`paralasguias".$_SESSION[IDSUCURSAL]."` ADD INDEX `NewIndex1` (`idremitente`);
		ALTER TABLE `".$la_bdatos."`.`paralasguias".$_SESSION[IDSUCURSAL]."` ADD INDEX `NewIndex2` (`idsucursalorigen`);
		ALTER TABLE `".$la_bdatos."`.`paralasguias".$_SESSION[IDSUCURSAL]."` ADD INDEX `NewIndex3` (`iddestino`);
		ALTER TABLE `".$la_bdatos."`.`paralasguias".$_SESSION[IDSUCURSAL]."` ADD INDEX `NewIndex4` (`idsucursaldestino`);
		ALTER TABLE `".$la_bdatos."`.`paralasguias".$_SESSION[IDSUCURSAL]."` ADD INDEX `NewIndex5` (`iddireccionremitente`);
		ALTER TABLE `".$la_bdatos."`.`paralasguias".$_SESSION[IDSUCURSAL]."` ADD INDEX `NewIndex6` (`iddestinatario`);
		ALTER TABLE `".$la_bdatos."`.`paralasguias".$_SESSION[IDSUCURSAL]."` ADD INDEX `NewIndex7` (`iddirecciondestinatario`);
		ALTER TABLE `".$la_bdatos."`.`paralosclientes".$_SESSION[IDSUCURSAL]."` ADD INDEX `NewIndex1` (`foliocliente`);
		ALTER TABLE `".$la_bdatos."`.`paralosclientes".$_SESSION[IDSUCURSAL]."` ADD INDEX `email` (`email`);
		ALTER TABLE `".$la_bdatos."`.`paralosclientes".$_SESSION[IDSUCURSAL]."` ADD INDEX `celular` (`celular`);
		ALTER TABLE `".$la_bdatos."`.`paralosclientes".$_SESSION[IDSUCURSAL]."` ADD INDEX `web` (`web`);
		ALTER TABLE `".$la_bdatos."`.`paralasguias".$_SESSION[IDSUCURSAL]."` ADD COLUMN `folioguia` VARCHAR(25) NULL AFTER `id`;
		ALTER TABLE `".$la_bdatos."`.`paradetalleguia".$_SESSION[IDSUCURSAL]."` ADD COLUMN `folioguia` VARCHAR(25) NULL AFTER `idguia`;
		ALTER TABLE `".$la_bdatos."`.`paralafacturacion".$_SESSION[IDSUCURSAL]."` ADD COLUMN `foliofactura` DOUBLE NULL AFTER `idfolio`,CHANGE `tipofactura` `tipofactura` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ;
		ALTER TABLE `".$la_bdatos."`.`paralafacturaciondetalle".$_SESSION[IDSUCURSAL]."` ADD COLUMN `foliofactura` DOUBLE NULL AFTER `factura`;
		ALTER TABLE `".$la_bdatos."`.`paralafacturacion".$_SESSION[IDSUCURSAL]."` ADD COLUMN `idcliente` DOUBLE NULL AFTER `tipoguia`;
		ALTER TABLE `".$la_bdatos."`.`paralafacturacion".$_SESSION[IDSUCURSAL]."` ADD COLUMN `foliosucursal` DOUBLE NULL AFTER `tipofactura`;
		ALTER TABLE `".$la_bdatos."`.`paralafacturaciondetalle".$_SESSION[IDSUCURSAL]."` ADD COLUMN `folioguia` VARCHAR(25) NULL AFTER `foliofactura`;
		ALTER TABLE `".$la_bdatos."`.`paraloscreditos".$_SESSION[IDSUCURSAL]."` ADD COLUMN `foliocliente` DOUBLE NULL AFTER `cliente`;
		
		#modificar los ids para eliminar los repetidos
		ALTER TABLE `".$la_bdatos."`.`paralasguias".$_SESSION[IDSUCURSAL]."` ADD INDEX `id` (`id`);
		ALTER TABLE `".$la_bdatos."`.`paradetalleguia".$_SESSION[IDSUCURSAL]."` ADD INDEX `idguia` (`idguia`);
		ALTER TABLE `".$la_bdatos."`.`paralafacturacion".$_SESSION[IDSUCURSAL]."` ADD INDEX `idfolio` (`idfolio`);
		ALTER TABLE `".$la_bdatos."`.`paralafacturaciondetalle".$_SESSION[IDSUCURSAL]."` ADD INDEX `factura` (`factura`);
		ALTER TABLE `".$la_bdatos."`.`paralosclientes".$_SESSION[IDSUCURSAL]."` ADD INDEX `id` (`id`);
		ALTER TABLE `".$la_bdatos."`.`paralasdirecciones".$_SESSION[IDSUCURSAL]."` ADD INDEX `codigo` (`codigo`);
		ALTER TABLE `".$la_bdatos."`.`paraloscreditos".$_SESSION[IDSUCURSAL]."` ADD INDEX `cliente` (`cliente`);
		
		/*ALTER TABLE `".$la_bdatos."`.`catalogocolonia` ADD INDEX `NewIndex1` (`cp`);
		ALTER TABLE `".$la_bdatos."`.`catalogocolonia` ADD INDEX `NewIndex2` (`poblacion`);
		ALTER TABLE `".$la_bdatos."`.`catalogopoblacion` ADD INDEX `NewIndex1` (`municipio`);
		ALTER TABLE `".$la_bdatos."`.`catalogomunicipio` ADD INDEX `NewIndex1` (`estado`);
		ALTER TABLE `".$la_bdatos."`.`catalogoestado` ADD INDEX `NewIndex1` (`pais`);
		ALTER TABLE `".$la_bdatos."`.`direccion` ADD INDEX `NewIndex1` (`calle`);
		ALTER TABLE `".$la_bdatos."`.`direccion` ADD INDEX `NewIndex2` (`codigo`);
		ALTER TABLE `".$la_bdatos."`.`catalogosucursal` ADD INDEX `NewIndex1` (`prefijo`);*/
		
		#borrar todos lo repetidos ********************************
		DELETE FROM paralosclientes".$_SESSION[IDSUCURSAL]." WHERE id IN(SELECT pk FROM migclientes);
		DELETE FROM paralasdirecciones".$_SESSION[IDSUCURSAL]." WHERE codigo IN(SELECT pk FROM migclientes);
		DELETE FROM paralafacturacion".$_SESSION[IDSUCURSAL]." WHERE idfolio IN(SELECT pk FROM migfacturas);
		DELETE FROM paralafacturaciondetalle".$_SESSION[IDSUCURSAL]." WHERE factura IN(SELECT pk FROM migfacturas);
		DELETE FROM paralasguias".$_SESSION[IDSUCURSAL]." WHERE id IN(SELECT pk FROM migguias);
		DELETE FROM paradetalleguia".$_SESSION[IDSUCURSAL]." WHERE idguia IN(SELECT pk FROM migguias);
		DELETE FROM paraloscreditos".$_SESSION[IDSUCURSAL]." WHERE cliente IN(SELECT pk FROM migcreditos);
		
		INSERT INTO migclientes (pk)
		SELECT id FROM paralosclientes".$_SESSION[IDSUCURSAL].";
		
		INSERT INTO migfacturas (pk)
		SELECT idfolio FROM paralafacturacion".$_SESSION[IDSUCURSAL].";
		
		INSERT INTO migguias (pk)
		SELECT id FROM paralasguias".$_SESSION[IDSUCURSAL].";
		
		INSERT INTO migcreditos (pk)
		SELECT cliente FROM paraloscreditos".$_SESSION[IDSUCURSAL].";
		#**********************************************************
		
		#insertar los clientes dentro de catalogocliente
		INSERT INTO catalogocliente
		(pk,personamoral,tipocliente,nombre,rfc,email,celular,web)
		SELECT id,IF(personamoral='M','SI','NO'),2,nombre,REPLACE(rfc,'-',''),email,celular,web FROM paralosclientes".$_SESSION[IDSUCURSAL]."
		GROUP BY id;
		#actualizar el folio de los clientes
		
		UPDATE paralosclientes".$_SESSION[IDSUCURSAL]." pc
		INNER JOIN catalogocliente cc ON pc.id = cc.pk
		SET pc.foliocliente = cc.id;
		
		#poner los clientes que tengan null a vacio los apeidos
		UPDATE catalogocliente SET paterno = '' WHERE ISNULL(paterno);
		UPDATE catalogocliente SET materno = '' WHERE ISNULL(materno);
		
		#direcciones
		#poner el cliente al que pertenece la direccion
		UPDATE paralasdirecciones".$_SESSION[IDSUCURSAL]." pd
		INNER JOIN catalogocliente pc ON pd.codigo = pc.pk
		SET pd.folio = pc.id;
		
		#actualizar las direcciones 11 y vacio a 82000
		UPDATE paralasdirecciones".$_SESSION[IDSUCURSAL]." SET cp = 82000 WHERE cp = 11 OR cp = '' OR LENGTH(cp)<5;
		
		
		#inertar en direccion las direcciones del cliente
		INSERT INTO direccion
		(origen,codigo,calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado,pais,usuario,fecha)
		SELECT 'cl', d.folio, d.calle, d.numero, d.crucecalles, d.cp, cc.descripcion, cp.descripcion,
		cm.descripcion, ce.descripcion, cp.descripcion, 1, CURRENT_DATE
		FROM paralasdirecciones".$_SESSION[IDSUCURSAL]." d
		INNER JOIN catalogocodigopostal cpo ON d.cp=cpo.codigopostal
		INNER JOIN catalogocolonia cc ON d.cp = cc.cp AND cc.cp = cpo.codigopostal
		INNER JOIN catalogopoblacion cp ON cc.poblacion=cp.id
		INNER JOIN catalogomunicipio cm ON cp.municipio=cm.id
		INNER JOIN catalogoestado ce ON cm.estado=ce.id
		INNER JOIN catalogopais cpa ON ce.pais=cpa.id 
		WHERE NOT ISNULL(d.folio)
		GROUP BY d.ids;
		
		
		
		#actualizar el id de direccion en las direcciones
		UPDATE paralasdirecciones".$_SESSION[IDSUCURSAL]." pd
		INNER JOIN direccion d ON pd.calle = d.calle AND pd.cp = d.cp
		SET pd.idfolio = d.id;
		
		#actualizar el folio de las direcciones si no tiene codigo postal
		UPDATE paralasdirecciones".$_SESSION[IDSUCURSAL]." pd
		SET cp = 82000 WHERE ISNULL(idfolio);
		
		#insertan las direcciones de 82000 que no tenian.
		INSERT INTO direccion
		(origen,codigo,calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado,pais,usuario,fecha)
		SELECT d.origen, d.folio, d.calle, d.numero, d.crucecalles, d.cp, cc.descripcion, cp.descripcion,
		cm.descripcion, ce.descripcion, cp.descripcion, 1, CURRENT_DATE
		FROM paralasdirecciones".$_SESSION[IDSUCURSAL]." d
		INNER JOIN catalogocodigopostal cpo ON d.cp=cpo.codigopostal
		INNER JOIN catalogocolonia cc ON d.cp = cc.cp AND cc.cp = cpo.codigopostal
		INNER JOIN catalogopoblacion cp ON cc.poblacion=cp.id
		INNER JOIN catalogomunicipio cm ON cp.municipio=cm.id
		INNER JOIN catalogoestado ce ON cm.estado=ce.id
		INNER JOIN catalogopais cpa ON ce.pais=cpa.id 
		WHERE NOT ISNULL(d.folio)
		AND ISNULL(idfolio)
		GROUP BY d.ids;
		
		#se actualizan las ultimas insertadas
		UPDATE paralasdirecciones".$_SESSION[IDSUCURSAL]." pd
		INNER JOIN direccion d ON pd.calle = d.calle AND pd.cp = d.cp
		SET pd.idfolio = d.id
		WHERE ISNULL(pd.idfolio);
		
		#para los creditos
		UPDATE paraloscreditos".$_SESSION[IDSUCURSAL]." pcr
		INNER JOIN catalogocliente plc ON pcr.cliente = plc.pk
		SET pcr.foliocliente = plc.id;

		INSERT INTO solicitudcredito
		(estado,fechaautorizacion, cliente, semanarevision, semanapago, montosolicitado, montoautorizado, diascredito)
		SELECT 'ACTIVADO',CURRENT_DATE, foliocliente, 1,1,montoautorizado, montoautorizado, diascredito FROM paraloscreditos".$_SESSION[IDSUCURSAL].";
		
		#actualizar prefijo GD02 a ZPN
		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." SET prefijo = 'ZPN' WHERE prefijo = 'GD02';
		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." SET idsucursalorigen = 'ZPN' WHERE idsucursalorigen = 'GD02';
		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." SET idsucursaldestino = 'ZPN' WHERE idsucursaldestino = 'GD02';
		
		#actualizar prefijo LEO1 a LE1
		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." SET prefijo = 'LE1' WHERE prefijo = 'LEO1';
		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." SET idsucursalorigen = 'LE1' WHERE idsucursalorigen = 'LEO1';
		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." SET idsucursaldestino = 'LE1' WHERE idsucursaldestino = 'LEO1';
		
		#actualizar prefijo LEO1 a LE1
		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." SET prefijo = 'MZT' WHERE prefijo = 'MZ01';
		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." SET idsucursalorigen = 'MZT' WHERE idsucursalorigen = 'MZ01';
		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." SET idsucursaldestino = 'MZT' WHERE idsucursaldestino = 'MZ01';
		
		
		
		#actualizar los datos de la guia
		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." pg
		INNER JOIN catalogosucursal cs ON pg.prefijo = cs.prefijo
		SET pg.folioguia = CONCAT(cs.idsucursal,LPAD(pg.folio,9,'0'),'Z');

		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." pg
		INNER JOIN catalogosucursal cso ON pg.idsucursalorigen = cso.prefijo
		SET pg.idsucursalorigen = cso.id;

		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." pg
		INNER JOIN catalogosucursal csd ON pg.idsucursaldestino = csd.prefijo
		SET pg.idsucursaldestino = csd.id;

		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." pg
		SET pg.iddestino = (SELECT id  FROM catalogodestino WHERE sucursal = pg.idsucursaldestino LIMIT 1);

		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." pg
		INNER JOIN catalogocliente pc1 ON pg.idremitente = pc1.pk
		SET pg.idremitente = pc1.id;

		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." pg
		INNER JOIN catalogocliente pc1 ON pg.iddestinatario = pc1.pk
		SET pg.iddestinatario = pc1.id;

		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." pg
		SET iddireccionremitente = (SELECT id FROM direccion WHERE codigo = pg.idremitente LIMIT 1);

		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." pg
		SET iddirecciondestinatario = (SELECT id FROM direccion WHERE codigo = pg.iddestinatario LIMIT 1);

		#actualizar el destino



		#actualizar los datos de la guia empresarial
		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." pg
		SET pg.folioguia = CONCAT('999',LPAD(pg.folio,9,'0'),'Z')
		WHERE prefijo = 'EMP';

		#
		/*UPDATE paralasguias".$_SESSION[IDSUCURSAL]." pg
		LEFT JOIN catalogosucursal cs ON pg.prefijo = cs.prefijo
		SET pg.folioguia = CONCAT(cs.idsucursal,LPAD(pg.folio,9,'0'),'Z')
		WHERE pg.prefijo = 'LE1';*/

		#actualizar las direcciones que quedaron como nulas si es que existen para esos clientes
		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." pg
		SET iddireccionremitente = IF(ISNULL(iddireccionremitente),(SELECT id FROM direccion WHERE codigo = pg.idremitente LIMIT 1),iddireccionremitente),
		iddirecciondestinatario = IF(ISNULL(iddirecciondestinatario),(SELECT id FROM direccion WHERE codigo = pg.idremitente LIMIT 1),iddirecciondestinatario)
		WHERE ISNULL(iddireccionremitente) OR ISNULL(iddirecciondestinatario);

		
		#actualizar los datos de la guia empresarial
		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." pg
		SET pg.folioguia = CONCAT('999',LPAD(pg.folio,9,'0'),'Z'),
		iddireccionremitente = (SELECT id FROM direccion WHERE codigo = pg.idremitente LIMIT 1),
		iddirecciondestinatario = (SELECT id FROM direccion WHERE codigo = pg.iddestinatario LIMIT 1)
		WHERE prefijo = 'EMP';
		
		#
		/*UPDATE paralasguias".$_SESSION[IDSUCURSAL]." pg
		LEFT JOIN catalogosucursal cs ON pg.prefijo = cs.prefijo
		SET pg.folioguia = CONCAT(cs.idsucursal,LPAD(pg.folio,9,'0'),'Z')
		WHERE pg.prefijo = 'LE1';*/
		
		#actualizar las direcciones que quedaron como nulas si es que existen para esos clientes
		UPDATE paralasguias".$_SESSION[IDSUCURSAL]." pg
		SET iddireccionremitente = IF(ISNULL(iddireccionremitente),(SELECT id FROM direccion WHERE codigo = pg.idremitente LIMIT 1),iddireccionremitente),
		iddirecciondestinatario = IF(ISNULL(iddirecciondestinatario),(SELECT id FROM direccion WHERE codigo = pg.idremitente LIMIT 1),iddirecciondestinatario)
		WHERE ISNULL(iddireccionremitente) OR ISNULL(iddirecciondestinatario);
		
		/*select * from paralasguias".$_SESSION[IDSUCURSAL]."
		WHERE ISNULL(iddireccionremitente) OR ISNULL(iddirecciondestinatario);
		
		select * from paralasdirecciones".$_SESSION[IDSUCURSAL]." where calle = 'Guadalupe No. 21';
		
		select * from paralasdirecciones".$_SESSION[IDSUCURSAL]." where isnull(idfolio)*/
		
		#insertar las guias en guiasventanilla
		INSERT INTO guiasventanilla
		(id,evaluacion,fecha,fechaentrega,factura,estado,ubicacion,
		entradasalida,tipoflete,ocurre,idsucursalorigen,iddestino,
		idsucursaldestino,condicionpago,idremitente,iddireccionremitente,
		iddestinatario,iddirecciondestinatario,entregaocurre,entregaead,restrinccion,
		totalpaquetes,totalpeso,totalvolumen,emplaye,bolsaempaque,totalbolsaempaque,
		avisocelular,celular,valordeclarado,acuserecibo,cod,recoleccion,observaciones,
		tflete,tdescuento,ttotaldescuento,tcostoead,trecoleccion,tseguro,totros,texcedente,
		tcombustible,subtotal,tiva,ivaretenido,total,nivel,efectivo,cheque,banco,ncheque,
		tarjeta,trasferencia,sector,clienteconvenio,sucursalconvenio,idvendedorconvenio,
		nvendedorconvenio,convenioaplicado,idusuario,usuario,fecha_registro,hora_registro,
		devolucion,recibio,tipoidentificacion,numeroidentificacion,firma)
		SELECT 
		folioguia,evaluacion,CONCAT(SUBSTRING(fecha,7,4),'-',SUBSTRING(fecha,4,2),'-',SUBSTRING(fecha,1,2)),CONCAT(SUBSTRING(fechaentrega,7,4),'-',SUBSTRING(fechaentrega,4,2),'-',SUBSTRING(fechaentrega,1,2)),factura,IF(ISNULL(fechaentrega) OR fechaentrega='0000-00-00' OR fechaentrega='NULL','ALMACEN DESTINO','ENTREGADA'),ubicacion,
		entradasalida,IF(tipoflete='P',0,1),IF(ocurre='S',1,0),idsucursalorigen,iddestino,
		idsucursaldestino,IF(condicionpago='S',1,0),idremitente,iddireccionremitente,
		iddestinatario,iddirecciondestinatario,entregaocurre,entregaead,restrinccion,
		totalpaquetes,totalpeso,totalvolumen,emplaye,bolsaempaque,totalbolsaempaque,
		avisocelular,celular,valordeclarado,IF(acuserecibo='S',1,0),cod,recoleccion,observaciones,
		tflete,tdescuento,ttotaldescuento,tcostoead,trecoleccion,tseguro,totros,texcedente,
		tcombustible,subtotal,tiva,ivaretenido,total,nivel,efectivo,cheque,banco,ncheque,
		tarjeta,trasferencia,sector,clienteconvenio,sucursalconvenio,idvendedorconvenio,
		nvendedorconvenio,convenioaplicado,idusuario,usuario,CONCAT(SUBSTRING(fecha_registro,7,4),'-',SUBSTRING(fecha_registro,4,2),'-',SUBSTRING(fecha_registro,1,2)),hora_registro,
		devolucion,recibio,tipoidentificacion,numeroidentificacion,firma
		FROM paralasguias".$_SESSION[IDSUCURSAL]."
		WHERE folioguia NOT LIKE '999%'
		GROUP BY folioguia;
		

		
		#insertar las guias en guiasempresariales
		INSERT INTO guiasempresariales
		(id,tipoguia,evaluacion,fecha,fechaentrega,factura,estado,ubicacion,
		entradasalida,tipoflete,ocurre,idsucursalorigen,iddestino,
		idsucursaldestino,tipopago,idremitente,iddireccionremitente,
		iddestinatario,iddirecciondestinatario,entregaocurre,entregaead,restrinccion,
		totalpaquetes,totalpeso,totalvolumen,emplaye,bolsaempaque,totalbolsaempaque,
		avisocelular,celular,valordeclarado,acuserecibo,cod,recoleccion,observaciones,
		tflete,tdescuento,ttotaldescuento,tcostoead,trecoleccion,tseguro,totros,texcedente,
		tcombustible,subtotal,tiva,ivaretenido,total,nivel,efectivo,cheque,banco,ncheque,
		tarjeta,trasferencia,sector,clienteconvenio,sucursalconvenio,idvendedorconvenio,
		nvendedorconvenio,convenioaplicado,idusuario,usuario,fecha_registro,hora_registro,
		devolucion,recibio,tipoidentificacion,numeroidentificacion,firma)
		SELECT 
		folioguia,'CONSIGNACION',evaluacion,CONCAT(SUBSTRING(fecha,7,4),'-',SUBSTRING(fecha,4,2),'-',SUBSTRING(fecha,1,2)),CONCAT(SUBSTRING(fechaentrega,7,4),'-',SUBSTRING(fechaentrega,4,2),'-',SUBSTRING(fechaentrega,1,2)),factura,IF(ISNULL(fechaentrega) OR fechaentrega='0000-00-00' OR fechaentrega='NULL','ALMACEN DESTINO','ENTREGADA'),ubicacion,
		entradasalida,IF(tipoflete='P','PAGADA','POR COBRAR'),IF(ocurre='S',1,0),idsucursalorigen,iddestino,
		idsucursaldestino,IF(condicionpago='S','CREDITO','CONTADO'),idremitente,iddireccionremitente,
		iddestinatario,iddirecciondestinatario,entregaocurre,entregaead,restrinccion,
		totalpaquetes,totalpeso,totalvolumen,emplaye,bolsaempaque,totalbolsaempaque,
		avisocelular,celular,valordeclarado,IF(acuserecibo='S',1,0),cod,recoleccion,observaciones,
		tflete,tdescuento,ttotaldescuento,tcostoead,trecoleccion,tseguro,totros,texcedente,
		tcombustible,subtotal,tiva,ivaretenido,total,nivel,efectivo,cheque,banco,ncheque,
		tarjeta,trasferencia,sector,clienteconvenio,sucursalconvenio,idvendedorconvenio,
		nvendedorconvenio,convenioaplicado,idusuario,usuario,CONCAT(SUBSTRING(fecha_registro,7,4),'-',SUBSTRING(fecha_registro,4,2),'-',SUBSTRING(fecha_registro,1,2)),hora_registro,
		devolucion,recibio,tipoidentificacion,numeroidentificacion,firma
		FROM paralasguias".$_SESSION[IDSUCURSAL]."
		WHERE folioguia LIKE '999%'
		GROUP BY folioguia;
		
		#actualizar el detalle de la guia
		UPDATE paradetalleguia".$_SESSION[IDSUCURSAL]." 
		INNER JOIN paralasguias".$_SESSION[IDSUCURSAL]." ON paradetalleguia".$_SESSION[IDSUCURSAL].".idguia = paralasguias".$_SESSION[IDSUCURSAL].".id
		SET paradetalleguia".$_SESSION[IDSUCURSAL].".folioguia = paralasguias".$_SESSION[IDSUCURSAL].".folioguia;
		#insertar el detalle de las guias en guiaventanilla_detalle
		INSERT INTO guiaventanilla_detalle
		(idguia,cantidad,descripcion,contenido,pesou,alto,ancho,largo,peso,volumen)
		SELECT 
		folioguia,cantidad,descripcion,contenido,peso,alto,ancho,largo,peso,volumen
		FROM paradetalleguia".$_SESSION[IDSUCURSAL]." where folioguia not like '999%';
		
		#insertar el detalle de las guias en guiasempresariales_detalle
		INSERT INTO guiasempresariales_detalle
		(id,cantidad,descripcion,contenido,alto,ancho,largo,peso,volumen)
		SELECT 
		folioguia,cantidad,descripcion,contenido,alto,ancho,largo,peso,volumen
		FROM paradetalleguia".$_SESSION[IDSUCURSAL]." where folioguia like '999%';
		
		
		
		UPDATE guiasventanilla g
		INNER JOIN 
		(	SELECT idguia, SUM(cantidad) cantidad
			FROM guiaventanilla_detalle
			WHERE idguia LIKE '%Z'
			GROUP BY idguia
		) d ON g.id = d.idguia
		SET g.totalpaquetes = d.cantidad
		
		#actualizar la cantidad total de paquetes
		UPDATE guiasempresariales g
		INNER JOIN 
		(	SELECT id idguia, SUM(cantidad) cantidad
			FROM guiasempresariales_detalle
			WHERE id LIKE '%Z'
			GROUP BY id
		) d ON g.id = d.idguia
		SET g.totalpaquetes = d.cantidad
		
		#la facturacion
		#actualizar prefijo GD02 a ZPN
		UPDATE paralafacturacion".$_SESSION[IDSUCURSAL]." SET idsucursal = 'ZPN' WHERE idsucursal = 'GD02';
		
		#actualizar prefijo LEO1 a LE1
		UPDATE paralafacturacion".$_SESSION[IDSUCURSAL]." SET idsucursal = 'LE1' WHERE idsucursal = 'LEO1';
		
		#actualizar los campos de la factura
		UPDATE paralafacturacion".$_SESSION[IDSUCURSAL]." pf
		INNER JOIN catalogosucursal cs ON pf.idsucursal = cs.prefijo
		LEFT JOIN catalogocliente pc ON pf.cliente = pc.pk
		SET pf.foliofactura = CONCAT(cs.idsucursal,LPAD(pf.folio,9,'0')),
		pf.idcliente = pc.id,
		pf.foliosucursal = cs.id;
		
		
		
		#actualizar los campos del detalle de factura
		UPDATE paralafacturaciondetalle".$_SESSION[IDSUCURSAL]." pfd
		INNER JOIN paralafacturacion".$_SESSION[IDSUCURSAL]." pf ON pfd.factura = pf.idfolio
		SET pfd.foliofactura = pf.foliofactura;
		
		UPDATE paralafacturaciondetalle".$_SESSION[IDSUCURSAL]." pfd
		INNER JOIN paralasguias".$_SESSION[IDSUCURSAL]." pg ON pfd.guia = pg.id
		SET pfd.folioguia = pg.folioguia;
		
		#insertar la factura
		INSERT INTO facturacion
		(folio,tipofactura,idsucursal,sustitucion,facturaestado,credito,tipoguia,
		cliente,nombrecliente,apellidopaternocliente,apellidomaternocliente,rfc,calle,
		numero,codigopostal,colonia,crucecalles,poblacion,municipio,estado,pais,
		telefono,fax,guiasempresa,guiasnormales,flete,totaldescuento,excedente,
		ead,recoleccion,seguro,combustible,otros,subtotal,iva,ivaretenido,total,
		sobseguro,sobexcedente,sobsubtotal,sobiva,sobivaretenido,sobmontoafacturar,
		otroscantidad,otrosdescripcion,otrosimporte,otrossubtotal,otrosiva,
		otrosivaretenido,otrosmontofacturar,usuario,idusuario,fecha,estadocobranza,
		enrelacion,ivacobrado,ivarcobrado,personamoral,xml,cadenaoriginal,fechacancelacion)
		SELECT 
		(-1)*foliofactura,'NORMAL',foliosucursal,sustitucion,facturaestado,IF(credito='S','SI','NO'),'ventanilla',
		idcliente,nombrecliente,apellidopaternocliente,apellidomaternocliente,rfc,calle,
		numero,codigopostal,colonia,crucecalles,poblacion,municipio,estado,pais,
		telefono,fax,guiasempresa,guiasnormales,flete,totaldescuento,excedente,
		ead,recoleccion,seguro,combustible,otros,IF(stipofactura<>'O',subtotal,0),IF(stipofactura='N',iva,0),IF(stipofactura='N',ivaretenido,0),IF(stipofactura='N',total,0),
		sobseguro,sobexcedente,sobsubtotal,sobiva,sobivaretenido,sobmontoafacturar,
		otroscantidad,otrosdescripcion,otrosimporte,
		IF(stipofactura='O',subtotal,0),
		IF(stipofactura='O',iva,0),
		IF(stipofactura='O',ivaretenido,0),
		IF(stipofactura='O',total,0),
		usuario,idusuario,CONCAT(SUBSTRING(fecha,7,4),'-',SUBSTRING(fecha,4,2),'-',SUBSTRING(fecha,1,2)),estadocobranza,
		enrelacion,ivacobrado,ivarcobrado,personamoral,xml,cadenaoriginal,fechacancelacion
		FROM paralafacturacion".$_SESSION[IDSUCURSAL]."
		GROUP BY foliofactura;
	
		#insertar el detalle de factura
		INSERT INTO facturadetalle
		(factura,folio,tipoguia,fecha,flete,cantidaddescuento,excedente,costoead,costorecoleccion,
		costoseguro,costocombustible,otros,subtotal,iva,ivaretenido,total,tipo)
		SELECT 
		(-1)*foliofactura,folioguia,tipoguia,fecha,flete,0,excedente,costoead,costorecoleccion,
		costoseguro,costocombustible,otros,subtotal,iva,ivaretenido,total,'G'
		FROM paralafacturaciondetalle".$_SESSION[IDSUCURSAL].";
		
		#consultas de verificacion de los estados de la guia
		UPDATE guiasventanilla SET fechaentrega = NULL WHERE fechaentrega = '0000-00-00' AND id LIKE '%Z';

		UPDATE guiasventanilla SET estado = 'ALMACEN DESTINO' WHERE ISNULL(fechaentrega) AND id LIKE '%Z';
		
		UPDATE guiasventanilla gv
		INNER JOIN facturadetalle fd ON gv.id = fd.folio
		INNER JOIN paralasguias9 pg ON gv.id = pg.folioguia
		SET gv.factura = fd.factura;
		
		    END$$

	DELIMITER ;
	";
	
	$fp=fopen('consultas'.$_SESSION[IDSUCURSAL].'.sql','w');        // Abrir el archivo para anexar al final
	fwrite($fp,$cuerpo);            // Escribir en el archivo
	fclose($fp);                    // Cerrrar el archivo
	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Migración del Sistema</title>
<link href="estilosMigracion.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<?
		if(empty($_SESSION[IDSUCURSAL])){
			die("<div id='error'>Favor de loguearse para registrar la información de la migración en su sucursal</div>");
		}
	?>
    <form name="form1" action="migracionPaso3.php" method="post" enctype="multipart/form-data">
    <div id="acomodoCentrado">
	<table width="993" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td width="16" height="175">&nbsp;</td>
            <td width="214"><img src="../../images/pmm-icons/12migracion200x200.png" /></td>
            <td width="13"></td>
            <?
				$s = "select descripcion from catalogosucursal where id = '$_SESSION[IDSUCURSAL]'";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
			?>
            <td width="790" id="estiloTitulo">Migración del sistema <br />
            Sucursal: <a style="color:#C00"><?=$f->descripcion?></a><br />
            Paso 2: Ejecutar Proceso Registro</td>
            <td width="16"></td>
        </tr>
    	<tr>
    	  <td height="23">&nbsp;</td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
  </tr>
  		<tr>
    	  <td height="23">&nbsp;</td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
  </tr>
    	<tr>
    	  <td height="29">&nbsp;</td>
    	  <td colspan="3" id="estiloInstruccion">
          <table>
          	<tr>
            	<td colspan="2">
          		<a style="font-size:18px; color:#060; padding-left:-10px;">Instrucciones:</a>
    	    </td>
            </tr>
          	<tr>
            	<td width="21"></td>
            	<td width="938"><p>En este paso se ejecutará un proceso interno para crear las tablas de los datos que se van a migrar en la base de datos<br />
           	  </p></td>
            </tr>
          	<tr>
          	  <td colspan="2"><a style="font-size:18px; color:#F60; padding-left:-10px;">Advertencia:</a></td>
       	    </tr>
          	<tr>
          	  <td></td>
          	  <td>No cerrar la página y esperar hasta que termine</td>
       	    </tr>
          </table>
          </td>
    	  <td></td>
  	  </tr>
    	<tr>
    	  <td height="19">&nbsp;</td>
    	  <td colspan="3">&nbsp;</td>
    	  <td></td>
  	  </tr>
    	<tr>
    	  <td height="29">&nbsp;</td>
    	  <td colspan="3" align="center">
          		<input type="submit" name="enviar" value="Siguiente" class="button" />
          </td>
    	  <td></td>
  	  </tr>
    	<tr>
    	  <td height="15"></td>
    	  <td colspan="3" align="center"></td>
    	  <td></td>
  	  </tr>
    </table>
    </div>
    </form>
</body>
</html>