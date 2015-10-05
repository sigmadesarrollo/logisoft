<?
	require_once("web_capacitacion/Conectar.php");
	$l = conectarse("webpmm");
	
	
	mysql_query("BEGIN",$l) or die(mysql_error($l)."M");
	
	mysql_query("insert into aa_aa1 set valor = 'a'",$l) or die(mysql_error($l)."x");
	mysql_query("call prueba_proc()",$l) or die(mysql_error($l)."pp");
	mysql_query("insert into aa_aa2 set valor = 'b'",$l) or die(mysql_error($l)."y");
	mysql_query("insert into aa_aa3 set valor = 'c'",$l) or die(mysql_error($l)."z");
	
	//mysql_query("ROLLBACK",$l);
	mysql_query("COMMIT",$l) or die(mysql_error($l)."N");
	
?>