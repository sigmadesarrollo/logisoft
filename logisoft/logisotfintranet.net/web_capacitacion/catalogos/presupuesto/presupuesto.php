<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion,':',cs.id) AS descripcion FROM catalogosucursal cs
	ORDER BY cs.descripcion";
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../../javascript/funciones.js"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/moautocomplete.js"></script>
<script src="../../javascript/ClaseMensajes.js"></script>
<script>
	var u = document.all;
	var mens = new ClaseMensajes();
	var v_suc	= "";
	mens.iniciar('../../javascript');
	
	window.onload = function(){	
		u.sucursal.focus();
		obtenerGeneral();
	}
	
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","presupuesto_con.php?accion=1&val="+Math.random());
	}
	
	function mostrarGeneral(datos){
		var row = datos.split(",");
		u.folio.value	= row[0];
		u.fecha.value	= row[1]; 
	}

	function obtenerSucursal(id){
		u.sucursal_hidden.value = id;
		v_suc = id;
		consultaTexto("mostrarSucursal","presupuesto_con.php?accion=2&sucursal="+id+"&val="+Math.random());
	}
	
	function mostrarSucursal(datos){
		u.sucursal.value = datos;
	}
	
	function obtenerFolio(folio){
		u.folio.value = folio;
		consultaTexto("mostrarDatos","presupuesto_con.php?accion=3&folio="+folio+"&val="+Math.random());
	}
	
	function mostrarDatos(datos){		
		if(datos.indexOf("noencontro")<0){
			limpiar("");
			var obj = eval(convertirValoresJson(datos));
			u.enero.value 		= obj.principal.enero;
			u.enero.value 		= convertirMoneda(u.enero.value);
			u.r_enero.value 	= obj.principal.r_enero;
			u.r_enero.value 		= convertirMoneda(u.r_enero.value);
			u.febrero.value 	= obj.principal.febrero;
			u.febrero.value 		= convertirMoneda(u.febrero.value);
			u.r_febrero.value 	= obj.principal.r_febrero;
			u.r_febrero.value 		= convertirMoneda(u.r_febrero.value);
			u.marzo.value 		= obj.principal.marzo;
			u.marzo.value 		= convertirMoneda(u.marzo.value);
			u.r_marzo.value 	= obj.principal.r_marzo;
			u.r_marzo.value 		= convertirMoneda(u.r_marzo.value);
			u.abril.value 		= obj.principal.abril;
			u.abril.value 		= convertirMoneda(u.abril.value);
			u.r_abril.value 	= obj.principal.r_abril;
			u.r_abril.value 		= convertirMoneda(u.r_abril.value);
			u.mayo.value 		= obj.principal.mayo;
			u.mayo.value 		= convertirMoneda(u.mayo.value);
			u.r_mayo.value 		= obj.principal.r_mayo;
			u.r_mayo.value 		= convertirMoneda(u.r_mayo.value);
			u.junio.value 		= obj.principal.junio;
			u.junio.value 		= convertirMoneda(u.junio.value);
			u.r_junio.value 	= obj.principal.r_junio;
			u.r_junio.value 		= convertirMoneda(u.r_junio.value);
			u.julio.value 		= obj.principal.julio;
			u.julio.value 		= convertirMoneda(u.julio.value);
			u.r_julio.value 	= obj.principal.r_julio;
			u.r_julio.value 		= convertirMoneda(u.r_julio.value);
			u.agosto.value 		= obj.principal.agosto;
			u.agosto.value 		= convertirMoneda(u.agosto.value);
			u.r_agosto.value 	= obj.principal.r_agosto;		
			u.r_agosto.value 		= convertirMoneda(u.r_agosto.value);
			u.septiembre.value 	= obj.principal.septiembre;
			u.septiembre.value 		= convertirMoneda(u.septiembre.value);
			u.r_septiembre.value = obj.principal.r_septiembre;
			u.r_septiembre.value 		= convertirMoneda(u.r_septiembre.value);
			u.octubre.value 	= obj.principal.octubre;
			u.octubre.value 		= convertirMoneda(u.octubre.value);
			u.r_octubre.value 	= obj.principal.r_octubre;
			u.r_octubre.value 		= convertirMoneda(u.r_octubre.value);
			u.noviembre.value 	= obj.principal.noviembre;
			u.noviembre.value 		= convertirMoneda(u.noviembre.value);
			u.r_noviembre.value = obj.principal.r_noviembre;
			u.r_noviembre.value 		= convertirMoneda(u.r_noviembre.value);
			u.diciembre.value 	= obj.principal.diciembre;
			u.diciembre.value 		= convertirMoneda(u.diciembre.value);
			u.r_diciembre.value = obj.principal.r_diciembre;
			u.r_diciembre.value 		= convertirMoneda(u.r_diciembre.value);
			u.sucursal_hidden.value	= obj.principal.sucursal;		
			u.sucursal.value	= obj.principal.dessucursal;
			u.fecha.value		= obj.principal.fechapresupuesto;
			v_suc 				= u.sucursal_hidden.value;
			u.accion.value 		= "modificar";
			
			u.diasenero.value = obj.principal.dias_enero;
			u.diasfebrero.value = obj.principal.dias_febrero;
			u.diasmarzo.value = obj.principal.dias_marzo;
			u.diasabril.value = obj.principal.dias_abril;
			u.diasmayo.value = obj.principal.dias_mayo;
			u.diasjunio.value = obj.principal.dias_junio;
			u.diasjulio.value = obj.principal.dias_julio;
			u.diasagosto.value = obj.principal.dias_agosto;
			u.diasseptiembre.value = obj.principal.dias_septiembre;
			u.diasnoviembre.value = obj.principal.dias_noviembre;
			u.diasoctubre.value = obj.principal.dias_octubre;
			u.diasdiciembre.value = obj.principal.dias_diciembre;
		}else{
			mens.show("A","El numero de Folio no existe","¡Atención!","folio");
			limpiar("");
		}
	}
	
	function validar(){
		<?=$cpermiso->verificarPermiso(414,$_SESSION[IDUSUARIO]);?>
		if(u.sucursal_hidden.value == undefined || u.sucursal_hidden.value == "undefined" || u.sucursal_hidden.value == "no" || u.sucursal_hidden.value == "NO"){
			u.sucursal_hidden.value = v_suc;
		}
		if(u.sucursal_hidden.value == undefined || u.sucursal.value == ""){
			mens.show("A","Debe capturar Sucursal","¡Atención!","sucursal");
			return false;
		}
		
		if(u.accion.value == ""){
			u.btnGuardar.style.visibility = "hidden";
			consultaTexto("registrar","presupuesto_con.php?accion=4&enero="+u.enero.value.replace("$ ","").replace(/,/g,"")			
			+"&febrero="+u.febrero.value.replace("$ ","").replace(/,/g,"")
			+"&marzo="+u.marzo.value.replace("$ ","").replace(/,/g,"")
			+"&abril="+u.abril.value.replace("$ ","").replace(/,/g,"")
			+"&mayo="+u.mayo.value.replace("$ ","").replace(/,/g,"")
			+"&junio="+u.junio.value.replace("$ ","").replace(/,/g,"")
			+"&julio="+u.julio.value.replace("$ ","").replace(/,/g,"")
			+"&agosto="+u.agosto.value.replace("$ ","").replace(/,/g,"")
			+"&septiembre="+u.septiembre.value.replace("$ ","").replace(/,/g,"")
			+"&octubre="+u.octubre.value.replace("$ ","").replace(/,/g,"")
			+"&noviembre="+u.noviembre.value.replace("$ ","").replace(/,/g,"")
			+"&diciembre="+u.diciembre.value.replace("$ ","").replace(/,/g,"")
			+"&sucursal_hidden="+u.sucursal_hidden.value
			+"&diasenero="+u.diasenero.value
			+"&diasfebrero="+u.diasfebrero.value
			+"&diasmarzo="+u.diasmarzo.value
			+"&diasabril="+u.diasabril.value
			+"&diasmayo="+u.diasmayo.value
			+"&diasjunio="+u.diasjunio.value
			+"&diasjulio="+u.diasjulio.value
			+"&diasagosto="+u.diasagosto.value
			+"&diasseptiembre="+u.diasseptiembre.value
			+"&diasoctubre="+u.diasoctubre.value			
			+"&diasnoviembre="+u.diasnoviembre.value
			+"&diasdiciembre="+u.diasdiciembre.value
			+"&tipo=grabar&val="+Math.random());	
			
		}else{
			u.btnGuardar.style.visibility = "hidden";
			consultaTexto("registrar","presupuesto_con.php?accion=4&enero="+u.enero.value.replace("$ ","").replace(/,/g,"")
			+"&febrero="+u.febrero.value.replace("$ ","").replace(/,/g,"")
			+"&marzo="+u.marzo.value.replace("$ ","").replace(/,/g,"")
			+"&abril="+u.abril.value.replace("$ ","").replace(/,/g,"")
			+"&mayo="+u.mayo.value.replace("$ ","").replace(/,/g,"")
			+"&junio="+u.junio.value.replace("$ ","").replace(/,/g,"")
			+"&julio="+u.julio.value.replace("$ ","").replace(/,/g,"")
			+"&agosto="+u.agosto.value.replace("$ ","").replace(/,/g,"")
			+"&septiembre="+u.septiembre.value.replace("$ ","").replace(/,/g,"")
			+"&octubre="+u.octubre.value.replace("$ ","").replace(/,/g,"")
			+"&noviembre="+u.noviembre.value.replace("$ ","").replace(/,/g,"")
			+"&diciembre="+u.diciembre.value.replace("$ ","").replace(/,/g,"")
			+"&sucursal_hidden="+u.sucursal_hidden.value
			+"&diasenero="+u.diasenero.value
			+"&diasfebrero="+u.diasfebrero.value
			+"&diasmarzo="+u.diasmarzo.value
			+"&diasabril="+u.diasabril.value
			+"&diasmayo="+u.diasmayo.value
			+"&diasjunio="+u.diasjunio.value
			+"&diasjulio="+u.diasjulio.value
			+"&diasagosto="+u.diasagosto.value
			+"&diasseptiembre="+u.diasseptiembre.value
			+"&diasoctubre="+u.diasoctubre.value			
			+"&diasnoviembre="+u.diasnoviembre.value
			+"&diasdiciembre="+u.diasdiciembre.value
			+"&folio="+u.folio.value
			+"&tipo=modificar&val="+Math.random());
		}
	}
	
	function registrar(datos){
		if(datos.indexOf("ok")>-1){
			var row = datos.split(",");
			if(row[1] == "grabar"){
				mens.show("I","Los datos han sido guardados satisfactoriamente");
				u.folio.value = row[2];
				u.accion.value = "modificar";
			}else{
				mens.show("I","Los cambios han sido guardados satisfactoriamente");
			}
			u.btnGuardar.style.visibility = "visible";
		}else{		
			mens.show("A","Hubo un error al guardar "+datos,"¡Atención!");
			u.btnGuardar.style.visibility = "visible";
		}
	}
	
	function convertirMoneda(valor){		
		valor = (valor=="")?"0.00":valor;		
		valor = Math.round(parseFloat(valor)*100)/100;
		valor = "$ "+numcredvar(valor.toLocaleString());
		return valor;
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		cadena = ((cadena=="NaN")?"0.00":cadena);
		return cadena;
	}
	
	function limpiar(tipo){	
		u.enero.value 		= "";
		u.r_enero.value 	= "";
		u.febrero.value 	= "";
		u.r_febrero.value 	= "";
		u.marzo.value 		= "";
		u.r_marzo.value 	= "";
		u.abril.value 		= "";
		u.r_abril.value 	= "";
		u.mayo.value 		= "";
		u.r_mayo.value 		= "";
		u.junio.value 		= "";
		u.r_junio.value 	= "";
		u.julio.value 		= "";
		u.r_julio.value 	= "";
		u.agosto.value 		= "";
		u.r_agosto.value 	= "";		
		u.septiembre.value 	= "";
		u.r_septiembre.value = "";
		u.octubre.value 	= "";
		u.r_octubre.value 	= "";
		u.noviembre.value 	= "";
		u.r_noviembre.value = "";
		u.diciembre.value 	= "";
		u.r_diciembre.value = "";
		u.sucursal_hidden.value	= "";		
		u.sucursal.value	= "";
		v_suc 				= "";
		u.accion.value 		= "";
		u.diasenero.value 	= "";
		u.diasfebrero.value = "";
		u.diasmarzo.value 	= "";
		u.diasabril.value 	= "";
		u.diasmayo.value 	= "";
		u.diasjunio.value 	= "";
		u.diasjulio.value 	= "";
		u.diasagosto.value 	= "";
		u.diasseptiembre.value = "";
		u.diasnoviembre.value = "";
		u.diasoctubre.value = "";
		u.diasdiciembre.value = "";
		if(tipo!="")
			obtenerGeneral();
	}
	
	var desc = new Array(<?php echo $desc; ?>);
	
