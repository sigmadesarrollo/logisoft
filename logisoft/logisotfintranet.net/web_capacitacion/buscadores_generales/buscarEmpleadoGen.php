<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
		var	buscarid		=	(u.buscarid.value!="")?"&numempleado="+u.buscarid.value:"";
		var buscarrfc		=	(u.buscarrfc.value!="")?"&rfc="+u.buscarrfc.value:"";		
		var	buscarnombre	=	(u.buscarnombre.value!="")?"&nombre="+u.buscarnombre.value:"";
		var	buscarpaterno	=	(u.buscarpaterno.value!="")?"&paterno="+u.buscarpaterno.value:"";
		var	buscarmaterno	=	(u.buscarmaterno.value!="")?"&materno="+u.buscarmaterno.value:"";
		var	buscarsucursal	=	(u.buscarsucursal.value!="")?"&sucursal="+u.buscarsucursal.value:"";
		
		var valor = buscarrfc + buscarid + buscarnombre + buscarpaterno + buscarmaterno + buscarsucursal;
		consulta("resultado","buscarEmpleadoGen_con.php?accion=1"+valor+"&suert="+Math.random());		
	}		
}

function ponerOnClick(valor,valordevuelto){
	var caja = "<? if($_GET[caja]!=""){echo ','.$_GET[caja];}else{ echo '';} ?>";
	return	"<a style='cursor:hand;' onclick='parent.<?=$_GET[funcion]?>("+valordevuelto+caja+"); if(parent.mens!=null && parent.mens.getMensaje()>-1){ parent.mens.cerrar();}else{parent.VentanaModal.cerrar();}'>"+valor+"</a>";
}

function resultado(datos){	
	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
	if(latabla=="")
	latabla=document.all.txtHint.innerHTML;
	document.all.txtHint.innerHTML=latabla;
	reiniciar_indice(valt);
	if(con>0){
		for(m=0;m<con;m++){			
			id			= datos.getElementsByTagName('id').item(m).firstChild.data;
			rfc			= datos.getElementsByTagName('rfc').item(m).firstChild.data;
			idcliente	= datos.getElementsByTagName('numempleado').item(m).firstChild.data;
			nombre		= datos.getElementsByTagName('nombre').item(m).firstChild.data;
			paterno		= datos.getElementsByTagName('paterno').item(m).firstChild.data;
			materno		= datos.getElementsByTagName('materno').item(m).firstChild.data;

			insertar_en_tabla(valt,ponerOnClick(idcliente,id)+"└"+
			ponerOnClick(rfc,id)+"└"+ponerOnClick(nombre,id)+"└"+
			ponerOnClick(paterno,id)+"└"+ponerOnClick(materno,id));
		}	
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
  <table width="600" class="Tablas"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla"># Empleado</td>
      <td class="FondoTabla">R.F.C</td>
      <td class="FondoTabla">Nombre</td>
      <td class="FondoTabla">Paterno</td>
      <td class="FondoTabla">Materno</td>
      <td class="FondoTabla">Sucursal</td>
    </tr>
    <tr>
      <td width="16%" class="FondoTabla"><input name="buscarid" type="text" id="buscarid" value="" class="Tablas" size="16" style="border:none;text-transform:uppercase" onKeyUp="ObtenerConsulta(event,this.name,this.value)" /></td>
      <td width="16%" class="FondoTabla"><span class="Tablas">
        <input name="buscarrfc" type="text" id="buscarrfc" value="" class="Tablas" size="12" style="border:none;text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />
      </span></td>
      <td width="18%" class="FondoTabla"><span class="Tablas">
        <input name="buscarnombre" type="text" id="buscarnombre" value="" class="Tablas" size="16" style="border:none; text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />
      </span></td>
      <td width="21%" class="FondoTabla"><span class="Tablas">
        <input name="buscarpaterno" type="text" id="buscarpaterno" value="" class="Tablas" size="15" style="border:none ;text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />
      </span></td>
      <td width="14%" class="FondoTabla"><span class="Tablas">
        <input name="buscarmaterno" type="text" id="buscarmaterno" value="" class="Tablas" size="15" style="border:none;text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />
      </span></td>
	  
      <td width="15%" class="FondoTabla"><span class="Tablas">
        <input name="buscarsucursal" type="text" id="buscarsucursal" value="" class="Tablas" size="15" style="border:none;text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />
      </span></td>
    </tr>
    <tr>
      <td colspan="6" class="Tablas">
      <div id="txtHint" style="width:100%; height:300px; overflow: scroll;">
      <table width="572" border="0" align="left" cellpadding="0" cellspacing="0" class="Tablas" id="tablaclientes" alagregar="" alborrar="">
      	  <tr>
       		<td width="87" class="Tablas" ></td>
            <td width="85" class="Tablas"></td>
            <td width="113" class="Tablas"></td>
            <td width="104" class="Tablas"></td>
            <td width="183" class="Tablas"></td>
		</tr>
      	  <tr id="fil_0">
      	    <td class="Tablas" >&nbsp;</td>
      	    <td class="Tablas">&nbsp;</td>
      	    <td class="Tablas">&nbsp;</td>
      	    <td class="Tablas">&nbsp;</td>
      	    <td class="Tablas">&nbsp;</td>
   	      </tr>
      </table>
      </div>      </td>
    </tr>
</table>  
  <input name="tipo" type="hidden" value="<?=$_GET['tipo']; ?>">
  
<?
//} ?>
</body>
</html>