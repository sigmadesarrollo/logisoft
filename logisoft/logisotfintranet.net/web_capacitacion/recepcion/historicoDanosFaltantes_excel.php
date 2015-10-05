<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=danosFaltante_Excel.xls");
	
	require_once("../ConectarSolo.php");
	$l = Conectarse('webpmm');
	
	function cambiaf_a_normal($fecha){ //Convierte fecha de mysql a normal
    	ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha); 
	    $lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1]; 
	    return $lafecha; 
	} 
	function cambiaf_a_mysql($fecha){//Convierte fecha de normal a mysql 
    	ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
	    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
    	return $lafecha; 
	} 
	
	$s = "select descripcion
	from catalogosucursal where id = '$_GET[sucursal]'";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$sucusalNombre = ($f->descripcion=="")?"TODAS":$f->descripcion;
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
	}
</style>
	<table width="1604" border="0" cellpadding="1" cellspacing="0">
    	<tr>
        	<td colspan="11" align="center" class="titulo">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</td>
        </tr>
    	<tr>
    	  <td width="124">REPORTE</td>
    	  <td colspan="10">DA&Ntilde;OS Y FALTANTES</td>
   	  </tr>
    	<tr>
    	  <td>SUCURSAL</td>
    	  <td colspan="10" align="left"><?=$sucusalNombre?></td>
   	  </tr>
    	<tr>
    	  <td height="9px"></td>
    	  <td width="69">&nbsp;</td>
    	  <td width="101"></td>
    	  <td width="166"></td>
    	  <td width="140"></td>
    	  <td width="379"></td>
    	  <td width="80"></td>
    	  <td colspan="4"></td>
      </tr>
      
    	<tr>
    	      <td width="124" class="cabecera" align="center">SE GENERO EN</td>
    	      <td width="69" class="cabecera" align="center" >FOLIO QUEJA</td>
    	      <td width="101" class="cabecera" align="center" >TIPO</td>
    	      <td width="166" class="cabecera" align="center" >No GUIA</td>
    	      <td width="140" class="cabecera" align="center">ESTADO GUIA</td>
    	      <td width="379" class="cabecera" align="left">DESTINATARIO</td>
    	      <td width="80" class="cabecera" align="left">DESTINO</td>
    	      <td width="83" class="cabecera" align="left" >ORIGEN</td>
    	      <td width="108" class="cabecera" align="center" >FECHA RECEPCION</td>
    	      <td width="108" class="cabecera" align="center" >FOLIO RECEPCION</td>
    	      <td width="222" class="cabecera" align="left" >COMENTARIOS</td>
      </tr>
    	    <?
					$s = "SELECT * FROM reportedanosfaltante_detallado 
					WHERE ".(($_GET[sucursal]!="todas")? " sucursal=".$_GET[sucursal]." AND " : "")." 
					fecharecepcion BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";
					$r = mysql_query($s,$l) or die($s);
					$totales = 0;
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_object($r)){
						$totales++;
					?>
    	    <tr>
    	      <td align="center"><?=$f->segenero?></td>
    	      <td align="center"><?=$f->folioqueja?></td>
    	      <td align="center"><?=$f->tipo?></td>
    	      <td align="center"><?=$f->guia?></td>
    	      <td align="center"><?=$f->estado?></td>
    	      <td align="left"><?=$f->destinatario?></td>
    	      <td align="left"><?=$f->destino?></td>
    	      <td align="left"><?=$f->origen?></td>
    	      <td align="center"><?=$f->fecharecepcion?></td>
    	      <td align="center"><?=$f->recepcion?></td>
    	      <td align="left"><?=$f->comentarios?></td>
          </tr>
    	    <?
						}
				  ?>
          <tr>
    	      <td colspan="2" align="center" class="cabecera">&nbsp;</td>
    	      <td align="center" class="cabecera">TOTALES</td>
    	      <td align="center" class="cabecera"><?=$totales?></td>
    	      <td colspan="7" class="cabecera" align="right">&nbsp;</td>
   	      </tr>
</table>
    	    