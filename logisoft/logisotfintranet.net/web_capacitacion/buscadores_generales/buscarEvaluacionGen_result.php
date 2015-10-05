<?	session_start(); 
	require_once('../Conectar.php');
	$link = Conectarse("webpmm");
	
	$losands = ($_GET[ands]=="1")?" and e.guiaempresarial<>0 ":" and e.guiaempresarial=0 ";
?>

	  <table width="100%" border="0" align="center" class="Tablas">
          <?
		  
		$get =@mysql_query("SELECT e.folio, DATE_FORMAT(e.fechaevaluacion,'%d/%m/%Y') AS fechaevaluacion, e.estado,
		e.guiaempresarial, e.recoleccion, e.destino, e.sucursaldestino,	e.bolsaempaque, 
		e.cantidadbolsa, e.totalbolsaempaque, e.emplaye, e.totalemplaye, e.sucursal, e.usuario, e.fecha,
		cd.descripcion AS ndestino FROM evaluacionmercancia e
		INNER JOIN catalogodestino cd ON e.destino = cd.id
		WHERE e.folio = ".$_GET[folio]." and e.estado='GUARDADO'
		and e.sucursal = ".$_SESSION[IDSUCURSAL]." ".$losands." ",$link);
		
			while($row=@mysql_fetch_array($get)){
			?>
				<tr >
       <td width="45" class="Tablas" ><span onClick="parent.<?=$_GET[funcion]?>('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[0];?></span></td>
            <td width="124" class="Tablas"><?=$row[1]; ?></td>
            <td width="155" class="Tablas"><?=($_GET[ands]!="1")?$row[4]:$row[3];?></td>
            <td width="156" class="Tablas"><?=htmlentities($row[ndestino]); ?></td>
		  </tr>	
		<?	}  ?>      
		</table>