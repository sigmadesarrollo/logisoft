<? session_start();


	/*if(!$_SESSION[IDUSUARIO]!=""){


		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");


	}*/


	require_once('../Conectar.php');


	$link = Conectarse('webpmm');


	$get = @mysql_query('SELECT COUNT(*) FROM bitacorasalida WHERE folio 


	NOT IN (SELECT foliobitacora FROM comprobantedeliquidaciondebitacora) ');	


	$total = mysql_result($get,0);


	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }


	$pp = 20;


?>








<link href="../FondoTabla.css" rel="stylesheet" type="text/css">


<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />


<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


  <tr>


    <td width="14%" class="FondoTabla">#Bitacora</td>


    <td width="18%" class="FondoTabla"><div align="left">Fecha</div></td>


	 <td width="68%" class="FondoTabla"><div align="left">Estado</div></td>


  </tr>


  <tr>


    <td colspan="3" height="300px" valign="top">


      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">


        <?		


$get = mysql_query("SELECT folio, date_format(fechabitacora,'%d/%m/%Y') as fechabitacora, 'NO LIQUIDADO' as estado FROM bitacorasalida WHERE folio NOT IN (SELECT foliobitacora FROM comprobantedeliquidaciondebitacora) limit ".$st.",".$pp,$link);


		while($row=@mysql_fetch_array($get)){


	?>


        


        <tr>


          <td width="69"><span style="cursor:pointer;color:#0000FF" onclick="window.parent.obtener('<?=$row['folio'];?>');parent.VentanaModal.cerrar();">


            <?= $row['folio'];?>


          </span></td>


          <td width="385" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['fechabitacora']; ?>" readonly="true" style="width:100px; border:none; cursor:default">


          <input name="estado" type="text" class="Tablas" id="estado" style="width:200px; border:none; cursor:default" value="<?=$row['estado']; ?>" readonly="true" /></td>         


          <td width="42"></td>


        </tr>


        <? } ?>


    </table>   </td>


  </tr>


  <tr>


    <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarBitacora_ComprobantedeliquidaciondeBitacora.php?st='); ?></font></td>


  </tr>


</table>


