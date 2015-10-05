<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	ini_set('post_max_size','512M');
	ini_set('upload_max_filesize','512M');
	ini_set('memory_limit','500M');
	ini_set('max_execution_time',600);
	ini_set('limit',-1);
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	$_GET[sucorigen] = $_SESSION[IDSUCURSAL];
	
	//solicitar guias
	if($_GET[accion] == 2){
		$arrefechainicio = split("/",$_GET[fechainicio]);
		$arrefechafin = split("/",$_GET[fechafin]);
		$nfecin = $arrefechainicio[2]."-".$arrefechainicio[1]."-".$arrefechainicio[0];
		$nfecfi = $arrefechafin[2]."-".$arrefechafin[1]."-".$arrefechafin[0];
			
		$s = "SELECT gv.id,'NORMAL' as tipoguia,gv.evaluacion,date_format(gv.fecha, '%d/%m/%Y') fecha,gv.fechaentrega,gv.factura,gv.estado,gv.tipoflete,
			gv.ocurre,gv.idsucursalorigen,gv.idsucursalorigen,gv.idsucursaldestino,gv.entregaocurre,gv.entregaead,gv.restrinccion,gv.totalpaquetes,gv.totalpeso,
			gv.totalvolumen,gv.emplaye,gv.bolsaempaque,gv.totalbolsaempaque,gv.avisocelular,gv.celular,gv.valordeclarado,gv.acuserecibo,gv.cod,gv.recoleccion, 
			gv.observaciones,gv.tflete,gv.tdescuento,gv.ttotaldescuento,gv.tcostoead,gv.trecoleccion,gv.tseguro,gv.totros,gv.texcedente,gv.tcombustible,
			gv.subtotal,gv.tiva,gv.ivaretenido,gv.total,gv.efectivo,gv.cheque,gv.banco,gv.ncheque,gv.tarjeta,gv.trasferencia,gv.usuario,gv.fecha_registro,
			gv.hora_registro,date_format(current_date, '%d/%m/%Y') fechaactual,'G' as tipo
			FROM guiasventanilla as gv
			inner join pagoguias as pg on gv.id = pg.guia
			where isnull(factura) AND gv.condicionpago = 0 AND (gv.estado <> 'CANCELADA' and gv.estado <> 'CANCELADO')
			AND pg.sucursalacobrar=$_GET[sucorigen] and pg.pagado = 'S' and gv.fecha between '$nfecin' AND '$nfecfi'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$enc = mysql_num_rows($r);
			$arre = array();
			while($f = mysql_fetch_object($r)){
				$f->seleccion = 'S';
				
				$arre[] = $f;
			}
			$grid1 = json_encode($arre);
			echo '({
				   "grid1":'.$grid1.'
			})';
		}else{
			echo '({
				   "grid1":[]
			})';
		}
	}
	
	echo $xml;
	
?>
