<?	session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

	require_once('../Conectar.php');

	$link = Conectarse('webpmm');

	$get = @mysql_query('select count(*) from catalogoruta where enuso=0');	

	$total = mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }

	$pp = 20;

?>





<link href="../FondoTabla.css" rel="stylesheet" type="text/css">

<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />

<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr>

    <td width="11%" class="FondoTabla">ID</td>

    <td width="58%" class="FondoTabla">Descripción</td>

    <td width="31%" class="FondoTabla">D&iacute;as de Salida </td>
  </tr>

  <tr>

    <td height="300px" colspan="4" valign="top" class="Tablas">

      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">

        <?	
			$get = mysql_query("SELECT cr.id, cr.descripcion, d.diasalidas FROM catalogoruta cr
			INNER JOIN catalogorutadetalle d ON cr.id = d.ruta
			WHERE cr.enuso=0 AND d.tipo=1 
			order by cr.id asc
			limit ".$st.",".$pp,$link);

		while($row=@mysql_fetch_array($get)){ ?>

        <tr>

          <td width="55"><span style="cursor:pointer;color:#0000FF" onclick="window.parent.obtenerRutaBusqueda('<?=$row['id'];?>');parent.VentanaModal.cerrar();">

            <?= $row['id'];?>

          </span></td>

          <td width="287" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['descripcion']; ?>" readonly="true" style="width:270px; border:none; cursor:default"></td>         

          <td width="122" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['diasalidas']; ?>" readonly="true" style="width:70px; border:none; cursor:default"></td>
          <td width="32"></td>
        </tr>

        <? } ?>
    </table>    </td>
  </tr>

  <tr>

    <td colspan="4" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarRuta.php?st='); ?></font></td>
  </tr>
</table>

