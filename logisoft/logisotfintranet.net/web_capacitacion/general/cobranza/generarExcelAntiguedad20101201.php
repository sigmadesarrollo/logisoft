<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=estadoCuenta_Excel.xls");
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
		$s = "SELECT prefijo FROM catalogosucursal WHERE id = ".$_GET[sucursal];
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		$_GET[sucursalprefijo] = $f->prefijo;
	}
	
	$s = "SELECT cliente, prefijosucursal, folio, DATE_FORMAT(IFNULL(fechafactura,fecha),'%d/%m/%Y') AS fecha, 
	DATE_FORMAT(IFNULL(fechavencimiento,fechavencimientof),'%d/%m/%Y') AS fechavenc,
	IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<0,0,DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))) AS diasvencidos,
	IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<=0,total,0) AS alcorriente,
	IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<16 
	AND DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))>0,total,0) c1a15dias,
	IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<31 
	AND DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))>15,total,0) c16a30dias,
	IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<61 
	AND DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))>30,total,0) c31a60dias,
	IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))>60,total,0) may60dias,
	total AS saldo,
	IFNULL(factura,'') AS factura, IFNULL(contrarecibo,'') AS contrarecibo
	FROM reporte_cobranza1
	WHERE estado = 'ACTIVA' AND pagado = 'N' AND folio<>0 AND prefijosucursal = '$_GET[sucursalprefijo]'";
	$r  = mysql_query($s,$l) or die($s);
?>
	<table width="1211">
    	<tr>
        	<td colspan="13" style="font-weight:bold; font-size:16">TITULO: ANTIGUEDAD DE SALDOS</td>
        </tr>
    	<tr>
    	  <td width="69" style="font-weight:bold;">SUCURSAL</td>
    	  <td width="130" style="font-weight:bold;">GUIA/FACTURA</td>
    	  <td width="54" style="font-weight:bold;">FECHA</td>
    	  <td width="76" style="font-weight:bold;">FECHA VTO.</td>
    	  <td width="70" style="font-weight:bold;">DIAS VENC.</td>
    	  <td width="106" style="font-weight:bold;">AL CORRIENTE</td>
    	  <td width="78" style="font-weight:bold;">1-15 DIAS</td>
    	  <td width="85" style="font-weight:bold;">16-30 DIAS</td>
    	  <td width="77" style="font-weight:bold;">31-60 DIAS</td>
    	  <td width="78" style="font-weight:bold;">60- DIAS</td>
    	  <td width="103" style="font-weight:bold;">SALDO</td>
    	  <td width="121" style="font-weight:bold;">FACTURA</td>
    	  <td width="108" style="font-weight:bold;">CONTRARECIBO</td>
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
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;	
			if($cliente!=cambio_texto($f[cliente])){
				if($cliente!=""){
				?>
				<tr>
				  <td colspan="3" style="font-weight:bold">TOTAL <?=cambio_texto($cliente)?></td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
				  <td style="font-weight:bold"><?=number_format($var_alco)?></td>
				  <td style="font-weight:bold"><?=number_format($var_115)?></td>
				  <td style="font-weight:bold"><?=number_format($var_1630)?></td>
				  <td style="font-weight:bold"><?=number_format($var_3160)?></td>
				  <td style="font-weight:bold"><?=number_format($var_60)?></td>
				  <td style="font-weight:bold"><?=number_format($var_saldo)?></td>
				  <td></td>
				  <td></td>
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
                  <td colspan="3" style="font-weight:bold">TOTAL <?=cambio_texto($f[cliente])?></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
              </tr>
				<?
			}
			if($cliente == cambio_texto($f[cliente])){
				$cant++;
			}
			?>
			<tr>
				  <td><?=$f[1]?></td>
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
			  </tr>
			<?
				$var_alco  	+= $f[6];
				$var_115   	+= $f[7];
				$var_1630   += $f[8];
				$var_3160   += $f[9];
				$var_60   	+= $f[10];
				$var_saldo 	+= $f[11];
			}
			?>
      <tr>
    	  <td></td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
  	  </tr>
    </table>