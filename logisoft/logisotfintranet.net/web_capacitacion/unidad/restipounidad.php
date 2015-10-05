
<?
	include('../Conectar.php');
	$link=Conectarse('pmm');
	$codigo=$_GET['codigo'];
	
	if($codigo!=""){
			$sql="SELECT * FROM catalogotipounidad WHERE codigo='$codigo'";
			$rest=mysql_query($sql,$link);
			$row = mysql_fetch_array($rest);
			$codigo=$codigo;
			$descripcion=htmlentities($row[descripcion]);
	}


?>

<input name="descripcion" type="text"  id="descripcion" onkeypress="return tabular(event,this)" onblur="trim(document.getElementById('descripcion').value,'descripcion');" size="50" value="<?= $descripcion ?>" style="font-size:9px; font:tahoma" />

