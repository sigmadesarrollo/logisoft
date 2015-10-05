
<?
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	$unidad=$_GET['unidad'];
	$descripcion=$_GET['descripcion'];

if($unidad!=""){
		$sql="SELECT CT.codigo,CT.descripcion FROM catalogocargadescarga CCD INNER JOIN catalogotipounidad CT 
ON CCD.unidad=CT.codigo WHERE CCD.unidad='$unidad'";
		$rest=mysql_query($sql,$link);
		if (!mysql_num_rows($rest)){
			$sql="SELECT * FROM catalogotipounidad WHERE codigo='$unidad'";
			$rest=mysql_query($sql,$link);
			$row = mysql_fetch_array($rest);
			$unidad=$row[codigo];
			$descripcion=htmlentities($row[descripcion]);
		
		}else{
		 $sqlb="SELECT CCD.unidad,CT.descripcion, CCD.tcarga, CCD.tdescarga FROM 	catalogocargadescarga CCD INNER JOIN catalogotipounidad CT ON CCD.unidad=CT.codigo WHERE CCD.unidad='$unidad'";
	$result=mysql_query($sqlb,$link);
	$roww= mysql_fetch_array($result); 
			$unidad=htmlentities($unidad);
			$descripcion=htmlentities($roww[descripcion]);
			$ticarga=htmlentities($roww[tcarga]);
			$tidescarga=htmlentities($roww[tdescarga]);					
		}
}
			
	

?>
<table width="249" border="0">
  <tr>
    <td width="65"><strong class="Tablas">Descripcion:</strong></td>
    <td colspan="3"><label>
      <input name="descripcion" type="text" id="descripcion" tabindex="2" value="<?=$descripcion ?>" size="43" readonly="readonly" style="background-color: #FFFF99; font-size:9px; font:tahoma" />
    </label></td>
  </tr>
  <tr>
    <td><strong class="Tablas">T. Carga: </strong></td>
    <td width="92"><input name="ticarga" type="text" id="ticarga" onkeypress="return Numeros(event)"  onkeydown="return tabular(event,this)" value="<?=$ticarga ?>" size="10" tabindex="3" style="font:tahoma; font-size:9px"/></td>
    <td width="94"><strong class="Tablas">T. Descarga:</strong></td>
    <td width="68"><input name="tidescarga" type="text" id="tidescarga" onkeypress="return Numeros(event)" onkeydown="return tabular(event,this)" value="<?=$tidescarga ?>" size="10" tabindex="4"/ style="font:tahoma; font-size:9px" /></td>
  </tr>
</table>
