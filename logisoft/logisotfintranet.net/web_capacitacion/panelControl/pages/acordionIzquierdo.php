<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<link  href="../css/estilosclaseacordeon.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../javascript/ClaseAcordeon.js"></script>
<script>
	function mostrarPagina(id){
		switch(id){
			/*  EMPRESA  */
			case "0"://CATALOGO SUCURSAL
				parent.document.all.pagina.src = "../../catalogos/sucursal/catalogosucursal.php";
			break;
			case "1"://CATALOGO PUESTO
				parent.document.all.pagina.src = "../../catalogos/empleado/Catalogopuesto.php";
			break;
			case "2"://CATALOGO EMPLEADO
				parent.document.all.pagina.src = "../../catalogos/empleado/catalogoempleado.php";
			break;
			case "3"://CATALOGO SERVICIOS
				parent.document.all.pagina.src = "../../catalogos/sucursal/catalogoservicio.php";
			break;
			case "4"://CATALOGO UNIDAD
				parent.document.all.pagina.src = "../../catalogos/unidad/catalogounidad.php";
			break;
			case "5"://CATALOGO TIPO UNIDAD
				parent.document.all.pagina.src = "../../catalogos/unidad/catalogotipounidad.php";
			break;
			case "6"://CATALOGO DESTINO
				parent.document.all.pagina.src = "../../catalogos/sucursal/catalogodestino.php";
			break;
			case "7"://CATALOGO RUTAS
				parent.document.all.pagina.src = "../../catalogos/rutas/catalogoRutas.php";
			break;
			case "8"://CATALOGO MOTIVOS
				parent.document.all.pagina.src = "../../catalogos/motivos/CatalogoMotivos.php";
			break;			
			case "9"://CATALOGO DESCRIPCION
				parent.document.all.pagina.src = "../../catalogos/sucursal/catalogodescripcion.php";
			break;
			
			/* CONFIGURACION REGIONAL */
			case "10"://CATALOGO PAIS
				parent.document.all.pagina.src = "../../catalogos/colonias/catalogopais.php";
			break;
			case "11"://CATALOGO ESTADOS
				parent.document.all.pagina.src = "../../catalogos/colonias/catalogoestado.php";
			break;
			case "12"://CATALOGO MUNICIPIO
				parent.document.all.pagina.src = "../../catalogos/colonias/catalogomunicipio.php";
			break;
			case "13"://CATALOGO POBLACION
				parent.document.all.pagina.src = "../../catalogos/colonias/catalogopoblacion.php";
			break;
			case "14"://CATALOGO COLONIA
				parent.document.all.pagina.src = "../../catalogos/colonias/catalogocolonias.php";
			break;
			case "15"://CATALOGO CODIGO POSTAL
				parent.document.all.pagina.src = "../../catalogos/colonias/catalogocodigopostal.php";
			break;
			case "16"://CATALOGO SECTORES
				parent.document.all.pagina.src = "../../catalogos/sector/CatalogoSector.php";
			break;
			/* CLIENTES  */
			case "17"://CATALOGO TIPO CLIENTE
				parent.document.all.pagina.src = "../../catalogos/cliente/tipocliente.php";
			break;
			case "18"://CATALOGO CLIENTE
				parent.document.all.pagina.src = "../../catalogos/cliente/client.php";
			break;
			case "19"://CATALOGO PROSPECTO
				parent.document.all.pagina.src = "../../catalogos/cliente/prospecto.php";
			break;
			/* PROVEEDOR */
			case "20"://CATALOGO TIPO PROVEEDOR
				parent.document.all.pagina.src = "../../catalogos/proveedores/catalogotipoproveedor.php";
			break;
			case "21"://CATALOGO PROVEEDOR
				parent.document.all.pagina.src = "../../catalogos/proveedores/catalogoproveedores.php";
			break;
			
			case "22"://CONFIGURADOR GENERAL
				parent.document.all.pagina.src = "../../catalogos/configuradores/configuradorgeneral.php";
			break;
			case "23"://CONFIGURADOR SERVICIOS
				parent.document.all.pagina.src = "../../catalogos/configuradores/configuradorservicio.php";
			break;
			case "24"://CONFIGURADOR FLETES
				parent.document.all.pagina.src = "../../catalogos/configuradorflete/puntovta_configuracion.php";
			break;
			case "25"://CONFIGURADOR RECOLECCIONES
				parent.document.all.pagina.src = "../../recoleccion/configuradorRecolecciones.php";
			break;
			case "26"://CONFIGURADOR DISTANCIAS
				parent.document.all.pagina.src = "../../catalogos/configuradores/configurador_distancias.php";
			break;
			case "27"://CONFIGURADOR TIEMPOS ENTREGA
				parent.document.all.pagina.src = "../../catalogos/configuradores/configurador_tiempos_entrega.php";
			break;
			case "28"://DEPOSITOS CAJA CHICA
				parent.document.all.pagina.src = "../../cajachica/configuradordepositoscajachica.php";
			break;
			case "29"://CONFIGURADOR PRECINTOS
				parent.document.all.pagina.src = "../../catalogos/configuradores/configuradorFoliosPrecintos.php";
			break;
			case "30"://CONFIGURADOR IMPRESIONES
				parent.document.all.pagina.src = "../../catalogos/configuradores/configuracionimpresiones.php";
			break;
			case "31"://LIBERAR USUARIO
				parent.document.all.pagina.src = "../../sesiones/liberarUsuarios.php";
			break;
			case "32"://CONTROL ACCESOS
				parent.document.all.pagina.src = "../../catalogos/configuradores/permisosusuario.php";
			break;
			case "33"://CONTROL MODULOS GRUPO
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
			
				$s = "SELECT * FROM administradormenunuevo";
				$r = mysql_query($s,$l) or die($s);
				$line = 0;
				while($f = mysql_fetch_object($r)){
			?>
            <li>
                <h3 align="left"><?=utf8_encode($f->nombre)?></h3>
                <div class="acc-section">
                    <div class="acc-content">
						<table  border="0" cellpadding="0" cellspacing="0">
						<?
							$s = "select * from modulos_menu_nuevo where grupo=$f->id";
							$rx = mysql_query($s,$l) or die($s);							
							while($fx = mysql_fetch_object($rx)){
						?>
							<tr>
								<td align="left">
									<table width="205" border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td colspan="3" class="<? if ($line % 2 ==0){ echo 'fila1' ;}else{ echo 'fila2' ;} ?>">											<table cellpadding="0" cellspacing="0" border="0"><tr><td onclick="mostrarPagina('<?=$line ?>')" ><?=utf8_encode($fx->nombre)?></td>
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
