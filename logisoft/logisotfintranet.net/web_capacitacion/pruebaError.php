<?
	require_once("Conectar.php");
	require_once("fn-error.php");
	$l = Conectarse("webpmm");
	
	if($_GET["xxx"]){
		mysql_query("xx",$l) or postError("xx");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
	<form action="" method="get">
    	<input type='text' name="xxx" value="xxx" />
        <input type="submit" value="ooo" />
    </form>
</body>
</html>