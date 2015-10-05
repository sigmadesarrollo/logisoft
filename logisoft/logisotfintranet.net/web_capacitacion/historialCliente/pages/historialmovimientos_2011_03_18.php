<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/DataSetSinFiltro.js"></script>
<script src="../../javascript/ajax.js"></script>
<script>
	
	var tabla1 = new ClaseTabla();
	var u = document.all;
	var pag1_cantidadporpagina = 30;
	var DS1 = new DataSet();
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"Guia", medida:80, alineacion:"left",  datos:"guia"},
			{nombre:"Origen", medida:60, alineacion:"left", datos:"origen"},
			{nombre:"Destino", medida:60, alineacion:"left",  datos:"destino"},
			{nombre:"Remitente", medida:120, alineacion:"left", datos:"remitente"},
			{nombre:"Destinatario", medida:120, alineacion:"left", datos:"destinatario"},
			{nombre:"Subtotal", medida:80, tipo:"moneda", alineacion:"right", datos:"subtotal"},
			{nombre:"Iva", medida:80, tipo:"moneda", alineacion:"right", datos:"tiva"},
			{nombre:"IvaRet", medida:80, tipo:"moneda", alineacion:"left", datos:"ivaretenido"},
			{nombre:"Total", medida:80, tipo:"moneda", alineacion:"right", datos:"total"}
		],
		filasInicial:30,
		alto:160,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	DS1.crear({
			'paginasDe':30,
			'objetoTabla':tabla1,
			'objetoPaginador':document.getElementById('detalle_pag'),
			'nombreVariable':'DS1',
			'ubicacion':'../',
			'funcionOrdenar':function(a,b){
				return parseInt(a.guia.toString().substring(0,12)+a.guia.toString().charCodeAt(12))-
				parseInt(b.guia.toString().substring(0,12)+b.guia.toString().charCodeAt(12))
			}
		});
	
	window.onload = function(){
		tabla1.create();		
		obtenerDetalle();
	}
	
	function obtenerDetalle(){
		consultaTexto("resTabla","historialmovimientos_con.php?accion=1&cliente=<?=$_GET[cliente] ?>&ran="+Math.random());
	}
	
	function resTabla(datos){
		obj = eval(datos);
		DS1.setJsonData(obj);
	}
	
</script>
<title>Documento sin t&iacute;tulo</title>
<link href="../../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="91%" border="1" cellpadding="0" cellspacing="0" bordercolor="#282828">
    <tr>
      <td style="background:#282828; color:#FFFFFF;font-family: tahoma; font-size: 12px; font-weight: bold;">HISTORIAL DE MOVIMIENTOS</td>
    </tr>
    <tr>
      <td><div style="background-color:#282828"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table></td>
        </tr>
        <tr>
          <td><div id="div_paginado5" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion5('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion5('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion5('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion5('ultimo')" />          
          <input type="hidden" name="pag5_total" />
          <input type="hidden" name="pag5_contador" value="0" />
          <input type="hidden" name="pag5_adelante" value="" />
          <input type="hidden" name="pag5_atras" value="" />
          <input type="hidden" name="pag5_idcliente" value="" />
		  </div>
		  </td>
        </tr>
      </table></div></td>
    </tr>
    <tr>
    	<td id="detalle_pag" style="border:1px #000 solid"></td>
    </tr>
  </table>
</form>
</body>
</html>
