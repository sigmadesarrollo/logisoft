<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");	
	
	if($_GET[accion]==1){ //asignacionguiasempresariales.php
		if($_GET[tiposol]!=""){
			if($_GET[tiposol]=="PREPAGADA"){
				$inner = "AND generacionconvenio.prepagadas = 1 AND CURRENT_DATE<generacionconvenio.vigencia";
			}elseif($_GET[tiposol]=="CONSIGNACION"){
				$inner = "
				AND (generacionconvenio.consignacioncaja=1 OR generacionconvenio.consignacionkg=1 OR generacionconvenio.consignaciondescuento=1)
				AND CURRENT_DATE<generacionconvenio.vigencia";
			}
		}		
		$s = "SELECT sc.montoautorizado FROM solicitudcredito sc WHERE sc.cliente=$_GET[idcliente]";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$montoautorizado = $f->montoautorizado;
			$creditoactivado = $f->activado;
		}else{
			$montoautorizado = 0;
			$creditoactivado = 'NO';
		}
		
		$s = "select $montoautorizado-sum(restar) as disponible,'a' as grupo from(
			SELECT IFNULL(SUM(IF(pagado='N', total,0)),0) AS restar
			FROM pagoguias 
			WHERE cliente = '$_GET[idcliente]' AND credito='SI' AND tipo<>'EMPRESARIAL'
		)as t
		group by grupo";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);	
		$disponible = $f->disponible;
		
		$s = "SELECT generacionconvenio.folio, catalogocliente.id, catalogocliente.nombre, 
		catalogocliente.paterno, catalogocliente.materno, catalogocliente.personamoral,
		limitekg, costo, preciokgexcedente, prepagadas, consignacionkg, consignacioncaja, consignaciondescuento, consignaciondescantidad,
		cg.cargocombustible, cg.ivaretenido, cs.iva, generacionconvenio.sucursal
		FROM catalogocliente 
		inner JOIN generacionconvenio ON catalogocliente.id = generacionconvenio.idcliente 
		INNER JOIN configuradorgeneral AS cg ON 1 = cg.id
		LEFT JOIN catalogosucursal AS cs ON generacionconvenio.sucursal = cs.id
		WHERE catalogocliente.id = '$_GET[idcliente]'
		AND CURRENT_DATE < generacionconvenio.vigencia 
		AND generacionconvenio.estadoconvenio = 'ACTIVADO'";
		
		
		$r = mysql_query($s,$l) or die($s);
		$arre 			= array();
		$f 				= mysql_fetch_object($r);
		$folio 			= $f->folio;
		$f->nombre		= cambio_texto($f->nombre);
		$f->paterno		= cambio_texto($f->paterno);
		$f->materno		= cambio_texto($f->materno);
		$f->disponible  = $disponible;
		
		$f->desactivado = "NO";		
		$f->estadocredito = "";
		$f->tienecredito = "";
		$s = "SELECT cc.activado, sc.estado FROM catalogocliente cc
		INNER JOIN solicitudcredito sc ON cc.id = sc.cliente
		WHERE id = '$_GET[idcliente]'";
		$g = mysql_query($s,$l) or die($s);
		$gc = mysql_fetch_object($g);
		if(mysql_num_rows($g)>0){
			if($gc->activado=="NO" || $gc->estado=="BLOQUEADO"){
				$f->desactivado = "SI";				
			}
			$f->estadocredito = cambio_texto($gc->estado);
			$f->tienecredito = "SI";
		}		
		$arre[]		 	= $f;
		$datoscliente	= str_replace("null", '""',json_encode($arre));
		$sucursales		= '""';
		$servicios		= '""';
		if($folio!=""){
			$s = "SELECT clave,nombre,tipo FROM convenio_servicios_sucursales WHERE idconvenio = $folio and tipo = 'SUCONSIGNACION2'";
			$r = mysql_query($s,$l) or die($s);
			$arre = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$arre[] = $f;
				}
				$sucursales = str_replace("null",'""',json_encode($arre));
			}
			// combos de prepagadas
			$s = "SELECT clave,nombre,tipo FROM convenio_servicios_sucursales WHERE idconvenio = $folio and tipo = 'SRCONSIGNACION'";
			$r = mysql_query($s,$l) or die($s);
			$arre = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$arre[] = $f;
				}
				$servicios = str_replace("null",'""',json_encode($arre));
			}
		}
		$solicitudes = "";
		$s = "SELECT preocon, cantidad FROM solicitudguiasempresarialesnw WHERE folio = ".$_GET[solicitud]."";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		$f = mysql_fetch_object($r);
		$arre[]	= $f;
		$solicitudes = str_replace("null",'""',json_encode($arre));
		
		$s = "SELECT sd.idsucursal FROM solicitudcredito sc
		INNER JOIN solicitudcreditosucursaldetalle sd ON sc.folio = sd.solicitud
		WHERE sd.idsucursal IN(0,$_SESSION[IDSUCURSAL]) AND sc.cliente = $_GET[idcliente] AND sc.estado = 'ACTIVADO'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$creditable = "SI";
		}else{
			$creditable = "NO";
		}
		echo "[{datoscliente:$datoscliente,
				servicios:$servicios,
				sucursales:$sucursales,
				solicitudes:$solicitudes,
				creditable:'$creditable'}]";
	}
	
	if($_GET[accion]==2){ //asignacionguiasempresariales.php
		/*if($_GET[valor]==3){
				$s = "SELECT * FROM cconvenio_configurador_preciokg 
						where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
						GROUP BY zona";
				$r = mysql_query($s,$l) or die($s);
				$sihay = (mysql_num_rows($r)>0)?1:0;
				
						if($sihay==1){
							$s = "SELECT * FROM cconvenio_configurador_preciokg 
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
							$s = "SELECT cconvenio_configurador_preciokg.*, kmi as zoi, kmf as zof FROM cconvenio_configurador_preciokg 
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
							$s = "SELECT * FROM cconvenio_configurador_preciokg 
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
                  style="width:55; text-align:right" name="caja<?=$zona?>" value="<?=$f->valor?>"></td>
                  <?	
				  		$zona++;
						}
					?>
                </tr>
              </table>
			<?
		}*/
		
		/*if($_GET[valor]==4){ 
			$s = "SELECT * FROM cconvenio_configurador_caja 
					where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
					GROUP BY zona";
					
			$r = mysql_query($s,$l) or die($s);
			$sihay = (mysql_num_rows($r)>0)?1:0;
			
						if($sihay==1){
							$s = "SELECT * FROM cconvenio_configurador_caja 
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
							$s = "SELECT cconvenio_configurador_caja.*, kmi as zoi, kmf as zof FROM cconvenio_configurador_caja 
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
					$s = "SELECT * FROM cconvenio_configurador_caja WHERE tipo='CONSIGNACION' and idconvenio = $_GET[idconvenio] GROUP BY descripcion";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_array($r)){
				?>
                <tr>
                	<td>&nbsp;</td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='descripcion[]' id='descripcion' value='<?=cambio_texto($f[descripcion])?>'></td>
                    <? 
					$s = "SELECT * FROM cconvenio_configurador_caja WHERE tipo='CONSIGNACION' and idconvenio = $_GET[idconvenio] and descripcion = '$f[descripcion]' order by zona";
					$rx = mysql_query($s,$l) or die($s);
					while($fx = mysql_fetch_object($rx)){
					?>
                    <td>
                      <input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:55; text-align:right' name='zona<?=$fx->zona?>[]' id='zona<?=$fx->zona?>' value='<?=$fx->precio?>'>
                    </td>
                    <? }?>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='pesolimite[]' id='pesolimite' value='<?=$f["pesolimite"]?>'></td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion' 
					  style='width:80; text-align:right' name='precioexcedente[]' id='precioexcedente' value='<?=$f["preciokgexcedente"]?>'></td>
                </tr>
                <?
					}
				?>
              </table>
		<?
		}*/
		
		/*if($_GET[valor]==4){
			$s = "SELECT * FROM cconvenio_configurador_caja 
					where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
					GROUP BY zona";
			$r = mysql_query($s,$l) or die($s);
			$sihay = (mysql_num_rows($r)>0)?1:0;
			
						if($sihay==1){
							$s = "SELECT * FROM cconvenio_configurador_caja 
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
							$s = "SELECT cconvenio_configurador_caja.*, kmi as zoi, kmf as zof FROM cconvenio_configurador_caja 
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
					$s = "SELECT * FROM cconvenio_configurador_caja WHERE tipo='CONSIGNACION' and idconvenio = $_GET[idconvenio] GROUP BY descripcion";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_array($r)){
				?>
                <tr>
                	<td>&nbsp;</td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='descripcion[]' id='descripcion' value='<?=$f[descripcion]?>'  
					  onDblClick='seleccionar(obtenerIndice(this,"descripcion"),"zona1")'></td>
                    <? 
					$s = "SELECT * FROM cconvenio_configurador_caja WHERE tipo='CONSIGNACION' and idconvenio = $_GET[idconvenio] and descripcion = '$f[descripcion]' order by zona";
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
		}*/
		
		if($_GET[valor]==1){
				$s = "SELECT * FROM cconvenio_configurador_preciokg 
						where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
						GROUP BY zona";
				$r = mysql_query($s,$l) or die($s);
				$sihay = (mysql_num_rows($r)>0)?1:0;
				
						if($sihay==1){
							$s = "SELECT * FROM cconvenio_configurador_preciokg 
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
							$s = "SELECT cconvenio_configurador_preciokg.*, kmi as zoi, kmf as zof FROM cconvenio_configurador_preciokg 
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
							$s = "SELECT * FROM cconvenio_configurador_preciokg 
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
                  style="width:55; text-align:right" name="caja<?=$zona?>" value="<?=$f->valor?>" ></td>
                  <?	
				  		$zona++;
						}
					?>
                </tr>
              </table>
			<?
		}
		
		if($_GET[valor]==2){
			$s = "SELECT * FROM cconvenio_configurador_caja 
					where tipo = 'CONSIGNACION' and idconvenio = $_GET[idconvenio]
					GROUP BY zona";
			$r = mysql_query($s,$l) or die($s);
			$sihay = (mysql_num_rows($r)>0)?1:0;
			
						if($sihay==1){
							$s = "SELECT * FROM cconvenio_configurador_caja 
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
							$s = "SELECT cconvenio_configurador_caja.*, kmi as zoi, kmf as zof FROM cconvenio_configurador_caja 
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
					$s = "SELECT * FROM cconvenio_configurador_caja WHERE tipo='CONSIGNACION' and idconvenio = $_GET[idconvenio] GROUP BY descripcion";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_array($r)){
				?>
                <tr>
                	<td>&nbsp;</td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='descripcion[]' id='descripcion' value='<?=$f[descripcion]?>'  				  ></td>
                    <? 
					$s = "SELECT * FROM cconvenio_configurador_caja WHERE tipo='CONSIGNACION' and idconvenio = $_GET[idconvenio] and descripcion = '$f[descripcion]' order by zona";
					$rx = mysql_query($s,$l) or die($s);
					while($fx = mysql_fetch_object($rx)){
					?>
                    <td>
                      <input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:55; text-align:right' name='zona<?=$fx->zona?>[]' id='zona<?=$fx->zona?>' value='<?=$fx->precio?>'   >
                    </td>
                    <? }?>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='pesolimite[]' id='pesolimite' value='<?=$f["pesolimite"]?>' 
					  ></td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion' 
					  style='width:80; text-align:right' name='precioexcedente[]' id='precioexcedente' value='<?=$f["preciokgexcedente"]?>' ></td>
                </tr>
                <?
					}
				?>
              </table>
		<?
		}
	}
	
	if($_GET[accion]==3){ //asignacionguiasempresariales.php
		$s = "SELECT CONCAT(cuentac,numerodesde,CHAR(ASCII(letra)+aumento)) AS folioinicio, 
		CONCAT(cuentac,numerohasta,CHAR(ASCII(letra)+aumento)) AS foliofinal, 
		$_GET[costo]*$_GET[cantidad] AS importe, cuentac 
		FROM (
			SELECT 
				(SELECT idsucursal FROM catalogosucursal WHERE id = 1) AS cuentac,
				
			LPAD(
				IFNULL(
					IF(SUBSTRING(MAX(hastafolio),4,9)+1=1000000000,1,SUBSTRING(MAX(hastafolio),4,9)+1)
				,1)
			,9,'0') AS numerodesde,
			
			LPAD(
				IFNULL(
					IF(SUBSTRING(MAX(hastafolio),4,9)+$_GET[cantidad]=1000000000,1,SUBSTRING(MAX(hastafolio),4,9)+$_GET[cantidad])
				,$_GET[cantidad])
			,9,'0') AS numerohasta,
			IFNULL(SUBSTRING(MAX(hastafolio),13,1),'A') AS letra,
			IF(SUBSTRING(MAX(hastafolio),4,9)+1=1000000000,1,0) AS aumento
			FROM solicitudguiasempresariales WHERE SUBSTRING(hastafolio,1,3) = (SELECT idsucursal FROM catalogosucursal WHERE id = 1)
			AND hastafolio NOT LIKE '%Z'
		) AS t1";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->importe = number_format($f->importe,2,'.','');
		echo str_replace("null",'""',json_encode($f));
	}
	
	if($_GET[accion]==4){ //asignacionguiasempresariales.php
		
		$s = "SELECT IFNULL((SELECT MAX(foliotipo)+1 FROM solicitudguiasempresariales WHERE prepagada='$_GET[prepagada]'),1) as nuevofolio";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "SELECT CONCAT(cuentac,numerodesde,CHAR(ASCII(letra)+aumento)) AS folioinicio, 
		CONCAT(cuentac,numerohasta,CHAR(ASCII(letra)+aumento)) AS foliofinal, 
		cuentac 
		FROM (
			SELECT 
				(SELECT idsucursal FROM catalogosucursal WHERE id = 1) AS cuentac,
				
			LPAD(
				IFNULL(
					IF(SUBSTRING(MAX(hastafolio),4,9)+1=1000000000,1,SUBSTRING(MAX(hastafolio),4,9)+1)
				,1)
			,9,'0') AS numerodesde,
			LPAD(
				IFNULL(
					IF(SUBSTRING(MAX(hastafolio),4,9)+$_GET[cantidad]=1000000000,1,SUBSTRING(MAX(hastafolio),4,9)+$_GET[cantidad])
				,$_GET[cantidad])
			,9,'0') AS numerohasta,
			IFNULL(SUBSTRING(MAX(hastafolio),13,1),'A') AS letra,
			IF(SUBSTRING(MAX(hastafolio),4,9)+1=1000000000,1,0) AS aumento
			FROM solicitudguiasempresariales WHERE SUBSTRING(hastafolio,1,3) = (SELECT idsucursal FROM catalogosucursal WHERE id = 1)
			AND hastafolio NOT LIKE '%Z'
		) AS t1";
		$rx = mysql_query($s,$l) or die($s);
		$fx = mysql_fetch_object($rx);
		
		$_GET[desdefolio] = $fx->folioinicio;
		$_GET[hastafolio] = $fx->foliofinal;
		
		$s = "INSERT INTO solicitudguiasempresariales 
		SET foliotipo=$f->nuevofolio, condicionpago='$_GET[condicionpago]',
		idcliente='$_GET[idcliente]', nombre='$_GET[nombre]', apepat='$_GET[apepat]', 
		apemat='$_GET[apepat]', prepagada='$_GET[prepagada]', idconvenio=$_GET[idconvenio],
		cantidad='$_GET[cantidad]', desdefolio='$_GET[desdefolio]', hastafolio='$_GET[hastafolio]', 
		subtotal='$_GET[subtotal]', combustible='$_GET[combustible]', iva='$_GET[iva]',
		ivar='$_GET[ivar]', total='$_GET[total]', fecha=current_date,
		usuario='$_SESSION[NOMBREUSUARIO]', sucursalacobrar='$_GET[sucursalacobrar]',
		idusuario='$_SESSION[IDUSUARIO]'".(($_GET[prepagada]=="SI")?", foliosactivados='NO'":", foliosactivados='SI'");
		mysql_query($s,$l) or die($s);
		$idsolicitud = mysql_insert_id($l);
		
		//$s = "CALL insertarFolios($idsolicitud)";
		//mysql_query($s,$l) or die($s);
		
		$s="UPDATE solicitudguiasempresarialesnw SET STATUS='FOLIADO',usuario = '$_SESSION[IDUSUARIO]',
		fecha = CURRENT_DATE WHERE folio = $_GET[folio]";//Made In mexico
		mysql_query($s,$l) or die($s);
		
		$s = "CALL proc_RegistroClientes('ventas',".$_GET[idcliente].",0,0,".$idsolicitud.")";
		mysql_query($s,$l) or die($s);
		
		echo "guardo,$idsolicitud";
	}
	
	if($_GET[accion]==5){ //asignacionguiasempresariales.php
		$s = "SELECT gc.folio, sg.id, sg.estado, sg.factura as foliofactura,
		if(sg.factura<>0 AND not isnull(sg.factura),'SI','NO') as factura ,
		sg.foliotipo, sg.idcliente, sg.nombre, sg.apepat, sg.apemat, sg.condicionpago,
		sg.prepagada, sg.cantidad, sg.desdefolio, sg.hastafolio, sg.subtotal, sg.prepagada,
		sg.combustible, sg.iva, sg.ivar, sg.total, date_format(sg.fecha, '%d-%m-%Y') AS fecha, gc.preciokgexcedente,
		limitekg, costo, consignacionkg, consignacioncaja, 
		consignaciondescuento, consignaciondescantidad, gc.sucursal
		FROM solicitudguiasempresariales AS sg
		INNER JOIN generacionconvenio AS gc ON sg.idconvenio = gc.folio 
		WHERE sg.id=$_GET[idsolicitud]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
			$servicios = str_replace("null",'""',json_encode($arre));
			$f->nombre		= cambio_texto($f->nombre);
			$f->apepat		= cambio_texto($f->apepat);
			$f->apemat		= cambio_texto($f->apemat);
			$f->subtotal 	= number_format($f->subtotal,2,'.','');
			$sucursales		= '""';
			$servicios		= '""';
			$s = "SELECT clave,nombre,tipo FROM convenio_servicios_sucursales 
			WHERE idconvenio = $f->folio and tipo = 'SUCONSIGNACION2'";
			//echo $s;
			$r = mysql_query($s,$l) or die($s);
			$arre = array();
			while($fx = mysql_fetch_object($r)){
				$arre[] = $fx;
			}
			$sucursales = str_replace("null",'""',json_encode($arre));	
					
			// combos de prepagadas
			$s = "SELECT clave,nombre,tipo FROM convenio_servicios_sucursales WHERE idconvenio = $f->folio and tipo = 'SRCONSIGNACION'";
			//echo $s;
			$r = mysql_query($s,$l) or die($s);
			$arre = array();
			while($fx = mysql_fetch_object($r)){
				$arre[] = $fx;
			}
			$servicios = str_replace("null",'""',json_encode($arre));			
		$datoscliente 	= str_replace("null",'""', json_encode($f));
		echo "({datoscliente:$datoscliente,
				servicios:$servicios,
				sucursales:$sucursales})";
	}
	
	if($_GET[accion]==6){ //asignacionguiasempresariales.php
		$s = "SELECT IFNULL((SELECT MAX(foliotipo)+1 FROM solicitudguiasempresariales WHERE prepagada='$_GET[prepagada]'),1) as nuevofolio";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo $f->nuevofolio;
	}
	
	if($_GET[accion]==7){ //asignacionguiasempresariales.php
		$s = "SELECT IFNULL(MAX(id),0)+1 AS newfolio FROM solicitudguiasempresariales";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		echo $f->newfolio;
	}else if($_GET[accion]==8){ // Folio y fecha  solicitudguiasempresariales.php
			$s = "select date_format(current_date, '%d/%m/%Y') AS fecha";	
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$row=ObtenerFolio('solicitudguiasempresarialesnw','webpmm');
			$f->folio=$row[0];
			echo str_replace("null",'""',json_encode($f));
	}else if($_GET[accion]==9){ // Datos Cliente + prepagada o consignacion solicitudguiasempresariales.php
		$s = "SELECT gc.folio, cc.id, cc.nombre, cc.paterno, cc.materno,gc.prepagadas,
		IF(gc.consignacioncaja=1 OR gc.consignacionkg=1 OR gc.consignaciondescuento=1,1,0) AS consignacion,
		date_format(gc.vigencia,'%d/%m/%Y') as vigencia, gc.estadoconvenio FROM catalogocliente cc
		INNER JOIN generacionconvenio gc ON cc.id = gc.idcliente 
		LEFT JOIN catalogosucursal cs ON gc.sucursal = cs.id  
		WHERE cc.id = $_GET[idcliente] AND 
		CURRENT_DATE < gc.vigencia AND gc.estadoconvenio = 'ACTIVADO'";
		$r=mysql_query($s,$l) or die($s);		
		$f = mysql_fetch_array($r);
		$f[estadoconvenio] = cambio_texto($f[estadoconvenio]);
		echo str_replace("null",'""',json_encode($f));
				
	}else if($_GET[accion]==10){//Guardar solicitudguiasempresariales.php
		$s="INSERT INTO solicitudguiasempresarialesnw 
		(folio, ncliente, nombre, paterno, materno,preocon,cantidad, usuario, fecha, status, sucursal)
		VALUES 
		(NULL, '".$_GET[cliente]."', '".$_GET[nombre]."', '".$_GET[paterno]."', '".$_GET[materno]."',
		'".$_GET[preocon]."','".$_GET[cantidad]."','$_SESSION[IDUSUARIO]', CURRENT_DATE,' ',".$_SESSION[IDSUCURSAL].")";
		mysql_query(str_replace("''","null",$s),$l) or die($s);
		echo "1";
	}else if($_GET[accion]==11){//Buscar Registros solicitudguiasempresariales.php
	
		$s="SELECT sgen.folio, catalogocliente.id, catalogocliente.nombre,catalogocliente.paterno,
 catalogocliente.materno,sgen.preocon, generacionconvenio.preciokgexcedente 
 ,DATE_FORMAT(sgen.fecha,'%d/%m/%Y')AS fecha,sgen.cantidad
 FROM catalogocliente
 INNER JOIN solicitudguiasempresarialesnw  sgen ON sgen.ncliente =catalogocliente.id
 INNER JOIN generacionconvenio 
 ON catalogocliente.id = generacionconvenio.idcliente LEFT JOIN catalogosucursal AS cs 
 ON generacionconvenio.sucursal = cs.id 
 WHERE sgen.folio =$_GET[folio]";
		$r=mysql_query($s,$l) or die($s);
		$f = mysql_fetch_array($r);
		echo str_replace("null",'""',json_encode($f));
	}else if($_GET[accion]==12){//Cancelar solicitudguiasempresariales.php
	
		$s="UPDATE solicitudguiasempresarialesnw SET STATUS='CANCELADA',usuario = '$_SESSION[IDUSUARIO]' , fecha = CURRENT_DATE
	WHERE folio = $_GET[folio]";
		mysql_query(str_replace("''","null",$s),$l) or die($s);
		
		echo "1";
		
	}else if($_GET[accion]==13){//Autorizar solicitudguiasempresariales.php
		$s="UPDATE solicitudguiasempresarialesnw SET STATUS='AUTORIZADA',usuario = '$_SESSION[IDUSUARIO]' , fecha = CURRENT_DATE
	WHERE folio = $_GET[folio]";
		mysql_query(str_replace("''","null",$s),$l) or die($s);
		
		echo "1";
	}else if($_GET[accion]==14){//Autorizar solicitudguiasempresariales.php
		$s="UPDATE solicitudguiasempresarialesnw SET STATUS='AUTORIZADA',
		usuario = '$_SESSION[IDUSUARIO]' , fecha = CURRENT_DATE
		WHERE folio = $_GET[folio]";
		mysql_query(str_replace("''","null",$s),$l) or die($s);	
		echo "autorizo";
	}else if($_GET[accion]==15){//Autorizar solicitudguiasempresariales.php	
		$s="UPDATE solicitudguiasempresariales SET estado='CANCELADA'
		WHERE id = $_GET[folio]";
		mysql_query(str_replace("''","null",$s),$l) or die($s);	
		echo "cancelo";
	}
	
?>