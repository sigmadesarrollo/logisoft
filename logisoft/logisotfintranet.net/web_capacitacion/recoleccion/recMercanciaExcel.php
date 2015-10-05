<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=recMercanciaExcel.xls");
	
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
        	<td colspan="11" align="center" class="titulo">PAQUETERIA Y MENSAJERIA</td>
        </tr>
    	<tr>
    	  <td>REPORTE:</td>
    	  <td colspan="10">RECOLECCION DE MERCANCIA</td>
   	  </tr>
    	<tr>
    	  <td height="9px" colspan="11"></td>
      </tr>
    	<tr>
    	      <td width="55" class="cabecera" align="center">FOLIO</td>
    	      <td width="250" class="cabecera" align="center">CLIENTE</td>
              <td width="250" class="cabecera" align="center">DIRECCION</td>
          	  <td width="77" class="cabecera" align="center">TRANSMITIDA</td>
    	      <td width="50" class="cabecera" align="center">REALIZO</td>
    	      <td width="50" class="cabecera" align="center">UNIDAD</td>
    	      <td width="65" class="cabecera" align="center">FECHA</td>
    	      <td width="80" class="cabecera" align="center">TELEFONO</td>
    	      <td width="190" class="cabecera" align="center">FOLIO RECOLECCION/EMPRESARIAL</td>
    	      <td width="55" class="cabecera" align="center">MOTIVOS</td>
    	      <td width="90" class="cabecera" align="center">GUIA</td>
      </tr>
    	    <?
				$s = "SELECT r.folio, r.sucursal, r.estado, r.horario, CONCAT(c.nombre,' ',c.paterno,' ',c.materno) AS cliente,
				CONCAT(r.calle,' #',r.numero,' ',r.colonia) AS direccion, r.telefono,
				DATE_FORMAT(r.fecharecoleccion,'%d/%m/%Y') AS fecha, r.unidad,
				DATE_FORMAT(r.fecharegistro,'%d/%m/%Y') AS fecharegistro,
				r.transmitida, r.realizo
				FROM recoleccion r
				INNER JOIN catalogocliente c ON r.cliente = c.id
				WHERE (r.fecharegistro ='".cambiaf_a_mysql($_GET[fecha])."' AND r.sucursal=".$_GET[sucursal]." ".(($_GET['cliente'] !="")? " 
				AND r.cliente =".$_GET['cliente']."" : "")."".(($_GET['folio'] !="")? " AND r.folio ='$_GET[folio]'" : "").") 
				OR (r.fecharegistro ='".cambiaf_a_mysql($_GET[fecha])."' AND r.estado<>'REALIZADO' AND r.estado<>'CANCELADO' AND r.sucursal=".$_GET[sucursal]."
				".(($_GET['cliente'] !="")? " AND r.cliente =".$_GET['cliente']."" : "")."".(($_GET['folio'] !="")? " AND r.folio ='$_GET[folio]'" : "").")";
				$r = mysql_query($s,$l) or die($s);
			
		 		while($f = mysql_fetch_object($r)){
					$f->cliente = cambio_texto($f->cliente);
					$f->direccion = cambio_texto($f->direccion);
					$f->colorcan = "";
					$f->colorrep = "";
					
					$sc = mysql_query("SELECT r.motivo, m.descripcion AS desmotivo, m.color FROM recoleccionmotivocancelacion r
					INNER JOIN catalogomotivos m ON r.motivo = m.id
					WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]." AND r.fecharegistro='".cambiaf_a_mysql($_GET[fecha])."'",$l);
					$can = mysql_fetch_object($sc);
					$f->colorcan = $can->color;
					$f->motivos = "";
					if($f->estado=="CANCELADO"){
						$f->motivos = cambio_texto($can->desmotivo);
					}
					
					$sr = mysql_query("SELECT r.motivo, m.descripcion AS desmotivoreprogramar,m.color FROM recoleccionmotivoreprogramacion r
					INNER JOIN recoleccion rc ON r.recoleccion = rc.folio AND rc.sucursal
					INNER JOIN catalogomotivos m ON r.motivo = m.id
					WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]." AND rc.fecharegistro >= '".cambiaf_a_mysql($_GET[fecha])."'",$l);					
					$rep = mysql_fetch_object($sr);
					$f->colorrep = $rep->color;
					if($rep->desmotivoreprogramar!=""){
						$f->motivos = cambio_texto($rep->desmotivoreprogramar);
					}if($rep->desmotivoreprogramar=="" && $f->estado!="CANCELADO"){
						$f->motivos = cambio_texto($f->motivos);
					}$recolecciones = ""; $empresariales = ""; $guiasempresariales = "";
					
					$em =  mysql_query("SELECT gv.id AS guia FROM guiasventanilla gv
						INNER JOIN recolecciondetallefoliorecoleccion r ON gv.recoleccion = r.foliosrecolecciones
						WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_GET[sucursal]."",$l) or die($em);
						if(mysql_num_rows($em)>0){
							while($rowd=mysql_fetch_array($em)){
								$guiasempresariales .=$rowd[0].",";
							}$guiasempresariales = substr($guiasempresariales,0,strlen($guiasempresariales)-1);
						}
					
					if($f->estado=="REALIZADO"){
						$sr = mysql_query("SELECT foliosrecolecciones FROM recolecciondetallefoliorecoleccion 
						WHERE recoleccion='".$f->folio."' AND sucursal=".$_GET['sucursal']."",$l) or die($sr);					
						if(mysql_num_rows($sr)>0){
							while($row=mysql_fetch_array($sr)){
								$recolecciones .=$row[0].",";
							}$recolecciones = substr($recolecciones,0,strlen($recolecciones)-1);
						}
							
						$se = mysql_query("SELECT foliosempresariales FROM recolecciondetallefolioempresariales 
						WHERE recoleccion='".$f->folio."' AND sucursal=".$_GET['sucursal']."",$l) or die($se);
						if(mysql_num_rows($se)>0){					
							while($rowd=mysql_fetch_array($se)){
								$empresariales .=$rowd[0].",";
							}
							$empresariales = substr($empresariales,0,strlen($empresariales)-1);
						}
					}
					
				if((!empty($recolecciones) || $recolecciones!=" ") && (!empty($empresariales) || $recolecciones!=" ")){							
					$f->folios = $recolecciones."--".$empresariales;
				}
				
				if($f->folios == "--"){
					$f->folios = "";
				}
					$f->guia = cambio_texto($guiasempresariales);	
					$f->folios = cambio_texto($f->folios);
			?>
				<tr>
				  <td align="center"><?=$f->folio?></td>
				  <td align="left"><?=$f->cliente?></td>
				  <td align="left"><?=$f->direccion?></td>
				  <td align="center"><?=$f->transmitida?></td>
				  <td align="center"><?=$f->realizo?></td>
				  <td align="center"><?=$f->unidad?></td>
				  <td align="center"><?=$f->fecha?></td>
				  <td align="center"><?=$f->telefono?></td>
				  <td align="right"><?=$f->folios?></td>
				  <td align="right"><?=$f->motivos?></td>
				  <td align="right"><?=$f->guia?></td>
			  </tr>
		<? }
			?>
    </table>