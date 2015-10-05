<?
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/ajax.js"></script>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../cliente/Tablas.css" rel="stylesheet" type="text/css" />
<script>
	var tabla1 = new ClaseTabla();
	var mens = new ClaseMensajes();
	var u = document.all;
	var pag1_cantidadporpagina = 30;
	mens.iniciar("../../javascript");
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[						
			{nombre:"CODIGO POSTAL", medida:90, alineacion:"left", datos:"codigopostal"},
			{nombre:"COLONIA", medida:250, alineacion:"left",  datos:"colonia"},
			{nombre:"CIUDAD", medida:100, alineacion:"left",  datos:"poblacion"},
			{nombre:"MUNICIPIO", medida:100, alineacion:"left", datos:"municipio"},			
			{nombre:"ESTADO", medida:100, alineacion:"left", datos:"estado"}
		],
		filasInicial:20,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});

	window.onload = function(){
		tabla1.create();
		u.colonia.focus();
	}

	function obtenerColonia(){
		if(u.colonia.value == ""){
			mens.show("A","Debe capturar Colonia","¡Atención!","colonia");
			return false;
		}
		
		consultaTexto("resTabla1","consultaColonia_con.php?accion=1&colonia="+u.colonia.value
		+"&ciudad="+u.ciudad.value+"&contador="+u.pag1_contador.value+"&val="+Math.random());
	}
	
	function resTabla1(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;
		
		if(obj.registros.length==0){
			mens.show("A","No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla1.clear();
		}else{			
			tabla1.setJsonData(obj.registros);
		}
		if(obj.paginado==1){
			document.getElementById('paginado').style.visibility = 'visible';
		}else{
			document.getElementById('paginado').style.visibility = 'hidden';
		}
	}
	
	function paginacion(movimiento){
		switch(movimiento){
			case 'primero':				
				consultaTexto("resTabla1","consultaColonia_con.php?accion=1&contador=0&colonia="+u.colonia.value
				+"&ciudad="+u.ciudad.value+"&val="+Math.random());				
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","consultaColonia_con.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)+1)
					+"&colonia="+u.colonia.value+"&ciudad="+u.ciudad.value+"&val="+Math.random());				
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","consultaColonia_con.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)-1)
					+"&colonia="+u.colonia.value+"&ciudad="+u.ciudad.value+"&val="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla1","consultaColonia_con.php?accion=1&contador="+contador
				+"&colonia="+u.colonia.value+"&ciudad="+u.ciudad.value+"&val="+Math.random());
				break;
		}
	}
	
	function limpiar(){
		tabla1.clear();
		u.colonia.value="";
		u.ciudad.value = "";
		u.colonia.focus();
	}
	
</script>
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Consulta de Colonia </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="10%">Colonia:</td>
              <td width="27%"><label>
                <input name="colonia" type="text" id="colonia" class="Tablas" onkeypress="if(event.keyCode==13){document.getElementById('ciudad').focus()}" />
              </label></td>
              <td width="11%">Ciudad:</td>
              <td width="20%"><input name="ciudad" type="text" id="ciudad" class="Tablas" onkeypress="if(event.keyCode==13){obtenerColonia()}" /></td>
              <td width="28%"><div class="ebtn_Generar" onclick="obtenerColonia()"></div></td>
              <td width="4%">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">
          </table></td>
        </tr>
        <tr>
          <td><div id="paginado" align="center" style="visibility:hidden">              
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion('ultimo')" />
		  <input type="hidden" name="pag1_total" />
          <input type="hidden" name="pag1_contador" value="0" />
          <input type="hidden" name="pag1_adelante" value="" />
          <input type="hidden" name="pag1_atras" value="" />
          </div></td>
        </tr>
		<tr>
			<td align="right"><div class="ebtn_nuevo" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')"</div></td>
		</tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
