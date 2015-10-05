<?  session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	include('../../Conectar.php');
	$link=Conectarse('webpmm');
	$get = mysql_query('select count(*) from catalogomunicipio');
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>

<script src="select.js"></script>
<script>
		function enviarMunicipio(municipio,e){
			tecla=(document.all) ? e.keyCode : e.which;
			if(tecla!=13){
				 return;
			}else{
				FiltroMunicipio_CatMunicipio(municipio,'1');
			}
		}

</script>
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />
<form name="buscar" >
  <table width="500" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">ID</td>
      <td class="FondoTabla">Nombre</td>
      <td class="FondoTabla">&nbsp;</td>
    </tr>

    <tr>

      <td width="7%" class="FondoTabla">&nbsp;</td>

      <td width="85%" colspan="2" class="FondoTabla"><input class="Tablas" type="text" name="buscar" id="buscar" onkeydown="enviarMunicipio(this.value,event)" style="text-transform:uppercase;"/></td>
    </tr>

    <tr>

      <td colspan="3" height="300px" valign="top" ><div id="txtHint" style="width:100%; height:auto;"><table width="100%" border="0" align="center" class="Tablas">

          <?	

		$get = mysql_query('SELECT cm.id, cm.descripcion AS municipio, ce.descripcion AS estado FROM catalogomunicipio cm
		INNER JOIN catalogoestado ce ON cm.estado = ce.id limit '.$st.','.$pp,$link);		

		while($row=@mysql_fetch_array($get)){

		

	?> 

          <tr >

       <td width="10%" class="Tablas" >

<span onclick="window.parent.obtener('<?= $row['id'];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row['id'];?></span></td>

            <td width="79%" class="Tablas"><?=htmlentities($row['descripcion']); ?></td>

            <td width="19px"></td>
          </tr>

          <? } ?>

      </table></div></td>
    </tr>

    <tr>

      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarmunicipio.php?st='); ?></font></td>
    </tr>
  </table> 

</form>

<? //} ?>