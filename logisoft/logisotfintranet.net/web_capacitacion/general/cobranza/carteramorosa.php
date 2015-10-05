<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar2.css?random=20051112"></LINK>
<SCRIPT type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar2.js?random=20060118"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script>
	var tabla1 		= new ClaseTabla();
	var	u		= document.all;
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"SUCURSAL", medida:50, alineacion:"center", datos:"sucursal"},
			{nombre:"GUIA/FACTURA", medida:50, alineacion:"center",  datos:"guia/factura"},
			{nombre:"FECHA", medida:40, alineacion:"center",  datos:"fecha"},	
			{nombre:"FECHA VTO", medida:70, alineacion:"center",  datos:"fecha vto"},
			{nombre:"AL CORRIENTE", medida:70, alineacion:"center",  datos:"al corriente"},
			{nombre:"1-15 DIAS", medida:50, alineacion:"center",  datos:"1-15 dias"},
			{nombre:"16-30 DIAS", medida:70, alineacion:"center",  datos:"16-30 dias"},
			{nombre:"31-60 DIAS", medida:70, alineacion:"center",  datos:"31-60 dias"},
			{nombre:"> 60- DIAS", medida:70, alineacion:"center",  datos:"> 60 dias"},
			{nombre:"SALDO", medida:50, alineacion:"center",  datos:"saldo"},
			{nombre:"FACTURA", medida:50, alineacion:"center",  datos:"factura"},
			{nombre:"CONTRARECIBO", medida:50, alineacion:"center",  datos:"contrarecibo"}
		],
		filasInicial:30,
		alto:150,
		seleccion:true,
		ordenable:false,
		//eventoDblClickFila:"verRecoleccion()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
-->
</style>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
<link href="../../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="716" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="716"><table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">
      </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table width="651" height="15" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="3">&nbsp;</td>
            <td width="542"><div align="right">Total</div></td>
            <td width="106" align="center"><span class="style31">
              <input name="tsaldo" type="text" class="Tablas" id="tsaldo" style="width:100px;background-color:#FFFF99;text-align:right; " readonly=""  />
            </span></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
</body>
<script>
	parent.frames[1].document.getElementById('titulo').innerHTML = '';
</script>
</html>