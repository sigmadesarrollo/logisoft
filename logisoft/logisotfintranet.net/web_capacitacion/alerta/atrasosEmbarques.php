<?	$s = "SELECT d.guia, g.fecha, em.foliobitacora, bs.fechabitacora, cr.descripcion, bs.unidad FROM embarquedemercancia em
	INNER JOIN embarquedemercanciadetalle d ON em.folio = d.idembarque
	INNER JOIN (SELECT id, fecha FROM guiasventanilla
	UNION
	SELECT id, fecha FROM guiasempresariales) g ON d.guia = d.id
	INNER JOIN bitacorasalida bs ON em.foliobitacora = bs.folio
	INNER JOIN catalogoruta cr ON bs.ruta = cr.id";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script>
	var tabla1 		= new ClaseTabla();
	var	u			= document.all;
	var mens 		= new ClaseMensajes();
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"# GUIA", medida:50, alineacion:"center",  datos:"guia"},
			{nombre:"FECHA EMISION", medida:90, alineacion:"center",  datos:"fechaemision"},
			{nombre:"BITACORA SALIDA", medida:90, alineacion:"center", datos:"bitacorasalida"},
			{nombre:"FECHA BITACORA", medida:90, alineacion:"center", datos:"fechabitacora"},
			{nombre:"RUTA", medida:70, alineacion:"center", datos:"ruta"},
			{nombre:"UNIDAD", medida:50, alineacion:"center", datos:"unidad"}		
		],
		filasInicial:15,
		alto:150,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtener();
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table width="549" border="0" cellspacing="0" cellpadding="0" id="detalle">       
      </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>
