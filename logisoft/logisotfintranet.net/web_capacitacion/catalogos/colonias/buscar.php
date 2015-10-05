<? session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	include('../../Conectar.php');

	$link=Conectarse('webpmm');

	$tipo=$_GET['tipo'];

	switch ($tipo) {

		case "pais":

			$get = mysql_query('select count(*) from catalogopais');			

			

			break;

		case "estado":

			$get = mysql_query('select count(*) from catalogoestado');

			

			break;

		case "municipio":

			$get = mysql_query('select count(*) from catalogomunicipio');

			

			break;

		case "poblacion_municipioclic":

			$get = mysql_query('SELECT count(*) from catalogomunicipio AS CM INNER JOIN catalogoestado AS CE INNER JOIN catalogopais AS CPA ON CM.estado=CE.id && CE.pais=CPA.defaul'); 

			

			break;

		case "poblacion":

			$get = mysql_query('select count(*) from catalogopoblacion');

			

			break;

		case "colonia":

			$get = mysql_query('select count(*) from catalogocolonia');

			

			break;



	}		

	$total = mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }

	$pp = 20;

?>

<script src="select.js"></script>

<script>

function consultaColonia(e,obj){

	tecla=(document.all) ? e.keyCode : e.which;

	if(tecla==13 && obj!=""){

		ColoniaConsulta('colonia',obj); 

	}

}

</script>

<link href="Tablas.css" rel="stylesheet" type="text/css" />

<link href="FondoTabla.css" rel="stylesheet" type="text/css" />



<form name="buscar" >

  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td class="FondoTabla">ID</td>

      <td class="FondoTabla">Descripción</td>

    </tr>    

    <tr>

      <td colspan="2" height="300px" valign="top"><table width="100%" border="0" align="center" class="Tablas">

          <?

		  switch ($tipo) {		  

			case "pais":			

		$get = mysql_query('select * from catalogopais limit '.$st.','.$pp,$link);

		while($row=@mysql_fetch_array($get)){?>

		<tr >

       <td width="10%" class="Tablas" >

<span onclick="window.parent.obtener('<?=$row['id'];?>','<?=$row['descripcion'];?>','<?=$row['default']; ?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row['id'];?></span></td>

            <td width="79%" class="Tablas"><?=htmlentities(strtoupper($row['descripcion'])); ?></td>

            <td width="19px"></td>

          </tr>			

			<? }		

				break;

			case "estado":

		$get = mysql_query('select * from catalogoestado ORDER BY descripcion ASC limit '.$st.','.$pp,$link);		

				break;

			case "municipio":

		$get = mysql_query('select * from catalogomunicipio limit '.$st.','.$pp,$link);				

				break;

			case "poblacion_municipioclic":

		$get = mysql_query('SELECT CM.id AS id_municipio,UCASE(CM.descripcion) AS municipio_descripcion,CE.id AS id_estado, UCASE(CE.descripcion) as estado_descripcion,UCASE(CPA.descripcion) as pais_descripcion from catalogomunicipio AS CM  INNER JOIN catalogoestado AS CE  INNER JOIN catalogopais AS CPA  ON CM.estado=CE.id && CE.pais=CPA.defaul  limit '.$st.','.$pp,$link);

				

				break;

			case "poblacion":

		$get = mysql_query('select * from catalogopoblacion limit '.$st.','.$pp,$link);		

				break;

			case "colonia":

		$get = mysql_query('select * from catalogocolonia limit '.$st.','.$pp,$link);		

				break;

		}	

			if($tipo!="pais"){

			while($row=@mysql_fetch_array($get)){

			?>

				<tr >

    <? if($tipo!="poblacion_municipioclic"){?>

       <td width="10%" class="Tablas" >

<span onclick="window.parent.obtener('<?=$row['id'];?>','<?=$row['descripcion'] ?>'); parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row['id'];?></span></td>

            <td width="79%" class="Tablas"><?=htmlentities(strtoupper($row['descripcion'])); ?></td>

            <td width="19px"></td>

          </tr>	

          <? } else if($tipo=="poblacion_municipioclic"){ ?>

          <!--MOSTRAR MUNICIPIO CATALOGO POBLACION + ESTADO + PAIS -->

          <td width="10%" class="Tablas" >

<span onclick="window.parent.obtenerMunicipio('<?=$row['id_municipio'];?>','<?=$row['municipio_descripcion'] ?>','<?=$row['estado_descripcion']?>','<?=$row['pais_descripcion'] ?>'); parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row['id_municipio'];?></span></td>

            <td width="79%" class="Tablas"><?=htmlentities(strtoupper($row['municipio_descripcion'])); ?></td>

            <td width="19px"></td>

          </tr>	

          <? } ?>

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