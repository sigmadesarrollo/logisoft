<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion,':',cd.id),CONCAT(cd.descripcion,' - ',cs.prefijo,':',cd.id)) AS descripcion,
	cd.sucursal	FROM catalogodestino cd
	INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id
	ORDER BY descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".cambio_texto($f[0])."'".','.$desc;
			$ori= "'".cambio_texto($f[0])."'".','.$ori;
		}
		$desc = "'VARIOS:0',".$desc;		
		$desc = substr($desc, 0, -1);		
		$ori  = substr($ori, 0, -1);			
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<script src="../javascript/moautocomplete.js"></script>
<script>
	var desc = new Array(<?php echo $desc; ?>);
	var ori = new Array(<?php echo $ori; ?>);
</script>
<body>
 <input name="destino" type="text" class="Tablas" id="destino" style="width:130px" value="<?=$_POST[destino] ?>" 
				autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.destino_hidden.value=this.codigo;}" onblur="if(this.value!=''){document.all.destino_hidden.value = this.codigo; if(this.codigo==undefined){document.all.destino_hidden.value ='no'}}" />
                
                <input name="destino_hidden" type="text" id="destino_hidden" value="<?=$_POST[destino_hidden] ?>" />
</body>
</html>