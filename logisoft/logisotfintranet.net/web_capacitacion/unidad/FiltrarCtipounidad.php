<?
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	
if (isset($_REQUEST['cadena'])) {
   $_REQUEST['cadena'];
    $query="SELECT codigo,descripcion FROM catalogotipounidad WHERE (codigo like '$cadena%' and descripcion like '$_GET[descripcion]%') ";
	
}else if($_GET['descripcion']){
    $query="SELECT codigo,descripcion FROM catalogotipounidad WHERE (descripcion like 		    '$descripcion%')";
}else $query="select * from catalogotipounidad";
 

  	  
      $consulta=mysql_query($query,$link);
?>

<div id="div1" style="width:100%; height:300px; overflow: scroll;">
      <table width="100%" border="0" align="center">
        <?		
		while($row=@mysql_fetch_array($consulta)){
		?>
        <tr>
          <td width="10%" class="Tablas"><a href="JavaScript:parent.VentanaModal.cerrar();" onclick="window.parent.obtener('<?= $row['codigo'];?>','<?= $row['descripcion'];?>')";>
            <?= $row['codigo'];?></a></td>
          <td width="79%" class="Tablas"><?= $row['descripcion']; ?></td>
          <td width="19px"></td>
        </tr>
        <?  }  ?>
      </table>
    </div>

