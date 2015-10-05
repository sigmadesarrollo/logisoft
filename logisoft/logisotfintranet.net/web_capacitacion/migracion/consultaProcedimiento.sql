	DELIMITER $$

			USE `pmm_curso`$$
			
			DROP PROCEDURE IF EXISTS `meterPaquetesV`$$
			
			CREATE PROCEDURE `meterPaquetesV`()
			BEGIN
					DECLARE accion INT DEFAULT 0;
					DECLARE campo_idguia, campo_descripcion, campo_contenido VARCHAR(25);
					DECLARE campo_cantidad, campo_peso, campo_totalpeso, campo_totalpaquetes DOUBLE;
					
					DECLARE traza1 CURSOR FOR 
						SELECT gd.peso, gd.cantidad, gd.descripcion, gd.contenido, gd.idguia, gv.totalpeso, gv.totalpaquetes
						FROM guiaventanilla_detalle gd
						INNER JOIN paralasguias pg ON gd.idguia = pg.folioguia
						INNER JOIN guiasventanilla gv ON pg.folioguia = gv.id
						WHERE gv.estado='ALMACEN DESTINO' ORDER BY gv.id;
					DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET accion = 1;
					
					
			
					SET @i = 1;
					
					OPEN traza1;
						REPEAT
							FETCH traza1 INTO campo_peso,campo_cantidad,campo_descripcion,campo_contenido,campo_idguia,campo_totalpeso,campo_totalpaquetes;
							IF accion = 0 THEN
								SET @j = 1;
								REPEAT 
									
								
									INSERT INTO guiaventanilla_unidades SET idguia=campo_idguia, descripcion=campo_descripcion, 
									contenido=campo_contenido, peso=campo_peso/campo_cantidad, paquete=@i, 
									depaquetes=campo_totalpaquetes, ubicacion = '$_SESSION[IDSUCURSAL]', proceso='ALMACEN DESTINO',
									codigobarras=CONCAT(campo_idguia,LPAD(@i,4,0),LPAD(campo_totalpaquetes,4,0));
									
									SET @i = @i + 1;
									SET @j = @j + 1;
								
								UNTIL @j > campo_cantidad END REPEAT;
								
								IF @i >= campo_totalpaquetes THEN
									SET @i = 1;
								END IF;
							END IF;
						UNTIL accion END REPEAT;
					CLOSE traza1;
				END$$
			
			DELIMITER ;
			
			DELIMITER $$

			USE `pmm_curso`$$
			
			DROP PROCEDURE IF EXISTS `meterPaquetesE`$$
			
			CREATE PROCEDURE `meterPaquetesE`()
			BEGIN
					DECLARE accion INT DEFAULT 0;
					DECLARE campo_idguia, campo_descripcion, campo_contenido VARCHAR(25);
					DECLARE campo_cantidad, campo_peso, campo_totalpeso, campo_totalpaquetes DOUBLE;
					
					DECLARE traza1 CURSOR FOR 
						SELECT gd.peso, gd.cantidad, gd.descripcion, gd.contenido, gd.id idguia, gv.totalpeso, gv.totalpaquetes
						FROM guiasempresariales_detalle gd
						INNER JOIN paralasguias pg ON gd.id = pg.folioguia
						INNER JOIN guiasempresariales gv ON pg.folioguia = gv.id
						WHERE gv.estado='ALMACEN DESTINO' ORDER BY gv.id;
					DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET accion = 1;
					
					
			
					SET @i = 1;
					
					OPEN traza1;
						REPEAT
							FETCH traza1 INTO campo_peso,campo_cantidad,campo_descripcion,campo_contenido,campo_idguia,campo_totalpeso,campo_totalpaquetes;
							IF accion = 0 THEN
								SET @j = 1;
								REPEAT 
								
									INSERT INTO guiasempresariales_unidades SET idguia=campo_idguia, descripcion=campo_descripcion, 
									contenido=campo_contenido, peso=campo_peso/campo_cantidad,paquete=@i, proceso='ALMACEN DESTINO',
									depaquetes=campo_totalpaquetes, ubicacion = '$_SESSION[IDSUCURSAL]',
									codigobarras=CONCAT(campo_idguia,LPAD(@i,4,0),LPAD(campo_totalpaquetes,4,0));					
									
									SET @i = @i + 1;
									SET @j = @j + 1;
								
								UNTIL @j > campo_cantidad END REPEAT;
								
								IF @i >= campo_totalpaquetes THEN
									SET @i = 1;
								END IF;
							END IF;
						UNTIL accion END REPEAT;
					CLOSE traza1;
				END$$
			
			DELIMITER ;