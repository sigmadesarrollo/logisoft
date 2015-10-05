<? session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/

	include('../Conectar.php');

	$link=Conectarse('webpmm');

	$get = mysql_query('select count(*) from moduloconcesiones');

	$total = mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }

	$pp = 20;

?>



<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />



<form name="buscar" >

  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td width="10%" class="FondoTabla">Folio</td>

      <td width="57%" class="FondoTabla">Concesion</td>

      <td width="33%" class="FondoTabla">Fecha</td>
    </tr>

    <tr>

      <td colspan="3" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" class="Tablas">

          <?	

		$get = mysql_query('select m.folio, c.descripcion as sucursal, date_format(m.fechaconcesion,"%d/%m/%Y") as fecha,c.id from moduloconcesiones m
		inner join catalogosucursal c on m.sucursal = c.id
		ORDER BY descripcion ASC limit '.$st.','.$pp,$link);		

		while($row=@mysql_fetch_array($get)){

		

	?> 

          <tr >

       <td width="45" class="Tablas" >

<span onclick="parent.obtenerFolio('<?= $row[0];?>','<?= $row[3];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row[0];?></span></td>

            <td width="281" class="Tablas"><?=utf8_decode($row[1]); ?></td>
            <td width="100" class="Tablas"><?=utf8_decode($row[2]); ?></td>
            <td width="52"></td>
          </tr>

          <? } ?>

      </table></td>
    </tr>

    <tr>

      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarFolioConcesiones.php?st='); ?></font></td>
    </tr>
  </table> 
</form>