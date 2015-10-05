<?php
	function conectarse(){
		if (!($l = @mysql_connect('mysql.hostinger.mx',"u356875594_pmm","gqx64p9n"))){
			echo "Error conectando a la base de datos.";
			exit();
		}
		if (!mysql_select_db("pmm",$l)){
			echo "Error seleccionando la base de datos.";
			exit();
		}
		return $l;
	}
	
	function obtenerFolio($tabla,$idSucursal,$prefijo){
		$link = conectarse();
		$s = "SELECT IFNULL(MAX(Consecutivo),0) + 1 AS Cons FROM ".$tabla." WHERE IdSucursal = '".$idSucursal."'";
		$r = mysql_query($s,$link) or die(mysql_error($link)." ".$s);
		$f = mysql_fetch_object($r);
		$array = array("Folio"=>$prefijo."-".sprintf("%09d",$f->Cons),"Consecutivo"=>$f->Cons);
		return $array;
	}
	
	/*
	
	SELECT COLUMN_NAME
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA  LIKE 'pmm'
    AND TABLE_NAME = 'mantenimiento'
	
	*/
?>