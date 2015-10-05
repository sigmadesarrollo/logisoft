<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

	<?
		if(isset($_POST['o'])){
			require_once("../Conectar.php");
			$link=Conectarse('webpmm');
		}
	?>
<body>	
	<form action="" name="form1">
    	<input type="submit" name="o" />
    </form>
</body>
</html>