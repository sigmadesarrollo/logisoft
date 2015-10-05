<? 
	session_start(); 
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');

	$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion,':',cs.id) AS descripcion
	FROM catalogosucursal cs ORDER BY cs.descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
		}
		$desc = substr($desc, 0, -1);
	}
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
			{nombre:"GUIA", medida:85, alineacion:"left",  datos:"guia"},
			{nombre:"ORIGEN", medida:50, alineacion:"center", datos:"origen"},
			{nombre:"DESTINO", medida:50, alineacion:"center",  datos:"destino"},
			{nombre:"EMISION", medida:65, alineacion:"center", datos:"emision"},
			{nombre:"CANCELACION", medida:65, alineacion:"center", datos:"cancelacion"},
			{nombre:"IMPORTE", medida:80, tipo:"moneda", alineacion:"right", datos:"importe"},
			{nombre:"TIPOFLETE", medida:80, alineacion:"right", datos:"tipoflete"},
			{nombre:"CANCELO", medida:50, alineacion:"center", datos:"cancelo"},
			{nombre:"AFECTA", medida:50, alineacion:"center", datos:"afecta"},
			{nombre:"EMPLEADO CANCELO", medida:120, alineacion:"left", datos:"empleado"}
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
			return parseInt(a.guia.toString().substring(0,12)+a.guia.toString().charCodeAt(12))-
			parseInt(b.guia.toString().substring(0,12)+b.guia.toString().charCodeAt(12))
		}
	});
	
	window.onload = function(){
		tabla1.create();
	}
	
	function bloquearSucursal(valor){
		u.sucursal.readOnly=valor;
		u.sucursal.style.backgroundColor=(valor)?"#FFFF99":"";
		u.sucursal.value = "";
		u.sucursal_hidden.value = "";
		document.getElementById('imagenBuscarSucursal').style.display = (valor)?'none':'';
	}
	
	function obtenerDetalle(){
		var datos = $("form").serialize();
		crearLoading();
		$.ajax({
			type: "POST",
			url: "guiasceladas_con.php",
			data: "accion=1&"+datos,
			success: function(msg){
				ocultarLoading();
				try{
					var obj = eval(msg)
				}catch(e){
					mens.show('A',msg);
				}
				DS1.setJsonData(obj.registros);
				$("#totalguias").val(obj.total);
				$("#totalimportes").val("$ "+obj.importes);
			}
		});
	}
		
	function imprimirDetalle(){
		mens.popup('../buscadores_generales/impresionGeneral.php?funcion=resImprimir', 290, 150, 'ventana','Busqueda');
	}
	
	function resImprimir(valor){
		var laUrl = document.URL;
			
			if(laUrl.indexOf('pmmintranet.net')>-1){
				var direccion = laUrl.substr(0,
					laUrl.indexOf('pmmintranet.net')+15+
					((laUrl.indexOf('web_pruebas')>-1)?13:((laUrl.indexOf('web_capacitacion')>-1)?18:((laUrl.indexOf('web_capacitacionPruebas')>-1)?25:1)))
				);
			}else{
				var direccion = laUrl.substr(0,
					laUrl.indexOf('pmmintranet.com')+15+
					((laUrl.indexOf('web_pruebas')>-1)?13:((laUrl.indexOf('web_capacitacion')>-1)?18:((laUrl.indexOf('web_capacitacionPruebas')>-1)?25:1)))
				);
			}
			var datos = $("form").serialize();
		if(valor.indexOf("PDF")>-1){
			window.open(direccion+"tcpdf/reportes/guiasceladas_pdf.php?"+datos,"xReporte");
		}else if(valor.indexOf("EXCEL")>-1){	
			window.open(direccion+"reportesWeb/guiasceladas_excel.php?"+datos,null,"width=50 height=50 top=3000 left=3000");
		}
	}
	var desc = new Array(<?php echo $desc; ?>);
</script>
<title>Documento sin t&iacute;tulo</title>
</head>

