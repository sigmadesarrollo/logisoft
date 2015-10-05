<? session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/

	include('../Conectar.php');

	$link=Conectarse('webpmm');

	$get = mysql_query('select count(*) from catalogodestino WHERE subdestinos = 1');	

	$total = mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }

	$pp = 20;

?>

<script src="select.js"></script>

<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />







<form name="form1" >

  <table width="100%" border="0">

    <tr>

      <td><table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

          <tr>

            <td width="9%" class="FondoTabla">ID</td>

            <td width="41%" class="FondoTabla">Descripcion</td>

            <td width="36%" class="FondoTabla">&nbsp;</td>

            <td width="14%" class="FondoTabla">&nbsp;</td>

          </tr>

          <tr>

            <td height="300px" colspan="5" valign="top" class="Tablas" ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">

                    <?
					$get = mysql_query('SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo," - ",cd.descripcion),
					CONCAT(cd.descripcion," - ",cs.prefijo)) AS descripcion,
					cd.sucursal, cd.id FROM catalogodestino cd 
					INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id 
					WHERE cd.subdestinos = 1
					order by cd.descripcion limit '.$st.','.$pp,$link);
						while($row=@mysql_fetch_array($get)){
					?>

                  <tr>

                    <td width="43"><span style="cursor:pointer;color:#0000FF" onclick="window.parent.obtenerDestino('<?= $row['id'];?>','<?=$row['descripcion']; ?>','<?=$row['sucursal']; ?>');parent.VentanaModal.cerrar();">

                      <?= $row['id'];?>

                    </span></td>

                    <td class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row[descripcion]?>" readonly="true" style="width:200px; border:none; cursor:default" /></td>

                    <td width="42"></td>

                  </tr>

                  <? } ?>

                </table>

          </td>

          </tr>

          <tr>

            <td colspan="5" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarDestinoEvaluacion.php?st='); ?></font></td>

          </tr>

        </table>

      </td>

    </tr>

  </table>

</form>