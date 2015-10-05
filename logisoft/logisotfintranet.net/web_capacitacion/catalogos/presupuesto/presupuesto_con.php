<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$row = ObtenerFolio('catalogopresupuesto','webpmm');
		echo $row[0].",".date('d/m/Y');
	
	}else if($_GET[accion]==2){
		$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion) AS descripcion FROM catalogosucursal cs
		WHERE id = ".$_GET[sucursal]."";
		$r = mysql_query($s,$l) or die($s); 
		$f = mysql_fetch_object($r);
		$f->descripcion = cambio_texto($f->descripcion);
		
		echo $f->descripcion;
		
	}else if($_GET[accion]==3){
		$s = "SELECT folio,DATE_FORMAT(fechapresupuesto,'%d/%m/%Y') AS fechapresupuesto,
		sucursal,enero,r_enero,dias_enero,febrero,r_febrero,dias_febrero,
		marzo,r_marzo,dias_marzo,abril,r_abril,dias_abril,mayo,r_mayo,dias_mayo,
		junio,r_junio,dias_junio,julio,r_julio,dias_julio,agosto,r_agosto,
		dias_agosto,septiembre,r_septiembre,dias_septiembre,octubre,r_octubre,
		dias_octubre,noviembre,r_noviembre,dias_noviembre,diciembre,r_diciembre,
		dias_diciembre FROM catalogopresupuesto
		WHERE folio = ".$_GET[folio]."";
		$r = mysql_query($s,$l) or die($s); 
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			
			$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS descripcion FROM catalogosucursal
			WHERE id = ".$f->sucursal."";
			$r = mysql_query($s,$l) or die($s);
			$ff = mysql_fetch_object($r);
			$f->dessucursal = cambio_texto($ff->descripcion);
			
			$principal = str_replace('null','""',json_encode($f));
			
			echo "({principal:$principal})";
		}else{
			echo "noencontro";
		}
	}else if($_GET[accion]==4){
		if($_GET[tipo]=="grabar"){
			$s = "INSERT INTO catalogopresupuesto SET		
			fechapresupuesto = CURDATE(),
			sucursal	= ".$_GET[sucursal_hidden].",
			enero		= ".((!empty($_GET[enero]))? $_GET[enero] : 0).",			
			febrero		= ".((!empty($_GET[febrero]))? $_GET[febrero] : 0).",
			marzo		= ".((!empty($_GET[marzo]))? $_GET[marzo] : 0).",
			abril		= ".((!empty($_GET[abril]))? $_GET[abril] : 0).",
			mayo		= ".((!empty($_GET[mayo]))? $_GET[mayo] : 0).",
			junio		= ".((!empty($_GET[junio]))? $_GET[junio] : 0).",
			julio		= ".((!empty($_GET[julio]))? $_GET[julio] : 0).",
			agosto		= ".((!empty($_GET[agosto]))? $_GET[agosto] : 0).",
			septiembre	= ".((!empty($_GET[septiembre]))? $_GET[septiembre] : 0).",
			octubre		= ".((!empty($_GET[octubre]))? $_GET[octubre] : 0).",
			noviembre	= ".((!empty($_GET[noviembre]))? $_GET[noviembre] : 0).",
			diciembre	= ".((!empty($_GET[diciembre]))? $_GET[diciembre] : 0).",
			dias_enero	= ".((!empty($_GET[diasenero]))? $_GET[diasenero] : 0).",
			dias_febrero	= ".((!empty($_GET[diasfebrero]))? $_GET[diasfebrero] : 0).",
			dias_marzo	= ".((!empty($_GET[diasmarzo]))? $_GET[diasmarzo] : 0).",
			dias_abril	= ".((!empty($_GET[diasabril]))? $_GET[diasabril] : 0).",
			dias_mayo	= ".((!empty($_GET[diasmayo]))? $_GET[diasmayo] : 0).",
			dias_junio	= ".((!empty($_GET[diasjunio]))? $_GET[diasjunio] : 0).",
			dias_julio	= ".((!empty($_GET[diasjulio]))? $_GET[diasjulio] : 0).",
			dias_agosto	= ".((!empty($_GET[diasagosto]))? $_GET[diasagosto] : 0).",
			dias_septiembre = ".((!empty($_GET[diasseptiembre]))? $_GET[diasseptiembre] : 0).",
			dias_octubre = ".((!empty($_GET[diasoctubre]))? $_GET[diasoctubre] : 0).",
			dias_noviembre = ".((!empty($_GET[diasnoviembre]))? $_GET[diasnoviembre] : 0).",
			dias_diciembre = ".((!empty($_GET[diasdiciembre]))? $_GET[diasdiciembre] : 0).",			
			idusuario	= ".$_SESSION[IDUSUARIO].",
			fecha		= CURRENT_TIMESTAMP";
			mysql_query($s,$l) or die($s);
			$folio = mysql_insert_id();
			
			echo "ok,".$_GET[tipo].",".$folio;
			
		}else if($_GET[tipo]=="modificar"){
			$s = "UPDATE catalogopresupuesto SET
			sucursal	= ".$_GET[sucursal_hidden].",
			enero		= ".((!empty($_GET[enero]))? $_GET[enero] : 0).",
			febrero		= ".((!empty($_GET[febrero]))? $_GET[febrero] : 0).",
			marzo		= ".((!empty($_GET[marzo]))? $_GET[marzo] : 0).",
			abril		= ".((!empty($_GET[abril]))? $_GET[abril] : 0).",
			mayo		= ".((!empty($_GET[mayo]))? $_GET[mayo] : 0).",
			junio		= ".((!empty($_GET[junio]))? $_GET[junio] : 0).",
			julio		= ".((!empty($_GET[julio]))? $_GET[julio] : 0).",
			agosto		= ".((!empty($_GET[agosto]))? $_GET[agosto] : 0).",
			septiembre	= ".((!empty($_GET[septiembre]))? $_GET[septiembre] : 0).",
			octubre		= ".((!empty($_GET[octubre]))? $_GET[octubre] : 0).",
			noviembre	= ".((!empty($_GET[noviembre]))? $_GET[noviembre] : 0).",
			diciembre	= ".((!empty($_GET[diciembre]))? $_GET[diciembre] : 0).",
			dias_enero	= ".((!empty($_GET[diasenero]))? $_GET[diasenero] : 0).",
			dias_febrero	= ".((!empty($_GET[diasfebrero]))? $_GET[diasfebrero] : 0).",
			dias_marzo	= ".((!empty($_GET[diasmarzo]))? $_GET[diasmarzo] : 0).",
			dias_abril	= ".((!empty($_GET[diasabril]))? $_GET[diasabril] : 0).",
			dias_mayo	= ".((!empty($_GET[diasmayo]))? $_GET[diasmayo] : 0).",
			dias_junio	= ".((!empty($_GET[diasjunio]))? $_GET[diasjunio] : 0).",
			dias_julio	= ".((!empty($_GET[diasjulio]))? $_GET[diasjulio] : 0).",
			dias_agosto	= ".((!empty($_GET[diasagosto]))? $_GET[diasagosto] : 0).",
			dias_septiembre = ".((!empty($_GET[diasseptiembre]))? $_GET[diasseptiembre] : 0).",
			dias_octubre = ".((!empty($_GET[diasoctubre]))? $_GET[diasoctubre] : 0).",
			dias_noviembre = ".((!empty($_GET[diasnoviembre]))? $_GET[diasnoviembre] : 0).",
			dias_diciembre = ".((!empty($_GET[diasdiciembre]))? $_GET[diasdiciembre] : 0).",
			idusuario	= ".$_SESSION[IDUSUARIO].",
			fecha		= CURRENT_TIMESTAMP
			WHERE folio	= ".$_GET[folio]."";
			mysql_query($s,$l) or die($s);
			
			echo "ok,".$_GET[tipo];
		}		
	}
?>