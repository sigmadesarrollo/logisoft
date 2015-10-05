<html>
	<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
   	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../catalogos/cliente/select.js"></script>
<script type="text/javascript" src="../javascript/ajax.js"></script>
<script type="text/javascript" src="../javascript/funciones_tablas.js"></script>
<script>
var nav4 = window.Event ? true : false;
var valt = agregar_una_tabla("tablaclientes", "fil_", 1, "Tablas└Tablas", "");
var latabla= "";
var u = document.all;
function Numeros(evt){ 
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
	var key = nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 48 && key <= 57));
}

function ObtenerConsulta(e,nombrecaja,valor){
	tecla=(document.all) ? e.keyCode : e.which;
	if(tecla==13){
		var datosenviados = "";
		
		if(document.all.buscarnick.value!=""){
			datosenviados += "&nick="+document.all.buscarnick.value;
		}
		if(document.all.buscarrfc.value!=""){
			datosenviados += "&rfc="+document.all.buscarrfc.value;
		}
		if(document.all.buscarid.value!=""){
			datosenviados += "&id="+document.all.buscarid.value;
		}
		if(document.all.buscarnombre.value!=""){
			datosenviados += "&nombre="+document.all.buscarnombre.value;
		}
		if(document.all.buscarciudad.value!=""){
			datosenviados += "&ciudad="+document.all.buscarciudad.value;
		}
			
		consulta("resultado","buscarClienteGen_conguia.php?accion=1"+datosenviados+"&suert="+Math.random());		
	}
}

function ponerOnClick(valor,valordevuelto){
	return	"<a style='cursor:hand;' onclick='parent.<?=$_GET[funcion]?>("+valordevuelto+"); parent.VentanaModal.cerrar();'>"+valor+"</a>";
}

function resultado(datos){
	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
	if(latabla=="")
		latabla=document.all.txtHint.innerHTML;
		document.all.txtHint.innerHTML=latabla;
		reiniciar_indice(valt);
	if(con>0){
		u.tablaclientes.style.display = "";
		for(m=0;m<con;m++){
			nick		= datos.getElementsByTagName('nick').item(m).firstChild.data;
			rfc			= datos.getElementsByTagName('rfc').item(m).firstChild.data;
			idcliente	= datos.getElementsByTagName('idcliente').item(m).firstChild.data;
			nombre		= datos.getElementsByTagName('nombre').item(m).firstChild.data;
			sucursal	= datos.getElementsByTagName('sucursal').item(m).firstChild.data;
			if(datos.getElementsByTagName('credito').item(m)!=null){
				credito = "&nbsp;&nbsp;"+datos.getElementsByTagName('credito').item(m).firstChild.data;
			}
			if(datos.getElementsByTagName('convenio').item(m)!=null){
				convenio = datos.getElementsByTagName('convenio').item(m).firstChild.data;
			}
			
			insertar_en_tabla(valt,ponerOnClick(nick,idcliente)+"└"+ponerOnClick(rfc,idcliente)+"└"+
			ponerOnClick(idcliente,idcliente)+"└"+ponerOnClick(nombre,idcliente)+"└"+
			ponerOnClick(sucursal,idcliente)+"└"+ponerOnClick(credito,idcliente)+"└"+
			ponerOnClick(convenio,idcliente));
		}	
	}else{
		u.tablaclientes.style.display = "none";
	}
}
</script>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
body {
	margin-left: 1px;
	margin-top: 1px;
	margin-right: 1px;
	margin-bottom: 1px;
}
-->
</style>
</head>
<body>
<!-- 
filas ->> class="FondoTabla"
 -->
  <table width="790" class="Tablas"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="83" class="FondoTabla">Nick</td>
      <td width="81" class="FondoTabla">R.F.C</td>
      <td width="69" class="FondoTabla"># Cliente</td>
      <td width="336" class="FondoTabla">Nombre o Partes del nombre</td>
      <td width="75" class="FondoTabla">Sucursal</td>
	  <td width="60" class="FondoTabla">&nbsp;</td>
	  <td width="70" class="FondoTabla">&nbsp;</td>
    </tr>
    <tr>
      <td class="FondoTabla"><input class="Tablas" name="buscarnick" type="text" id="buscarnick" onKeyPress="ObtenerConsulta(event,this.name,this.value)" value="" size="10" style="border:none; text-transform:uppercase" /></td>
      <td class="FondoTabla"><span class="Tablas">
        <input name="buscarrfc" type="text" id="buscarrfc" value="" class="Tablas" size="12" style="border:none;text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />
      </span></td>
      <td class="FondoTabla" align="center">
        <input name="buscarid" type="text" id="buscarid" value="" class="Tablas" style="border:none;text-transform:uppercase; width:50px;" onKeyPress="return Numeros(event)" onKeyUp="ObtenerConsulta(event,this.name,this.value)" />
      </td>
      <td class="FondoTabla"><span class="Tablas">
        <input name="buscarnombre" type="text" id="buscarnombre" value="" class="Tablas" style="border:none; text-transform:uppercase; width:270px;" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />
      </span></td>
      <td class="FondoTabla">
	  	<input name="buscarciudad" type="text" id="buscarciudad" value="" class="Tablas"  style="border:none;text-transform:uppercase; width:70px;" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />
	  </td>
	  <td  class="FondoTabla">Crédito</td>
	  <td  class="FondoTabla">Convenio</td>
    </tr>
    <tr>
      <td colspan="7">
      <div id="txtHint" style="width:100%; height:300px; overflow: scroll;">
      <table width="762" id="tablaclientes" border="0" align="left" alagregar="" alborrar="" cellpadding="0" cellspacing="2">
      	  <tr>
       		<td width="81" class="Tablas" ></td>
            <td width="81" class="Tablas"></td>
            <td width="68" class="Tablas"></td>
            <td class="Tablas"></td>
            <td width="75" class="Tablas"></td>
			<td width="64" class="Tablas"></td>
			<td width="42" class="Tablas"></td>
		</tr>
      	  <tr id="fil_0">
      	    <td class="Tablas" >&nbsp;</td>
      	    <td class="Tablas">&nbsp;</td>
      	    <td class="Tablas">&nbsp;</td>
      	    <td class="Tablas">&nbsp;</td>
      	    <td class="Tablas">&nbsp;</td>
			<td class="Tablas">&nbsp;</td>
			<td class="Tablas">&nbsp;</td>
   	      </tr>
      </table>
      </div>
      </td>
    </tr>
</table>
  <input name="tipo" type="hidden" value="<?=$_GET['tipo']; ?>">
  
<?
//} ?>
</body>
</html>