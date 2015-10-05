<?	session_start();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" />
<script src="../javascript/ClaseMensajes.js"></script>
<script>
	var tabla1 		= new ClaseTabla();
	var	u			= document.all;	
	var pag1_cantidadporpagina = 30;
	var mens 		= new ClaseMensajes();
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"FOLIO", medida:70, alineacion:"center",  datos:"folio"},
			<? if($_SESSION[IDSUCURSAL]==1){?>
			{nombre:"SUCURSAL", medida:80, alineacion:"center",  datos:"sucursal"},
			<? } ?>
			{nombre:"PERSONA REQUIERE ENTREGA", medida:(('<?=$_SESSION[IDSUCURSAL]?>'==1)?250:330), alineacion:"left", datos:"persona"},
			{nombre:"FECHA ESPECIAL", medida:100, alineacion:"center",  datos:"fechaespecial"},
			{nombre:"GUIA", medida:80, alineacion:"left", datos:"guia"},
			{nombre:"ESTADO", medida:100, alineacion:"center",  datos:"estadoguia"},
			{nombre:"REGISTRO", medida:150, alineacion:"center",  datos:"registro"},
			{nombre:"OBSERVACIONES", medida:200, alineacion:"left", datos:"observaciones"}			
		],
		filasInicial:20,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});		

	window.onload = function(){
		tabla1.create();
	}	
	
	function obtenerDetalle(){		
		consultaTexto("resTabla1","consultasAlertas.php?accion=16&contador="+u.pag1_contador.value
		+"&inicio="+u.inicio.value+"&fin="+u.fin.value+"&s="+Math.random());
	}
	
	function resTabla1(datos){
		try{
			var obj = eval(convertirValoresJson(datos));
		}catch(e){
			mens.show("A",datos,"");
		}
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;
		
		tabla1.setJsonData(obj.registros);
		
		if(obj.paginado==1){
			document.getElementById('paginado').style.visibility = 'visible';
		}else{
			document.getElementById('paginado').style.visibility = 'hidden';
		}
	}
	
	function paginacion(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla1","consultasAlertas.php?accion=16&contador=0&s="+"&inicio="+u.inicio.value+"&fin="+u.fin.value+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","consultasAlertas.php?accion=16&contador="+(parseFloat(u.pag1_contador.value)+1)					
					+"&inicio="+u.inicio.value+"&fin="+u.fin.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","consultasAlertas.php?accion=16&contador="+(parseFloat(u.pag1_contador.value)-1)					
					+"&inicio="+u.inicio.value+"&fin="+u.fin.value+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla1","consultasAlertas.php?accion=16&contador="+contador
				+"&inicio="+u.inicio.value+"&fin="+u.fin.value+"&s="+Math.random());
				break;
		}
	}
	
	
	function imprimirDetalle(){
		window.open("entregasEspeciales_excel.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>"+"&inicio="+u.inicio.value+"&fin="+u.fin.value);
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="800" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">ENTREGAS ESPECIALES EAD </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      	  <tr>
          	<td>
            	<table width="780" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="17">&nbsp;</td>
                        <td width="100" align="right">
                        	<input name="inicio" type="text" class="Tablas" id="inicio" readonly="readonly" 
                            style="width:65px; background-color:#FFFF99; border:1px #CCC solid;" value="<?=date('d/m/Y') ?>" />
                            &nbsp;
                        </td>
                      <td width="35"><img src="../img/calendario.gif" id="calendarioInicio" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer;" title="Calendario" onclick="displayCalendar(document.all.inicio,'dd/mm/yyyy',this)" /></td>
                        <td width="34" align="center">Y</td>
                        <td width="104"align="right">
                        	<input name="fin" type="text" class="Tablas" id="fin" readonly="readonly" 
                            style="width:65px; background-color:#FFFF99; border:1px #CCC solid;" value="<?=date('d/m/Y') ?>" />
                            &nbsp;
                        </td>
                        <td width="35">
                        <img src="../img/calendario.gif" id="calendarioFin" alt="Alta" width="20" height="20" 
                        align="absbottom" style="cursor:pointer;" title="Calendario" 
                        onclick="displayCalendar(document.all.fin,'dd/mm/yyyy',this)" />
                        </td>
                        <td width="368">&nbsp;</td>
                        <td width="87" align="right"><div class="ebtn_Generar" onclick="obtenerDetalle()"></div></td>
                    </tr>
                </table>
            </td>
          </tr>
          <tr>
            <td><div id="txtDir" style=" height:280px; width:800px; overflow:auto" align="left">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">
                </table>
            </div></td>
          </tr>
          <tr>
            <td><div id="paginado" align="center" style="visibility:hidden"> <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion('primero')" /> <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion('atras')" /> <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion('adelante')" /> <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion('ultimo')" />
                    <input type="hidden" name="pag1_total" />
                    <input type="hidden" name="pag1_contador" value="0" />
                    <input type="hidden" name="pag1_adelante" value="" />
                    <input type="hidden" name="pag1_atras" value="" />
            </div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
      </table>
      
    <table align="right">
        <tr>
            <td width="106" align="center"><img src="../img/Boton_Imprimir.gif" onclick="imprimirDetalle()" style="cursor:pointer" /></td>
        </tr>
    </table>
                </td>
    </tr>
  </table>
</form>
</body>
</html>