</script>
<title>Documento sin t&iacute;tulo</title>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="400" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">REGISTRO PRESUPUESTO MENSUAL</td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>Folio:</td>
          <td><label>
            <input name="folio" type="text" id="folio" style="width:80px" class="Tablas" onkeypress="if(event.keyCode==13){obtenerFolio(this.value)}" />
            <img src="../../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../../buscadores_generales/buscarPresupuesto.php?funcion=obtenerFolio', 600, 550, 'ventana', 'Presupuesto')"/></label></td>
          <td>Fecha:</td>
          <td><input name="fecha" type="text" id="fecha" style="width:80px; background:#FFFF99" class="Tablas" readonly="" /></td>
        </tr>
        <tr>
          <td width="19%">&nbsp;</td>
          <td width="35%">&nbsp;</td>
          <td width="8%">&nbsp;</td>
          <td width="38%">&nbsp;</td>
        </tr>
        <tr>
          <td>Sucursal:</td>
          <td colspan="3"><label>
            <input name="sucursal" type="text" id="sucursal" class="Tablas" style="width:250px" autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.sucursal_hidden.value=this.codigo;}" onBlur="if(this.value!=''){document.all.sucursal_hidden.value = this.codigo; if(this.codigo==undefined){document.all.sucursal_hidden.value ='no'}}" />
            <img src="../../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../../buscadores_generales/buscarsucursal.php', 600, 550, 'ventana', 'Sucursales')"/></label></td>
          </tr>
        <tr>
          <td><input name="sucursal_hidden" type="hidden" id="sucursal_hidden" />
            <input name="accion" type="hidden" id="accion" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="52%" class="FondoTabla">PRESUPUESTO MES </td>
              <td width="48%" class="FondoTabla">DIAS HABILES POR MES </td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="16%">Enero:</td>
              <td width="32%"><label>
                <input name="enero" type="text" class="Tablas" id="enero" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value); document.all.febrero.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" >
              </label></td>
              <td width="21%"><input name="r_enero" type="hidden" class="Tablas" id="r_enero" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value); document.all.r_febrero.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}">
                Enero:</td>
              <td width="31%"><input name="diasenero" type="text" class="Tablas" id="diasenero" style="width:110px" onkeypress="if(event.keyCode == 13){document.all.diasfebrero.focus();}else{return solonumeros(event)}" ></td>
            </tr>
            <tr>
              <td>Febrero:</td>
              <td><input name="febrero" type="text" class="Tablas" id="febrero" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value); document.all.marzo.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" /></td>
              <td>Febrero:</td>
              <td><input name="diasfebrero" type="text" class="Tablas" id="diasfebrero" style="width:110px" onkeypress="if(event.keyCode == 13){document.all.diasmarzo.focus();}else{return solonumeros(event)}" /></td>
            </tr>
            <tr>
              <td>Marzo:</td>
              <td><input name="marzo" type="text" class="Tablas" id="marzo" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value); document.all.abril.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" /></td>
              <td><input name="r_febrero" type="hidden" class="Tablas" id="r_febrero" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value); document.all.r_marzo.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}">
                Marzo:</td>
              <td><input name="diasmarzo" type="text" class="Tablas" id="diasmarzo" style="width:110px" onkeypress="if(event.keyCode == 13){document.all.diasabril.focus();}else{return solonumeros(event)}" /></td>
            </tr>
            <tr>
              <td>Abril:</td>
              <td><input name="abril" type="text" class="Tablas" id="abril" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value); document.all.mayo.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" /></td>
              <td>Abril:</td>
              <td><input name="diasabril" type="text" class="Tablas" id="diasabril" style="width:110px" onkeypress="if(event.keyCode == 13){document.all.diasmayo.focus();}else{return solonumeros(event)}" /></td>
            </tr>
            <tr>
              <td>Mayo:</td>
              <td><input name="mayo" type="text" class="Tablas" id="mayo" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.junio.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" /></td>
              <td><input name="r_marzo" type="hidden" class="Tablas" id="r_marzo" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value); document.all.r_abril.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}">
                Mayo:</td>
              <td><input name="diasmayo" type="text" class="Tablas" id="diasmayo" style="width:110px" onkeypress="if(event.keyCode == 13){document.all.diasjunio.focus();}else{return solonumeros(event)}" /></td>
            </tr>
            <tr>
              <td>Junio:</td>
              <td><input name="junio" type="text" class="Tablas" id="junio" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.julio.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" /></td>
              <td>Junio:</td>
              <td><input name="diasjunio" type="text" class="Tablas" id="diasjunio" style="width:110px" onkeypress="if(event.keyCode == 13){document.all.diasjulio.focus();}else{return solonumeros(event)}" /></td>
            </tr>
            <tr>
              <td>Julio:</td>
              <td><input name="julio" type="text" class="Tablas" id="julio" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.agosto.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" /></td>
              <td><input name="r_abril" type="hidden" class="Tablas" id="r_abril" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value); document.all.mayo.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}">
                Julio:</td>
              <td><input name="diasjulio" type="text" class="Tablas" id="diasjulio" style="width:110px" onkeypress="if(event.keyCode == 13){document.all.diasagosto.focus();}else{return solonumeros(event)}" /></td>
            </tr>
            <tr>
              <td>Agosto:</td>
              <td><input name="agosto" type="text" class="Tablas" id="agosto" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.septiembre.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" /></td>
              <td>Agosto:</td>
              <td><input name="diasagosto" type="text" class="Tablas" id="diasagosto" style="width:110px" onkeypress="if(event.keyCode == 13){document.all.diasseptiembre.focus();}else{return solonumeros(event)}" /></td>
            </tr>
            <tr>
              <td>Septiembre:</td>
              <td><input name="septiembre" type="text" class="Tablas" id="septiembre" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.octubre.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" /></td>
              <td><input name="r_mayo" type="hidden" class="Tablas" id="r_mayo" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.r_junio.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}">
                Septiembre:</td>
              <td><input name="diasseptiembre" type="text" class="Tablas" id="diasseptiembre" style="width:110px" onkeypress="if(event.keyCode == 13){document.all.diasoctubre.focus();}else{return solonumeros(event)}" /></td>
            </tr>
            <tr>
              <td>Octubre:</td>
              <td><input name="octubre" type="text" class="Tablas" id="octubre" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.noviembre.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" /></td>
              <td>Octubre:</td>
              <td><input name="diasoctubre" type="text" class="Tablas" id="diasoctubre" style="width:110px" onkeypress="if(event.keyCode == 13){document.all.diasnoviembre.focus();}else{return solonumeros(event)}" /></td>
            </tr>
            <tr>
              <td>Noviembre:</td>
              <td><input name="noviembre" type="text" class="Tablas" id="noviembre" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.diciembre.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" /></td>
              <td><input name="r_junio" type="hidden" class="Tablas" id="r_junio" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.r_julio.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}">
                Noviembre:</td>
              <td><input name="diasnoviembre" type="text" class="Tablas" id="diasnoviembre" style="width:110px" onkeypress="if(event.keyCode == 13){document.all.diasdiciembre.focus();}else{return solonumeros(event)}" /></td>
            </tr>
            <tr>
              <td>Diciembre:</td>
              <td><input name="diciembre" type="text" class="Tablas" id="diciembre" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.enero.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" /></td>
              <td>Diciembre:</td>
              <td><input name="diasdiciembre" type="text" class="Tablas" id="diasdiciembre" style="width:110px" onkeypress="if(event.keyCode == 13){document.all.diasenero.focus();}else{return solonumeros(event)}" /></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="right"><input name="r_julio" type="hidden" class="Tablas" id="r_julio" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.r_agosto.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" />
                <input name="r_agosto" type="hidden" class="Tablas" id="r_agosto" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.r_septiembre.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" />
                <input name="r_septiembre" type="hidden" class="Tablas" id="r_septiembre" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.r_octubre.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" />
                <input name="r_octubre" type="hidden" class="Tablas" id="r_octubre" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.r_noviembre.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" />
                <input name="r_noviembre" type="hidden" class="Tablas" id="r_noviembre" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.r_diciembre.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" />
                <input name="r_diciembre" type="hidden" class="Tablas" id="r_diciembre" style="width:110px" onkeypress="if(event.keyCode == 13){this.value = convertirMoneda(this.value);document.all.r_enero.focus();}else{return tiposMoneda(event,this.value)}" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" /></td>
              <td align="right">&nbsp;</td>
            </tr>
            <tr>
              <td width="76%" align="right"><div id="btnGuardar" class="ebtn_guardar" onclick="validar();"></div></td>
              <td width="24%" align="right"><div class="ebtn_nuevo" onclick="mens.show('C','Perderá la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar(1)')"></div></td>
            </tr>
          </table></td>
          </tr>

      </table></td>
    </tr>
  </table>
</form>

</body>
</html>
