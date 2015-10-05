<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if (isset($_SESSION['gvalidar'])!=100){
echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";
	}else{*/
	require_once('../../Conectar.php');
	$link=Conectarse('webpmm');
	$get = mysql_query('select count(*) from catalogopoblacion');
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<script src="select.js"></script>
<script>
function consultaPoblacion(e,obj){
	tecla=(document.all) ? e.keyCode : e.which;
	if(tecla==13 && obj!=""){
		if(obj.length >= 4){
			PoblacionConsulta('poblacion',obj);
		}else{
			alerta('El Criterio de la busqueda debe ser mayor o igual a 4 caracteres','¡Atención!','colonia');
		}		
	}
}
function foco(){
	document.all.poblacion.focus();
}
</script>
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<form name="buscar" >
  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">ID</td>
      <td class="FondoTabla">Descripción</td>
    </tr>
    <tr>
      <td width="7%" class="FondoTabla">&nbsp;</td>
      <td width="85%" class="FondoTabla"><span class="Tablas">
        <input name="poblacion" type="text" class="Tablas" id="poblacion"  onkeypress="consultaPoblacion(event,this.value)" style="text-transform:uppercase" value="<?=$poblacion ?>" size="50" />
      </span></td>
    </tr>
    <tr>
      <td colspan="2" class="Tablas" height="300px" valign="top"><div id="txtPoblacion" style="width:100%; height:auto;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?	
		$get = mysql_query('select * from catalogopoblacion limit '.$st.','.$pp,$link);		
		while($row=@mysql_fetch_array($get)){
		
	?> 
          <tr >
       <td width="10%" class="Tablas" >
<span onclick="window.parent.obtenerPoblacionx('<?=$row['id'];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row['id'];?></span></td>
            <td width="79%" class="Tablas"><input name="descripcion" type="text" class="Tablas" value="<?=$row['descripcion']; ?>" size="50" readonly="true" style="border:none; cursor:pointer" /></td>
            <td width="19px"></td>
          </tr>
          <? } ?>
      </table>
	  </div>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarPoblacionPoblacion.php?st='); ?></font></td>
    </tr>
  </table> 
</form>
<script>foco();</script>
<? //} ?>