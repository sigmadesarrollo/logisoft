<? 
	session_start(); 
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/DataSet.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/jquery-1.4.2.min.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" />
<link href="../javascript/estiloclasetablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<script>
	
	var tabla1 = new ClaseTabla();
	var DS1 = new DataSet();
	var u = document.all;
	var mens = new ClaseMensajes();
	
	mens.iniciar('../javascript');
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"EMPLEADO", medida:180, alineacion:"left",  datos:"empleado"},
			{nombre:"TEMA", medida:180, alineacion:"left", datos:"tema"},
			{nombre:"FECHA", medida:100, alineacion:"center",  datos:"fecha"},
			{nombre:"HORA", medida:100, alineacion:"center", datos:"hora"}
		],
		filasInicial:30,
		alto:250,
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
			return parseInt(a.empleado.toString().substring(0,12)+a.empleado.toString().charCodeAt(12))-
			parseInt(b.empleado.toString().substring(0,12)+b.empleado.toString().charCodeAt(12))
		}
	});
	
	window.onload = function(){
		tabla1.create();
	}
	
	function bloquearEmpleado(valor){
		u.idempleado.readOnly=valor;
		u.idempleado.style.backgroundColor=(valor)?"#FFFF99":"";
		u.idempleado.value = "";
		document.getElementById('nombreEmpleado').innerHTML = "";
		document.getElementById('imagenBuscarEmpleado').style.display = (valor)?'none':'';
	}
	
	function bloquearTema(valor){
		u.idtema.readOnly=valor;
		u.idtema.style.backgroundColor=(valor)?"#FFFF99":"";
		u.idtema.value = "";
		document.getElementById('nombreTema').innerHTML = "";
		document.getElementById('imagenBuscarTema').style.display = (valor)?'none':'';
	}
	
	function devolverEmpleado(valor,nombre){
		$("input[name='idempleado']").val(valor);
		$("#nombreEmpleado").html(nombre);
	}
	
	function devolverTema(valor,nombre){
		$("input[name='idtema']").val(valor);
		$("#nombreTema").html(nombre);
	}
	
	function obtenerDetalle(){
		var datos = $("form").serialize();
		crearLoading();
		$.ajax({
			type: "POST",
			url: "FirmasEmpleado_con.php",
			data: "accion=1&"+datos,
			success: function(msg){
				ocultarLoading();
				try{
					var obj = eval(msg)
				}catch(e){
					mens.show('A',msg);
				}
				DS1.setJsonData(obj);
			}
		});
	}
</script>
<title>Documento sin t&iacute;tulo</title>
</head>

<body>


  <table width="630" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla" >FIRMAS EMPLEADOS</td>
    </tr>

    <tr>
      <td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td>
            <form id="form1" name="form1" method="post" action="">
            <table border="0" cellpadding="0" cellspacing="0">
        	  <tr>
        	    <td width="56">Empleado        	      </td>
        	    <td width="22"><input type="checkbox" name="todosempleados" checked="checked" onclick="bloquearEmpleado(this.checked);" /></td>
        	    <td width="48">Todos</td>
        	    <td width="65"><input type="text" name="idempleado" style="width:40px" /></td>
        	    <td width="55">
                <img src="../img/Buscar_24.gif" id="imagenBuscarEmpleado" width="24" height="23" align="absbottom" style="cursor:pointer; display:none" onclick="mens.popup('../buscadores_generales/buscarEmpleadoGen.php?funcion=devolverEmpleado', 620, 350, 'ventana', 'Busqueda');" />
                </td>
        	    <td width="298" id="nombreEmpleado">&nbsp;</td>
        	    <td width="82">&nbsp;</td>
      	    </tr>
      	  </table>
          <table border="0" cellpadding="0" cellspacing="0">
        	  <tr>
        	    <td width="56">Temas        	      </td>
        	    <td width="22"><input type="checkbox" name="todostemas" checked="checked" onclick="bloquearTema(this.checked);" /></td>
        	    <td width="48">Todos</td>
        	    <td width="65"><input type="text" name="idtema" style="width:40px" /></td>
        	    <td width="55"><img src="../img/Buscar_24.gif" id="imagenBuscarTema" width="24" height="23" align="absbottom" style="cursor:pointer; display:none" onclick="mens.popup('../buscadores_generales/buscarTemasGen.php?funcion=devolverTema', 600, 450, 'ventana', 'Busqueda');" /></td>
        	    <td width="298" id="nombreTema">&nbsp;</td>
        	    <td width="82"><div class="ebtn_Generar" onclick="obtenerDetalle()" ></div></td>
      	    </tr>
      	  </table>
            </form>
          </td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td id="detalle_pag" style="border:1px #666 solid">
          	
		  </td>
        </tr>
        <tr>
    	<td align="right">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
</body>
</html>
