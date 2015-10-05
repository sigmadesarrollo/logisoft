<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=enviadoyrecibido_Excel.xls");
	//header("Pragma: no-cache");
	//header("Expires: 0"); 
	
	require_once("../Conectar.php");
	$l = Conectarse('webpmm');	
	
		$nsucursal = $_GET[sucursal];
		$_GET[sucursal] = $_GET[sucursal_hidden];
		$fechas = "ENTRE $_GET[inicio] Y $_GET[fin]";
		$andfec1 = " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[inicio])."' AND '".cambiaf_a_mysql($_GET[fin])."' ";
		$andfec2 = " AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[inicio])."' AND '".cambiaf_a_mysql($_GET[fin])."' ";
?>
<style>
	table{
		font:Verdana, Geneva, sans-serif;
		font-size:12px;
		border: 1px #5FADDC solid;
	}
	.titulo{
		font-size:14px;
		font-weight:bold;
	}
	.cabecera{
		font-weight:bold;
		border:1px solid #5FADDC;
		/*background-color:#288ADB;*/
	}
	.totales{
		font-weight:bold;
		border:#ECF5FD;
		border:1px solid #5FADDC;
		/*background-color:#288ADB;*/
	}
	
</style>
	<table border="0" cellpadding="1" cellspacing="0">
    	<tr>
        	<td colspan="18" align="center" class="titulo">PAQUETERIA Y MENSAJERIA</td>
        </tr>
    	<tr>
    	  <td width="126">REPORTE:</td>
    	  <td colspan="17">RELACION DE ENVIADO</td>
   	  </tr>
    	<tr>
    	  <td>SUCURSAL:</td>
    	  <td colspan="17" align="left"><?=$nsucursal?></td>
   	  </tr>
    	<tr>
    	  <td>FECHAS</td>
    	  <td colspan="17" align="left"><?=$fechas?></td>
   	  </tr>
    	<tr>
    	  <td height="9px"></td>
    	  <td width="73">&nbsp;</td>
    	  <td width="95"></td>
    	  <td width="103"></td>
    	  <td width="107"></td>
    	  <td width="99"></td>
    	  <td width="112"></td>
    	  <td colspan="11"></td>
      </tr>
    	<tr>
    	      <td width="126" class="cabecera" align="center">GUIA</td>
    	      <td width="73" class="cabecera" align="center" >FECHA</td>
              <td width="95" class="cabecera" align="center" >TIPO FLETE</td>
          	  <td width="95" class="cabecera" align="center" >TIPO PAGO</td>
    	      <td width="95" class="cabecera" align="center" >FLETE</td>
    	      <td width="103" class="cabecera" align="center">DESC</td>
    	      <td width="107" class="cabecera" align="center">RECO</td>
    	      <td width="99" class="cabecera" align="right">EAD</td>
    	      <td width="112" class="cabecera" align="center" >SEGURO</td>
    	      <td width="96" class="cabecera" align="center" >OTROS</td>
    	      <td width="102" class="cabecera" align="center" >COMB</td>
			  <td width="102" class="cabecera" align="center" >EXC</td>
    	      <td width="100" class="cabecera" align="left" >IVA</td>
    	      <td width="105" class="cabecera" align="left" >IVA RET</td>
    	      <td width="111" class="cabecera" align="left" >TOTAL</td>
    	      <td width="132" class="cabecera" align="left" >ESTADO</td>
			  <td width="90" class="cabecera" align="left" >FACTURA</td>
    	      <td width="90" class="cabecera" align="left" >IMPORTE</td>
      </tr>
    	    <?
					$s = "CREATE TEMPORARY TABLE `guiasreporte_tmp` (
					  `id` VARCHAR(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
					  `fecha` VARCHAR(10) CHARACTER SET utf8 DEFAULT NULL,
					  `tflete` DOUBLE DEFAULT NULL,
					  `tdescuento` DOUBLE DEFAULT NULL,
					  `trecoleccion` DOUBLE DEFAULT NULL,
					  `tcostoead` DOUBLE DEFAULT NULL,
					  `tseguro` DOUBLE DEFAULT NULL,
					  `totros` DOUBLE DEFAULT NULL,
					  `tcombustible` DOUBLE DEFAULT NULL,
					  `texcedente` DOUBLE DEFAULT NULL,
					  `iva` DOUBLE DEFAULT NULL,
					  `ivaretenido` DOUBLE DEFAULT NULL,
					  `total` DOUBLE DEFAULT NULL,
					  `estado` VARCHAR(80) COLLATE utf8_unicode_ci DEFAULT NULL,
					  `tipoflete` VARCHAR(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
					  `tipopago` VARCHAR(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
					  `sucursal` VARCHAR(7) CHARACTER SET utf8 NOT NULL DEFAULT '',
					  `tiporep` CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT '',
					  `factura` DOUBLE DEFAULT NULL,
					  `importe` DOUBLE DEFAULT NULL
					) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
					mysql_query($s,$l) or die($s);
					//echo $s.";";
					
					$s = "INSERT INTO guiasreporte_tmp
					(SELECT gv.id,DATE_FORMAT(gv.fecha,'%d/%m/%Y') fecha,gv.tflete,gv.ttotaldescuento,gv.trecoleccion,gv.tcostoead,gv.tseguro,gv.totros,
					gv.tcombustible,gv.texcedente,gv.tiva,gv.ivaretenido,gv.total,gv.estado,IF(gv.tipoflete=0,'PAGADA','POR COBRAR') tipoflete, 
					IF(gv.condicionpago=1,'CREDITO','CONTADO') tipopago, cs.prefijo destino,'E',gv.factura,(f.total+f.sobmontoafacturar+f.otrosmontofacturar) AS importe
					FROM guiasventanilla gv
					INNER JOIN catalogosucursal cs on gv.idsucursaldestino = cs.id
					LEFT JOIN facturacion f ON gv.factura=f.folio
					WHERE gv.idsucursalorigen = $_GET[sucursal] $andfec1)
					UNION
					(SELECT ge.id, DATE_FORMAT(ge.fecha,'%d/%m/%Y') fecha,ge.tflete,ge.ttotaldescuento,ge.trecoleccion,ge.tcostoead,ge.tseguro,ge.totros, 
					ge.tcombustible,ge.texcedente,ge.tiva,ge.ivaretenido,ge.total,ge.estado,ge.tipoflete,ge.tipopago,cs.prefijo destino,'E',ge.factura,
					(f.total+f.sobmontoafacturar+f.otrosmontofacturar) AS importe
					FROM guiasempresariales ge
					INNER JOIN catalogosucursal cs on ge.idsucursaldestino = cs.id
					LEFT JOIN facturacion f ON ge.factura=f.folio
					WHERE ge.idsucursalorigen = $_GET[sucursal] $andfec2)";
					mysql_query($s,$l) or die($s);
					
					$s = "INSERT INTO guiasreporte_tmp
					(SELECT gv.id,DATE_FORMAT(gv.fecha,'%d/%m/%Y') fecha,gv.tflete,gv.ttotaldescuento,gv.trecoleccion,gv.tcostoead,gv.tseguro,gv.totros,
					gv.tcombustible,gv.texcedente,gv.tiva,gv.ivaretenido,gv.total,gv.estado,IF(gv.tipoflete=0,'PAGADA','POR COBRAR') tipoflete,
					IF(gv.condicionpago=1,'CREDITO','CONTADO') tipopago,cs.prefijo destino,'R',gv.factura,(f.total+f.sobmontoafacturar+f.otrosmontofacturar) AS importe
					FROM guiasventanilla gv
					INNER JOIN catalogosucursal cs on gv.idsucursalorigen = cs.id
					LEFT JOIN facturacion f ON gv.factura=f.folio
					WHERE gv.idsucursaldestino = $_GET[sucursal] $andfec1)
					UNION
					(SELECT ge.id,DATE_FORMAT(ge.fecha,'%d/%m/%Y') fecha,ge.tflete,ge.ttotaldescuento,ge.trecoleccion,ge.tcostoead,ge.tseguro,ge.totros,
					ge.tcombustible,ge.texcedente,ge.tiva,ge.ivaretenido,ge.total,ge.estado,ge.tipoflete,ge.tipopago,cs.prefijo destino,'R',ge.factura,
					(f.total+f.sobmontoafacturar+f.otrosmontofacturar) AS importe 
					FROM guiasempresariales ge
					INNER JOIN catalogosucursal cs on ge.idsucursalorigen = cs.id
					LEFT JOIN facturacion f ON ge.factura=f.folio
 					WHERE ge.idsucursaldestino = $_GET[sucursal] $andfec2)";
					mysql_query($s,$l) or die($s);
					
					//echo $s.";";
					$s = "SELECT * FROM guiasreporte_tmp WHERE tiporep = 'E' ORDER BY sucursal";
					//echo $s.";";
					$r = mysql_query($s,$l) or die($s);
					$destino = "";
					$final = false;
					
					
					//die("");					
					if(mysql_num_rows($r)>0){
						$total = mysql_num_rows($r);
						$cont = 6;
						while($f = mysql_fetch_object($r)){
							
							if($destino != $f->sucursal || $destino == ""){
								$destino = $f->sucursal;
					?>
                    
                    
                    
                    <?
						if($final==true){
							
								$final = false;
								$cont++;
					?>
								<tr>
								  <td align="center" colspan="2">CONTADO</td>
                                  <td align="right">&nbsp;</td>
                                  <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecontado[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">CREDITO</td>
                                  <td align="right">&nbsp;</td>
                                  <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecredito[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CONTADO</td>
                                  <td align="right">&nbsp;</td>
                                  <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CREDITO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td colspan="2" align="center" class="totales">TOTAL</td>
                                  <td class="totales" align="right">&nbsp;</td>
                                  <td class="totales" align="right">&nbsp;</td>
								  <td class="totales" align="right">=SUMA(E<?=($cont-4);?>:E<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(F<?=($cont-4);?>:F<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(G<?=($cont-4);?>:G<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(H<?=($cont-4);?>:H<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(I<?=($cont-4);?>:I<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(J<?=($cont-4);?>:J<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(K<?=($cont-4);?>:K<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(L<?=($cont-4);?>:L<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(M<?=($cont-4);?>:M<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(N<?=($cont-4);?>:N<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(O<?=($cont-4);?>:O<?=($cont-1);?>)</td>
								   <td class="totales" align="center"></td>
								   <td class="totales" align="center"></td>
								   <td class="totales" align="right"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="18">&nbsp;</td>
                              </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="18">&nbsp;</td>
                              </tr>
							<?
                                
								
								$arrecontado = array('tflete'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'texcedente'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								$arrecredito = array('tflete'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'texcedente'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								$arrecobcon = array('tflete'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'texcedente'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								$arrecobcre = array('tflete'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'texcedente'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								}
                            ?>
               
                              <? $cont++;?>     
			<tr>
    	      <td colspan="18" class="cabecera" align="left">&nbsp;DESTINO: <?=$f->sucursal?></td>
          </tr>
					<?
								$final = true;
							}
							
							if($f->tipoflete=='POR COBRAR'){
								if($f->tipopago=='CREDITO'){
									$arrecobcre[tflete] += $f->tflete;
									$arrecobcre[tdescuento] += $f->tdescuento;
									$arrecobcre[trecoleccion] += $f->trecoleccion;
									$arrecobcre[tcostoead] += $f->tcostoead;
									$arrecobcre[tseguro] += $f->tseguro;
									$arrecobcre[totros] += $f->totros;
									$arrecobcre[tcombustible] += $f->tcombustible;
									$arrecobcre[tcombustible] += $f->texcedente;
									$arrecobcre[tiva] += $f->tiva;
									$arrecobcre[ivaretenido] += $f->ivaretenido;
									$arrecobcre[total] += $f->total;
								}else{
									$arrecobcon[tflete] += $f->tflete;
									$arrecobcon[tdescuento] += $f->tdescuento;
									$arrecobcon[trecoleccion] += $f->trecoleccion;
									$arrecobcon[tcostoead] += $f->tcostoead;
									$arrecobcon[tseguro] += $f->tseguro;
									$arrecobcon[totros] += $f->totros;
									$arrecobcon[tcombustible] += $f->tcombustible;
									$arrecobcon[tcombustible] += $f->texcedente;
									$arrecobcon[tiva] += $f->tiva;
									$arrecobcon[ivaretenido] += $f->ivaretenido;
									$arrecobcon[total] += $f->total;
								}
							}else{
								if($f->tipopago=='CREDITO'){
									$arrecredito[tflete] += $f->tflete;
									$arrecredito[tdescuento] += $f->tdescuento;
									$arrecredito[trecoleccion] += $f->trecoleccion;
									$arrecredito[tcostoead] += $f->tcostoead;
									$arrecredito[tseguro] += $f->tseguro;
									$arrecredito[totros] += $f->totros;
									$arrecredito[tcombustible] += $f->tcombustible;
									$arrecredito[tcombustible] += $f->texcedente;
									$arrecredito[tiva] += $f->tiva;
									$arrecredito[ivaretenido] += $f->ivaretenido;
									$arrecredito[total] += $f->total;
								}else{
									$arrecontado[tflete] += $f->tflete;
									$arrecontado[tdescuento] += $f->tdescuento;
									$arrecontado[trecoleccion] += $f->trecoleccion;
									$arrecontado[tcostoead] += $f->tcostoead;
									$arrecontado[tseguro] += $f->tseguro;
									$arrecontado[totros] += $f->totros;
									$arrecontado[tcombustible] += $f->tcombustible;
									$arrecontado[tcombustible] += $f->texcedente;
									$arrecontado[tiva] += $f->tiva;
									$arrecontado[ivaretenido] += $f->ivaretenido;
									$arrecontado[total] += $f->total;
								}
							}
					?>
                              <? $cont++;?>
    	    <tr>
    	      <td align="center"><?=$f->id?></td>
    	      <td align="center"><?=$f->fecha?></td>
    	      <td align="right"><?=$f->tipoflete?></td>
    	      <td align="right"><?=$f->tipopago?></td>
    	      <td align="right"><?='$ '.number_format($f->tflete,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->tdescuento,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->trecoleccion,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->tcostoead,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->tseguro,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->totros,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->tcombustible,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->texcedente,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->tiva,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->ivaretenido,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->total,2)?></td>
    	      <td align="center"><?=$f->estado?></td>
			  <td align="center"><?=$f->factura?></td>
			  <td align="center"><?='$ '.number_format($f->importe,2)?></td>
          </tr>
					<?
						}
					}
                    
						$cont++;
					?>
								<tr>
								  <td align="center" colspan="2">CONTADO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecontado[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">CREDITO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecredito[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CONTADO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CREDITO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td colspan="2" align="center" class="totales">TOTAL</td>
								  <td class="totales" align="right">&nbsp;</td>
								  <td class="totales" align="right">&nbsp;</td>
								  <td class="totales" align="right">=SUMA(E<?=($cont-4);?>:E<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(F<?=($cont-4);?>:F<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(G<?=($cont-4);?>:G<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(H<?=($cont-4);?>:H<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(I<?=($cont-4);?>:I<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(J<?=($cont-4);?>:J<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(K<?=($cont-4);?>:K<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(L<?=($cont-4);?>:L<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(M<?=($cont-4);?>:M<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(N<?=($cont-4);?>:N<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(O<?=($cont-4);?>:O<?=($cont-1);?>)</td>
								  <td class="totales" align="center">&nbsp;</td>
								  <td class="totales" align="center"></td>
								  <td class="totales" align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="18">&nbsp;</td>
                              </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="18">&nbsp;</td>
                              </tr>
    </table>
	    
    <table border="0" cellpadding="1" cellspacing="0">
    	<tr>
        	<td colspan="18" align="center" class="titulo">PAQUETERIA Y MENSAJERIA</td>
        </tr>
    	<tr>
    	  <td width="126">REPORTE:</td>
    	  <td colspan="17">RELACION DE RECIBIDO</td>
   	  </tr>
    	<tr>
    	  <td>SUCURSAL:</td>
    	  <td colspan="17" align="left"><?=$nsucursal?></td>
   	  </tr>
    	<tr>
    	  <td>FECHAS</td>
    	  <td colspan="17" align="left"><?=$fechas?></td>
   	  </tr>
    	<tr>
    	  <td height="9px"></td>
    	  <td width="73">&nbsp;</td>
    	  <td width="95"></td>
    	  <td width="103"></td>
    	  <td width="107"></td>
    	  <td width="99"></td>
    	  <td width="112"></td>
    	  <td colspan="11"></td>
      </tr>
    	<tr>
    	      <td width="126" class="cabecera" align="center">GUIA</td>
    	      <td width="73" class="cabecera" align="center" >FECHA</td>
              <td width="95" class="cabecera" align="center" >TIPO FLETE</td>
          <td width="95" class="cabecera" align="center" >TIPO PAGO</td>
    	      <td width="95" class="cabecera" align="center" >FLETE</td>
    	      <td width="103" class="cabecera" align="center">DESC</td>
    	      <td width="107" class="cabecera" align="center">RECO</td>
    	      <td width="99" class="cabecera" align="right">EAD</td>
    	      <td width="112" class="cabecera" align="center" >SEGURO</td>
    	      <td width="96" class="cabecera" align="center" >OTROS</td>
    	      <td width="102" class="cabecera" align="center" >COMB</td>
    	      <td width="102" class="cabecera" align="center" >EXC</td>
    	      <td width="100" class="cabecera" align="left" >IVA</td>
    	      <td width="105" class="cabecera" align="left" >IVA RET</td>
    	      <td width="111" class="cabecera" align="left" >TOTAL</td>
    	      <td width="132" class="cabecera" align="left" >ESTADO</td>
    	      <td width="90" class="cabecera" align="left" >FACTURA</td>
    	      <td width="90" class="cabecera" align="left" >IMPORTE</td>
      </tr>
    	    <?					
					//echo $s.";";
					$s = "SELECT * FROM guiasreporte_tmp WHERE tiporep = 'R' ORDER BY sucursal";
					//echo $s.";";
					$r = mysql_query($s,$l) or die($s);
					$destino = "";
					$final = false;
					
					//die("");					
					if(mysql_num_rows($r)>0){
						$total = mysql_num_rows($r);
						$cont += 6;
						while($f = mysql_fetch_object($r)){
							
							if($destino != $f->sucursal || $destino == ""){
								$destino = $f->sucursal;
					?>
                    
                    
                    
                    <?
						if($final==true){
							
								$final = false;
								$cont++;
					?>
								<tr>
								  <td align="center" colspan="2">CONTADO</td>
                                  <td align="right">&nbsp;</td>
                                  <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecontado[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">CREDITO</td>
                                  <td align="right">&nbsp;</td>
                                <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecredito[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CONTADO</td>
                                  <td align="right">&nbsp;</td>
                                <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CREDITO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td colspan="2" align="center" class="totales">TOTAL</td>
                                  <td class="totales" align="right">&nbsp;</td>
                                <td class="totales" align="right">&nbsp;</td>
								  <td class="totales" align="right">=SUMA(E<?=($cont-4);?>:E<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(F<?=($cont-4);?>:F<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(G<?=($cont-4);?>:G<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(H<?=($cont-4);?>:H<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(I<?=($cont-4);?>:I<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(J<?=($cont-4);?>:J<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(K<?=($cont-4);?>:K<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(L<?=($cont-4);?>:L<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(M<?=($cont-4);?>:M<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(N<?=($cont-4);?>:N<?=($cont-1);?>)</td>
								  <td class="totales" align="center">=SUMA(O<?=($cont-4);?>:O<?=($cont-1);?>)</td>
								  <td class="totales" align="center"></td>
								  <td class="totales" align="center"></td>
								  <td class="totales" align="center"></td>

							  </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="18">&nbsp;</td>
                              </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="18">&nbsp;</td>
                              </tr>
							<?
                                
								
								$arrecontado = array('tflete'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'texcedente'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								$arrecredito = array('tflete'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'texcedente'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								$arrecobcon = array('tflete'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'texcedente'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								$arrecobcre = array('tflete'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'texcedente'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								}
                            ?>
               
                              <? $cont++;?>     
			<tr>
    	      <td colspan="18" class="cabecera" align="left">ORIGEN: <?=$f->sucursal?></td>
          </tr>
					<?
								$final = true;
							}
							
							if($f->tipoflete=='POR COBRAR'){
								if($f->tipopago=='CREDITO'){
									$arrecobcre[tflete] += $f->tflete;
									$arrecobcre[tdescuento] += $f->tdescuento;
									$arrecobcre[trecoleccion] += $f->trecoleccion;
									$arrecobcre[tcostoead] += $f->tcostoead;
									$arrecobcre[tseguro] += $f->tseguro;
									$arrecobcre[totros] += $f->totros;
									$arrecobcre[tcombustible] += $f->tcombustible;
									$arrecobcre[texcedente] += $f->texcedente;
									$arrecobcre[tiva] += $f->tiva;
									$arrecobcre[ivaretenido] += $f->ivaretenido;
									$arrecobcre[total] += $f->total;
								}else{
									$arrecobcon[tflete] += $f->tflete;
									$arrecobcon[tdescuento] += $f->tdescuento;
									$arrecobcon[trecoleccion] += $f->trecoleccion;
									$arrecobcon[tcostoead] += $f->tcostoead;
									$arrecobcon[tseguro] += $f->tseguro;
									$arrecobcon[totros] += $f->totros;
									$arrecobcon[tcombustible] += $f->tcombustible;
									$arrecobcon[texcedente] += $f->texcedente;
									$arrecobcon[tiva] += $f->tiva;
									$arrecobcon[ivaretenido] += $f->ivaretenido;
									$arrecobcon[total] += $f->total;
								}
							}else{
								if($f->tipopago=='CREDITO'){
									$arrecredito[tflete] += $f->tflete;
									$arrecredito[tdescuento] += $f->tdescuento;
									$arrecredito[trecoleccion] += $f->trecoleccion;
									$arrecredito[tcostoead] += $f->tcostoead;
									$arrecredito[tseguro] += $f->tseguro;
									$arrecredito[totros] += $f->totros;
									$arrecredito[tcombustible] += $f->tcombustible;
									$arrecredito[texcedente] += $f->texcedente;
									$arrecredito[tiva] += $f->tiva;
									$arrecredito[ivaretenido] += $f->ivaretenido;
									$arrecredito[total] += $f->total;
								}else{
									$arrecontado[tflete] += $f->tflete;
									$arrecontado[tdescuento] += $f->tdescuento;
									$arrecontado[trecoleccion] += $f->trecoleccion;
									$arrecontado[tcostoead] += $f->tcostoead;
									$arrecontado[tseguro] += $f->tseguro;
									$arrecontado[totros] += $f->totros;
									$arrecontado[tcombustible] += $f->tcombustible;
									$arrecontado[texcedente] += $f->texcedente;
									$arrecontado[tiva] += $f->tiva;
									$arrecontado[ivaretenido] += $f->ivaretenido;
									$arrecontado[total] += $f->total;
								}
							}
					?>
                              <? $cont++;?>
    	    <tr>
    	      <td align="center"><?=$f->id?></td>
    	      <td align="center"><?=$f->fecha?></td>
    	      <td align="right"><?=$f->tipoflete?></td>
    	      <td align="right"><?=$f->tipopago?></td>
    	      <td align="right"><?='$ '.number_format($f->tflete,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->tdescuento,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->trecoleccion,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->tcostoead,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->tseguro,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->totros,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->tcombustible,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->texcedente,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->tiva,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->ivaretenido,2)?></td>
    	      <td align="right"><?='$ '.number_format($f->total,2)?></td>
    	      <td align="center"><?=$f->estado?></td>
			  <td align="center"><?=$f->factura?></td>
			  <td align="right"><?='$ '.number_format($f->importe,2)?></td>
          </tr>
					<?
						}
					}
                    
						$cont++;
					?>
								<tr>
								  <td align="center" colspan="2">CONTADO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecontado[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecontado[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">CREDITO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecredito[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecredito[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CONTADO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcon[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CREDITO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tflete],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tdescuento],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[trecoleccion],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tcostoead],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tseguro],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[totros],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tcombustible],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[texcedente],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[tiva],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[ivaretenido],2)?></td>
								  <td align="right"><?='$ '.number_format($arrecobcre[total],2)?></td>
								  <td align="center"></td>
								  <td align="center"></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td colspan="2" align="center" class="totales">TOTAL</td>
								  <td class="totales" align="right">&nbsp;</td>
								  <td class="totales" align="right">&nbsp;</td>
								  <td class="totales" align="right">=SUMA(E<?=($cont-4);?>:E<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(F<?=($cont-4);?>:F<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(G<?=($cont-4);?>:G<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(H<?=($cont-4);?>:H<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(I<?=($cont-4);?>:I<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(J<?=($cont-4);?>:J<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(K<?=($cont-4);?>:K<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(L<?=($cont-4);?>:L<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(M<?=($cont-4);?>:M<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(N<?=($cont-4);?>:N<?=($cont-1);?>)</td>
								  <td class="totales" align="right">=SUMA(O<?=($cont-4);?>:O<?=($cont-1);?>)</td>
								  <td class="totales" align="center"></td>
								  <td class="totales" align="center"></td>
								  <td class="totales" align="right"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="18">&nbsp;</td>
                              </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="18">&nbsp;</td>
                              </tr>
    </table>
	