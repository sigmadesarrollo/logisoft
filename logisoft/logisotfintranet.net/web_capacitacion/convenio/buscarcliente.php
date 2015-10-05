<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if (isset($_SESSION['gvalidar'])!=100){
echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";
	}else{*/
	include('../../Conectar.php');
	$link=Conectarse('webpmm');
	$get =@mysql_query("SELECT ifnull(cn.nick,'') as nick, cc.rfc, cc.id as ncliente, cc.nombre, cc.paterno, cc.materno FROM catalogocliente cc LEFT JOIN catalogoclientenick cn ON cc.id=cn.cliente");
	$total =@mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<script src="select.js"></script>
<script>
var nav4 = window.Event ? true : false;
function Numeros(evt){ 
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
var key = nav4 ? evt.which : evt.keyCode; 
return (key <= 13 || (key >= 48 && key <= 57));
}
function ObtenerConsulta(e,nombrecaja,valor){
tecla=(document.all) ? e.keyCode : e.which;
if(tecla==13){
	switch(nombrecaja){
		case "buscarnick":
			ConsultarClienteFiltro('nick',valor);
		break;
		case "buscarrfc":		
			ConsultarClienteFiltro('rfc',valor);
		break;		
		case "buscarid":		
			ConsultarClienteFiltro('id',valor);		
		break;		
		case "buscarnombre":
			ConsultarClienteFiltro('nombre',valor);
		break;		
		case "buscarpaterno":
			ConsultarClienteFiltro('paterno',valor);
		break;
		case "buscarmaterno":
			ConsultarClienteFiltro('materno',valor);
		break;
	}
}

}
</script>
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<form name="buscar" >
  <table width="600"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Nick</td>
      <td class="FondoTabla">R.F.C</td>
      <td class="FondoTabla"># Cliente</td>
      <td class="FondoTabla">Nombre</td>
      <td class="FondoTabla">Paterno</td>
      <td class="FondoTabla">Materno</td>
    </tr>
    <tr>
      <td width="16%" class="FondoTabla"><input class="Tablas" name="buscarnick" type="text" id="buscarnick" onkeypress="ObtenerConsulta(event,this.name,this.value)" value="<?=$buscarnick ?>" size="10" style="border:none; text-transform:uppercase" /></td>
      <td width="12%" class="FondoTabla"><span class="Tablas">
        <input name="buscarrfc" type="text" id="buscarrfc" value="<?=$row[1] ?>" class="Tablas" size="12" style="border:none;text-transform:uppercase" onkeypress="ObtenerConsulta(event,this.name,this.value)" />
      </span></td>
      <td width="12%" class="FondoTabla"><div align="center"><span class="Tablas">
        <input name="buscarid" type="text" id="buscarid" value="<?=$buscarid?>" class="Tablas" size="4" style="border:none;text-transform:uppercase" onKeyPress="return Numeros(event)" onkeyup="ObtenerConsulta(event,this.name,this.value)" />
      </span></div></td>
      <td width="18%" class="FondoTabla"><span class="Tablas">
        <input name="buscarnombre" type="text" id="buscarnombre" value="<?=$buscarnombre ?>" class="Tablas" size="16" style="border:none; text-transform:uppercase" onkeypress="ObtenerConsulta(event,this.name,this.value)" />
      </span></td>
      <td width="17%" class="FondoTabla"><span class="Tablas">
        <input name="buscarpaterno" type="text" id="buscarpaterno" value="<?=$buscarpaterno ?>" class="Tablas" size="15" style="border:none ;text-transform:uppercase" onkeypress="ObtenerConsulta(event,this.name,this.value)" />
      </span></td>
      <td width="25%" class="FondoTabla"><span class="Tablas">
        <input name="buscarmaterno" type="text" id="buscarmaterno" value="<?=$buscarmaterno ?>" class="Tablas" size="15" style="border:none;text-transform:uppercase" onkeypress="ObtenerConsulta(event,this.name,this.value)" />
      </span></td>
    </tr>
    <tr>
      <td colspan="6"><div id="txtHint" style="width:100%; height:300px; overflow: scroll;"><table width="100%" border="0" align="center">
          <?	
		/*$get = mysql_query('SELECT ifnull(cn.nick,"") as nick, cc.rfc, cc.id as ncliente, cc.nombre, cc.paterno, cc.materno FROM catalogocliente cc
LEFT JOIN catalogoclientenick cn ON cc.id=cn.cliente limit '.$st.','.$pp,$link);		
		while($row=@mysql_fetch_array($get)){*/
		
	?> 
          <tr>
       <td width="89" class="Tablas" >
<input class="Tablas" name="nick" type="text" id="nick" readonly="" value="<?=$row[0] ?>" size="10" style="border:none; cursor:pointer" onclick="window.parent.obtener('<?= $row[2];?>');parent.VentanaModal.cerrar();" />
</td>
            <td width="100" class="Tablas"><input name="rfc" type="text" id="rfc" value="<?=$row[1] ?>" class="Tablas" size="12" readonly="" style="border:none; cursor:pointer" onclick="window.parent.obtener('<?= $row[2];?>');parent.VentanaModal.cerrar();" /></td>
            <td width="48" class="Tablas"><input readonly="" name="id3" type="text" id="id3" value="<?=$row[2] ?>" class="Tablas" size="4" style="border:none; cursor:pointer" onclick="window.parent.obtener('<?= $row[2];?>');parent.VentanaModal.cerrar();" /></td>
            <td width="110" class="Tablas"><input readonly="" name="nombre" type="text" id="nombre" value="<?=$row[3] ?>" class="Tablas" size="16" style="border:none; cursor:pointer" onclick="window.parent.obtener('<?= $row[2];?>');parent.VentanaModal.cerrar();" /></td>
            <td width="101" class="Tablas"><input readonly="" name="paterno" type="text" id="paterno" value="<?=$row[4] ?>" class="Tablas" size="15" style="border:none; cursor:pointer" onclick="window.parent.obtener('<?= $row[2];?>');parent.VentanaModal.cerrar();" /></td>
            <td width="82" class="Tablas"><input readonly="" name="materno" type="text" id="materno" value="<?=$row[5] ?>" class="Tablas" size="15" style="border:none; cursor:pointer" onclick="window.parent.obtener('<?= $row[2];?>');parent.VentanaModal.cerrar();" /></td>
            <td width="36"></td>
          </tr>
          <? //} ?>
      </table></div></td>
    </tr>
    <tr>
      <td colspan="6" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarcliente.php?st='); ?></font></td>
    </tr>
  </table> 
</form>
<? //} ?>