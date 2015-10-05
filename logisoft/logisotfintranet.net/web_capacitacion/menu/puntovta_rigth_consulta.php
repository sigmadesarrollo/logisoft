<?
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT
(SELECT COUNT(*) FROM evaluacionmercancia WHERE estado = 'GUARDADO' AND sucursal=$_SESSION[IDSUCURSAL]) AS eval, 
(SELECT COUNT(*) FROM guiasventanilla WHERE estado = 'AUTORIZACION PARA CANCELAR' AND idsucursalorigen=$_SESSION[IDSUCURSAL]) AS guiapendcanc, 
(SELECT COUNT(*) FROM guiasventanilla WHERE estado = 'ALMACEN DESTINO' AND idsucursalorigen=$_SESSION[IDSUCURSAL]) AS guiaalma, 
(SELECT COUNT(*) FROM solicitudcredito WHERE estado='EN AUTORIZACION' AND idsucursal=$_SESSION[IDSUCURSAL]) AS credpendauto, 
(SELECT COUNT(*) FROM solicitudcredito WHERE estado='AUTORIZADO' AND idsucursal=$_SESSION[IDSUCURSAL]) AS credpendacti, 
(SELECT COUNT(*) FROM propuestaconvenio WHERE estadopropuesta = 'EN AUTORIZACION' AND sucursal=$_SESSION[IDSUCURSAL]) AS convpendauto,
(SELECT COUNT(*) FROM guiasventanilla_cs WHERE sucursal = $_SESSION[IDSUCURSAL] AND estado='SUSTITUCION') AS pendporsust,
(SELECT COUNT(*) FROM guiasventanilla_cs WHERE sucursal = $_SESSION[IDSUCURSAL] AND estado='AUTORIZADA PARA SUSTITUIR') AS autoparsust,
(SELECT COUNT(*) FROM solicitudguiasempresarialesnw where status<>'CANCELADA' AND STATUS<>'AUTORIZADA' AND STATUS<>'FOLIADO')AS SolicitudGuiPenAut,
(SELECT COUNT(*) FROM solicitudguiasempresarialesnw WHERE STATUS<>'CANCELADA'
 AND STATUS<>'' AND STATUS<>'FOLIADO') AS SolicitudGuiPenAsi
";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
//	echo "(".str_replace("null",'"0"',json_encode($f)).")";
	echo "({'eval':'$f->eval', 'guiapendcanc':'$f->guiapendcanc', 'guiaalma':'$f->guiaalma', 
	'credpendauto':'$f->credpendauto', 'credpendacti':'$f->credpendacti','convpendauto':'$f->convpendauto',
	'pendporsust':'$f->pendporsust','autoparsust':'$f->autoparsust',
	'SolicitudGuiPenAut':'$f->SolicitudGuiPenAut','SolicitudGuiPenAsi':'$f->SolicitudGuiPenAsi'})";
?>

