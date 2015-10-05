<?

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

<script src="../../javascript/ajax.js"></script>

<script>

	function mostrarPagina(id){

		switch(id){

			case "0"://CONFIGURADOR GENERAL

				parent.document.all.pagina.src = "../../catalogos/configuradores/configuradorgeneral.php";

			break;

			case "1"://CONFIGURADOR SERVICIOS

				parent.document.all.pagina.src = "../../catalogos/configuradores/configuradorservicio.php";

			break;

			case "2"://CONFIGURADOR FLETES

				parent.document.all.pagina.src = "../../catalogos/configuradorflete/puntovta_configuracion.php";

			break;

			case "3"://CONFIGURADOR RECOLECCIONES

				parent.document.all.pagina.src = "../../catalogos/configuradores/permisosgrupo.php";

			break;

			case "4"://CONFIGURADOR DISTANCIAS

				parent.document.all.pagina.src = "../../catalogos/configuradores/configurador_distancias.php";

			break;

			case "5"://CONFIGURADOR TIEMPOS ENTREGA

				parent.document.all.pagina.src = "../../catalogos/configuradores/configurador_tiempos_entrega.php";

			break;

			case "6"://DEPOSITOS CAJA CHICA

				parent.document.all.pagina.src = "../../catalogos/configuradores/configurador_tiempos_entrega.php";

			break;

			case "7"://CONFIGURADOR PRECINTOS

				parent.document.all.pagina.src = "../../catalogos/configuradores/configuradorFoliosPrecintos.php";

			break;

			case "8"://CONFIGURADOR IMPRESIONES

				parent.document.all.pagina.src = "../../catalogos/configuradores/configuracionimpresiones.php";

			break;

			case "9"://LIBERAR USUARIO

				parent.document.all.pagina.src = "../../sesiones/liberarUsuarios.php";

			break;

			case "10"://CONTROL ACCESOS

				parent.document.all.pagina.src = "../../catalogos/configuradores/permisosgrupo.php";

			break;

		}		

	}



</script>



</head>



<body>

	<table>

	<tr>

	<td height="215" colspan="4" valign="top" align="center">	  	

      	<ul class="acc" id="acc">

			<?

				$varopcion 		= 0;

				$varopcion2 	= 0;

				$line = 0;

				$s = "SELECT * FROM administradormenunuevo where id=5";

				$r = mysql_query($s,$l) or die($s);

				while($f = mysql_fetch_object($r)){

			?>

            <li>

                <h3 align="left"><?=$f->nombre?></h3>

                <div class="acc-section">

                    <div class="acc-content">

						<table  border="0" cellpadding="0" cellspacing="0">

						<?

							$s = "select * from modulos_menu_nuevo where grupo=$f->id and grupo=5";

							$rx = mysql_query($s,$l) or die($s);							

							while($fx = mysql_fetch_object($rx)){

						?>

							<tr>

								<td align="left">

									<table width="215" border="0" cellpadding="0" cellspacing="0">

										<tr>

											<td colspan="3" class="<? if ($line % 2 ==0){ echo 'fila1' ;}else{ echo 'fila2' ;} ?>">											<table cellpadding="0" cellspacing="0" border="0"><tr><td onclick="mostrarPagina('<?=$line; ?>')"><?=$fx->nombre?></td>

											<td width="37"></td></tr></table>											</td>

										</tr>

									

								  </table>								</td>

						    </tr>

						<?

							$line++;

							}

						?>

						</table>

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

</script>

