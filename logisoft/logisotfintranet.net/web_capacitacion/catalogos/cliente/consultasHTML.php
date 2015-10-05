<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../../Conectar.php');
	$link = Conectarse('webpmm');
	
	if($_GET['accion']==6){
		if($_GET[valor]==1){				
		$s ="SELECT kg.zona, kg.kmi, kg.kmf, kg.valor
		FROM generacionconvenio gn
		INNER JOIN cconvenio_configurador_preciokg kg ON gn.folio = kg.idconvenio
		WHERE kg.tipo = 'CONVENIO' AND gn.folio = $_GET[idconvenio]
		AND gn.idcliente=$_GET[cliente]
		GROUP BY kg.zona";
	
				$r = mysql_query($s,$link) or die($s);				
				$tamanotabla = 55*mysql_num_rows($r);
						
					?>
              <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+55?>">
                <tr>
                  <td height="16" width="55"  class="formato_columnasg">&nbsp;</td>
                  <?
				$s = "SELECT kg.*, kg.kmi AS zoi, kg.kmf  AS zof
				FROM generacionconvenio gn
			INNER JOIN cconvenio_configurador_preciokg kg ON gn.folio = kg.idconvenio
			WHERE kg.tipo = 'CONVENIO' AND gn.folio = $_GET[idconvenio]
			AND gn.idcliente=$_GET[cliente] GROUP BY kg.zona";
		
						$r = mysql_query($s,$link) or die($s);
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
		$s = "SELECT kg.zona, kg.kmi, kg.kmf, kg.valor FROM generacionconvenio gn
INNER JOIN cconvenio_configurador_preciokg kg ON gn.folio = kg.idconvenio
WHERE kg.tipo = 'CONVENIO' AND gn.folio = $_GET[idconvenio] AND gn.idcliente=$_GET[cliente]
GROUP BY kg.zona";
						$r = mysql_query($s,$link) or die($s);
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
			$s = "SELECT c.descripcion, c.zona, c.kmi, c.kmf, c.precio,
			c.pesolimite, c.preciokgexcedente FROM generacionconvenio gn
			INNER JOIN cconvenio_configurador_caja c ON gn.folio = c.idconvenio
			WHERE c.tipo = 'CONVENIO' AND gn.folio = $_GET[idconvenio]
			AND gn.idcliente=$_GET[cliente] GROUP BY c.zona";			
			$r = mysql_query($s,$link) or die($s);
			$s = "SELECT cantidaddescuento FROM generacionconvenio WHERE folio = $_GET[idconvenio]
			AND idcliente=$_GET[cliente]";
			$des = mysql_query($s,$link) or die($s); 
			$desc = mysql_fetch_object($des); 
			$descuento = (($desc->cantidaddescuento!="")?$desc->cantidaddescuento.' %': 0);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?> Descuento:
                <input name="descuento" type="text" class="Tablas" style="background:#FF9; width:50px" value="<?=$descuento ?>" readonly="true" />
			  <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+265?>" id="tablaconveniopreciocaja">
                <tr>
                  <td height="16" width="25"  class="formato_columnasg">
                  	
                    </td>
                  <td height="16" width="80"  class="formato_columnasg">Descripcion</td>
                  <?
						
$s = "SELECT c.*, c.kmi AS zoi, c.kmf AS zof FROM generacionconvenio gn
INNER JOIN cconvenio_configurador_caja c ON gn.folio = c.idconvenio
WHERE c.tipo = 'CONVENIO' AND gn.folio = $_GET[idconvenio]
AND gn.idcliente=$_GET[cliente] GROUP BY c.zona";

						$r = mysql_query($s,$link) or die($s);
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
					$s = "SELECT c.descripcion, c.zona, c.kmi, c.kmf, c.precio, c.pesolimite, c.preciokgexcedente  FROM generacionconvenio gn
INNER JOIN cconvenio_configurador_caja c ON gn.folio = c.idconvenio
WHERE c.tipo = 'CONVENIO' AND gn.folio = $_GET[idconvenio] AND gn.idcliente=$_GET[cliente]
GROUP BY c.descripcion";

					$r = mysql_query($s,$link) or die($s);
					while($f = mysql_fetch_array($r)){
				?>
                <tr>
                	<td>&nbsp;</td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='descripcion[]' id='descripcion' value='<?=$f[descripcion]?>'  
					  onDblClick='seleccionar(obtenerIndice(this,"descripcion"),"zona1")'></td>
                    <? 
					$s = "SELECT c.descripcion, c.zona, c.kmi, c.kmf, c.precio, c.pesolimite, c.preciokgexcedente  FROM generacionconvenio gn
INNER JOIN cconvenio_configurador_caja c ON gn.folio = c.idconvenio
WHERE c.tipo = 'CONVENIO' AND gn.folio = $_GET[idconvenio] AND gn.idcliente=$_GET[cliente] and 
c.descripcion = '$f[descripcion]'
GROUP BY c.zona";

					$rx = mysql_query($s,$link) or die($s);
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
			$s ="SELECT kg.zona, kg.kmi, kg.kmf, kg.valor
		FROM generacionconvenio gn
		INNER JOIN cconvenio_configurador_preciokg kg ON gn.folio = kg.idconvenio
		WHERE kg.tipo = 'CONSIGNACION' AND gn.folio = $_GET[idconvenio]
		AND gn.idcliente=$_GET[cliente]
		GROUP BY kg.zona";	
				$r = mysql_query($s,$link) or die($s);				
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
              <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+55?>">
                <tr>
                  <td height="16" width="55"  class="formato_columnasg">&nbsp;</td>
                  <?
				  $s = "SELECT kg.*, kg.kmi AS zoi, kg.kmf  AS zof
				FROM generacionconvenio gn
			INNER JOIN cconvenio_configurador_preciokg kg ON gn.folio = kg.idconvenio
			WHERE kg.tipo = 'CONSIGNACION' AND gn.folio = $_GET[idconvenio]
			AND gn.idcliente=$_GET[cliente] GROUP BY kg.zona";						
						$r = mysql_query($s,$link) or die($s);
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
	$s = "SELECT kg.zona, kg.kmi, kg.kmf, kg.valor FROM generacionconvenio gn
INNER JOIN cconvenio_configurador_preciokg kg ON gn.folio = kg.idconvenio
WHERE kg.tipo = 'CONSIGNACION' AND gn.folio = $_GET[idconvenio] AND gn.idcliente=$_GET[cliente]
GROUP BY kg.zona";
						$r = mysql_query($s,$link) or die($s);
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
					$s = "SELECT c.descripcion, c.zona, c.kmi, c.kmf, c.precio,
			c.pesolimite, c.preciokgexcedente FROM generacionconvenio gn
			INNER JOIN cconvenio_configurador_caja c ON gn.folio = c.idconvenio
			WHERE c.tipo = 'CONSIGNACION' AND gn.folio = $_GET[idconvenio]
			AND gn.idcliente=$_GET[cliente] GROUP BY c.zona";
			$r = mysql_query($s,$link) or die($s);
			$s = "SELECT consignaciondescantidad FROM generacionconvenio 
			WHERE folio = $_GET[idconvenio]
			AND idcliente=$_GET[cliente]";
			$des = mysql_query($s,$link) or die($s); 
			$desc = mysql_fetch_object($des); $descuento2=(($desc->consignaciondescantidad!="")?$desc->consignaciondescantidad.' %':0);
			$tamanotabla = 55*mysql_num_rows($r);
						
					?>
			Descuento:
            <input name="descuento2" type="text" class="Tablas" style="background:#FF9; width:50px" value="<?=$descuento2 ?>" readonly="true" />
              <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+265?>" id="tablaconveniopreciocaja">
                <tr>
                  <td height="16" width="25"  class="formato_columnasg">
                  	
                    </td>
                  <td height="16" width="80"  class="formato_columnasg">Descripcion</td>
                  <?
				  $s = "SELECT c.*, c.kmi AS zoi, c.kmf AS zof FROM generacionconvenio gn
INNER JOIN cconvenio_configurador_caja c ON gn.folio = c.idconvenio
WHERE c.tipo = 'CONSIGNACION' AND gn.folio = $_GET[idconvenio]
AND gn.idcliente=$_GET[cliente] GROUP BY c.zona";
						
						$r = mysql_query($s,$link) or die($s);
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
				$s = "SELECT c.descripcion, c.zona, c.kmi, c.kmf, c.precio, c.pesolimite, c.preciokgexcedente  FROM generacionconvenio gn
INNER JOIN cconvenio_configurador_caja c ON gn.folio = c.idconvenio
WHERE c.tipo = 'CONSIGNACION' AND gn.folio = $_GET[idconvenio] AND gn.idcliente=$_GET[cliente]
GROUP BY c.descripcion";
					$r = mysql_query($s,$link) or die($s);
					while($f = mysql_fetch_array($r)){
				?>
                <tr>
                	<td>&nbsp;</td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='descripcion[]' id='descripcion' value='<?=$f[descripcion]?>'  
					  onDblClick='seleccionar(obtenerIndice(this,"descripcion"),"zona1")'></td>
                    <? 
					
				$s = "SELECT c.descripcion, c.zona, c.kmi, c.kmf, c.precio, c.pesolimite, c.preciokgexcedente  FROM generacionconvenio gn
INNER JOIN cconvenio_configurador_caja c ON gn.folio = c.idconvenio
WHERE c.tipo = 'CONSIGNACION' AND gn.folio = $_GET[idconvenio] AND gn.idcliente=$_GET[cliente] and 
c.descripcion = '$f[descripcion]'
GROUP BY c.zona";
					$rx = mysql_query($s,$link) or die($s);
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
	}else if($_GET['accion']==8){// OBTENER SERVICIOS SUCURSALES ETC. CONVENIO
		//servicios grid
		$servicioscobro = "";		
		$s = "SELECT s1.servicio, s1.cobro, s1.precio FROM generacionconvenio c		
		INNER JOIN cconvenio_servicios s1 ON c.folio = s1.idconvenio
		WHERE s1.tipo = 'CONVENIO' AND c.folio=".$_GET[idconvenio]."
		AND c.idcliente=".$_GET[cliente]."";		
		$r = mysql_query($s,$link) or die($s);
		$ser = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$ser[] = $f;
			}
			$servicioscobro = str_replace("null",'""',json_encode($ser));
		}else{
			$servicioscobro = "0";
		}
		$servicioscobro2 = "";
		$s = "SELECT s1.servicio, s1.cobro, s1.precio FROM generacionconvenio c		
		INNER JOIN cconvenio_servicios s1 ON c.folio = s1.idconvenio
		WHERE s1.tipo = 'CONSIGNACION' AND c.folio=".$_GET[idconvenio]."
		AND c.idcliente=".$_GET[cliente]."";
		$r = mysql_query($s,$link) or die($s);
		$ser2 = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$ser2[] = $f;
			}
			$servicioscobro2 = str_replace("null",'""',json_encode($ser2));
		}else{
			$servicioscobro2 = "0";
		}	
		//servicios combo
		$servicioscombo1 = "";
		
		$s="SELECT s.nombre FROM generacionconvenio c
		INNER JOIN cconvenio_servicios_sucursales s ON c.folio=s.idconvenio
		WHERE s.tipo='SRCONVENIO' AND c.folio=".$_GET[idconvenio]."
		AND c.idcliente=".$_GET[cliente]."";		
		$r = mysql_query($s,$link) or die($s);
		$arre = array();
		$f = mysql_fetch_object($r);
		$f->nombre = utf8_encode($f->nombre);
		if($f->nombre=="TODOS"){
			$sql = mysql_query("SELECT descripcion AS nombre FROM catalogoservicio WHERE restringir='SI'",$link);
			while($f = mysql_fetch_object($sql)){
				$f->descripcion = utf8_encode($f->nombre);
				$arre[] = $f;
			}
		}else{
				$arre[] = $f;
			while($f = mysql_fetch_object($r)){
				$f->nombre = utf8_encode($f->nombre);
				$arre[] = $f;
			}
		}
		
		$servicioscombo1 = str_replace("null",'""',json_encode($arre));
		
		$servicioscombo2 = "";		
		$s="SELECT s.nombre FROM generacionconvenio c
		INNER JOIN cconvenio_servicios_sucursales s ON c.folio=s.idconvenio
		WHERE s.tipo='SUCONVENIO' AND c.folio=".$_GET[idconvenio]."
		AND c.idcliente=".$_GET[cliente]."";		
		$r = mysql_query($s,$link) or die($s);
		$arre = array();
		$f = mysql_fetch_object($r);
		$f->nombre = utf8_encode($f->nombre);
		if($f->nombre=="TODOS"){
			$sql = mysql_query("SELECT descripcion AS nombre  FROM catalogosucursal WHERE id>1",$link);
			while($f = mysql_fetch_object($sql)){
				$f->descripcion = utf8_encode($f->nombre);
				$arre[] = $f;
			}
		}else{
				$arre[] = $f;
			while($f = mysql_fetch_object($r)){
				$f->nombre = utf8_encode($f->nombre);
				$arre[] = $f;
			}
		}
		$servicioscombo2 = str_replace("null",'""',json_encode($arre));
				
		// combos de prepagadas
		$servicioscombo3 = "";		
		$s="SELECT s.nombre FROM generacionconvenio c
		INNER JOIN cconvenio_servicios_sucursales s ON c.folio=s.idconvenio
		WHERE s.tipo='SRCONSIGNACION' AND c.folio=".$_GET[idconvenio]."
		AND c.idcliente=".$_GET[cliente]."";		
		$r = mysql_query($s,$link) or die($s);
		$arre = array();
		$f = mysql_fetch_object($r);
		$f->nombre = utf8_encode($f->nombre);
		if($f->nombre=="TODOS"){
		$sql = mysql_query("SELECT descripcion AS nombre FROM catalogoservicio WHERE restringir='SI'",$link);
			while($f = mysql_fetch_object($sql)){
				$f->descripcion = utf8_encode($f->nombre);
				$arre[] = $f;
			}
		}else{
			$arre[] = $f;
			while($f = mysql_fetch_object($r)){
				$f->nombre = utf8_encode($f->nombre);
				$arre[] = $f;
			}
		}		
		$servicioscombo3 = str_replace("null",'""',json_encode($arre));
		
		$servicioscombo4 = "";
		$s="SELECT s.nombre FROM generacionconvenio c
		INNER JOIN cconvenio_servicios_sucursales s ON c.folio=s.idconvenio
		WHERE s.tipo='SUCONSIGNACION' OR s.tipo='SUCONSIGNACION2' AND c.folio=".$_GET[idconvenio]."
		AND c.idcliente=".$_GET[cliente]."";
		$r = mysql_query($s,$link) or die($s);
		$arre = array();
		$f = mysql_fetch_object($r);
		$f->nombre = utf8_encode($f->nombre);
		if($f->nombre=="TODOS"){
			$sql = mysql_query("SELECT descripcion AS nombre FROM catalogosucursal WHERE id>1",$link);
			while($f = mysql_fetch_object($sql)){
				$f->descripcion = utf8_encode($f->nombre);
				$arre[] = $f;
			}
		}else{
				$arre[] = $f;
			while($f = mysql_fetch_object($r)){
				$f->nombre = utf8_encode($f->nombre);
				$arre[] = $f;
			}
		}
		
		$servicioscombo4 = str_replace("null",'""',json_encode($arre));
		echo "[{serviciogrid1:$servicioscobro, 
				serviciogrid2:$servicioscobro2, 
				serviciocombo1:$servicioscombo1, 
				serviciocombo2:$servicioscombo2, 
				serviciocombo3:$servicioscombo3,
				serviciocombo4:$servicioscombo4}]";
	}
	
?>