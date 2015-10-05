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
			{nombre:"NUMERO REPARTO EAD", medida:100, alineacion:"center",  datos:"numerorepartoead"},
			{nombre:"# GUIA", medida:50, alineacion:"center",  datos:"guia"},
			{nombre:"FECHA EMISION", medida:80, alineacion:"center",  datos:"fechaemision"},
			{nombre:"DESTINATARIO", medida:70, alineacion:"center", datos:"destinatario"},
			{nombre:"DIRECCION", medida:70, alineacion:"center", datos:"direccion"},
			{nombre:"SECTOR", medida:50, alineacion:"center", datos:"sector"},
			{nombre:"TIPO FLETE", medida:55, alineacion:"center", datos:"tipoflete"},
			{nombre:"IMPORTE", medida:60, alineacion:"center", datos:"importe"},
			{nombre:"CHOFER ASIGNADO", medida:85, alineacion:"center", datos:"choferasignado"}			
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
      <td><table width="550" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="549" border="0" cellspacing="0" cellpadding="0" id="detalle">  
			</table>
		</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
