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
	
	if(!empty($_GET[reporteweb])){
		$s = "SELECT prefijo FROM catalogosucursal WHERE id = '".$_GET[sucursal]."'";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		$_GET[sucursalprefijo] = $f->prefijo;
		
		$and = " AND rc1.prefijosucursal = '$_GET[sucursalprefijo]' ";
	}
	
	if($_GET[sucursalprefijo]==""){
		$and = "";
	}
	
	$s = "SELECT UCASE(cliente) cliente, prefijosucursal,folio,DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha,fechavenc,diasvencidos,
		alcorriente,c1a15dias,c16a30dias,c31a60dias,may60dias,saldo,factura,contrarecibo FROM(
		SELECT cs.prefijo AS prefijosucursal,IFNULL(CONCAT(sc.nombre,' ',sc.paterno,' ',sc.materno),'') AS cliente,
		IFNULL(sc.folio,'') AS folio,ge.fecha,IFNULL(ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY)),0) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(sc.diascredito,0) DAY))<=0,ge.total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY))<16,ge.total,0) AS c1a15dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY))<31,ge.total,0) AS c16a30dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY))<61,ge.total,0) AS c31a60dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY))>60,ge.total,0) AS may60dias,
		ge.total AS saldo,0 AS factura,IFNULL(ge.acuserecibo,0)AS contrarecibo, ge.id FROM guiasempresariales ge 
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id
		LEFT JOIN solicitudcredito sc ON ge.idremitente=sc.cliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND ge.idsucursalorigen='$_GET[sucursalprefijo]'
		UNION
		SELECT cs.prefijo AS prefijosucursal,IFNULL(CONCAT(sc.nombre,' ',sc.paterno,' ',sc.materno),'') AS cliente,
		IFNULL(sc.folio,'') AS folio,gv.fecha,IFNULL(ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY)),0) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(sc.diascredito,0) DAY))<=0,gv.total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY))<16,gv.total,0) AS c1a15dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY))<31,gv.total,0) AS c16a30dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY))<61,gv.total,0) AS c31a60dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY))>60,gv.total,0) AS may60dias,
		gv.total AS saldo,0 AS factura,IFNULL(gv.acuserecibo,0)AS contrarecibo,gv.id FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id
		LEFT JOIN solicitudcredito sc ON gv.idremitente=sc.cliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND idsucursalorigen='$_GET[sucursalprefijo]'
		UNION
		SELECT cs.prefijo AS prefijosucursal,IFNULL(CONCAT(sc.nombre,' ',sc.paterno,' ',sc.materno),'') AS cliente,
		IFNULL(sc.folio,'') AS folio,f.fecha,IFNULL(ADDDATE(f.fecha,INTERVAL sc.diascredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY)),0) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(sc.diascredito,0) DAY))<=0,f.total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY))<16,f.total,0) AS c1a15dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY))<31,f.total,0) AS c16a30dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY))<61,f.total,0) AS c31a60dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY))>60,f.total,0) AS may60dias,
		f.total AS saldo,IFNULL(f.folio,'')AS factura,0 AS contrarecibo, f.folio AS id FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		LEFT JOIN solicitudcredito sc ON f.cliente=sc.cliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.idsucursal='$_GET[sucursalprefijo]')t ";
	$r  = mysql_query($s,$l) or die($s);
?>
	<table width="1211">
    	<tr>
        	<td colspan="13" style="font-weight:bold; font-size:16">TITULO: ANTIGUEDAD DE SALDOS</td>
        </tr>
    	<tr>
    	  <td width="69" style="font-weight:bold; border:#000 solid 1px"">SUCURSAL</td>
    	  <td width="130" style="font-weight:bold; border:#000 solid 1px"">GUIA/FACTURA</td>
    	  <td width="54" style="font-weight:bold; border:#000 solid 1px"">FECHA</td>
    	  <td width="76" style="font-weight:bold; border:#000 solid 1px"">FECHA VTO.</td>
    	  <td width="70" style="font-weight:bold; border:#000 solid 1px"">DIAS VENC.</td>
    	  <td width="106" style="font-weight:bold; border:#000 solid 1px"">AL CORRIENTE</td>
    	  <td width="78" style="font-weight:bold; border:#000 solid 1px"">1-15 DIAS</td>
    	  <td width="85" style="font-weight:bold; border:#000 solid 1px"">16-30 DIAS</td>
    	  <td width="77" style="font-weight:bold; border:#000 solid 1px"">31-60 DIAS</td>
    	  <td width="78" style="font-weight:bold; border:#000 solid 1px"">60- DIAS</td>
    	  <td width="103" style="font-weight:bold; border:#000 solid 1px"">SALDO</td>
    	  <td width="121" style="font-weight:bold; border:#000 solid 1px"">FACTURA</td>
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
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;	
			if($cliente!=cambio_texto($f[cliente])){
				if($cliente!=""){
				?>
				<tr>
				  <td colspan="4"  style="border-bottom: 1px #000 solid; border-left:1px #000 solid; font-weight:bold">TOTAL <?=cambio_texto($cliente)?></td>
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
              	<td colspan="13" style="font-weight:bold"></td>
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
                  <td style="border-top:#000 1px solid; border-left:#000 1px solid;font-weight:bold" colspan="3"><?=cambio_texto($f[cliente])?></td>
                  <td style="border-top:#000 1px solid">&nbsp;</td>
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
				  <td style=" border-left:1px solid #000"><?=$f[1]?></td>
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
				  <td style=" border-right:1px solid #000"><?=$f[13]?></td>
			  </tr>
			<?
				$var_alco  	+= $f[6];
				$var_115   	+= $f[7];
				$var_1630   += $f[8];
				$var_3160   += $f[9];
				$var_60   	+= $f[10];
				$var_saldo 	+= $f[11];
				
				$alcorriente += $f[alcorriente];
				$vencido += $f[7]+$f[8]+$f[9]+$f[10];
				$vartotal += $f[saldo];
			}
			?>
     		 <tr>
				  <td colspan="4"  style="border-bottom: 1px #000 solid; border-left:1px #000 solid; font-weight:bold">TOTAL <?=cambio_texto($cliente)?></td>
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
				  <td colspan="4"  style="border-bottom: 1px #000 solid; border-left:1px #000 solid; font-weight:bold">TOTAL</td>
				  <td style="border-bottom: 1px #000 solid; ">&nbsp;</td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold">VENCIDO</td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($vencido,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold">AL CORR.</td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($alcorriente,2)?></td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold">TOTAL</td>
				  <td style="border-bottom: 1px #000 solid; font-weight:bold"><?=number_format($vartotal,2)?></td>
				  <td style="border-bottom: 1px #000 solid; ">&nbsp;</td>
				  <td style="border-bottom: 1px #000 solid; border-right:1px #000 solid">&nbsp;</td>
			  </tr>
    </table>