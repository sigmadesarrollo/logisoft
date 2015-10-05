<?

	require_once('../../Conectar.php');

	$l = Conectarse('webpmm');

	

	if($_GET[accion] == 1){

		$s = "SELECT cc.foliocredito, cc.saldo, sc.montoautorizado AS limitecredito,
		cc.diascredito, cc.diapago, cc.diarevision, cc.activado,
		CONCAT_WS(' ',cc.nombre, cc.paterno, cc.materno) AS cliente,
		cc.clasificacioncliente, sc.estado
		FROM catalogocliente cc
		inner join solicitudcredito sc on cc.id = sc.cliente
		WHERE cc.id=".$_GET[cliente]." AND (sc.estado='ACTIVADO' or sc.estado='BLOQUEADO')";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			if($_GET[tipo]==1){
					while($f = mysql_fetch_object($r)){
						$s = "SELECT $f->limitecredito - SUM(adquirido) disponible, SUM(ventames) ventames, SUM(saldo) saldo FROM (
								(SELECT IFNULL(SUM(IF(pagado='N', total,0)),0) AS adquirido, 
								IFNULL(SUM(IF(MONTH(CURRENT_DATE)=MONTH(fechacreo) AND 
								YEAR(CURRENT_DATE)=YEAR(fechacreo) ,1, 0)),0) AS ventames, 
								IFNULL(SUM(IF(pagado='N', total,0)),0) AS saldo
								FROM pagoguias 
								WHERE cliente = '".$_GET['cliente']."' AND tipo <> 'EMPRESARIAL'
								AND credito = 'SI')
								UNION
								(SELECT IFNULL(SUM(IF(pg.pagado='N', pg.total,0)),0) AS adquirido, 
								IFNULL(SUM(IF(MONTH(CURRENT_DATE)=MONTH(pg.fechacreo) AND 
								YEAR(CURRENT_DATE)=YEAR(pg.fechacreo) ,1, 0)),0) AS ventames, 
								IFNULL(SUM(IF(pg.pagado='N', pg.total,0)),0) AS saldo
								FROM pagoguias pg
								INNER JOIN guiasempresariales ge ON pg.guia = ge.id
								WHERE pg.cliente = '".$_GET['cliente']."' AND pg.tipo = 'EMPRESARIAL' AND ge.tipoguia <> 'PREPAGADA' 
								AND credito = 'SI')
						) t1";
						$rx = mysql_query($s,$l) or die($s);
						$fx = mysql_fetch_object($rx);
						$f->nombre = cambio_texto($f->cliente);
						$f->clasificacioncliente = (($f->clasificacioncliente=="SELECCIONA" || $f->clasificacioncliente=="selecciona")?"SELECCIONAR":$f->clasificacioncliente);					
	
						$s = "SELECT FORMAT(IFNULL(SUM(total),0),2) as ventames FROM pagoguias WHERE cliente = ".$_GET[cliente]." AND MONTH(fechacreo) = MONTH(CURDATE())
						AND fechacancelacion IS NULL";
						$rr = mysql_query($s,$l) or die($s);
						$fc = mysql_fetch_object($rr);
						
						$f->disponible = $fx->disponible;
						$f->ventames = $fc->ventames;
						$f->saldo = $fx->saldo;					
						$f->estado = cambio_texto($f->estado);
						$registros[] = $f;
	
					}
	
					echo str_replace('null','""',json_encode($registros));
	
				
	
			}else if($_GET[tipo]==2){
	
					while($f = mysql_fetch_object($r)){
	
						$f->nombre = cambio_texto($f->nombre);					
						$f->estado = cambio_texto($f->estado);
						$registros[] = $f;
	
					}
	
					echo str_replace('null','""',json_encode($registros));
	
			}

		}else{

			echo "no encontro";

		}

	}else if($_GET[accion] == 2){//OBTENER PROSPECTO

		$lqs=mysql_query("SELECT IFNULL(MAX(id),0)+1 as id FROM catalogoprospecto",$l);

		$rest=mysql_fetch_array($lqs);

		echo $rest[0];

		

	}else if($_GET[accion] == 3){
		
		
		$s = "SELECT id, CONCAT_WS(' ',nombre,paterno,materno) as cliente, rfc FROM catalogocliente
		WHERE rfc=UCASE('".trim($_GET[rfc])."') and id <> '$_GET[codigo]'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$f->rfc = cambio_texto($f->rfc);
			$f->cliente = cambio_texto($f->cliente);
			echo str_replace('null','""',json_encode($f));
		}else{
			echo "no encontro";
		}		

	}else if($_GET[accion] == 4){

		$s = "SELECT id, CONCAT_WS(' ',nombre,paterno,materno) as cliente, rfc FROM catalogoprospecto

		WHERE rfc=UCASE('".trim($_GET[rfc])."')";

		$r = mysql_query($s,$l) or die($s);

		if(mysql_num_rows($r)>0){

			$f = mysql_fetch_object($r);

			$f->rfc = cambio_texto($f->rfc);

			$f->cliente = cambio_texto($f->cliente);

			echo str_replace('null','""',json_encode($f));

		}else{

			echo "no encontro";

		}

		

	}

?>