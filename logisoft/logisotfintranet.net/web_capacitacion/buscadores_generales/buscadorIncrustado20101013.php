
<div id="<?=$nombreBuscador?>_fondo" style="width:100%; height:100%; top:0px; left:0px; position:fixed; background:#000; z-index:998; display:none; filter:alpha(opacity=50);">
</div>
<div style="position:absolute; display:none; background-image:url(<?=$raiz?>img/fondo_nuevoBuscar.jpg); left: 15px; width: 810px; top: 131px; height: 361px; z-index:999" id="<?=$nombreBuscador?>">
	<table width="808" border="0" cellpadding="0" cellspacing="0" style="color:#FFF; font-size:12px"><tr>
    <td width="751" height="18" class="FondoTabla" style="font-weight:bold;">&nbsp;&nbsp;&nbsp;BUSCAR CLIENTES</td>
    <td width="45"><a href="#" class="FondoTabla" onclick="<?=$funcionOcultar?>()" style="color:#FFF">Cerrar</a></td>
    </tr></table>
	<table width="790" class="Tablas"  border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193" style="background-color:#FFF">
    <tr>
      <td width="83" class="FondoTabla" style="background-color:#2b6ba9">Nick</td>
      <td width="81" class="FondoTabla" style="background-color:#2b6ba9">R.F.C</td>
      <td width="69" class="FondoTabla" style="background-color:#2b6ba9"># Cliente</td>
      <td width="336" class="FondoTabla" style="background-color:#2b6ba9">Nombre o Partes del nombre</td>
      <td width="75" class="FondoTabla" style="background-color:#2b6ba9">Sucursal</td>
	  <td width="60" class="FondoTabla" style="background-color:#2b6ba9">&nbsp;</td>
	  <td width="70" class="FondoTabla" style="background-color:#2b6ba9">&nbsp;</td>
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
</div>
<script type="text/javascript" src="<?=$raiz?>javascript/funciones_tablas.js"></script>
<link href="<?=$raiz?>recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="<?=$raiz?>FondoTabla.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	.FondoTabla{
		background-color:#2b6ba9;
	}
</style>
<script>
var nav4 = window.Event ? true : false;
var valt = agregar_una_tabla("tablaclientes", "fil_", 1, "Tablas└Tablas", "");
var latabla= "";
var u = document.all;

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
			
		consulta("resultado","<?=$raiz?>buscadores_generales/buscarClienteGen_conguia.php?accion=1"+datosenviados+"&suert="+Math.random());		
	}
}

function ponerOnClick(valor,valordevuelto){
	return	"<a style='cursor:hand;' onclick='<?=$funcion?>("+valordevuelto+");'>"+valor+"</a>";
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
			var nick		= datos.getElementsByTagName('nick').item(m).firstChild.data;
			var rfc			= datos.getElementsByTagName('rfc').item(m).firstChild.data;
			var idcliente	= datos.getElementsByTagName('idcliente').item(m).firstChild.data;
			var nombre		= datos.getElementsByTagName('nombre').item(m).firstChild.data;
			var sucursal	= datos.getElementsByTagName('sucursal').item(m).firstChild.data;
			if(datos.getElementsByTagName('credito').item(m)!=null){
				var credito = "&nbsp;&nbsp;"+datos.getElementsByTagName('credito').item(m).firstChild.data;
			}
			if(datos.getElementsByTagName('convenio').item(m)!=null){
				var convenio = datos.getElementsByTagName('convenio').item(m).firstChild.data;
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

function <?=$funcionMostrar?>(){
	document.getElementById('<?=$nombreBuscador?>_fondo').style.display='';
	document.getElementById('<?=$nombreBuscador?>').style.display='';
	document.getElementById('buscarnombre').focus();
}
function <?=$funcionOcultar?>(){
	document.getElementById('<?=$nombreBuscador?>_fondo').style.display='none';
	document.getElementById('<?=$nombreBuscador?>').style.display='none';
}
</script>
