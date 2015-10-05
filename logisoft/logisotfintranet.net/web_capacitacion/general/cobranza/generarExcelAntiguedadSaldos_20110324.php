<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=AntiguedadDeSaldos.xls");
	header("Expires: 0");
?>
<style>
	td{
		font-size:12px;
		font-family:Arial, Helvetica, sans-serif;
	}
</style>
<?
	function cambio_texto($texto){
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
	
	function cambiaf_a_mysql($fecha){//Convierte fecha de normal a mysql
    	ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
	    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
    	return $lafecha; 
	}
	
	$str = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; 
	if(!ereg("dbserver",$str)){
		$l = mysql_connect("localhost","pmm","gqx64p9n");
	}else{
		$l = mysql_connect("DBSERVER","root","root");
	}
	if(ereg("web_pruebas/",$str)){
		mysql_select_db("pmm_dbpruebas", $l);
	}else if(ereg("web_capacitacion/",$str)){
		mysql_select_db("pmm_curso", $l);
	}else if(ereg("web/",$str)){
		mysql_select_db("pmm_dbweb", $l);
	}else if(ereg("dbserver",$str)){
		mysql_select_db("webpmm", $l);
	}
	if($_GET[todassucursales]!='true'){
			$s = "SELECT prefijo FROM catalogosucursal WHERE id = '".$_GET[sucursal]."'";
			$r = mysql_query($s,$l) or die($s);
			$ff = mysql_fetch_object($r);
		}
		if($_GET[todassucursales]!='true' && $_GET[sucursal]!=""){
			$andsucursal = " AND prefijosucursal = '$ff->prefijo' ";
		}
		
		if($_GET[idCliente]!='')
		{
			$folioCliente = " AND idcliente = $_GET[idCliente]";
		}	
	$s = "SELECT rc.prefijosucursal,rc.cliente,rc.folio, IFNULL(DATE_FORMAT(rc.fecha,'%d/%m/%Y'),'') AS fechaguia, 
		IFNULL(DATE_FORMAT(rc.fechafactura,'%d/%m/%Y'),'') AS fechafactura,
		DATE_FORMAT(IFNULL(rc.fechavencimiento,rc.fechavencimientof),'%d/%m/%Y') AS fechavenc, 
		IF(DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))<0,0,DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))<=0,rc.total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))<16 
		AND DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))>0,rc.total,0) c1a15dias,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))<31 
		AND DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))>15,rc.total,0) c16a30dias,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))<61 
		AND DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))>30,rc.total,0) c31a60dias,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))>60,rc.total,0) may60dias,
		total AS saldo,IFNULL(rc.factura,'') AS factura, IFNULL(co.contrarecibo,'') AS contrarecibo,rc.idcliente
		FROM reporte_cobranza1 rc
		LEFT JOIN registrodecontrarecibos co ON rc.factura = co.factura
		WHERE estado = 'ACTIVA' AND pagado = 'N' $andsucursal $folioCliente and folio >0
		AND (rc.tipo='V' OR (rc.tipo='E' AND NOT ISNULL(rc.factura))) GROUP BY rc.folio ";
	$r  = mysql_query($s,$l) or die($s);
