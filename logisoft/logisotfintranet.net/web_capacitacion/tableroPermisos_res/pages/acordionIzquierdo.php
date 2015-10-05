<?	session_start();

	require_once('../../Conectar.php');

	$l = Conectarse('webpmm');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

<link  href="../css/estilosclaseacordeon.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="../../javascript/ClaseAcordeon.js"></script>

<script>

	function mostrarPagina(pagina){

		if(pagina=='ACCESO DENEGADO'){

			parent.alerta3("Usted no tiene permiso para acceder a este modulo","¡Atencion!");

		}else{

			parent.document.all.pagina.src = pagina;	

		}

	}



</script>

</head>



<body>

    <table width="230" border="0" align="center" cellpadding="0" cellspacing="0">

      <tr>

	  	<td align="left">

			<ul class="acc" id="acc">

              <?

			  	$s = "SELECT permisos_tablerogpo.nombre AS grupo, permisos_tablerogpo.id

				FROM permisos_tablerogpo

				INNER JOIN permisos_modulos ON permisos_tablerogpo.id = permisos_modulos.grupo

				INNER JOIN permisos_permisos AS pp ON permisos_modulos.id = pp.idmodulo

				INNER JOIN permisos_grupospermisos AS pgp ON pp.id = pgp.idpermiso

				INNER JOIN catalogoempleado AS ce ON pgp.idgrupo = ce.grupo

				WHERE ce.id = $_SESSION[IDUSUARIO]

				GROUP BY permisos_tablerogpo.id

				ORDER BY grupo ASC";

				$r = mysql_query($s,$l) or die($s);

				while($f = mysql_fetch_object($r)){

			  ?>

			  <li>

				<h3><?=$f->grupo?></h3>

				<div class="acc-section">

					<div class="acc-content">

                    	<?

							$s = "SELECT pm.nombre, IF(ISNULL(pep.idempleado),'ACCESO DENEGADO',pm.vinculo) AS vinculo

							FROM permisos_modulos AS pm

							INNER JOIN permisos_permisos AS pp ON pm.id = pp.idmodulo AND pp.descripcion='Acceso'

							INNER JOIN permisos_grupospermisos AS pgp ON pp.id = pgp.idpermiso

							LEFT JOIN permisos_empleadospermisos AS pep ON pp.id = pep.idpermiso AND pep.idempleado = $_SESSION[IDUSUARIO]

							INNER JOIN catalogoempleado AS ce ON pgp.idgrupo = ce.grupo

							WHERE pm.grupo = $f->id AND ce.id = $_SESSION[IDUSUARIO]";

							$rx = mysql_query($s,$l) or die($s);

							while($fx = mysql_fetch_object($rx)){

						?>

						<table width="100%" class="<?=($cf)?'fila1':'fila2'; $cf=!$cf;?>" border="0" cellpadding="0" cellspacing="0">

							<tr>

							  <td align="left" onclick="mostrarPagina('<?=$fx->vinculo?>')"><?=$fx->nombre?></td>

							</tr>

						</table>

                        <?

							}

						?>

					</div>

				</div>

			  </li>

              <?

			  	}

			  ?>

			</ul>		

		</td>

 	  </tr>     

	</table>



</body>

</html>

<script>

		var parentAccordion=new TINY.accordion.slider("parentAccordion");

		parentAccordion.init("acc","h3",1,-1);

		

		var nestedAccordion=new TINY.accordion.slider("nestedAccordion");

		nestedAccordion.init("nested","h3",1,-1);

</script>

