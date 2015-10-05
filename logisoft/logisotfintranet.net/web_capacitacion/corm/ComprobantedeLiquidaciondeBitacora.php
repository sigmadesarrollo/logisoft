<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');	
	$link=Conectarse('webpmm'); 
	
	$folio=$_POST['folio'];$fecha=$_POST['fecha'];
	$foliobitacora=$_POST['foliobitacora'];
	$unidad=$_POST['unidad'];
	$gastos=$_POST['gastos'];
	$foliopre=$_POST['foliopre'];
	$cantidadpre=$_POST['cantidadpre'];
	$conductor=$_POST['conductor'];
	$usuario=$_SESSION[NOMBREUSUARIO];
	$registros=$_POST['registros'];
	$afavorencontra=$_POST[afavorencontra];
	$cantidadpre2 =$_POST[cantidadpre2];
	$gastos2 = $_POST[gastos2];
	
	if($_POST['accion'] == ""){
		$fecha = date('d/m/Y'); 
		$s = "SELECT obtenerFolio('comprobantedeliquidaciondebitacora',".$_SESSION[IDSUCURSAL].") AS folio";
		$r = mysql_query($s,$link) or die($s); $f = mysql_fetch_object($r);
		$folio = $f->folio;
		
	}else if($_POST['accion'] == "grabar"){
		$sqlIns =mysql_query("insert into comprobantedeliquidaciondebitacora 
		(folio, foliobitacora, usuario, fecha, sucursal)
		values 
		(obtenerFolio('comprobantedeliquidaciondebitacora',".$_SESSION[IDSUCURSAL]."), '$foliobitacora', 
		'$usuario', CURRENT_DATE,".$_SESSION[IDSUCURSAL].")",$link) or die(mysql_error($link));		
		$folio = mysql_insert_id();
		
		$s = "SELECT folio FROM comprobantedeliquidaciondebitacora WHERE id = ".$folio;
		$r = mysql_query($s,$link) or die($s); $fo = mysql_fetch_object($r);
		$folio = $fo->folio;
		
		 if($registros>0){
			//INSERTAR TABLA DETALLE
			for($i=0;$i<$registros;$i++){
				$sqlins=mysql_query("insert into comprobantedeliquidaciondebitacoradetalle 
				(comprobantedeliquida, idconcepto,concepto, cantidad, usuario, fecha,sucursal)
				values('$folio', '".$_POST["tabladetalle_IDCONCEPTO"][$i]."',
				'".$_POST["tabladetalle_CONCEPTO"][$i]."',
				".substr(str_replace(',','',$_POST["tabladetalle_CANTIDAD"][$i]),2).",
				'$usuario', CURRENT_TIMESTAMP,".$_SESSION[IDSUCURSAL].")",$link)
				or die("error en linea ".__LINE__.mysql_error($link));
		
				$detalle .= "{
					idconcepto:'".$_POST["tabladetalle_IDCONCEPTO"][$i]."',
					concepto:'".$_POST["tabladetalle_CONCEPTO"][$i]."',
					cantidad:".substr(str_replace(',','',$_POST["tabladetalle_CANTIDAD"][$i]),2)."},";
			}$detalle = substr($detalle,0,strlen($detalle)-1);
		}
		$fecha = date('d/m/Y'); 
		$mensaje ='Los datos han sido guardados correctamente';
		$accion = "modificar";
		
	}else if($_POST['accion'] == "modificar"){
		$sqlUpd =mysql_query("UPDATE comprobantedeliquidaciondebitacora SET 
		foliobitacora='$foliobitacora', usuario='$usuario', fecha=CURRENT_DATE 
		WHERE folio='$folio' and sucursal = ".$_SESSION[IDSUCURSAL]."",$link)
		or die("Error en la linea ".__LINE__.mysql_error($link));
		
		$s = "DELETE FROM comprobantedeliquidaciondebitacoradetalle 
		WHERE comprobantedeliquida='$folio' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$e=mysql_query($s,$link)or die("Error en la linea ".__LINE__.mysql_error($link));
		if($registros>0){
		//INSERTAR TABLA DETALLE
		for($i=0;$i<$registros;$i++){
			$sqlins=mysql_query("insert into comprobantedeliquidaciondebitacoradetalle 
					(comprobantedeliquida, idconcepto,concepto, cantidad, usuario, fecha,sucursal)
					values('$folio', '".$_POST["tabladetalle_IDCONCEPTO"][$i]."',
					'".$_POST["tabladetalle_CONCEPTO"][$i]."',
					".substr(str_replace(',','',$_POST["tabladetalle_CANTIDAD"][$i]),2).",
					'$usuario', CURRENT_TIMESTAMP,".$_SESSION[IDSUCURSAL].")",$link) or die("error en linea ".__LINE__);
	
			$detalle .= "{
				idconcepto:'".$_POST["tabladetalle_IDCONCEPTO"][$i]."',
				concepto:'".$_POST["tabladetalle_CONCEPTO"][$i]."',
				cantidad:".substr(str_replace(',','',$_POST["tabladetalle_CANTIDAD"][$i]),2)."},";
		}$detalle = substr($detalle,0,strlen($detalle)-1);
		}	
		$mensaje ='Los cambios han sido guardados correctamente';
		$accion = "modificar";
		$fecha = $_POST[fecha]; 
		
	}else if($_POST[accion]=="liquidar"){
		$s = "UPDATE comprobantedeliquidaciondebitacora SET 
		status='COMPROBANTE LIQUIDACION' WHERE folio='".$_POST[folio]."' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$link) or die(mysql_error($link));
		//pone en status ala preliquidacion
		$sql="UPDATE bitacorasalida SET liquidada=1 WHERE folio='$foliobitacora'";
		mysql_query($sql,$link) or die(mysql_error($link));
		
		$s = "DELETE FROM comprobantedeliquidaciondebitacoradetalle 
		WHERE comprobantedeliquida='$folio' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$e=mysql_query($s,$link)or die("Error en la linea ".__LINE__.mysql_error($link));
		if($registros>0){
		//INSERTAR TABLA DETALLE
			for($i=0;$i<$registros;$i++){
				$sqlins=mysql_query("insert into comprobantedeliquidaciondebitacoradetalle 
						(comprobantedeliquida, idconcepto,concepto, cantidad, usuario, fecha,sucursal)
						values('$folio', '".$_POST["tabladetalle_IDCONCEPTO"][$i]."',
						'".$_POST["tabladetalle_CONCEPTO"][$i]."',
						".substr(str_replace(',','',$_POST["tabladetalle_CANTIDAD"][$i]),2).", 
						'$usuario', CURRENT_TIMESTAMP,".$_SESSION[IDSUCURSAL].")",$link) or die("error en linea ".__LINE__);
		
				$detalle .= "{
					idconcepto:'".$_POST["tabladetalle_IDCONCEPTO"][$i]."',
					concepto:'".$_POST["tabladetalle_CONCEPTO"][$i]."',
					cantidad:".substr(str_replace(',','',$_POST["tabladetalle_CANTIDAD"][$i]),2)."},";
			}
			$detalle = substr($detalle,0,strlen($detalle)-1);
		}
		$s = "call proc_RegistroOperaciones('LIQUIDACION',".$foliobitacora.",0,".$_SESSION[IDSUCURSAL].")";
		mysql_query($s,$link) or die($s);
		
		$mensaje ='La liquidacion han sido realizada correctamente';
		$accion = "liquidar";
		$fecha = $_POST[fecha]; 
	}else if($_POST[accion]=="limpiar"){
		$folio="";
		$fecha="";
		$foliobitacora="";
		$unidad="";
		$gastos="";
		$foliopre="";
		$cantidadpre="";
		$conductor="";
		$accion="";
		$fecha = date('d/m/Y'); 
		$s = "SELECT obtenerFolio('comprobantedeliquidaciondebitacora',".$_SESSION[IDSUCURSAL].") AS folio";
		$r = mysql_query($s,$link) or die($s); $f = mysql_fetch_object($r);
		$folio = $f->folio;
	}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/shortcut.js"></script>
<script src="../javascript/ajax.js"></script>
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
var tabla1 = new ClaseTabla();
	
tabla1.setAttributes({       
	nombre:"tabladetalle",
	campos:[
		{nombre:"IDCONCEPTO", medida:4,tipo:"oculto", alineacion:"center", datos:"idconcepto"},
		{nombre:"CONCEPTO", medida:350, alineacion:"left", datos:"concepto"},
		{nombre:"CANTIDAD", medida:150,tipo:"moneda", alineacion:"right", datos:"cantidad"}
	],
	filasInicial:8,
	alto:100,
	seleccion:true,
	ordenable:true,
	eventoClickFila:"document.all.eliminar.value=tabla1.getSelectedIdRow()",
	eventoDblClickFila:"ModificarFila()",
	nombrevar:"tabla1"
});

	window.onload = function(){
		tabla1.create();	
		obtenerDetalles();
	}
	
	function resPrestamos(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			alerta3(datos);
			return false;
		}
		if (obj=="false"){
			return false;
		}
		tabla1.setJsonData(obj);
	}
	
	function obtenerDetalles(){
	var datosTabla = <? if($detalle!=""){echo "[".$detalle."]";}else{echo "0";} ?>;
		if(datosTabla!=0){			
			for(var i=0; i<datosTabla.length;i++){
				tabla1.add(datosTabla[i]);
			}
		}
	}
	
	
	function EliminarFila(){
		if(tabla1.getSelectedRow().idconcepto==7){
			alerta3("No puede quitar un prestamo","ATENCION");
			return false;
		}
		
		if(document.all.eliminar.value!=""){
			if(tabla1.getValSelFromField("concepto","CONCEPTO")!=""){
				tabla1.deleteById(document.all.eliminar.value);
			}
		}else{
			alerta('Seleccione la fila a eliminar','¡Atención!','tabladetalle');
		}
	}
	
	
	function ModificarFila(){
		var obj = tabla1.getSelectedRow();
		if(tabla1.getValSelFromField("concepto","CONCEPTO")!=""){
			document.all.concepto.value			=obj.idconcepto;
			document.all.cantidad.value			=obj.cantidad;
			document.all.modificarfila.value	=tabla1.getSelectedIdRow();
		}
	}
	
	function tabular(e,obj){
		tecla=(document.all) ? e.keyCode : e.which;
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

	function Validar(){
		<?=$cpermiso->verificarPermiso("312",$_SESSION[IDUSUARIO]);?>
		u.registros.value = tabla1.getRecordCount();
		if(u.foliobitacora.value==""){
			alerta('Debe Capturar Folio Bitácora','¡Atención!','foliobitacora');
			return false;
		}
		
		if(u.afavorencontra.value=="0"){//ENCONTRA
			if(parseFloat(u.totalentregar.value)==0){
				if(u.accion.value==""){
					u.accion.value = "grabar";
					document.form1.submit();
				}else if(u.accion.value=="modificar"){
					u.accion.value = "modificar";
					document.form1.submit();
				}else if(u.accion.value=="liquidar"){
					u.accion.value = "liquidar";
					document.form1.submit();
				}
			}else{
			
				if(tabla1.getRecordCount()==0){
					alerta3("Debe agregar concepto(s) al detalle","¡Atención!");
					return false;
				}
				var total = 0;
				if(u.afavorencontra.value==1){
					total = parseFloat(u.gastos.value) - parseFloat(u.cantidadpre.value);
				}else if(u.afavorencontra.value=='0'){
					total = parseFloat(u.gastos.value) + parseFloat(u.cantidadpre.value);
				}else{
					total = parseFloat(u.gastos.value);
				}
				
				var can=0;
				for(var i=0;i<tabla1.getRecordCount();i++){
					can = parseFloat(u["tabladetalle_CANTIDAD"][i].value.replace("$ ","").replace(/,/g,"")) + parseFloat(can);
					//alert(parseFloat(u["tabladetalle_CANTIDAD"][i].value));
				}
				var totalgasto = 0;
				if(parseFloat(can)!=parseFloat(u.totalentregar.value)){
					if (u.afavorencontra.value==0){
						//totalgasto = parseFloat(u.gastos.value)+parseFloat(u.cantidadpre.value);
						alerta3('La suma de las cantidades de los conceptos debe ser igual a :'+convertirMoneda(u.totalentregar.value));
						return false;
					}					
				}else{
					if(u.accion.value==""){
						u.accion.value = "grabar";
						document.form1.submit();
					}else if(u.accion.value=="modificar"){
						u.accion.value = "modificar";
						document.form1.submit();
					}else if(u.accion.value=="liquidar"){
						u.accion.value = "liquidar";
						document.form1.submit();
					}
				}
			}
		}else if(u.afavorencontra.value==1){// AFAVOR
			if(parseFloat(u.totalentregar.value)==0){
				if(u.accion.value==""){
					u.accion.value = "grabar";
					document.form1.submit();
				}else if(u.accion.value=="modificar"){
					u.accion.value = "modificar";
					document.form1.submit();
				}else if(u.accion.value=="liquidar"){
					u.accion.value = "liquidar";
					document.form1.submit();
				}
			}else{
				if(tabla1.getRecordCount()==0){
					alerta3("Debe agregar concepto(s) al detalle","¡Atención!");
					return false;
				}
				var total = 0;
				if(u.afavorencontra.value==1){
					total = parseFloat(u.gastos.value) - parseFloat(u.cantidadpre.value);
				}else if(u.afavorencontra.value=='0'){
					total = parseFloat(u.gastos.value) + parseFloat(u.cantidadpre.value);
				}else{
					total = parseFloat(u.gastos.value);
				}
				
				var can=0;
				for(var i=0;i<tabla1.getRecordCount();i++){
					can = parseFloat(u["tabladetalle_CANTIDAD"][i].value.replace("$ ","").replace(/,/g,"")) + parseFloat(can);
				}
				var totalgasto = 0;
				if(parseFloat(can)!=parseFloat(u.totalentregar.value)){
					if (u.afavorencontra.value==1){
						//totalgasto=parseFloat(u.gastos.value)-parseFloat(u.cantidadpre.value);	
						alerta3('La suma de las cantidades de los conceptos debe ser igual a :'+convertirMoneda(u.totalentregar.value));
						return false;
					}					
				}else{
					if(u.accion.value==""){
						u.accion.value = "grabar";
						document.form1.submit();
					}else if(u.accion.value=="modificar"){
						u.accion.value = "modificar";
						document.form1.submit();
					}else if(u.accion.value=="liquidar"){
						u.accion.value = "liquidar";
						document.form1.submit();
					}
				}
			}
		}
	}


	function Limpiar(){
		u.folio.value	="";
		u.fecha.value	="";
		u.foliobitacora.value="";
		u.unidad.value	="";
		u.gastos.value	="";
		u.gastos2.value	="";
		u.foliopre.value="";
		u.cantidadpre.value="";
		u.cantidadpre2.value="";
		u.conductor.value="";
		u.registros.value ="";
		u.afavorencontra.value=""
		tabla1.clear();
		u.accion.value="limpiar";
		u.totalentregar.value = "";
		document.form1.submit();
	}

function obtener(folio){
	//OPTENER BITACORA
	consulta("ObtenerFolio","consultaCORM.php?accion=15&folio="+folio+"&sid="+Math.random());
}

function ObtenerFolio(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){
			if(datos.getElementsByTagName('existeliquidacion').item(0).firstChild.data == "si"){
				alerta("El folio de bitacora de salida ya ha sido registrado en una liquidación",'¡Atención!','foliobitacora');
				return false;
			}
			
			if(datos.getElementsByTagName('existepreliquidacion').item(0).firstChild.data == "no"){
			alerta("El folio de bitacora de salida no ha sido registrado en una preliquidación",'¡Atención!','foliobitacora');
				return false;
			}
			u.foliobitacora.value	=datos.getElementsByTagName('foliobitacora').item(0).firstChild.data;
			u.unidad.value			=datos.getElementsByTagName('unidad').item(0).firstChild.data;
			u.gastos2.value			=convertirMoneda(datos.getElementsByTagName('gastos').item(0).firstChild.data);
			u.gastos.value			=datos.getElementsByTagName('gastos').item(0).firstChild.data;
			u.conductor.value		=datos.getElementsByTagName('conductor').item(0).firstChild.data;
			u.foliopre.value		=datos.getElementsByTagName('foliopre').item(0).firstChild.data;
			u.cantidadpre2.value	=convertirMoneda(datos.getElementsByTagName('cantidadpre').item(0).firstChild.data);
			u.cantidadpre.value		=datos.getElementsByTagName('cantidadpre').item(0).firstChild.data;
			u.afavorencontra.value	=datos.getElementsByTagName('afavorencontra').item(0).firstChild.data;
			
			consultaTexto("resPrestamos","consultaCORM_json.php?accion=1&folio="+u.foliobitacora.value+"&cos="+Math.random());
			
			if(u.afavorencontra.value==1){
				u.totalentregar.value 	= parseFloat(u.gastos.value) - parseFloat(u.cantidadpre.value);	
			}else{
				u.totalentregar.value 	= parseFloat(u.gastos.value) + parseFloat(u.cantidadpre.value);	
			}
			
			
			if (u.cantidadpre.value==""){
				u.cantidadpre.value=0;
			}else if(u.cantidadpre.value>0){
			}else{
				u.cantidadpre.value=0;
			}
			esNan('cantidadpre2');
			esNan('cantidadpre');
			
		}else{
			u.foliobitacora.value	="";
			u.unidad.value			="";
			u.gastos2.value			="";
			u.gastos.value			="";
			u.conductor.value		="";
			u.foliopre.value		="";
			u.cantidadpre2.value		="";
			u.cantidadpre.value		="";
			u.afavorencontra.value	="";
		}
}