?>
	<table width="1211">
    	<tr>
        	<td colspan="14" style="font-weight:bold; font-size:16">TITULO: ANTIGUEDAD DE SALDOS</td>
        </tr>
    	<tr>
    	  <td width="97" style="font-weight:bold; border:#000 solid 1px"">SUCURSAL</td>
		  <td width="162" style="font-weight:bold; border:#000 solid 1px"">GUIA/FACTURA</td>
    	  <td width="77" style="font-weight:bold; border:#000 solid 1px"">FECHA</td>
		  <td width="76" style="font-weight:bold; border:#000 solid 1px"">FECHA FACT</td>
    	  <td width="94" style="font-weight:bold; border:#000 solid 1px"">FECHA VTO.</td>
    	  <td width="53" style="font-weight:bold; border:#000 solid 1px"">DIAS VENC.</td>
    	  <td width="86" style="font-weight:bold; border:#000 solid 1px"">AL CORRIENTE</td>
    	  <td width="61" style="font-weight:bold; border:#000 solid 1px"">1-15 DIAS</td>
    	  <td width="63" style="font-weight:bold; border:#000 solid 1px"">16-30 DIAS</td>
    	  <td width="61" style="font-weight:bold; border:#000 solid 1px"">31-60 DIAS</td>
    	  <td width="64" style="font-weight:bold; border:#000 solid 1px"">60- DIAS</td>
    	  <td width="87" style="font-weight:bold; border:#000 solid 1px"">SALDO</td>
    	  <td width="62" style="font-weight:bold; border:#000 solid 1px"">FACTURA</td>
    	  <td width="108" style="font-weight:bold; border:#000 solid 1px">CONTRARECIBO</td>
      </tr>
      	<?
		$arre = array();
		$arr = 0;
		$cliente = "";
		$cant = 0;
		$total = 0;
		$var_alco  	= 0;
		$var_115   	= 0;
		$var_1630   = 0;
		$var_3160   = 0;
		$var_60   	= 0;
		$var_saldo 	= 0;
		
		$alcorriente = 0;
		$vencido = 0;
		$vartotal = 0;
		
		$to_115 = 0;
		$to_1630 = 0;
		$to_3160 = 0;
		$to_60 = 0;
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;	
			if($cliente!=cambio_texto($f[cliente])){
				if($cliente!=""){
				?>
				<tr>
				  <td colspan="5"  style="border-bottom: 1px #000 solid; border-left:1px #000 solid; font-weight:bold">TOTAL <?=cambio_texto($cliente)?></td>
				  <td style="border-bottom: 1px #000 solid; ">&nbsp;</td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_alco,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_115,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_1630,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_3160,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_60,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_saldo,2)?></td>
				  <td style="border-bottom: 1px #000 solid; ">&nbsp; </td>
				  <td style="border-bottom: 1px #000 solid; border-right:1px #000 solid">&nbsp;</td>
			  </tr>
              <tr>
              	<td colspan="14" style="font-weight:bold"></td>
              </tr>
			  <?
	  			$var_alco  	= 0;
				$var_115   	= 0;
				$var_1630   = 0;
				$var_3160   = 0;
				$var_60   	= 0;
				$var_saldo 	= 0;
	  			$cant = 0;
				}
				$cliente = cambio_texto($f[cliente]);
				?>
              <tr>
                  <td colspan="4" style="border-top:#000 1px solid; border-left:#000 1px solid;font-weight:bold">
				  <?=cambio_texto($f[cliente])?></td>
                  <td style="border-top:#000 1px solid">No. <?=cambio_texto($f[idcliente])?></td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
                  <td style="border-top:#000 1px solid; border-right:#000 1px solid">&nbsp;</td>
              </tr>
			<?
			}
			if($cliente == cambio_texto($f[cliente])){
				$cant++;
			}
			?>
			<tr>
				  <td style=" border-left:1px solid #000"><?=$f[0]?></td>
				  <td><?=$f[2]?></td>
				  <td><?=$f[3]?></td>
				  <td><?=$f[4]?></td>
				  <td><?=$f[5]?></td>
				  <td><?=$f[6]?></td>
				  <td><?=$f[7]?></td>
				  <td><?=$f[8]?></td>
				  <td><?=$f[9]?></td>
				  <td><?=$f[10]?></td>
				  <td><?=$f[11]?></td>
				  <td><?=$f[12]?></td>
				  <td><?=$f[13]?></td>
				  <td style=" border-right:1px solid #000"><?=$f[14]?></td>
			  </tr>
			<?
				$var_alco  	+= $f[7];
				$var_115   	+= $f[8];
				$var_1630   += $f[9];
				$var_3160   += $f[10];
				$var_60   	+= $f[11];
				$var_saldo 	+= $f[12];
				
				$alcorriente += $f[alcorriente];
				$vencido += $f[8]+$f[9]+$f[10]+$f[11];
				$vartotal += $f[saldo];
				
				$to_115 	+= $f[8];
				$to_1630 	+= $f[9];
				$to_3160 	+= $f[10];
				$to_60 		+= $f[11];
			}
			?>
     		 <tr>
				  <td colspan="5"  style="border-bottom: 1px #000 solid; border-left:1px #000 solid; font-weight:bold">TOTAL <?=cambio_texto($cliente)?></td>
				  <td style="border-bottom: 1px #000 solid; ">&nbsp;</td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_alco,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_115,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_1630,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_3160,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_60,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($var_saldo,2)?></td>
				  <td style="border-bottom: 1px #000 solid; ">&nbsp;</td>
				  <td style="border-bottom: 1px #000 solid; border-right:1px #000 solid">&nbsp;</td>
			  </tr>
              
              <tr>
				  <td colspan="5"  style="border-bottom: 1px #000 solid; border-left:1px #000 solid; font-weight:bold">TOTAL</td>
				  <td style="border-bottom: 1px #000 solid; ">&nbsp;</td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold">&nbsp;</td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($to_115,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($to_1630,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($to_3160,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($to_60,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($vartotal,2)?></td>
				  <td style="border-bottom: 1px #000 solid; ">&nbsp;</td>
				  <td style="border-bottom: 1px #000 solid; border-right:1px #000 solid">&nbsp;</td>
			  </tr>
    </table>