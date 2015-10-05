

<?

	require_once("../../Conectar.php");

	$l = Conectarse("webpmm");

?>

<html>

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<title></title>

<link href="FondoTabla.css" rel="stylesheet" type="text/css">

<link href="Tablas.css" rel="stylesheet" type="text/css">

<link href="puntovta.css" rel="stylesheet" type="text/css">

<style type="text/css">

<!--

.style1 {

	font-size: 14px;

	font-weight: bold;

	color: #FFFFFF;

}

.style2 {

	color: #464442;

	font-size:9px;

	border: 0px none;

	background:none

}

.style3 {

	font-size: 9px;

	color: #464442;

}

.style4 {color: #025680;font-size:9px }

.style5 {color: #FFFFFF ; font-size:9px}

.Balance {background-color: #FFFFFF; border: 0px none}

.Balance2 {background-color: #DEECFA; border: 0px none;}

.estilo_relleno{

	background-color:#006192;

	font-size: 9px;

	color:#FFFFFF;

	font-weight:bold;

}

.estilo_div {

	background: white;  width:200px; height:100px; overflow: scroll;

	border: 1px solid #006699;

}

.estilo_borsup{

	border-top-width: thin;

	border-top-style: solid;

	border-top-color: #006699;

}

.estilo_borsupeizq{

	border-top-width: thin;

	border-left-width: thin;

	border-top-style: solid;

	border-left-style: solid;

	border-top-color: #006699;

	border-left-color: #006699;

}

.estilo_borizq{

	border-left-width: thin;

	border-left-style: solid;

	border-left-color: #006699;

	border-top-width: 1px;

	border-top-style: dotted;

	border-top-color: #006699;

}

.estilo_borsupdelg{

	border-top-width: 1px;

	border-top-style: dotted;

	border-top-color: #006699;

}

.estilo_celvac{

	background-color:#CCCCCC;

}

.estilo_celvacsup{

	background-color:#CCCCCC;

	border-top-width: thin;

	border-top-style: solid;

	border-top-color: #006699;

}

.estilo_celvacsupizq{

	background-color:#CCCCCC;

	border-top-width: thin;

	border-top-style: solid;

	border-top-color: #006699;

	border-left-width: thin;

	border-left-style: solid;

	border-left-color: #006699;

}

.estilo_celvacizq{

	background-color:#CCCCCC;

	border-left-width: thin;

	border-left-style: solid;

	border-left-color: #006699;

}

.Tablas{

	font-family: tahoma;

	font-size: 9px;

	font-style: normal;

	font-weight: bold;

}

-->

<!--

.Estilo1 {

	color: #FFFFFF;

	font-weight: bold;

	font-size: 13px;

	font-family: tahoma;

}

-->

</style>

</head>

<body>

<form id="form1" name="form1" method="post" >



  <table width="100%" border="0">

    <tr>

      <td><br></td>

    </tr>

    <tr>

      <td><table width="670" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

        <tr>

          <td class="FondoTabla">Datos Generales </td>

        </tr>

        <tr>

          <td>

		  <table width="666" border="0" cellpadding="0" class="Tablas" cellspacing="0">

		  	<tr>

				<td width="3" height="26">&nbsp;</td>

				<td width="574">

					<table width="562" border="0" cellpadding="0" cellspacing="0">

						<tr>

							<td width="101">Origen</td>

							<td width="189">

								<select name="origen" style="width:180px">

									<option value="0">.::Seleccione::.</option>

									<? 

										$s = "select id, descripcion from catalogosucursal order by descripcion";

										$r = mysql_query($s,$l) or die($s);

										while($f = mysql_fetch_object($r)){

									?>

										<option value="<?=$f->id?>"><?=strtoupper($f->descripcion)?></option>

									<?

										}

									?>

						  </select>				</td>

							<td width="63">Destino</td>

							<td width="209">

								<select name="destino" style="width:200px">

									<option value="0">.::Seleccione::.</option>

									<? 

										$s = "select id, descripcion from catalogosucursal order by descripcion";

										$r = mysql_query($s,$l) or die($s);

										while($f = mysql_fetch_object($r)){

									?>

										<option value="<?=$f->id?>"><?=strtoupper($f->descripcion)?></option>

									<?

										}

									?>

						  </select>						  </td>

						</tr>

					</table>				</td>

				<td width="84" rowspan="2" align="center"><img src="../../img/Boton_Agregari.gif" width="70" height="20"></td>

				<td width="10">&nbsp;</td>

			</tr>

		  	<tr>

		  	  <td>&nbsp;</td>

		  	  <td>

			  	<table border="0" cellpadding="0" cellspacing="0">

					<tr>

						<td width="158">Tiempo entrega ocurre </td>

						<td width="131"><input type="text" name="teo" style="width:122px"></td>

						<td width="83">Tiempo EAD </td>

						<td width="189"><input type="text" name="ead" style="width:180px"></td>

					  </tr>

				</table>			  </td>

		  	  <td>&nbsp;</td>

		  	  </tr>

			  <tr>

			  	<td>&nbsp;</td>

				<td colspan="2">

					<table width="638" border="0" cellpadding="0" cellspacing="0">

						<tr>

						  <td width="21"><input type="checkbox" name="inc_tiempo"></td>

							<td width="157">Incrementar tiempo </td>

							<td width="166">Si lo documenta antes de</td>

							<td width="92">

								<select name="horas">

									<option value="0"></option>

									<option value="1">12:00 PM</option>

									<option value="2">14:00 PM</option>

									<option value="3">16:00 PM</option>

								</select>							</td>

							<td width="92">Incrementar</td>

						  <td width="110"><input type="text" name="cantidad" style="width:70px"></td>

						</tr>

					</table>				</td>

				<td>&nbsp;</td>

			  </tr>

		  	<tr>

		  	  <td>&nbsp;</td>

		  	  <td colspan="2">

			  	<div id=detalle name=detalle class="barras_div" style=" height:450px; width:654px; overflow:scroll;" align=left>

				<?

					$s = "select id, prefijo from catalogosucursal order by prefijo";

					$r = mysql_query($s,$l) or die($s);

					$con = mysql_num_rows($r);

					

				?>

			  	<table border="0" cellpadding="0" cellspacing="0" width="<?=($con*100)+100?>px">

					<tr>

						<td width="100px">&nbsp;</td>

					<?

						$s = "select id, prefijo from catalogosucursal order by prefijo";

						$r = mysql_query($s,$l) or die($s);

						while($f = mysql_fetch_object($r)){

					?>

						<td width="100px" colspan="2" rowspan="2" align="center" class="estilo_relleno"><?=$f->id?> <?=$f->prefijo?></td>

					<?

						}

					?>

					</tr>

					<tr>

					  <td>&nbsp;</td>

					  </tr>

					<?

						$s = "select id, prefijo from catalogosucursal order by prefijo";

						$r = mysql_query($s,$l) or die($s);

						while($f = mysql_fetch_object($r)){

						$idfila = $f->id;

					?>

					

					<tr>

						<td width="100px" rowspan="2" align="center" class="estilo_relleno"><?=$f->id?> <?=$f->prefijo?></td>

					<?

						$s = "select id, prefijo from catalogosucursal order by prefijo";

						$rx = mysql_query($s,$l) or die($s);

						$antes = true;

						while($fx = mysql_fetch_object($rx)){

							if($fx->id==$idfila)

								$antes = false;

							if($antes){

								$where = "idorigen = $idfila and iddestino = $fx->id";

							}else{

								$where = "iddestino = $idfila and idorigen = $fx->id";

							}

							$s = "select tentrega as e, siocurre as si from catalogotiempodeentregas where $where";

							$ry = mysql_query($s,$l) or die($s);

							$fy = mysql_fetch_object($ry);

					?>

						<td width="50" onDblClick="document.all.origen.value = <?=($antes)?$idfila:$fx->id?>; document.all.destino.value = <?=(!$antes)?$idfila:$fx->id?>;"

						style="background-color:<?=(($fy->si==1)?"#FFCC00":(($fy->si==2)?"#3399CC":(($fy->si==3)?"#00CC00":"")))?>" 

						class="<?=(($idfila!=$fx->id)?"estilo_borsupeizq":"estilo_celvacsupizq");?>" align="center"><?=(($fy->e!="")?$fy->e:"&nbsp;");?> </td>

					    <td width="50" style="background-color:<?=(($fy->si==1)?"#FFCC00":(($fy->si==2)?"#3399CC":(($fy->si==3)?"#00CC00":"")))?>"

						align="center" class="<?=(($idfila!=$fx->id)?"estilo_borsup":"estilo_celvacsup");?>">&nbsp;</td>

					    <?

						}

					?>

					</tr>

					<tr>

					  <?

						$s = "select id, prefijo from catalogosucursal order by prefijo";

						$rx = mysql_query($s,$l) or die($s);

						$antes = true;

						while($fx = mysql_fetch_object($rx)){

							if($fx->id==$idfila)

								$antes = false;

							if($antes){

								$where = "idorigen = $idfila and iddestino = $fx->id";

							}else{

								$where = "iddestino = $idfila and idorigen = $fx->id";

							}

							$s = "select tentrega as a, siocurre as si from catalogotiempodeentregas where $where";

							$ry = mysql_query($s,$l) or die($s);

							$fy = mysql_fetch_object($ry);

					?>

						<td width="50" align="center" style="background-color:<?=(($fy->si==1)?"#FFCC00":(($fy->si==2)?"#3399CC":(($fy->si==3)?"#00CC00":"")))?>"

						class="<?=(($idfila!=$fx->id)?"estilo_borizq":"estilo_celvacizq");?>">&nbsp;</td>

					    <td width="50" style="background-color:<?=(($fy->si==1)?"#FFCC00":(($fy->si==2)?"#3399CC":(($fy->si==3)?"#00CC00":"")))?>"

						class="<?=(($idfila!=$fx->id)?"estilo_borsupdelg":"estilo_celvac");?>" align="center"><?=(($fy->a!="")?$fy->a:"&nbsp;");?> </td>

					    <?

						}

					?>

					  </tr>

					

					<?

						}

					?>

				</table>

			  </div>			  </td>

		  	  <td>&nbsp;</td>

		  	  </tr>

		  </table>

		  </td>

        </tr>

      </table></td>

    </tr>

  </table>

</form>

</body>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'MOTIVOS';

</script>