function agregarVar(){
	var u 		= document.all;
	var total 	= 0;
	if(u.concepto.value=="" || u.concepto.value==0){
		alerta('Debe capturar una Concepto','¡Atención!','concepto');
		return false;
	}else if(u.cantidad.value==""){
		alerta('Debe capturar Cantidad','¡Atención!','cantidad');
		return false;	
	}else if(u.modificarfila.value!=""){
		tabla1.deleteById(document.all.modificarfila.value);
		u.modificarfila.value="";
	}
	var concepto = tabla1.getValuesFromField("idconcepto",":");
	if(concepto.indexOf(u.concepto.value)!=-1){
		alerta3('El concepto ya fue agregado');
		return false;
	}

	var registro 	= new Object();
	registro.cantidad 	= document.getElementById('cantidad').value;
	registro.idconcepto	= document.getElementById('concepto').value;	
	registro.concepto	= document.getElementById('concepto').options[document.getElementById('concepto').options.selectedIndex].text;
	tabla1.add(registro);
	
	u.cantidad.value ="";
	u.concepto.value ="";
}
//***************************
shortcut.add("Ctrl+b",function() {
	if(document.form1.oculto.value=="1"){
abrirVentanaFija('prestamossucursal_buscar.php?tipo=1', 550, 450, 'ventana', 'Busqueda')}
});
	function OptenerFolioComprobante(folio){
		consulta("mostrarFolioComprobante","consultaCORM.php?accion=16&folio="+folio+"&sid="+Math.random());
	}
	
		function mostrarFolioComprobante(datos){
				var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
				if(con>0){
					u.folio.value			=datos.getElementsByTagName('folio').item(0).firstChild.data;
					u.fecha.value			=datos.getElementsByTagName('fecha').item(0).firstChild.data;
					u.foliobitacora.value	=datos.getElementsByTagName('foliobitacora').item(0).firstChild.data;
					u.unidad.value			=datos.getElementsByTagName('unidad').item(0).firstChild.data;
					u.gastos2.value			=convertirMoneda(datos.getElementsByTagName('gastos').item(0).firstChild.data);
					u.gastos.value			=datos.getElementsByTagName('gastos').item(0).firstChild.data;
					u.conductor.value		=datos.getElementsByTagName('conductor').item(0).firstChild.data;
					u.foliopre.value		=datos.getElementsByTagName('foliopre').item(0).firstChild.data;
					u.cantidadpre.value		=datos.getElementsByTagName('cantidadpre').item(0).firstChild.data;
					u.estado.value			=datos.getElementsByTagName('estado').item(0).firstChild.data;
					u.cantidadpre2.value	=convertirMoneda(datos.getElementsByTagName('cantidadpre').item(0).firstChild.data);
					u.afavorencontra.value  =datos.getElementsByTagName('afavorencontra').item(0).firstChild.data
					if (u.cantidadpre.value==""){
						u.cantidadpre.value=0;
					}else if(u.cantidadpre.value>0){
					}else{
						u.cantidadpre.value=0;
					}
					esNan('cantidadpre2');
					esNan('cantidadpre');
					
					if (u.estado.value=='LIQUIDADO'){
						u.accion.value = "liquidar";
						u.btnliquidar.style.visibility="hidden";
						u.tb_guardar.style.visibility="hidden";
					}else{
						u.accion.value = "modificar";
						u.btnliquidar.style.visibility="visible";
						u.tb_guardar.style.visibility="hidden";
					}
					tabla1.setXML(datos);
				
				}else{
					alerta3("No se encontro el folio","Busqueda de Folio");
					Limpiar();
				}
		}
	
	
	function liquidar(){
		<?=$cpermiso->verificarPermiso("400",$_SESSION[IDUSUARIO]);?>
		var total 			= 0;
		var totalgasto		= 0;
		u.registros.value 	= tabla1.getRecordCount();
		
		if(u.unidad.value==""){
			alerta('Debe Capturar Folio Bitácora','¡Atención!','foliobitacora');
		}else if(tabla1.getRecordCount()==0 && u.afavorencontra.value==0){
			alerta3("Debe agregar concepto(s) al detalle","¡Atención!");
			return false;			
		}else{
			if(u.afavorencontra.value==1){
				total=parseFloat(u.gastos.value)-parseFloat(u.cantidadpre.value);
			}else if(u.afavorencontra.value=='0'){
				total=parseFloat(u.gastos.value)+parseFloat(u.cantidadpre.value);
			}else{
				total=parseFloat(u.gastos.value);
			}
			if(u.afavorencontra.value==0){
				var cantidad = tabla1.getValuesFromField("cantidad",":");
				cantidad	 = cantidad.split(":");
				var can=0;
				for(i=0;i<cantidad.length;i++){
					can+=parseFloat(cantidad[i]);
				}
				if(parseFloat(can)!=parseFloat(total)){
					if(u.afavorencontra.value==1){
						esNan('cantidadpre');
						esNan('cantidadpre2');
						esNan('gastos');
						totalgasto = parseFloat(u.gastos.value) - parseFloat(u.cantidadpre.value);
						alerta3('La suma de las cantidades de los conceptos debe ser igual a :'+convertirMoneda(totalgasto));
					}else if(u.afavorencontra.value==0){
						esNan('cantidadpre');
						esNan('cantidadpre2');
						esNan('gastos');
						totalgasto = parseFloat(u.gastos.value) + parseFloat(u.cantidadpre.value);
						alerta3('La suma de las cantidades de los conceptos debe ser igual a :'+convertirMoneda(totalgasto));
					}
					return false;
				}		
				if(parseFloat(u.cantidad.value)>parseFloat(total)){
					alerta('Ha superado el limite Gastos','¡Atención!','cantidad');
					return false;	
				}
			}		
			u.accion.value="liquidar";
			u.form1.submit();
		}
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
	
	function esNan(caja){
		if (document.getElementById(caja).value.replace('$ ','').replace(/,/g,'')=="NaN"){
			document.getElementById(caja).value	= "0";
		}else if (document.getElementById(caja).value==""){
			document.getElementById(caja).value	= "0";
		}
	}
	function obtenerConductorBusqueda(id,caja){
		if(id!=""){
			switch(caja){
				case "1":
					u.conductor1.value = id;
				break;
			}
				consulta("mostrarConductor","consultaCORM.php?accion=2&empleado="+id+"&caja="+caja);
		}
	}
	function obtenerConductor(e,id,caja){
		tecla = (u) ? e.keyCode : e.which;
		if((tecla == 13 || tecla ==9)&& id!=""){
				consulta("mostrarConductor","consultaCORM.php?accion=2&empleado="+id+"&caja="+caja);
		}
	}
	function mostrarConductor(datos){
	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
	var caja = datos.getElementsByTagName('caja').item(0).firstChild.data;
		if(con>0){
		switch(caja){
		case "1":
				u.conductor.value = datos.getElementsByTagName('nombre').item(0).firstChild.data;
		break;
		}
		
		}else{
			alerta('El conductor no existe o ya fue asignado a una unidad','!Atencion!','conductor'+caja);
			switch(caja){
				case "1":
					u.conductor.value = "";
				break;
			}
		}
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
    <td width="600" class="FondoTabla Estilo4">Estado de Cuenta Empleados</td>
  </tr>
  <tr>
    <td ><div align="center">
      <table width="540" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="13%"><table width="540" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right">&nbsp;</td>
          </tr>
          <tr>
            <td align="right"><table width="60%" height="18" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="10%">Folio:</td>
                <td width="33%"><input name="folio" type="text" 
 class="Tablas" id="folio" style="width:100px;" value="<?=$folio ?>" onKeyPress="if(event.keyCode==13){OptenerFolioComprobante(this.value)};" ></td>
                <td width="7%"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarComprobantedeliquidaciondeBitacoraGen.php?funcion=OptenerFolioComprobante&tipo=1', 600, 600, 'ventana', 'Busqueda')" ></div></td>
                <td width="12%">Fecha:</td>
                <td width="38%"><input name="fecha" type="text" class="Tablas"
 id="fecha" style="width:100px; background:#FF9" value="<?=$fecha ?>" readonly></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:60px">Conductor</td>
                <td width="38"><div class="ebtn_buscar" onClick="abrirVentanaFija('buscarConductor.php?caja=1', 600, 500, 'ventana', 'Busqueda')"></div></td>
                <td width="80"><span class="Tablas">
                  <input name="conductor1" type="text" class="Tablas" id="conductor1" style="width:100px" onKeyDown="obtenerConductor(event,this.value,1); return tabular(event,this)" value="<?=$conductor1 ?>" onKeyPress="return Numeros(event); " onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''; " />
                			</span></td>
                <td width="80" ><input name="conductor" type="text" class="Tablas"
 					id="conductor" style="width:315px; background:#FF9" onKeyDown="return tabular(event,this)" value="<?=$conductor ?>" readonly  ></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:75px"><label>Concepto<br>
                </label></td>
                <td style="width:285px" ><label>
                  <select name="concepto" id="concepto" class="Tablas" style="width:200px">
                    <option value=""></option>
                    <? 
				  	$con=mysql_query("select id,descripcion from catalogoconcepto",$link); 
				  while($row=mysql_fetch_array($con)){
				  ?>
                    <option value="<?=$row[id] ?>">
                      <?=$row[descripcion]?>
                      </option>
                    <? } ?>
                  </select>
                </label></td>
                <td >Cantidad<br></td>
                <td><input name="cantidad" type="text" class="Tablas" id="cantidad" style="width:100px"  ></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td></td>
          </tr>
          <tr>
            <td></td>
          </tr>
          <tr>
            <td width="13%"><label>
              <input name="r" type="radio" onKeyDown="return tabular(event,this)" value="1" <? if($_POST[r]!='0'){echo "checked"; }?> >
              A Favor</label>
              <input name="r" type="radio" value="0" onKeyDown="return tabular(event,this)" <? if($_POST[r]=='0'){echo "checked"; }?> >
              En Contra </td>
             
          </tr>
		  <tr>
            <td align="right"><div class="ebtn_agregar" onClick="agregarVar()"></div></td>
          </tr>
          <tr>
            <td align="right"><table id="tabladetalle" border="0" cellspacing="0" cellpadding="0">
            </table></td>
          </tr>
          <tr>
            <td align="right" ><div class="ebtn_eliminar" onClick="EliminarFila()"></div></td>
          </tr>
          <tr>
            <td></td>
          </tr>
          <tr>
             <td>Total a Favor<input name="gastos2" type="text" class="Tablas" id="gastos2" style="width:100px; background:#FF9" onKeyDown="return tabular(event,this)" value="<?=$gastos2 ?>" readonly  ></td>
          </tr>
          <tr>
          
            <td><input name="accion" type="hidden" id="accion" value="<?=$accion?>">
              <input name="modificarfila" type="hidden" id="modificarfila">
              <input name="registros" type="hidden" id="registros">
              <input name="eliminar" type="hidden" id="eliminar">
              <input name="afavorencontra" type="hidden" id="afavorencontra" value="<?=$afavorencontra ?>">
              <input name="estado" type="hidden" id="estado" value="<?=$estado ?>">
              <input name="cantidadpre" type="hidden" id="cantidadpre" value="<?=$cantidadpre ?>"  >
              <input name="gastos" type="hidden" id="gastos" value="<?=$gastos ?>" >
              <input name="totalentregar" type="hidden" id="totalentregar" value="<?=$_POST[totalentregar] ?>">
              <span style="width:200px">
              <input name="foliopre" type="hidden" class="Tablas"
 id="foliopre" style="width:180px; background:#FF9" onKeyDown="return tabular(event,this)" value="<?=$foliopre ?>" readonly >
              <input name="cantidadpre2" type="hidden" class="Tablas"
 id="cantidadpre2" style="width:150px; background:#FF9" onKeyDown="return tabular(event,this)" value="<?=$cantidadpre2 ?>" readonly  >
              <input name="unidad" type="hidden" class="Tablas"
 id="unidad" style="width:100px; background:#FF9" onKeyDown="return tabular(event,this)" value="<?=$unidad ?>" readonly >
              <input name="foliobitacora" type="hidden" class="Tablas"
 id="foliobitacora" style="width:100px" onKeyPress="" onKeyDown="if(event.keyCode=='13'){obtener(this.value);};return tabular(event,this)" value="<?=$foliobitacora ?>" >
              </span>
              <div class="ebtn_buscar" style='display:none;' onClick="abrirVentanaFija('buscarBitacora_ComprobantedeliquidaciondeBitacora.php', 550, 450, 'ventana', 'Busqueda')"></div>
              
              <table width="24%" height="13" border="0" align="right" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="32%" ><div id="btnliquidar" class="ebtn_Liquidar" onClick="document.getElementById('accion').value='liquidar';Validar()" style="<? if($accion=='liquidar' || $accion==''){echo 'visibility:hidden';}else if($accion!=''){echo 'visibility:visible';}?>" ></div></td>
                  <td width="21%" id="tb_guardar"><div class="ebtn_guardar" onClick="Validar();" style="<? if($accion=='liquidar'){echo 'visibility:hidden';}?>"></div></td>
                  <td width="47%"><div class="ebtn_nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'Limpiar();', '');"></div></td>

                </tr>
            </table></td>
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
<? 
	if ($mensaje!=""){
		echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
	}
?>