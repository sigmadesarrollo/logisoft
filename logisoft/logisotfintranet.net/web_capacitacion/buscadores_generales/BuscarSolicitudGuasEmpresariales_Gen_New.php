<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	if($_GET[con]==1){
		$condicion=" where s.status<>'CANCELADA' AND s.status<>'AUTORIZADA' AND s.status<>'FOLIADO'";
	}else if($_GET[con]==2){		
		$condicion=" where s.status=''";
	}
	
	$get = @mysql_query("SELECT COUNT(*) FROM solicitudguiasempresarialesnw s ".$condicion."");	
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
		
?>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="10%" class="FondoTabla">ID</td>
    <td width="63%" class="FondoTabla">Nombre</td>
    <td width="12%" class="FondoTabla">Sucursal</td>
    <td width="15%" class="FondoTabla">Tipo</td>
  </tr>
  <tr>
    <td colspan="5" class="Tablas" height="300px" valign="top">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
        <?		
		$get = mysql_query("SELECT s.folio,CONCAT_WS(' ',s.nombre,s.paterno,s.materno) AS nombre,s.status,
		cs.prefijo as sucursal, IF(s.tipo='web','WEB','SISTEMA') as tipo 
		FROM solicitudguiasempresarialesnw s
		left join catalogosucursal cs on s.sucursal = cs.id $condicion
		order by s.folio desc
		limit ".$st.",".$pp,$link);
		while($row=@mysql_fetch_array($get)){
	?>
        <tr>
          <td width="48"><span style="cursor:pointer;color:#0000FF" onclick="window.parent.<?=$_GET[funcion]?>('<?=$row['folio'];?>');parent.VentanaModal.cerrar();">
            <?=$row['folio'];?>
          </span></td>
          <td width="313" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['nombre']; ?>" readonly="true" style="width:300px; border:none; cursor:default"></td>
          <td width="55" class="Tablas" align="center"><?=$row['sucursal'];?></td>
          <td width="55" class="Tablas" align="center"><?=$row['tipo'];?></td>
          <td width="25"></td>
        </tr>
        <? } ?>
    </table>  </td>
  </tr>
  <tr>
    <td colspan="5" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "BuscarSolicitudGuasEmpresariales_Gen_New.php?con=$_GET[con]&funcion=$_GET[funcion]&st="); ?></font></td>
  </tr>
</table>
