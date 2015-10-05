<? session_start();
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	$tipo=$_GET['tipo'];
	switch ($tipo) {
		case "descripcion":
			$get=@mysql_query('select count(*) from catalogodescripcion');
			break;
	}
	$total =@mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="select.js"></script>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script>
function Limpiar(){
	var tipo='<?=$tipo ?>';
	if(tipo=="descripcion"){
		window.opener.document.getElementById('abierto').value="";
	}
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body onUnload="Limpiar();">
<form name="buscar" >
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="7%" class="FondoTabla">ID</td>
      <td width="85%" class="FondoTabla">Nombre</td>
    </tr>
    <tr>
      <td colspan="2"><div id="txtHint" style="width:100%; height:300px; overflow: scroll;"><table width="100%" border="0" align="center">
          <?
		  switch ($tipo) {				
			case "descripcion":
		$get =@mysql_query('select * from catalogodescripcion limit '.$st.','.$pp,$link);		
				break;			
		}	
			
			while($row=@mysql_fetch_array($get)){
			?>
				<tr >
       <td width="10%" class="Tablas" >
<a href="javascript:close();" onClick="window.opener.obtener('<?=$row['id'];?>','<?=$row['descripcion'];?>'); " style="cursor: pointer; color:#0000FF"><?= $row['id'];?></a></td>
            <td width="79%" class="Tablas"><?=htmlentities(strtoupper($row['descripcion'])); ?></td>
            <td width="19px"></td>
          </tr>	
		<?	}
		
		?>

      </table></div></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscar.php?tipo='.$tipo.'&st='); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>
<? //} ?>