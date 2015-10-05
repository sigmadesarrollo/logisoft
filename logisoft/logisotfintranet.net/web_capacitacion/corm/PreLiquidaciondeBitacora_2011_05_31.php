<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');	
	$l=Conectarse('webpmm'); 
	$fecha = date('d/m/Y');
	
	$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS recibio FROM catalogoempleado 
	WHERE id = ".$_SESSION[IDUSUARIO]."";
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/shortcut.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<script>
	var u = document.all;
	var tabla1 	= new ClaseTabla();
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"DEVOLVER", medida:30, alineacion:"center", tipo:"checkbox", datos:"sel"},
			{nombre:"PRECINTO", medida:110, alineacion:"left", datos:"precinto"},
			{nombre:"FECHA ASIGNADO", medida:110, alineacion:"left", datos:"fechaasignado"}
		],
		filasInicial:5,
		alto:100,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		u.foliobitacora.focus();
		obtenerGeneral();
	}
	
	function tabular(e,obj){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla!=13) return;
		frm=obj.form;
		for(i=0;i<frm.elements.length;i++) 
			if(frm.elements[i]==obj) 
			{ 
				if (i==frm.elements.length-1) 
					i=-1;
				break 
			}
		if (frm.elements[i+1].disabled ==true )    
			tabular(e,frm.elements[i+1]);
		else if (frm.elements[i+1].readOnly ==true )    
			tabular(e,frm.elements[i+1]);
		else frm.elements[i+1].focus();
		return false;
	}  

	function obtener(folio){	
		consulta("ObtenerFolio","consultaCORM.php?accion=11&folio="+folio
		+"&sid="+Math.random());
	}
	
	function ObtenerFolio(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){
			
			if(datos.getElementsByTagName('existe').item(0).firstChild.data=="si"){
				alerta('El folio de bitacora capturado ya fue registrado en otra preliquidacion','¡Atención!','foliobitacora');
				u.foliobitacora.value = "";
				return false;
			}
			
			u.foliobitacora.value	=datos.getElementsByTagName('foliobitacora').item(0).firstChild.data;
			u.unidad.value			=datos.getElementsByTagName('unidad').item(0).firstChild.data;
			u.gastos.value			=convertirMoneda(datos.getElementsByTagName('gastos').item(0).firstChild.data);
			u.gastos2.value			=datos.getElementsByTagName('gastos').item(0).firstChild.data;
			u.conductor.value		=datos.getElementsByTagName('conductor').item(0).firstChild.data;
			u.entrego.value			=datos.getElementsByTagName('idconductor').item(0).firstChild.data;
			u.empleadoentrego.value=datos.getElementsByTagName('conductor').item(0).firstChild.data;
			consultaTexto("mostrarDetalle","preLiquidaciondeBitacora_con.php?accion=4&foliobitacora="+u.foliobitacora.value);
		}else{
			u.foliobitacora.value	= "";
			u.unidad.value			= "";
			u.gastos.value			= "";
			u.gastos2.value			= "";
			u.conductor.value		= "";
			u.entrego.value			= "";
			u.empleadoentrego.value = "";
			u.foliobitacora.focus();
			tabla1.clear();
		}
	}
	
	function mostrarDetalle(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla1.setJsonData(obj);
		}
	}
	
	function obtenerEmpleadoEntrego(id){
		consulta("mostrarEmpleadoEntrego","consultaCORM.php?id="+id+"&accion="+12+"&sid="+Math.random());
	}
	
	function mostrarEmpleadoEntrego(datos){
			var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
			if(con>0){
				u.entrego.value			=datos.getElementsByTagName('id').item(0).firstChild.data;
				u.empleadoentrego.value	=datos.getElementsByTagName('empleado').item(0).firstChild.data;
			}else{
				u.entrego.value			="";
				u.empleadoentrego.value	="";
			}
	}
	
	function obtenerEmpleadoRecibio(id){
		consulta("mostrarEmpleadoRecibio","consultaCORM.php?id="+id+"&accion="+12+"&sid="+Math.random());
	}
	
	function mostrarEmpleadoRecibio(datos){
			var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
			if(con>0){
				u.recibio.value			=datos.getElementsByTagName('id').item(0).firstChild.data;
				u.empleadorecibio.value	=datos.getElementsByTagName('empleado').item(0).firstChild.data;
			}else{
				u.recibio.value			="";
				u.empleadorecibio.value	="";
			}
	}
	
	function Validar(){
		<?=$cpermiso->verificarPermiso("311",$_SESSION[IDUSUARIO]);?>
		if(u.conductor.value==""){
				alerta('Debe Capturar Folio Bitácora','¡Atención!','foliobitacora');
		}else if(u.cantidad.value==""){
				alerta('Debe Capturar cantidad','¡Atención!','cantidad');
		}else if(u.empleadoentrego.value==""){
				alerta('Debe Capturar Entrego','¡Atención!','empleadoentrego');			
		}else if(u.empleadorecibio.value==""){
				alerta('Debe Capturar Recibio','¡Atención!','empleadorecibio');	
		}else if(parseFloat(u.cantidad.value) > parseFloat(u.gastos2.value)){				
				alerta('La Cantidad es Mayor que el Gasto','¡Atención!','cantidad');			
		}else{
			var nobaja = "";
			for(var i=0;i<tabla1.getRecordCount();i++){
				if(u["detalle_DEVOLVER"][i].checked==true){
					u.precintos.value += u["detalle_PRECINTO"][i].value+",";
				}else{
					nobaja += u["detalle_PRECINTO"][i].value+",";
				}
			}			
			if(u.accion.value==""){			
				u.d_guardar.style.visible = "hidden";
				consultaTexto("registro","preLiquidaciondeBitacora_con.php?accion=2&foliobitacora="+u.foliobitacora.value
				+"&afavor="+((u.r[0].checked == true)?1:0)
				+"&cantidad="+u.cantidad.value+"&entrego="+u.entrego.value
				+"&recibio="+u.recibio.value+"&precintos="+u.precintos.value.substr(0,u.precintos.value.length-1)
				+"&nobaja="+nobaja.substr(0,nobaja.length-1)+"&val="+Math.random());
			}else{
				u.d_guardar.style.visible = "hidden";
				consultaTexto("registro","preLiquidaciondeBitacora_con.php?accion=3&foliobitacora="+u.foliobitacora.value
				+"&afavor="+((u.r[0].checked == true)?1:0)
				+"&cantidad="+u.cantidad.value+"&entrego="+u.entrego.value
				+"&recibio="+u.recibio.value+"&precintos="+u.precintos.value.substr(0,u.precintos.value.length-1)
				+"&nobaja="+nobaja.substr(0,nobaja.length-1)+"&folio="+u.folio.value
				+"&fecha="+u.fecha.value+"&val="+Math.random());
			}
		}
	}
	
	function registro(datos){
		if(datos.indexOf("ok")>-1){
			var row = datos.split(",");
			if(row[1] == "grabar"){
				u.folio.value = row[2];
				info("Los datos han sido guardados correctamente","");
				u.d_guardar.style.visible = "visible";
				u.accion.value = "modificar";
			}else if(row[1] == "modificar"){
				info("Los cambios han sido guardados correctamente","");
				u.d_guardar.style.visible = "visible";
				u.accion.value = "modificar";
			}
		}else{
			alerta3("Hubo un error al guardar "+datos,"¡Atención!");
			u.d_guardar.style.visible = "visible";
		}
	}
	
	function Limpiar(){
		u.folio.value			="";
		u.fecha.value			="";
		u.foliobitacora.value	="";
		u.unidad.value			="";
		u.gastos.value			="";
		u.gastos2.value			="";
		u.conductor.value		="";
		u.r.value				="";
		u.cantidad.value		="";
		u.entrego.value			="";
		u.empleadoentrego.value	="";
		u.recibio.value			="";
		u.empleadorecibio.value	="";
		u.accion.value			="";
		u.d_guardar.style.visible = "visible";
		u.btnImprimir.style.visibility = "hidden";		
		tabla1.clear();
		obtenerGeneral();
		u.foliobitacora.focus();
	}
	
	function obtenerGeneral(){
		consultaTexto("mostrarfolio","preLiquidaciondeBitacora_con.php?accion=1&sid="+Math.random());
	}
	
	function mostrarfolio(datos){
		var row = datos.split(",");
		u.folio.value	= row[0];
		u.fecha.value	= row[1];
		u.empleadorecibio.value = row[2];
		u.recibio.value =  '<?=$_SESSION[IDUSUARIO] ?>';
	}
	
	function OptenerFolioPreliberacion(folio){
		consulta("mostrarFolioPreliberacion","consultaCORM.php?folio="+folio+"&accion=13&sid="+Math.random());
	}

	function mostrarFolioPreliberacion(datos){
			var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
			if(con>0){
				u.folio.value			=datos.getElementsByTagName('folio').item(0).firstChild.data;
				u.fecha.value			=datos.getElementsByTagName('fecha').item(0).firstChild.data;
				u.foliobitacora.value	=datos.getElementsByTagName('foliobitacora').item(0).firstChild.data;
				u.unidad.value			=datos.getElementsByTagName('unidad').item(0).firstChild.data;
				u.gastos.value			=convertirMoneda(datos.getElementsByTagName('gastos').item(0).firstChild.data);
				u.gastos2.value			=datos.getElementsByTagName('gastos').item(0).firstChild.data;
				u.conductor.value		=datos.getElementsByTagName('conductor').item(0).firstChild.data;
				var radio	= datos.getElementsByTagName('r').item(0).firstChild.data;
				if(radio==1){
					u.r[0].checked=true;
				}else{
					u.r[1].checked=true;
				}
				u.cantidad.value		=datos.getElementsByTagName('cantidad').item(0).firstChild.data;
				u.entrego.value			=datos.getElementsByTagName('entrego').item(0).firstChild.data;
				u.empleadoentrego.value	=datos.getElementsByTagName('empleadoentrego').item(0).firstChild.data;
				u.recibio.value			=datos.getElementsByTagName('recibio').item(0).firstChild.data;
				u.empleadorecibio.value	=datos.getElementsByTagName('empleadorecibio').item(0).firstChild.data;
				u.btnImprimir.style.visibility = "visible";
				if(datos.getElementsByTagName('liquidada').item(0).firstChild.data==1){
					u.d_guardar.style.visibility = "hidden";
				}
				tabla1.clear();
				consultaTexto("mostrarDetalle","preLiquidaciondeBitacora_con.php?accion=5&folio="+u.folio.value);
			}
	}

	function Imprimir(){
		<?=$cpermiso->verificarPermiso("399",$_SESSION[IDSUCURSAL]);?>
		//alert('Funcion Imprimir');
	}

	function convertirMoneda(valor){
		valorx = (valor=="")?"0.00":valor;
		valor1 = Math.round(parseFloat(valorx)*100)/100;
		valor2 = "$ "+numcredvar(valor1.toLocaleString());
		return valor2;
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	
</script>
<style type="text/css">
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.Estilo5 {
	font-size: 9px;
	font-family: tahoma;
	font-style: italic;
}
-->
</style>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="550" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="600" class="FondoTabla Estilo4">PRELIQUIDACI&Oacute;N DE BIT&Aacute;CORA</td>
  </tr>
  <tr>
    <td ><div align="center">
      <table width="540" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2" align="right"><input name="accion" type="hidden" id="accion">
            <input name="gastos2" type="hidden" id="gastos2" style="text-align:right;width:100px; background:#FF9" onKeyDown="return tabular(event,this)" value="<?=$_POST[gastos2] ?>" readonly="readonly"  ></td>
        </tr>
        <tr>
          <td colspan="2" align="right"><table width="60%" height="18" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="10%">Folio:</td>
              <td width="33%"><input name="folio" type="text" class="Tablas" 
 id="folio" style="width:100px; background:#FF9" value="<?=$folio ?>" readonly="readonly"></td>
              <td width="7%"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarPreliberaciondeGastosGen.php?funcion=OptenerFolioPreliberacion', 550, 450, 'ventana', 'Busqueda')" ></div></td>
              <td width="12%">Fecha:</td>
              <td width="38%"><input name="fecha" type="text" class="Tablas" 
 id="fecha" style="width:100px; background:#FF9" value="<?=$fecha ?>" readonly="readonly"></td>
            </tr>
          </table>            </td>
        </tr>
        <tr>
          <td colspan="2" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="75" style="width:75px">Folio Bit&aacute;cora<br></td>
              <td width="103"><input name="foliobitacora" type="text" class="Tablas" 
 id="foliobitacora" style="width:100px" value="<?=$_POST[foliobitacora] ?>" onKeyPress="if(event.keyCode=='13'){obtener(this.value);}" ></td>
              <td width="31"><div class="ebtn_buscar" onClick="abrirVentanaFija('buscarBitacora_Preliquidacion.php', 550, 450, 'ventana', 'Busqueda')"></div></td>
              <td width="43">Unidad<br></td>
              <td width="123"><input name="unidad" type="text" class="Tablas" 
 id="unidad" style="width:100px; background:#FF9" onKeyDown="return tabular(event,this)" value="<?=$_POST[unidad] ?>" readonly="readonly" ></td>
              <td width="43">Gastos<br></td>
              <td width="127"><input name="gastos" type="text" class="Tablas" 
 id="gastos" style="text-align:right;width:100px; background:#FF9" onKeyDown="return tabular(event,this)" value="<?=$_POST[gastos] ?>" readonly="readonly"  ></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td style="width:75px">Conductor</td>
              <td ><input name="conductor" type="text" class="Tablas" 
 id="conductor" style="width:443px; background:#FF9" onKeyDown="return tabular(event,this)" value="<?=$_POST[conductor] ?>" readonly="readonly"  ></td>
            </tr>
          </table>            </td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="13%"><label>
                <input name="r" type="radio" onKeyDown="return tabular(event,this)" value="1" <? if($_POST[r]!='0'){echo "checked"; }?> >
                A Favor</label></td>
              <td width="56%"><label>
                <input name="r" type="radio" value="0" onKeyDown="return tabular(event,this)" <? if($_POST[r]=='0'){echo "checked"; }?> >
                En Contra</label></td>
              <td width="8%">Cantidad<br></td>
              <td width="23%"><input name="cantidad" type="text" class="Tablas" 
 id="cantidad" style="width:100px" onKeyDown="return tabular(event,this)" value="<?=$_POST[cantidad] ?>"  ></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td style="width:60px">Entrego:<span style="width:70px">
            <input name="entrego" type="hidden" id="entrego" value="<?=$_POST[entrego] ?>" />
          </span></td>
          <td style="width:70px"><span class="Tablas">
            <input name="empleadoentrego" type="text" class="Tablas" id="empleadoentrego" style="width:365px;background:#FFFF99" value="<?=$_POST[empleadoentrego] ?>" readonly="" />
            </span></td>
          </tr>
        <tr>
          <td>Recibi&oacute;:
            <input name="recibio" type="hidden" id="recibio" value="<?=$_SESSION[IDUSUARIO] ?>" /></td>
          <td><span class="Tablas">
            <input name="empleadorecibio" type="text"  class="Tablas" id="empleadorecibio" style="width:365px;background:#FFFF99" value="<?=cambio_texto($f->recibio); ?>" readonly="" />
            </span></td>
          </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        
        <tr>
          <td colspan="2" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">
            
          </table></td>
        </tr>
        <tr>
          <td colspan="2">            </td>
        </tr>
        <tr>
          <td colspan="2"><input type="hidden" name="precintos"></td>
        </tr>
        <tr>
          <td colspan="2"><table width="42%" height="13" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
				<td align="left"><div id="btnImprimir" class="ebtn_imprimir" onClick="Imprimir()" style="visibility:hidden"></div></td>
              <td align="center"><div id="d_guardar" class="ebtn_guardar" onClick="Validar();"></div></td>
              <td align="right"><div class="ebtn_nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'Limpiar();', '');"></div></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div></td>
  </tr>
</table>
</form>
</body>

</html>