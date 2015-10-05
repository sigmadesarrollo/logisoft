<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=Cobranza30.xls");
	
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
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
        	<td colspan="9" align="center" class="titulo">PAQUETERIA Y MENSAJERIA</td>
        </tr>
    	<tr>
    	  <td>REPORTE:</td>
    	  <td colspan="8">COBRANZA > 30</td>
   	  </tr>
    	<tr>
    	  <td height="9px" colspan="9"></td>
      </tr>
    	<tr>
    	      <td width="60" class="cabecera" align="center">FACTURA</td>
    	      <td width="70" class="cabecera" align="center">FECHA EMISION</td>
              <td width="300" class="cabecera" align="center">CLIENTE</td>
          	  <td width="80" class="cabecera" align="right">IMPORTE</td>
    	      <td width="105" class="cabecera" align="center">TIPO FACTURA</td>
    	      <td width="80" class="cabecera" align="center">FECHA VENCIMIENTO</td>
    	      <td width="50" class="cabecera" align="center">DIAS ATRAZO</td>
    	      <td width="90" class="cabecera" align="center">CONTRARECIBO</td>
    	      <td width="70" class="cabecera" align="center">PROX DIA PAGO</td>
      </tr>
    	    <?
				$s = "SELECT factura,date_format(fechaemision,'%d/%m/%Y') fechaemision,nombre,importe,tipofactura, 
				date_format(fechavencimiento,'%d/%m/%Y') fechavencimiento,diasatraso,contrarecibo,diapago 
				FROM cobranza30dias_tmp	WHERE idusuario = $_SESSION[IDUSUARIO]
				".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
				$r = mysql_query($s,$l) or die($s);
				
				if(mysql_num_rows($r)>0){
					$total = mysql_num_rows($r);
					while($f = mysql_fetch_object($r)){
			?>
				<tr>
				  <td align="center"><?=$f->factura?></td>
				  <td align="center"><?=$f->fechaemision?></td>
				  <td align="left"><?=$f->nombre?></td>
				  <td align="right"><?='$ '.number_format($f->importe,2)?></td>
				  <td align="center"><?=$f->tipofactura?></td>
				  <td align="center"><?=$f->fechavencimiento?></td>
				  <td align="center"><?=$f->diasatraso?></td>
				  <td align="center"><?=$f->contrarecibo?></td>
				  <td align="center"><?=$f->diapago?></td>
			  </tr>
			<?
				}}
			?>
    </table>