<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=enviadoyrecibido.xls");
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
        	<td colspan="16" align="center" class="titulo">PAQUETERIA Y MENSAJERIA</td>
        </tr>
    	<tr>
    	  <td width="126">REPORTE:</td>
    	  <td colspan="15">RELACION DE ENVIADO</td>
   	  </tr>
    	<tr>
    	  <td>SUCURSAL:</td>
    	  <td colspan="15" align="left"><?=$nsucursal?></td>
   	  </tr>
    	<tr>
    	  <td>FECHAS</td>
    	  <td colspan="15" align="left"><?=$fechas?></td>
   	  </tr>
    	<tr>
    	  <td height="9px"></td>
    	  <td width="73">&nbsp;</td>
    	  <td width="95"></td>
    	  <td width="103"></td>
    	  <td width="107"></td>
    	  <td width="99"></td>
    	  <td width="112"></td>
    	  <td colspan="9"></td>
      </tr>
    	<tr>
    	      <td width="126" class="cabecera" align="center">GUIA</td>
    	      <td width="73" class="cabecera" align="center" >FECHA</td>
              <td width="95" class="cabecera" align="center" >TIPO FLETE</td>
          <td width="95" class="cabecera" align="center" >TIPO PAGO</td>
    	      <td width="95" class="cabecera" align="center" >FLETE</td>
              <td width="95" class="cabecera" align="center" >EXCED</td>
    	      <td width="103" class="cabecera" align="center">DESC</td>
    	      <td width="107" class="cabecera" align="center">RECO</td>
    	      <td width="99" class="cabecera" align="right">EAD</td>
    	      <td width="112" class="cabecera" align="center" >SEGURO</td>
    	      <td width="96" class="cabecera" align="center" >OTROS</td>
    	      <td width="102" class="cabecera" align="center" >COMB</td>
    	      <td width="100" class="cabecera" align="left" >IVA</td>
    	      <td width="105" class="cabecera" align="left" >IVA RET</td>
    	      <td width="111" class="cabecera" align="left" >TOTAL</td>
    	      <td width="132" class="cabecera" align="left" >ESTADO</td>
      </tr>
    	    <?
					$s = "CREATE TEMPORARY TABLE `guiasreporte_tmp` (
					  `id` VARCHAR(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
					  `fecha` VARCHAR(10) CHARACTER SET utf8 DEFAULT NULL,
					  `tflete` DOUBLE DEFAULT NULL,
					  `texcedente` DOUBLE DEFAULT NULL,
					  `tdescuento` DOUBLE DEFAULT NULL,
					  `trecoleccion` DOUBLE DEFAULT NULL,
					  `tcostoead` DOUBLE DEFAULT NULL,
					  `tseguro` DOUBLE DEFAULT NULL,
					  `totros` DOUBLE DEFAULT NULL,
					  `tcombustible` DOUBLE DEFAULT NULL,
					  `iva` DOUBLE DEFAULT NULL,
					  `ivaretenido` DOUBLE DEFAULT NULL,
					  `total` DOUBLE DEFAULT NULL,
					  `estado` VARCHAR(80) COLLATE utf8_unicode_ci DEFAULT NULL,
					  `tipoflete` VARCHAR(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
					  `tipopago` VARCHAR(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
					  `sucursal` VARCHAR(7) CHARACTER SET utf8 NOT NULL DEFAULT '',
					  `tiporep` CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT ''
					) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
					mysql_query($s,$l) or die($s);
					//echo $s.";";
					
					$s = "INSERT INTO guiasreporte_tmp
					(SELECT gv.id, DATE_FORMAT(gv.fecha,'%d/%m/%Y') fecha, gv.tflete, gv.texcedente, 
					gv.ttotaldescuento, gv.trecoleccion, gv.tcostoead, gv.tseguro, gv.totros, gv.tcombustible, gv.tiva, gv.ivaretenido, gv.total, gv.estado,
					IF(gv.tipoflete=0,'PAGADA','POR COBRAR') tipoflete, IF(gv.condicionpago=1,'CREDITO','CONTADO') tipopago, cs.prefijo destino,'E'
					FROM guiasventanilla gv
					INNER JOIN catalogosucursal cs on gv.idsucursaldestino = cs.id
					WHERE gv.idsucursalorigen = $_GET[sucursal] 
					$andfec1)
					UNION
					(SELECT ge.id, DATE_FORMAT(ge.fecha,'%d/%m/%Y') fecha, ge.tflete, ge.texcedente,
					ge.ttotaldescuento, ge.trecoleccion, ge.tcostoead, ge.tseguro, ge.totros, ge.tcombustible, ge.tiva, ge.ivaretenido, ge.total, ge.estado,
					ge.tipoflete, ge.tipopago, cs.prefijo destino,'E'
					FROM guiasempresariales ge
					INNER JOIN catalogosucursal cs on ge.idsucursaldestino = cs.id
					WHERE ge.idsucursalorigen = $_GET[sucursal] 
					$andfec2)";
					mysql_query($s,$l) or die($s);
					
					$s = "INSERT INTO guiasreporte_tmp
					(SELECT gv.id, DATE_FORMAT(gv.fecha,'%d/%m/%Y') fecha, gv.tflete, gv.texcedente,
					gv.ttotaldescuento, gv.trecoleccion, gv.tcostoead, gv.tseguro, gv.totros, gv.tcombustible, gv.tiva, gv.ivaretenido, gv.total, gv.estado,
					IF(gv.tipoflete=0,'PAGADA','POR COBRAR') tipoflete, IF(gv.condicionpago=1,'CREDITO','CONTADO') tipopago, cs.prefijo destino,'R'
					FROM guiasventanilla gv
					INNER JOIN catalogosucursal cs on gv.idsucursalorigen = cs.id
					WHERE gv.idsucursaldestino = $_GET[sucursal] 
					$andfec1)
					UNION
					(SELECT ge.id, DATE_FORMAT(ge.fecha,'%d/%m/%Y') fecha, ge.tflete, ge.texcedente,
					ge.ttotaldescuento, ge.trecoleccion, ge.tcostoead, ge.tseguro, ge.totros, ge.tcombustible, ge.tiva, ge.ivaretenido, ge.total, ge.estado,
					ge.tipoflete, ge.tipopago, cs.prefijo destino,'R'
					FROM guiasempresariales ge
					INNER JOIN catalogosucursal cs on ge.idsucursalorigen = cs.id
					WHERE ge.idsucursaldestino = $_GET[sucursal] 
					$andfec2)";
					mysql_query($s,$l) or die($s);
					
					//echo $s.";";
					$s = "select * from guiasreporte_tmp where tiporep = 'E' order by sucursal";
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
								  <td align="right"><?=$arrecontado[tflete]?></td>
                                  <td align="right"><?=$arrecontado[texcedente]?></td>
								  <td align="right"><?=$arrecontado[ttotaldescuento]?></td>
								  <td align="right"><?=$arrecontado[trecoleccion]?></td>
								  <td align="right"><?=$arrecontado[tcostoead]?></td>
								  <td align="right"><?=$arrecontado[tseguro]?></td>
								  <td align="right"><?=$arrecontado[totros]?></td>
								  <td align="right"><?=$arrecontado[tcombustible]?></td>
								  <td align="right"><?=$arrecontado[tiva]?></td>
								  <td align="right"><?=$arrecontado[ivaretenido]?></td>
								  <td align="right"><?=$arrecontado[total]?></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">CREDITO</td>
                                  <td align="right">&nbsp;</td>
                                <td align="right">&nbsp;</td>
								  <td align="right"><?=$arrecredito[tflete]?></td>
								  <td align="right"><?=$arrecredito[texcedente]?></td>
								  <td align="right"><?=$arrecredito[ttotaldescuento]?></td>
								  <td align="right"><?=$arrecredito[trecoleccion]?></td>
								  <td align="right"><?=$arrecredito[tcostoead]?></td>
								  <td align="right"><?=$arrecredito[tseguro]?></td>
								  <td align="right"><?=$arrecredito[totros]?></td>
								  <td align="right"><?=$arrecredito[tcombustible]?></td>
								  <td align="right"><?=$arrecredito[tiva]?></td>
								  <td align="right"><?=$arrecredito[ivaretenido]?></td>
								  <td align="right"><?=$arrecredito[total]?></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CONTADO</td>
                                  <td align="right">&nbsp;</td>
                                <td align="right">&nbsp;</td>
								  <td align="right"><?=$arrecobcon[tflete]?></td>
								  <td align="right"><?=$arrecobcon[texcedente]?></td>
								  <td align="right"><?=$arrecobcon[tdescuento]?></td>
								  <td align="right"><?=$arrecobcon[trecoleccion]?></td>
								  <td align="right"><?=$arrecobcon[tcostoead]?></td>
								  <td align="right"><?=$arrecobcon[tseguro]?></td>
								  <td align="right"><?=$arrecobcon[totros]?></td>
								  <td align="right"><?=$arrecobcon[tcombustible]?></td>
								  <td align="right"><?=$arrecobcon[tiva]?></td>
								  <td align="right"><?=$arrecobcon[ivaretenido]?></td>
								  <td align="right"><?=$arrecobcon[total]?></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CREDITO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?=$arrecobcre[tflete]?></td>
								  <td align="right"><?=$arrecobcre[texcedente]?></td>
								  <td align="right"><?=$arrecobcre[tdescuento]?></td>
								  <td align="right"><?=$arrecobcre[trecoleccion]?></td>
								  <td align="right"><?=$arrecobcre[tcostoead]?></td>
								  <td align="right"><?=$arrecobcre[tseguro]?></td>
								  <td align="right"><?=$arrecobcre[totros]?></td>
								  <td align="right"><?=$arrecobcre[tcombustible]?></td>
								  <td align="right"><?=$arrecobcre[tiva]?></td>
								  <td align="right"><?=$arrecobcre[ivaretenido]?></td>
								  <td align="right"><?=$arrecobcre[total]?></td>
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
								  <td class="totales" align="center">=SUMA(P<?=($cont-4);?>:P<?=($cont-1);?>)</td>
							  </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="16">&nbsp;</td>
                              </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="16">&nbsp;</td>
                              </tr>
							<?
                                
								
								$arrecontado = array('tflete'=>0,
													 'texcedente'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								$arrecredito = array('tflete'=>0,
													 'texcedente'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								$arrecobcon = array('tflete'=>0,
													 'texcedente'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								$arrecobcre = array('tflete'=>0,
													 'texcedente'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								
								
								}
                            ?>
               
                              <? $cont++;?>     
			<tr>
    	      <td colspan="16" class="cabecera" align="left">&nbsp;DESTINO: <?=$f->sucursal?></td>
          </tr>
					<?
								$final = true;
							}
							
							if($f->tipoflete=='POR COBRAR'){
								if($f->tipopago=='CREDITO'){
									$arrecobcre[tflete] += $f->tflete;
									$arrecobcre[texcedente] += $f->texcedente;
									$arrecobcre[tdescuento] += $f->tdescuento;
									$arrecobcre[trecoleccion] += $f->trecoleccion;
									$arrecobcre[tcostoead] += $f->tcostoead;
									$arrecobcre[tseguro] += $f->tseguro;
									$arrecobcre[totros] += $f->totros;
									$arrecobcre[tcombustible] += $f->tcombustible;
									$arrecobcre[tiva] += $f->tiva;
									$arrecobcre[ivaretenido] += $f->ivaretenido;
									$arrecobcre[total] += $f->total;
								}else{
									$arrecobcon[tflete] += $f->tflete;
									$arrecobcon[texcedente] += $f->texcedente;
									$arrecobcon[tdescuento] += $f->tdescuento;
									$arrecobcon[trecoleccion] += $f->trecoleccion;
									$arrecobcon[tcostoead] += $f->tcostoead;
									$arrecobcon[tseguro] += $f->tseguro;
									$arrecobcon[totros] += $f->totros;
									$arrecobcon[tcombustible] += $f->tcombustible;
									$arrecobcon[tiva] += $f->tiva;
									$arrecobcon[ivaretenido] += $f->ivaretenido;
									$arrecobcon[total] += $f->total;
								}
							}else{
								if($f->tipopago=='CREDITO'){
									$arrecredito[tflete] += $f->tflete;
									$arrecredito[texcedente] += $f->texcedente;
									$arrecredito[tdescuento] += $f->tdescuento;
									$arrecredito[trecoleccion] += $f->trecoleccion;
									$arrecredito[tcostoead] += $f->tcostoead;
									$arrecredito[tseguro] += $f->tseguro;
									$arrecredito[totros] += $f->totros;
									$arrecredito[tcombustible] += $f->tcombustible;
									$arrecredito[tiva] += $f->tiva;
									$arrecredito[ivaretenido] += $f->ivaretenido;
									$arrecredito[total] += $f->total;
								}else{
									$arrecontado[tflete] += $f->tflete;
									$arrecontado[texcedente] += $f->texcedente;
									$arrecontado[tdescuento] += $f->tdescuento;
									$arrecontado[trecoleccion] += $f->trecoleccion;
									$arrecontado[tcostoead] += $f->tcostoead;
									$arrecontado[tseguro] += $f->tseguro;
									$arrecontado[totros] += $f->totros;
									$arrecontado[tcombustible] += $f->tcombustible;
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
    	      <td align="right"><?=$f->tflete?></td>
    	      <td align="right"><?=$f->texcedente?></td>
    	      <td align="right"><?=$f->tdescuento?></td>
    	      <td align="right"><?=$f->trecoleccion?></td>
    	      <td align="right"><?=$f->tcostoead?></td>
    	      <td align="right"><?=$f->tseguro?></td>
    	      <td align="right"><?=$f->totros?></td>
    	      <td align="right"><?=$f->tcombustible?></td>
    	      <td align="right"><?=$f->tiva?></td>
    	      <td align="right"><?=$f->ivaretenido?></td>
    	      <td align="right"><?=$f->total?></td>
    	      <td align="center"><?=$f->estado?></td>
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
								  <td align="right"><?=$arrecontado[tflete]?></td>
								  <td align="right"><?=$arrecontado[texcedente]?></td>
								  <td align="right"><?=$arrecontado[tdescuento]?></td>
								  <td align="right"><?=$arrecontado[trecoleccion]?></td>
								  <td align="right"><?=$arrecontado[tcostoead]?></td>
								  <td align="right"><?=$arrecontado[tseguro]?></td>
								  <td align="right"><?=$arrecontado[totros]?></td>
								  <td align="right"><?=$arrecontado[tcombustible]?></td>
								  <td align="right"><?=$arrecontado[tiva]?></td>
								  <td align="right"><?=$arrecontado[ivaretenido]?></td>
								  <td align="right"><?=$arrecontado[total]?></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">CREDITO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?=$arrecredito[tflete]?></td>
								  <td align="right"><?=$arrecredito[texcedente]?></td>
								  <td align="right"><?=$arrecredito[tdescuento]?></td>
								  <td align="right"><?=$arrecredito[trecoleccion]?></td>
								  <td align="right"><?=$arrecredito[tcostoead]?></td>
								  <td align="right"><?=$arrecredito[tseguro]?></td>
								  <td align="right"><?=$arrecredito[totros]?></td>
								  <td align="right"><?=$arrecredito[tcombustible]?></td>
								  <td align="right"><?=$arrecredito[tiva]?></td>
								  <td align="right"><?=$arrecredito[ivaretenido]?></td>
								  <td align="right"><?=$arrecredito[total]?></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CONTADO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?=$arrecobcon[tflete]?></td>
								  <td align="right"><?=$arrecobcon[texcedente]?></td>
								  <td align="right"><?=$arrecobcon[tdescuento]?></td>
								  <td align="right"><?=$arrecobcon[trecoleccion]?></td>
								  <td align="right"><?=$arrecobcon[tcostoead]?></td>
								  <td align="right"><?=$arrecobcon[tseguro]?></td>
								  <td align="right"><?=$arrecobcon[totros]?></td>
								  <td align="right"><?=$arrecobcon[tcombustible]?></td>
								  <td align="right"><?=$arrecobcon[tiva]?></td>
								  <td align="right"><?=$arrecobcon[ivaretenido]?></td>
								  <td align="right"><?=$arrecobcon[total]?></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CREDITO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?=$arrecobcre[tflete]?></td>
								  <td align="right"><?=$arrecobcre[texcedente]?></td>
								  <td align="right"><?=$arrecobcre[tdescuento]?></td>
								  <td align="right"><?=$arrecobcre[trecoleccion]?></td>
								  <td align="right"><?=$arrecobcre[tcostoead]?></td>
								  <td align="right"><?=$arrecobcre[tseguro]?></td>
								  <td align="right"><?=$arrecobcre[totros]?></td>
								  <td align="right"><?=$arrecobcre[tcombustible]?></td>
								  <td align="right"><?=$arrecobcre[tiva]?></td>
								  <td align="right"><?=$arrecobcre[ivaretenido]?></td>
								  <td align="right"><?=$arrecobcre[total]?></td>
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
								  <td class="totales" align="center">=SUMA(P<?=($cont-4);?>:P<?=($cont-1);?>)</td>
							  </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="16">&nbsp;</td>
                              </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="16">&nbsp;</td>
                              </tr>
    </table>
	
    
    
    
    <table border="0" cellpadding="1" cellspacing="0">
    	<tr>
        	<td colspan="16" align="center" class="titulo">PAQUETERIA Y MENSAJERIA</td>
        </tr>
    	<tr>
    	  <td width="126">REPORTE:</td>
    	  <td colspan="15">RELACION DE RECIBIDO</td>
   	  </tr>
    	<tr>
    	  <td>SUCURSAL:</td>
    	  <td colspan="15" align="left"><?=$nsucursal?></td>
   	  </tr>
    	<tr>
    	  <td>FECHAS</td>
    	  <td colspan="15" align="left"><?=$fechas?></td>
   	  </tr>
    	<tr>
    	  <td height="9px"></td>
    	  <td width="73">&nbsp;</td>
    	  <td width="95"></td>
    	  <td width="103"></td>
    	  <td width="107"></td>
    	  <td width="99"></td>
    	  <td width="112"></td>
    	  <td colspan="9"></td>
      </tr>
    	<tr>
    	      <td width="126" class="cabecera" align="center">GUIA</td>
    	      <td width="73" class="cabecera" align="center" >FECHA</td>
              <td width="95" class="cabecera" align="center" >TIPO FLETE</td>
          <td width="95" class="cabecera" align="center" >TIPO PAGO</td>
    	      <td width="95" class="cabecera" align="center" >FLETE</td>
    	      <td width="95" class="cabecera" align="center" >EXCED</td>
    	      <td width="103" class="cabecera" align="center">DESC</td>
    	      <td width="107" class="cabecera" align="center">RECO</td>
    	      <td width="99" class="cabecera" align="right">EAD</td>
    	      <td width="112" class="cabecera" align="center" >SEGURO</td>
    	      <td width="96" class="cabecera" align="center" >OTROS</td>
    	      <td width="102" class="cabecera" align="center" >COMB</td>
    	      <td width="100" class="cabecera" align="left" >IVA</td>
    	      <td width="105" class="cabecera" align="left" >IVA RET</td>
    	      <td width="111" class="cabecera" align="left" >TOTAL</td>
    	      <td width="132" class="cabecera" align="left" >ESTADO</td>
      </tr>
    	    <?					
					//echo $s.";";
					$s = "select * from guiasreporte_tmp where tiporep = 'R' order by sucursal";
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
								  <td align="right"><?=$arrecontado[tflete]?></td>
								  <td align="right"><?=$arrecontado[texcedente]?></td>
								  <td align="right"><?=$arrecontado[tdescuento]?></td>
								  <td align="right"><?=$arrecontado[trecoleccion]?></td>
								  <td align="right"><?=$arrecontado[tcostoead]?></td>
								  <td align="right"><?=$arrecontado[tseguro]?></td>
								  <td align="right"><?=$arrecontado[totros]?></td>
								  <td align="right"><?=$arrecontado[tcombustible]?></td>
								  <td align="right"><?=$arrecontado[tiva]?></td>
								  <td align="right"><?=$arrecontado[ivaretenido]?></td>
								  <td align="right"><?=$arrecontado[total]?></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">CREDITO</td>
                                  <td align="right">&nbsp;</td>
                                <td align="right">&nbsp;</td>
								  <td align="right"><?=$arrecredito[tflete]?></td>
								  <td align="right"><?=$arrecredito[texcedente]?></td>
								  <td align="right"><?=$arrecredito[tdescuento]?></td>
								  <td align="right"><?=$arrecredito[trecoleccion]?></td>
								  <td align="right"><?=$arrecredito[tcostoead]?></td>
								  <td align="right"><?=$arrecredito[tseguro]?></td>
								  <td align="right"><?=$arrecredito[totros]?></td>
								  <td align="right"><?=$arrecredito[tcombustible]?></td>
								  <td align="right"><?=$arrecredito[tiva]?></td>
								  <td align="right"><?=$arrecredito[ivaretenido]?></td>
								  <td align="right"><?=$arrecredito[total]?></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CONTADO</td>
                                  <td align="right">&nbsp;</td>
                                <td align="right">&nbsp;</td>
								  <td align="right"><?=$arrecobcon[tflete]?></td>
								  <td align="right"><?=$arrecobcon[texcedente]?></td>
								  <td align="right"><?=$arrecobcon[tdescuento]?></td>
								  <td align="right"><?=$arrecobcon[trecoleccion]?></td>
								  <td align="right"><?=$arrecobcon[tcostoead]?></td>
								  <td align="right"><?=$arrecobcon[tseguro]?></td>
								  <td align="right"><?=$arrecobcon[totros]?></td>
								  <td align="right"><?=$arrecobcon[tcombustible]?></td>
								  <td align="right"><?=$arrecobcon[tiva]?></td>
								  <td align="right"><?=$arrecobcon[ivaretenido]?></td>
								  <td align="right"><?=$arrecobcon[total]?></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CREDITO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?=$arrecobcre[tflete]?></td>
								  <td align="right"><?=$arrecobcre[texcedente]?></td>
								  <td align="right"><?=$arrecobcre[tdescuento]?></td>
								  <td align="right"><?=$arrecobcre[trecoleccion]?></td>
								  <td align="right"><?=$arrecobcre[tcostoead]?></td>
								  <td align="right"><?=$arrecobcre[tseguro]?></td>
								  <td align="right"><?=$arrecobcre[totros]?></td>
								  <td align="right"><?=$arrecobcre[tcombustible]?></td>
								  <td align="right"><?=$arrecobcre[tiva]?></td>
								  <td align="right"><?=$arrecobcre[ivaretenido]?></td>
								  <td align="right"><?=$arrecobcre[total]?></td>
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
								  <td class="totales" align="center">=SUMA(P<?=($cont-4);?>:P<?=($cont-1);?>)</td>
							  </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="16">&nbsp;</td>
                              </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="16">&nbsp;</td>
                              </tr>
							<?
                                
								
								$arrecontado = array('tflete'=>0,
													 'texcedente'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								$arrecredito = array('tflete'=>0,
													 'texcedente'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								$arrecobcon = array('tflete'=>0,
													 'texcedente'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								$arrecobcre = array('tflete'=>0,
													 'texcedente'=>0,
													 'tdescuento'=>0,
													 'trecoleccion'=>0,
													 'tcostoead'=>0,
													 'tseguro'=>0,
													 'totros'=>0,
													 'tcombustible'=>0,
													 'tiva'=>0,
													 'ivaretenido'=>0,
													 'total'=>0);
								
								
								}
                            ?>
               
                              <? $cont++;?>     
			<tr>
    	      <td colspan="16" class="cabecera" align="left">ORIGEN: <?=$f->sucursal?></td>
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
    	      <td align="right"><?=$f->tflete?></td>
    	      <td align="right"><?=$f->texcedente?></td>
    	      <td align="right"><?=$f->tdescuento?></td>
    	      <td align="right"><?=$f->trecoleccion?></td>
    	      <td align="right"><?=$f->tcostoead?></td>
    	      <td align="right"><?=$f->tseguro?></td>
    	      <td align="right"><?=$f->totros?></td>
    	      <td align="right"><?=$f->tcombustible?></td>
    	      <td align="right"><?=$f->tiva?></td>
    	      <td align="right"><?=$f->ivaretenido?></td>
    	      <td align="right"><?=$f->total?></td>
    	      <td align="center"><?=$f->estado?></td>
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
								  <td align="right"><?=$arrecontado[tflete]?></td>
								  <td align="right"><?=$arrecontado[texcedente]?></td>
								  <td align="right"><?=$arrecontado[tdescuento]?></td>
								  <td align="right"><?=$arrecontado[trecoleccion]?></td>
								  <td align="right"><?=$arrecontado[tcostoead]?></td>
								  <td align="right"><?=$arrecontado[tseguro]?></td>
								  <td align="right"><?=$arrecontado[totros]?></td>
								  <td align="right"><?=$arrecontado[tcombustible]?></td>
								  <td align="right"><?=$arrecontado[tiva]?></td>
								  <td align="right"><?=$arrecontado[ivaretenido]?></td>
								  <td align="right"><?=$arrecontado[total]?></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">CREDITO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?=$arrecredito[tflete]?></td>
								  <td align="right"><?=$arrecredito[texcedente]?></td>
								  <td align="right"><?=$arrecredito[tdescuento]?></td>
								  <td align="right"><?=$arrecredito[trecoleccion]?></td>
								  <td align="right"><?=$arrecredito[tcostoead]?></td>
								  <td align="right"><?=$arrecredito[tseguro]?></td>
								  <td align="right"><?=$arrecredito[totros]?></td>
								  <td align="right"><?=$arrecredito[tcombustible]?></td>
								  <td align="right"><?=$arrecredito[tiva]?></td>
								  <td align="right"><?=$arrecredito[ivaretenido]?></td>
								  <td align="right"><?=$arrecredito[total]?></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CONTADO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?=$arrecobcon[tflete]?></td>
								  <td align="right"><?=$arrecobcon[texcedente]?></td>
								  <td align="right"><?=$arrecobcon[tdescuento]?></td>
								  <td align="right"><?=$arrecobcon[trecoleccion]?></td>
								  <td align="right"><?=$arrecobcon[tcostoead]?></td>
								  <td align="right"><?=$arrecobcon[tseguro]?></td>
								  <td align="right"><?=$arrecobcon[totros]?></td>
								  <td align="right"><?=$arrecobcon[tcombustible]?></td>
								  <td align="right"><?=$arrecobcon[tiva]?></td>
								  <td align="right"><?=$arrecobcon[ivaretenido]?></td>
								  <td align="right"><?=$arrecobcon[total]?></td>
								  <td align="center"></td>
							  </tr>
                              <? $cont++;?>
                              <tr>
								  <td align="center" colspan="2">COBRAR CREDITO</td>
								  <td align="right">&nbsp;</td>
								  <td align="right">&nbsp;</td>
								  <td align="right"><?=$arrecobcre[tflete]?></td>
								  <td align="right"><?=$arrecobcre[texcedente]?></td>
								  <td align="right"><?=$arrecobcre[tdescuento]?></td>
								  <td align="right"><?=$arrecobcre[trecoleccion]?></td>
								  <td align="right"><?=$arrecobcre[tcostoead]?></td>
								  <td align="right"><?=$arrecobcre[tseguro]?></td>
								  <td align="right"><?=$arrecobcre[totros]?></td>
								  <td align="right"><?=$arrecobcre[tcombustible]?></td>
								  <td align="right"><?=$arrecobcre[tiva]?></td>
								  <td align="right"><?=$arrecobcre[ivaretenido]?></td>
								  <td align="right"><?=$arrecobcre[total]?></td>
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
								  <td class="totales" align="center">=SUMA(P<?=($cont-4);?>:P<?=($cont-1);?>)</td>
							  </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="16">&nbsp;</td>
                              </tr>
                              <? $cont++;?>
                              <tr>
                              	<td colspan="16">&nbsp;</td>
                              </tr>
    </table>
	