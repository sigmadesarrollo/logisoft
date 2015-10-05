<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if (isset($_SESSION['gvalidar'])!=100){

echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";

	}else{*/

	include('../../Conectar.php');

	$link=Conectarse('webpmm');

	$tipo=$_GET['tipo'];

	switch ($tipo) {

		case "tipounidad":

			$get =@mysql_query('select count(*) from catalogotipounidad');			

			break;

		case "tipounidad1":

			$get =@mysql_query('select count(*) from catalogotipounidad');			

			break;

		case "unidad":

			$get = @mysql_query('select count(*) from catalogounidad cu 
			INNER JOIN catalogotipounidad ctu ON cu.tipounidad=ctu.id');

			break;

		case "carga":

			$get = @mysql_query('select count(*) from catalogotiempocargadescarga');

			break;		

	}		

	$total = mysql_result($get,0);
	
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }

	$pp = 20;

?>

<script src="select.js"></script>

<link href="Tablas.css" rel="stylesheet" type="text/css" />

<link href="FondoTabla.css" rel="stylesheet" type="text/css" />



<form name="buscar" >

  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

	<?

		 if($tipo=="unidad") { ?>

	      <td width="25%" class="FondoTabla">Num. Economico</td>			

		  <? }else{?>

	      <td width="7%" class="FondoTabla">ID</td>		  

		 <? }

		   ?>



      <td width="85%" class="FondoTabla">Descripci&oacute;n</td>

    </tr>

    <tr>

      <td colspan="2" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">

          <?

		  switch ($tipo) {		  

			case "unidad":			

		$get = @mysql_query('select cu.numeroeconomico, ctu.descripcion  from catalogounidad cu INNER JOIN catalogotipounidad ctu ON cu.tipounidad=ctu.id  limit '.$st.','.$pp,$link);

		while($row=@mysql_fetch_array($get)){?>

		<tr >

       <td width="25%" class="Tablas" >

<span onclick="window.parent.obtener('<?=$row[0];?>','<?=$tipo?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">

<?=$row[0];?></span></td>

            <td width="79%" class="Tablas"><?=htmlentities(strtoupper($row[1])); ?></td>

            <td width="19px"></td>

          </tr>			

			<? }		

				break;

		case "tipounidad":

	$get = mysql_query('select * from catalogotipounidad limit '.$st.','.$pp,$link);

				while($row=@mysql_fetch_array($get)){?>

		<tr >

       <td width="10%" class="Tablas" >

<span onclick="window.parent.obtener('<?=$row[0];?>','<?=$row[1];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">

<?=$row[0];?></span></td>

            <td width="79%" class="Tablas"><?=htmlentities(strtoupper($row[1])); ?></td>

            <td width="19px"></td>

          </tr>			

			<? }

			break;		

		case "carga":

	$get = mysql_query('select * from catalogotiempocargadescarga limit '.$st.','.$pp,$link);

			break;

			case "tipounidad1":

	$get = mysql_query('select * from catalogotipounidad limit '.$st.','.$pp,$link);

			break;	

		}	

			if($tipo!="unidad" && $tipo!="tipounidad"){

			while($row=@mysql_fetch_array($get)){

			?>

				<tr >

       <td width="10%" class="Tablas" >

<span onclick="window.parent.obtener('<?=$row['id'];?>','<?=$tipo ?>'); parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row['id'];?></span></td>

            <td width="79%" class="Tablas"><?=htmlentities(strtoupper($row['descripcion'])); ?></td>

            <td width="19px"></td>

          </tr>	

		<?	}

		}

		?>



      </table></td>

    </tr>

    <tr>

      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscar.php?tipo='.$tipo.'&st='); ?></font></td>

    </tr>

  </table> 

</form>

<? //} ?>