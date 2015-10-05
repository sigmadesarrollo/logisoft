<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if (isset($_SESSION['gvalidar'])!=100){

echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";

	}else{*/

	require_once('../../Conectar.php');

	$link=Conectarse('webpmm');

	$usuario=$_SESSION[NOMBREUSUARIO];

	$tipo=$_GET['tipo'];

	$get = mysql_query('select count(*) from catalogopoblacion');

	$total = mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }

	$pp = 20;



?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Untitled Document</title>

<script src="select.js"></script>

<script language="javascript">

function enviarPoblacion(poblacion,e){

		tecla=(document.all) ? e.keyCode : e.which;		

        if(tecla!=13){

			 return;	

		}else{

			FiltroPoblacion(poblacion,'2');

		}

}



function enviarMunicipio(municipio,e){

			tecla=(document.all) ? e.keyCode : e.which;		

           if(tecla!=13){

				 return;	

			}else{

				FiltroMunicipio(municipio,'3');

			}

}

</script>





<link href="../../css/FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="Tablas.css" rel="stylesheet" type="text/css" />

</head>



<body>



<form name="form1">

<? if($tipo==1){ 

//BUSCAR POBLACION

?>

  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td width="7%" class="FondoTabla">ID</td>

      <td width="85%" class="FondoTabla">Poblacion</td>

    </tr>

    <tr>

      <td colspan="2" class="FondoTabla"><label>

        <input name="buscar" type="text" class="Tablas" id="buscar" style="text-transform:uppercase;"  onkeydown="enviarPoblacion(this.value,event)"/>

      </label></td>

    </tr>

    <tr>

      <td colspan="2" class="Tablas"><div id="txtHint" style="width:100%; height:300px; overflow: scroll;">

        <table width="96%" border="0" class="Tablas">

          <? 

 	$get = mysql_query("select * from catalogopoblacion",$link);	

	while($row=mysql_fetch_array($get)){

?>

          <tr style="cursor:pointer" onClick="parent.obtener('<?=$row['id'];?>','<?=$row['descripcion'] ?>'); parent.VentanaModal.cerrar();">

            <td width="11%"><?=$row[0]?></td>

            <td width="89%"><?=$row[1]?></td>

          </tr>

          <? }?>

        </table>

      </div></td>

    </tr>

  </table>

<? } ?>



<? if($tipo==2){

//MOSTRAR MUNICIPIOS+ESTADO+PAIS

?>

 <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td width="7%" class="FondoTabla">ID</td>

      <td width="85%" class="FondoTabla">Municipio</td>

    </tr>

    <tr>

      <td colspan="2" class="FondoTabla"><label>

        <input name="buscar" type="text" class="Tablas" id="buscar" style="text-transform:uppercase;" onKeyDown="enviarMunicipio(this.value,event)"/>

      </label></td>

    </tr>

    <tr>

      <td colspan="2" class="Tablas"><div id="txtHint" style="width:100%; height:300px; overflow: scroll;">

        <table border="0" width="96%">

          <? 

	$get = mysql_query("SELECT CM.id AS id_municipio,UCASE(CM.descripcion) AS municipio_descripcion,  CE.id AS id_estado, UCASE(CE.descripcion) as estado_descripcion,

UCASE(CPA.descripcion) as pais_descripcion  from catalogomunicipio AS CM   INNER JOIN catalogoestado AS CE   INNER JOIN catalogopais AS CPA   ON CM.estado=CE.id && CE.pais=CPA.defaul ",$link);	

	while($row=mysql_fetch_array($get)){

?>

          <tr style="cursor:pointer" onClick="parent.obtenerMunicipio('<?=$row['0'];?>','<?=$row['1'] ?>','<?=$row['3'] ?>','<?=$row['4'] ?>'); parent.VentanaModal.cerrar();">

            <td width="11%"><?=$row[0]?></td>

            <td width="89%"><?=$row[1]?></td>

          </tr>

          <? }?>

        </table>

      </div></td>

    </tr>

  </table>

<? } ?>



</form>

</body>

</html>

<? //} ?>