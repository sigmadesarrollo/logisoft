<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=estadoCuenta_Excel.xls");
	//header("Pragma: no-cache");
	//header("Expires: 0"); 
	
	require_once("../../Conectar.php");
	$l = Conectarse('webpmm');
	
	$s = "select * from catalogosucursal where id = '$_GET[sucursal]'";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$nsucursal = $f->descripcion;
	
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
	
</style>
	<table width="1728" border="0" cellpadding="1" cellspacing="0">
    	<tr>
        	<td colspan="14" align="center" class="titulo">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</td>
        </tr>
    	<tr>
    	  <td colspan="2">REPORTE</td>
    	  <td colspan="12">CONCECIONES (VENTAS RECIBIDAS)</td>
   	  </tr>
    	<tr>
    	  <td colspan="2"></td>
    	  <td colspan="12" align="left"><?=$nsucursal?></td>
   	  </tr>
    	<tr>
    	  <td colspan="2"></td>
    	  <td colspan="12" align="left"><?=date("d/m/Y");?></td>
   	  </tr>
    	<tr>
    	  <td height="9px" colspan="2"></td>
    	  <td width="95">&nbsp;</td>
    	  <td colspan="2"></td>
    	  <td colspan="2"></td>
    	  <td colspan="2"></td>
    	  <td colspan="5"></td>
      </tr>
    	<tr>
    	      <td width="132" height="19" align="left" class="cabecera">GUIA</td>
    	      <td width="83" align="left" class="cabecera">FECHA</td>
    	      <td align="right" class="cabecera" >FLETE</td>
    	      <td width="92" align="right" class="cabecera">DESCUENTO</td>
    	      <td width="114" align="right" class="cabecera">FLETE NETO</td>
    	      <td width="109" align="right" class="cabecera">COMISION</td>
    	      <td width="113" align="right" class="cabecera">RECOLECCION</td>
    	      <td width="109" align="right" class="cabecera" >COM. RAD</td>
    	      <td width="98" align="right" class="cabecera" >ENTREGA</td>
    	      <td width="108" class="cabecera" align="right" >COM. EAD</td>
    	      <td width="114" class="cabecera" align="right" >C. SOBREPESO</td>
    	      <td width="119" class="cabecera" align="right" >TOTAL</td>
    	      <td width="205" class="cabecera" align="right" >CONDICION</td>
    	      <td width="207" align="right" class="cabecera" >STATUS</td>
      </tr>
    	    <?
				$s = "INSERT INTO reporte_concesionestmp(guia,idusuario)
				SELECT guia, ".$_GET[usuario]." FROM reporte_concesiones WHERE tipo = 'R'
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal = ".$_GET[sucursal]." AND activo = 'S' AND folioconcesion IS NULL";
				mysql_query($s,$l) or die($s);
				
				$s = "SELECT guia,DATE_FORMAT(fechaguia,'%d/%m/%Y') AS fechaguia,tipoguia,tipoflete,condicionpago,flete,descuento,fleteneto,
				comision,recoleccion,comisionead,entrega,comisionrad,total,condicion,estado,sucursal,activo FROM reporte_concesiones WHERE tipo = 'R'
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE()")."
				AND sucursal = ".$_GET[sucursal]." AND activo = 'S' AND folioconcesion IS NULL";
				$r = mysql_query($s,$l) or die($s);
					$r = mysql_query($s,$l) or die($s);
					if(mysql_num_rows($r)>0){
						while($f = mysql_fetch_object($r)){
					?>
    	    <tr>
    	      <td align="left"><?=$f->guia?></td>
    	      <td align="left"><?=$f->fechaguia?></td>
    	      <td align="right"><?=$f->flete?></td>
    	      <td align="right"><?=$f->descuento?></td>
    	      <td align="right"><?=$f->fleteneto?></td>
    	      <td align="right"><?=$f->comision?></td>
    	      <td align="right"><?=$f->recoleccion?></td>
    	      <td align="right"><?=$f->comisionrad?></td>
    	      <td align="right"><?=$f->entrega?></td>
    	      <td align="right"><?=$f->comisionead?></td>
    	      <td align="right"><?=$f->sobrepeso?></td>
    	      <td align="right"><?=$f->total?></td>
    	      <td align="right"><?=$f->condicion?></td>
    	      <td align="right"><?=$f->estado?></td>
          </tr>
					<?
						}
					}
                    ?>
    </table>
    	    