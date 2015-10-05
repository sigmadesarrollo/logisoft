<? session_start();

	require_once('../Conectar.php');
	$link = Conectarse('webpmm');	
	$get = @mysql_query("SELECT COUNT(*) FROM liquidacioncobranza where sucursal=" .$_GET[sucursal]."");
	$criterio=" WHERE lc.sucursal=" .$_GET[sucursal]."";
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>


<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />


<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />


<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


  <tr> 


    <td width="8%" class="FondoTabla">Folio</td>


    <td width="22%" class="FondoTabla">Fecha</td>
    <td width="21%" class="FondoTabla">Estado</td>


    <td width="37%" class="FondoTabla">Cobrador</td>


    <td width="12%" class="FondoTabla">&nbsp;</td>
  </tr>


  <tr> 


    <td height="300px" colspan="6" valign="top" class="Tablas">


        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">


          <?


	$sql = @mysql_query("SELECT lc.folio, DATE_FORMAT(lc.fechaliquidacion,'%d/%m/%Y') AS fechaliquidacion,
IF(lc.estado='LIQUIDADO','APLICADO','GUARDADO')AS estado,CONCAT(ce.nombre,' ',ce.apellidopaterno,' ',ce.apellidomaterno)AS cobrador FROM liquidacioncobranza lc
INNER JOIN relacioncobranza rc ON lc.foliocobranza=rc.folio
INNER JOIN catalogoempleado ce ON rc.cobrador=ce.id $criterio GROUP BY lc.folio ORDER BY lc.fechaliquidacion limit $st,$pp",$link);


		


		  	


		


		while($row=@mysql_fetch_array($sql)){


	?>


          <tr> 


            <td width="40"><span style="cursor:pointer;color:#0000FF" onclick="parent.<?=$_GET['funcion']?>('<?=$row[0]?>'); parent.VentanaModal.cerrar();"> 


              <?= $row[0];?>


              </span></td>


            <td width="105" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row[1]?>" readonly="true" style="width:100px; border:none; cursor:default"></td>


            <td width="106" class="Tablas"><input name="estado" type="text" class="Tablas" id="estado" style="width:100px; border:none; cursor:default" value="<?=$row[2]?>" readonly="true" /></td>


            <td width="245"><input name="estado2" type="text" class="Tablas" id="estado2" style="width:200px; border:none; cursor:default" value="<?=$row[3]?>" readonly="true" /></td>
          </tr>


      <? } ?>
        </table>    </td>
  </tr>


  <tr> 


    <td colspan="6" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarFolioLiquidacionCobranzaGen.php?funcion=$_GET[funcion]&sucursal=$_GET[sucursal]&st="); ?></font></td>
  </tr>
</table>


