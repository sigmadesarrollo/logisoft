<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=estadoCuenta_Excel.xls");
	//header("Pragma: no-cache");
	//header("Expires: 0"); 
	
	require_once("../Conectar.php");
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
	<table width="1176" border="0" cellpadding="1" cellspacing="0">
    	<tr>
        	<td colspan="9" align="center" class="titulo">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</td>
        </tr>
    	<tr>
    	  <td width="129">REPORTE</td>
    	  <td colspan="8">ENTREGAS ESPECIALES EAD</td>
   	  </tr>
    	<tr>
    	  <td></td>
    	  <td colspan="8" align="left"><?=$nsucursal?></td>
   	  </tr>
    	<tr>
    	  <td></td>
    	  <td colspan="8" align="left"><?=date("d/m/Y");?></td>
   	  </tr>
    	<tr>
    	  <td height="9px"></td>
    	  <td width="72">&nbsp;</td>
    	  <td width="76"></td>
    	  <td width="117"></td>
    	  <td width="101"></td>
    	  <td width="56"></td>
    	  <td width="123"></td>
    	  <td colspan="2"></td>
      </tr>
    	<tr>
    	      <td width="129" class="cabecera" align="center">FOLIO</td>
    	      <td width="100" align="left" class="cabecera" >SUCURSAL</td>
			  <td width="100" align="left" class="cabecera" >PERSONA REQUIERE ENTREGA</td>
    	      <td width="123" class="cabecera" align="center" >FECHA ESPECIAL</td>
    	      <td width="145" class="cabecera" align="center" >GUIA</td>
			  <td width="100" align="left" class="cabecera" >ESTADO</td>
			  <td width="100" align="left" class="cabecera" >REGISTRO</td>
    	      <td width="337" colspan="2" align="center" class="cabecera" >OBSERVACIONES</td>
      </tr>
   <?
		$s = "SELECT folio,sucursal,persona,fechaespecial,guia,estadoguia,registro,observaciones FROM (
		SELECT e.folio, DATE_FORMAT(e.fechaead,'%d/%m/%Y') AS fechaespecial,
		e.guia, e.observaciones, cs.prefijo AS sucursal,IF(e.personarequireead<>'',e.personarequireead,IF(e.opcion2=0,
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno),CONCAT_WS(' ',de.nombre,de.paterno,de.materno))) AS persona,
		gv.estado estadoguia, CONCAT_WS(' ',ce.nombre, ce.apellidopaterno, ce.apellidomaterno) registro
		FROM entregasespecialesead e
		INNER JOIN guiasventanilla gv ON gv.id = e.guia
		INNER JOIN catalogoempleado ce ON e.idusuario = ce.id
		INNER JOIN catalogosucursal cs ON e.sucursal = cs.id
		INNER JOIN catalogocliente re ON e.remitente = re.id
		INNER JOIN catalogocliente de ON e.destinatario = de.id
		WHERE e.fechaead BETWEEN '".cambiaf_a_mysql($_GET[inicio])."' AND '".cambiaf_a_mysql($_GET[fin])."'
		".(($_GET[sucursal]!=1)? " AND e.sucursal = $_GET[sucursal]" : "")."
		UNION
		SELECT e.folio, DATE_FORMAT(e.fechaead,'%d/%m/%Y') AS fechaespecial,
		e.guia, e.observaciones, cs.prefijo AS sucursal,IF(e.personarequireead<>'',e.personarequireead,
		IF(e.opcion2=0,CONCAT_WS(' ',re.nombre,re.paterno,re.materno),CONCAT_WS(' ',de.nombre,de.paterno,de.materno))) AS persona,
		ge.estado estadoguia, CONCAT_WS(' ',ce.nombre, ce.apellidopaterno, ce.apellidomaterno) registro
		FROM entregasespecialesead e
		INNER JOIN guiasempresariales ge ON ge.id = e.guia
		INNER JOIN catalogoempleado ce ON e.idusuario = ce.id
		INNER JOIN catalogosucursal cs ON e.sucursal = cs.id
		INNER JOIN catalogocliente re ON e.remitente = re.id
		INNER JOIN catalogocliente de ON e.destinatario = de.id
		WHERE e.fechaead BETWEEN '".cambiaf_a_mysql($_GET[inicio])."' AND '".cambiaf_a_mysql($_GET[fin])."'
		".(($_GET[sucursal]!=1)? " AND e.sucursal = $_GET[sucursal]" : "")."
		) t ";
					$r = mysql_query($s,$l) or die($s);
					$importes=0;
					if(mysql_num_rows($r)>0){
						$total = mysql_num_rows($r);
						while($f = mysql_fetch_object($r)){
							$f->observacion = utf8_encode($f->observacion);
							$f->sucursal = utf8_encode($f->sucursal);
							$f->persona = utf8_encode($f->persona);
					?>
    	    <tr>
    	      <td align="center"><?=$f->folio?></td>
			  <td align="center"><?=$f->sucursal?></td>
    	      <td align="left"><?=$f->persona?></td>
    	      <td align="center"><?=$f->fechaespecial?></td>
    	      <td align="center"><?=$f->guia?></td>
			  <td align="center"><?=$f->estadoguia?></td>
			  <td align="center"><?=$f->registro?></td>
    	      <td colspan="2" align="center"><?=$f->observaciones?></td>
   	      </tr>
					<?
						}
					}
                    ?>
    </table>
    	    