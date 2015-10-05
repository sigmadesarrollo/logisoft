<?
	require_once("Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "SELECT * FROM catalogosucursal where id not in (1,45,46,47)";
	$r = mysql_query($s,$l) or die($s);
	while($f = mysql_fetch_object($r)){
		$s = "drop table if exists `zzz_suc_".$f->prefijo."`";
		mysql_query($s,$l) or die($s);
		
		$s = "CREATE TABLE `zzz_suc_".$f->prefijo."` (
		  `id` INT(11) NOT NULL AUTO_INCREMENT,
		  `valor` VARCHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT '',
		  PRIMARY KEY (`id`)
		) ENGINE=MYISAM 
		AUTO_INCREMENT=20000
		DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		mysql_query($s,$l) or die(mysql_error($l)."-----".$s);
		
	}
?>