<body>


  <table width="630" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla" >GUIAS CANCELADAS</td>
    </tr>

    <tr>
      <td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td>
            <form id="form1" name="form1" method="post" action="">
            <table border="0" cellpadding="0" cellspacing="0">
        	  <tr>
        	    <td width="56">Sucursal
        	      <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="" /></td>
        	    <td width="22"><input type="checkbox" name="checktodas" checked="checked" onclick="bloquearSucursal(this.checked);" /></td>
        	    <td width="48">Todas</td>
        	    <td width="251"><input name="sucursal" type="text" id="sucursal" style="width:200px; background:#FFFF99" readonly="readonly" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.sucursal_hidden.value = this.codigo; if(u.sucursal_hidden.value=='undefined'){ this.codigo=''; u.sucursal_hidden.value='';}}" onblur="document.all.sucursal_hidden.value = this.codigo; if(u.sucursal_hidden.value=='undefined'){ this.codigo=''; u.sucursal_hidden.value=''; }" />
        	      <img src="../img/Buscar_24.gif" id="imagenBuscarSucursal" width="24" height="23" align="absbottom" style="cursor:pointer; display:none" onclick="abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 600, 450, 'ventana', 'Busqueda');" /></td>
        	    <td width="50">&nbsp;QUE&nbsp;</td>
        	    <td width="117"><select name="sucursalmovio">
        	      <option value="0" selected="selected">GENERÓ</option>
        	      <option value="1">CANCELÓ</option>
      	      </select></td>
        	    <td width="82"><div class="ebtn_Generar" onclick="obtenerDetalle()" ></div></td>
      	    </tr>
      	  </table>
       	  <table width="624" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="124">
                        	<select name="tipofecha" id="tipofecha">
                            	<option value="0" selected="selected">EMISIÓN EN</option>
                                <option value="1">CANCELACIÓN EN</option>
                        	</select>
                        </td>
                        <td width="107" align="right">
                        	<input name="inicio" type="text" class="Tablas" id="inicio" readonly="readonly" 
                            style="width:65px; background-color:#FFFF99; border:1px #CCC solid;" value="<?=date('d/m/Y') ?>" />
                            &nbsp;
                        </td>
                      <td width="27"><img src="../img/calendario.gif" id="calendarioInicio" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer;" title="Calendario" onclick="displayCalendar(document.all.inicio,'dd/mm/yyyy',this)" /></td>
                        <td width="42" align="center">Y</td>
                        <td width="107"align="right">
                        	<input name="fin" type="text" class="Tablas" id="fin" readonly="readonly" 
                            style="width:65px; background-color:#FFFF99; border:1px #CCC solid;" value="<?=date('d/m/Y') ?>" />
                            &nbsp;
                        </td>
                        <td width="217">
                        <img src="../img/calendario.gif" id="calendarioFin" alt="Alta" width="20" height="20" 
                        align="absbottom" style="cursor:pointer;" title="Calendario" 
                        onclick="displayCalendar(document.all.fin,'dd/mm/yyyy',this)" />
                        </td>
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
          <td>
          	<table width="277" align="right">
            	<tr>
                    <td>Guias</td>
                    <td><input type="text" name="totalguias" id="totalguias" style="width:80px; text-align:right" readonly="readonly" /></td>
                	<td>Importe</td>
                    <td><input type="text" name="totalimportes" id="totalimportes" style="width:80px; text-align:right" readonly="readonly" /></td>
               	</tr>
          	</table>
          </td>
        </tr>
        <tr>
          <td id="detalle_pag" style="border:1px #666 solid">
          	
		  </td>
        </tr>
        <tr>
    	<td align="right">
                <table>
                    <tr>
                        <td width="106" align="center"><img src="../img/Boton_Imprimir.gif" onclick="imprimirDetalle()" /></td>
                    </tr>
                </table>
            </td>
        </tr>
      </table></td>
    </tr>
  </table>
</body>
</html>
