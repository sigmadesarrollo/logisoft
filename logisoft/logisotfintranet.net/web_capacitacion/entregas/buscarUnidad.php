<? session_start();


	if(!$_SESSION[IDUSUARIO]!=""){


		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");


	}


	require_once('../Conectar.php');


	$link = Conectarse('webpmm');


	$get = @mysql_query('select count(*) from catalogounidad u
					INNER JOIN catalogotipounidad t ON u.tipounidad = t.id
					WHERE fueradeservicio=0');


	$total = mysql_result($get,0);


	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }


	$pp = 20;


?>


<link href="Tablas.css" rel="stylesheet" type="text/css">





<link href="../FondoTabla.css" rel="stylesheet" type="text/css">


<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


  <tr>


    <td width="7%" class="FondoTabla">ID</td>


    <td width="42%" class="FondoTabla">Descripci&oacute;n</td>


    <td width="43%" class="FondoTabla">No. Economico</td>


  </tr>


  <tr>


    <td colspan="3"><div id="div" style="width:100%; height:300px; overflow: scroll;">


      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">


        <?		


		$get = mysql_query('SELECT u.id, t.descripcion, u.numeroeconomico FROM catalogounidad u


INNER JOIN catalogotipounidad t ON u.tipounidad = t.id
WHERE fueradeservicio=0 limit '.$st.','.$pp,$link);


		while($row=@mysql_fetch_array($get)){


	?>


        <tr>


          <td width="46"><span style="cursor:pointer;color:#0000FF" onclick="window.parent.obtenerUnidadBusqueda('<?=$row['numeroeconomico'];?>');parent.VentanaModal.cerrar();">


            <?= $row['id'];?>


          </span></td>


          <td width="223" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['descripcion']; ?>" readonly="true" style="width:200px; border:none; cursor:default"></td>


          <td width="203" class="Tablas"><input class="Tablas" name="economico" type="text" value="<?=$row['numeroeconomico']; ?>" readonly="true" style="width:170px; border:none; cursor:default"></td>


          <td width="24"></td>


        </tr>


        <? } ?>


      </table>


    </div></td>


  </tr>


  <tr>


    <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarUnidad.php?st='); ?></font></td>


  </tr>


</table>


