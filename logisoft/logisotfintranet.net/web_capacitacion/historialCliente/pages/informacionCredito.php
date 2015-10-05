<?	session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="../../javascript/ajax.js"></script>
<script>
	var u = document.all;
	var combo1 = "<select name='sltsucursales' id='sltsucursales' style='width:210px;font-size:9px;font-weight: bold;'>";
	window.onload = function(){
		consultaTexto("mostrarCliente","../../catalogos/cliente/consultaCredito_con.php?accion=1&tipo=1&cliente=<?=$_GET[cliente]?>&val="+Math.random());
	}
	
	function mostrarCliente(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.foliocredito.value	= obj[0].foliocredito;
			u.activado.checked		= ((obj[0].activado=="SI")?true:false);
			u.clasificacioncliente.value = obj[0].clasificacioncliente;
			u.saldo.value			= '$ '+numcredvar(obj[0].saldo);
			u.disponible.value		= ((obj[0].disponible<"0")?0:'$ '+numcredvar(obj[0].disponible.toString()));
			u.ventames.value		= obj[0].ventames;
			u.limitecredito.value	= '$ '+numcredvar(obj[0].limitecredito);
			u.diacredito.value		= obj[0].diascredito;
			u.diapago.value			= obj[0].diapago;
			u.diarevision.value		= obj[0].diarevision;
			if(u.foliocredito.value!="" || u.foliocredito.value!="0"){
				consulta("mostrarSucursales","../../catalogos/cliente/consultasClientes.php?accion=4&cliente=<?=$_GET[cliente] ?>&credito="+u.foliocredito.value);
			}
		}
	}
	
	function mostrarSucursales(datos){
		if(datos.getElementsByTagName('total').item(0).firstChild.data>=1){
				u.celsuc.innerHTML = combo1;
			var combo = document.all.sltsucursales;		
				combo.options.length = null;
				uOpcion = document.createElement("OPTION");
				uOpcion.value=0;
				uOpcion.text="SUC. EN LAS QUE APLICA CREDITO";
				combo.add(uOpcion);
			var total =datos.getElementsByTagName('total').item(0).firstChild.data;
			for(i=0;i<total;i++){	
				uOpcion = document.createElement("OPTION");
				uOpcion.value=datos.getElementsByTagName('sucursal').item(i).firstChild.data;
				uOpcion.text=datos.getElementsByTagName('sucursal').item(i).firstChild.data;
				combo.add(uOpcion);
			}			
		}
	}
	
	function numcredvar(cad){
		var flag = false; 
		if(cad.indexOf('.') == cad.length - 1) flag = true; 
		var num = cad.split(',').join(''); 
		cad = Number(num).toLocaleString(); 
		if(flag) cad += '.'; 
		return cad;
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/reseter.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<table width="610" align="center" cellpadding="0" cellspacing="0" class="datos-cliente-letra">
  <tr>
    <td height="109"><table width="100%" border="0" id="credito" cellpadding="1" cellspacing="0" >
      <tr><td>&nbsp;</td></tr>
	  <tr>
	  	<td>&nbsp;</td>
        <td ><input name="activado" type="checkbox" id="activado" style="width:13px" value="SI" disabled="disabled"/>Activado</td>
        <td width="131">&nbsp;</td>
        <td width="21">&nbsp;</td>
        <td>&nbsp;</td>
        <td width="225">&nbsp;</td>
      </tr>
	  <tr><td>&nbsp;</td></tr>
      <tr>
	  	<td>&nbsp;</td>
        <td >Folio Cr&eacute;dito:</td>
        <td colspan="2" ><input name="foliocredito" type="text" class="text2" id="foliocredito" value="<?=$f->foliocredito ?>"  readonly="readonly" /></td>
        <td >Clasificaci&oacute;n:</td>
        <td> <select name="clasificacioncliente" id="clasificacioncliente"  style="width:123px; font-size:9px; text-transform:uppercase;font-weight: bold;">
                      <option value="SELECCIONAR" selected="selected"  >Seleccionar</option>
                      <option value="MALO" <? if($f->clasificacioncliente=="MALO"){echo 'selected';} ?>>MALO</option>
                      <option value="BUENO" <? if($f->clasificacioncliente=="BUENO"){echo 'selected';} ?>>BUENO</option>
                      <option value="REGULAR" <? if($f->clasificacioncliente=="REGULAR"){echo 'selected';} ?>>REGULAR</option>
                      <option value="EXCELENTE" <? if($f->clasificacioncliente=="EXCELENTE"){echo 'selected';} ?>>EXCELENTE</option>
                    </select> </td>
      </tr>
      <tr>
	  	<td>&nbsp;</td>
        <td width="108" >Saldo:</td>
        <td colspan="2" ><input name="saldo" type="text" class="text2" id="saldo" value="<?=$f->saldo ?>" readonly="readonly" /></td>
        <td width="113" >Disponible:</td>
        <td ><input name="disponible" type="text" class="text2" id="disponible" value="<?=$f->disponible ?>" readonly="readonly" /></td>
      </tr>
      <tr>
	  	<td>&nbsp;</td>
        <td >Limite Credito: </td>
        <td colspan="2" ><input name="limitecredito" type="text" class="text2" id="limitecredito" value="<?=$f->limitecredito ?>" readonly="readonly" /></td>
        <td >Ventas Mes: </td>
        <td ><input name="ventames" type="text" class="text2" id="ventames" value="<?=$f->ventames ?>"  readonly="readonly" /></td>
      </tr>
      <tr>
	  	<td>&nbsp;</td>
        <td >D&iacute;as Cr&eacute;dito:</td>
        <td colspan="2" ><input name="diacredito" type="text" class="text2" id="diacredito" value="<?=$f->diacredito ?>" readonly="readonly" /></td>
        <td  >D&iacute;as Revisi&oacute;n:</td>
        <td  ><input name="diarevision" type="text" class="text2" id="diarevision" value="<?=$f->diasrevision ?>" readonly="readonly" /></td>
      </tr>
      <tr>
	  	<td>&nbsp;</td>
        <td >D&iacute;as Pago:</td>
        <td ><input name="diapago" type="text" class="text2" id="diapago" value="<?=$f->diapago ?>" readonly="readonly" /></td>
        <td >&nbsp;</td>
        <td  >Sucursales Cred:</td>
        <td  id="celsuc"><select name="sltsucursales" id="sltsucursales" style="width:210px; font-size:9px;font-weight: bold;">
          <option selected="selected" style="width:205px; font-size:9px;font-weight: bold;">SUC. EN LAS QUE APLICA CREDITO</option>
        </select></td>
      </tr>
	  <tr><td>&nbsp;</td></tr>
    </table></td>
  </tr>
</table>
</form>
</body>
</html>
