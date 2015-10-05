<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	$get = @mysql_query("SELECT COUNT(DISTINCT(unidad)) FROM
	(SELECT unidad FROM guiaventanilla_unidades WHERE unidad IS NOT NULL AND unidad<>''
	UNION
	SELECT unidad FROM guiasempresariales_unidades WHERE unidad IS NOT NULL AND unidad<>'') t");
	
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="41%" class="FondoTabla">Unidad</td>
    <td width="36%" class="FondoTabla">&nbsp;</td>
    <td width="14%" class="FondoTabla">&nbsp;</td>
  </tr>
  <tr>
    <td height="310" colspan="4" valign="top" class="Tablas">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?
		  		$sql = @mysql_query("SELECT unidad FROM (	
				SELECT DISTINCT(unidad) AS unidad FROM guiaventanilla_unidades 
				WHERE unidad IS NOT NULL AND unidad<>''
				UNION
				SELECT DISTINCT(unidad) AS unidad FROM guiasempresariales_unidades 
				WHERE unidad IS NOT NULL AND unidad<>''
				) t
				GROUP BY unidad
				ORDER BY unidad limit ".$st.','.$pp);
		
		while($row=@mysql_fetch_array($sql)){
	?>
          <tr>
            <td width="205" class="Tablas"><span style="cursor:pointer;color:#0000FF" onclick="parent.<?=$_GET['funcion']?>('<?=$row[0]?>'); parent.VentanaModal.cerrar();">
              <?= $row[0];?>
              </span></td>
            <td width="206" class="Tablas">&nbsp;</td>
            <td width="42"></td>
          </tr>
      <? } ?>
    </table>    </td>
  </tr>
  <tr>
    <td colspan="4" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarunidadrecepcionespecial.php?funcion=$_GET[funcion]&tipo=$_GET[tipo]&st="); ?></font></td>
  </tr>
</table>
