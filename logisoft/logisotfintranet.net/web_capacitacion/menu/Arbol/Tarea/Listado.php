<?
	include ("../../Conectar.php");
	$conexion = Conectarse("pruebas");
	$sql = mysql_query("SELECT TR.Folio, Concat(TR.Sguia, '-' , TR.Nguia) As Guia, TR.Contacto, TR.Email, TR.Dia, 		 	TR.Hora, CTR.Descripcion From TReportes TR INNER JOIN CatalogoTipoReporte CTR ON TR.TReporte = CTR.Codigo WHERE  	TR.Treporte = '$Tipo' ORDER BY TR.Folio");
?><style type="text/css">
<!--
#form1 table {
	font-weight: bold;
}
-->
</style>

<form id="form1" name="form1" method="post" action="../../rptotros.php">

<style type="text/css">
<!--
.MiPrimerEstilo {
	text-align: center;
	font-weight: bold;
}
-->
</style><table width="100%" border="1">
  <tr>
    <td colspan="6" align="center">Detallado de Informaci&oacute;n</td>
</tr>
  <tr class="MiPrimerEstilo">
    <td >Folio</td>
    <td>Guia</td>
    <td>Contacto</td>
    <td>E-Mail</td>
    <td>Fecha</td>
    <td>Hora</td>
  </tr>

<?	
		while($row = mysql_fetch_array($sql,$conexion))
	{
?>
  <tr>
    <td><a  href='../consulta.php?Folio=$row['Folio']'><?= $row['Folio']; ?></a></td>
    <td><?= $row['Guia']; ?></td>
    <td><?= $row['Contacto']; ?></td>
    <td><?= $row['Email']; ?></td>
    <td><?= $row['Dia']; ?></td>
    <td><?= $row['Hora']; ?></td>
  </tr>
<?
	}	
?>  
</table>

</form>
