<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmintranet.net';</script>");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css">
<link href="../../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css" />
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script>
	var tabla1 	= new ClaseTabla();
	var	u		= document.all;
	var pag1_cantidadporpagina = 10;
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"ENT. EL MISMO DÍA", medida:100, alineacion:"center", datos:"undiaead"},
			{nombre:"ENT. MAS DE 2 DÍAS", medida:100, alineacion:"center", datos:"dosdiaead"},
			{nombre:"GUÍAS PEND. ENTREGA", medida:100, alineacion:"center",  datos:"faltanteead"},
			{nombre:"ENT_EL MISMO DÍA", medida:100, alineacion:"center", datos:"undiarec"},
			{nombre:"ENT_MAS DE 2 DÍAS", medida:100, alineacion:"center", datos:"dosdiasrec"},
			{nombre:"GUÍAS_PEND. ENTREGA", medida:100, alineacion:"center",  datos:"faltanterec"}
		],
		filasInicial:15,
		alto:180,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtenerDetalle();
	}
	
	function obtenerDetalle(){
		consultaTexto("resTabla5","reporteProductividad_con.php?cliente=<?=$_GET[cliente] ?>&contador="+u.pag5_contador.value);
	}
	
	function resTabla5(datos){
		var obj = eval(datos);
		u.pag5_total.value = obj.total;
		u.pag5_contador.value = obj.contador;
		u.pag5_adelante.value = obj.adelante;
		u.pag5_atras.value = obj.atras;
		tabla1.setJsonData(obj.registros);
		
		if(obj.paginado==1){
			document.getElementById('div_paginado5').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado5').style.visibility = 'hidden';
		}
	}
	function paginacion5(movimiento){
		switch(movimiento){
			case 'primero':
					consultaTexto("resTabla5","reporteProductividad_con.php?cliente=<?=$_GET[cliente] ?>&contador=0");
				break;
			case 'adelante':
				if(u.pag5_adelante.value==1){
					consultaTexto("resTabla5","reporteProductividad_con.php?cliente=<?=$_GET[cliente] ?>&contador="+(parseFloat(u.pag5_contador.value)+1));
				}
				break;
			case 'atras':
				if(u.pag5_atras.value==1){					
					consultaTexto("resTabla5","reporteProductividad_con.php?cliente=<?=$_GET[cliente] ?>&contador="+(parseFloat(u.pag5_contador.value)-1));
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag5_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla5","reporteProductividad_con.php?cliente=<?=$_GET[cliente] ?>&contador="+contador);
				break;
		}
	}
	
</script>

</head>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
			<td align="center" width="220" style="background:#282828; color:#FFFFFF;font-family: tahoma; font-size: 12px; font-weight: bold;">EAD</td>
			<td align="center" width="220" style="background:#282828; color:#FFFFFF;font-family: tahoma; font-size: 12px; font-weight: bold;">RECOLECCION</td>
		 </tr>
		</table>
	</td>
  </tr>
  <tr>
    <td><div style="background-color:#282828">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
		  </div></td>
          </tr>
        </table>
	</div>
	</td>
  </tr>
</table>
</form>
</body>
</html>
