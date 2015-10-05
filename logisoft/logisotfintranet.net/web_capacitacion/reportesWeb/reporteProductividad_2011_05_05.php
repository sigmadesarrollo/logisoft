<?	session_start();
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
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css">
<link href="../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css" />
<link href="../javascript/estiloclasetablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/jquery-1.4.2.min.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/DataSet.js"></script>
<script>
	var tabla1 	= new ClaseTabla();
	var	u		= document.all;
	var pag1_cantidadporpagina = 10;
	var DS1 = new DataSet();
	var mens = new ClaseMensajes();
	var desc = new Array(<?php echo $desc; ?>);
	
	mens.iniciar('../javascript');
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
	}
	
	function obtenerDetalle(){
		if(u.idcliente.value==""){
			mens.show("A","Proporcione al cliente","ATENCION","idcliente");
			return false;
		}
		
		if(u.checktodas.checked){
			var mas = "&sucursal=0";
		}else{
			var mas = "&sucursal="+u.sucursal_hidden.value;
		}
		consultaTexto("resTabla5","reporteProductividad_con.php?accion=1&cliente="+u.idcliente.value+mas+"&mat"+Math.random());
	}
	
	function resTabla5(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		DS1.setJsonData(obj)
	}
	
	function bloquearSucursal(valor){
		u.sucursal.readOnly=valor;
		u.sucursal.style.backgroundColor=(valor)?"#FFFF99":"";
		u.sucursal.value = "";
		u.sucursal_hidden.value = "";
		document.getElementById('imagenBuscarSucursal').style.display = (valor)?'none':'';
	}
	
	function traerAlcliente(id,nombre){
		document.all.idcliente.value = id;
		document.getElementById('nombreCliente').innerHTML = nombre;
		ocultarBuscador();
	}
	
	function buscarCliente(valor){
		consultaTexto("resCliente","reporteProductividad_con.php?accion=2&cliente="+valor+"&mat="+Math.random());
	}
	
	function resCliente(valor){
		var obj = eval(valor);
		document.all.idcliente.value = obj.id;
		document.getElementById('nombreCliente').innerHTML = obj.ncliente;
	}
	
	function limpiar(){
		document.all.idcliente.value = "";
		document.getElementById('nombreCliente').innerHTML = "";
		bloquearSucursal(true);
	}
</script>

</head>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="603" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Reporte de Productividad</td>
    </tr>
    <tr>
    	<td>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
    	<td>
        <table border="0" cellpadding="0" cellspacing="0">
        	  <tr class="Tablas">
        	    <td width="56">Sucursal:
        	      <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="" /></td>
        	    <td width="22"><input type="checkbox" name="checktodas" checked="checked" onclick="bloquearSucursal(this.checked);" /></td>
        	    <td width="48">Todas</td>
        	    <td width="251"><input name="sucursal" type="text" id="sucursal" style="width:200px; background:#FFFF99" readonly="readonly" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.sucursal_hidden.value = this.codigo; if(u.sucursal_hidden.value=='undefined'){ this.codigo=''; u.sucursal_hidden.value='';}}" onblur="document.all.sucursal_hidden.value = this.codigo; if(u.sucursal_hidden.value=='undefined'){ this.codigo=''; u.sucursal_hidden.value=''; }" />
        	      <img src="../img/Buscar_24.gif" id="imagenBuscarSucursal" width="24" height="23" align="absbottom" style="cursor:pointer; display:none" onclick="mens.show('../buscadores_generales/buscarsucursal.php', 600, 450, 'ventana', 'Busqueda');" /></td>
        	    <td width="50">&nbsp;</td>
        	    <td width="117">&nbsp;</td>
        	    <td width="82">&nbsp;</td>
      	    </tr>
      	  </table>
          <table border="0" cellpadding="0" cellspacing="0">
        	  <tr class="Tablas">
        	    <td width="55">Cliente:</td>
        	    <td width="21">&nbsp;</td>
        	    <td width="47"><input type="text" style="width:40px" name="idcliente" onkeypress="if(event.keyCode==13) {buscarCliente(this.value)}" /></td>
        	    <td width="41">
      <img src="../img/Buscar_24.gif" id="imagenBuscarSucursal" width="24" height="23" align="absbottom" 
      style="cursor:pointer;" onclick="mostrarBuscador(); tipoClienteBuscado = 'R';" />
      </td>
        	    <td width="318" id="nombreCliente">&nbsp;</td>
        	    <td width="37">&nbsp;</td>
        	    <td width="81"><img src="../img/Boton_Generar.gif" onclick="obtenerDetalle()" /></td>
      	    </tr>
      	  </table>
        </td>
    </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
			<td align="center" width="220" style="font-family: tahoma; font-size: 12px; font-weight: bold;">EAD</td>
			<td align="center" width="220" style="font-family: tahoma; font-size: 12px; font-weight: bold;">RECOLECCION</td>
		 </tr>
		</table>
	</td>
  </tr>
  <tr>
    <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">              
            </table></td>
          </tr>
          <tr>
            <td id="detalle_pag" style="border:1px solid #000"></td>
          </tr>
        </table>
	</div>
	</td>
  </tr>
  <tr>
    <td align="right"><img style="cursor:pointer" src="../img/Boton_Nuevo.gif" onclick="limpiar()" /></td>
  </tr>
</table>
</td>
</tr>
</table>
</form>
<?
	$raiz = "../";
	$funcion = "traerAlcliente";
	$nombreBuscador = "buscadorClientes";
	$funcionMostrar = "mostrarBuscador";
	$funcionOcultar = "ocultarBuscador";
	include("../buscadores_generales/buscadorIncrustado.php");
?>
</body>
</html>
