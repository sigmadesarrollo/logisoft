<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$conexion = Conectarse('webpmm');
	$fecha=date('d/m/Y');
	$iva = 0;
	$subtotal = 0;
	$total = 0;
	if ($_POST[accion]=="grabar"){
		
		$sql ="insert into notacredito set
		sucursal=".$_SESSION[IDSUCURSAL].",
		fechanotacredito='" .cambiaf_a_mysql($_POST[fecha]) ."',
		guia='".$_POST[guia]."',
		cliente=".$_POST[idcliente].",
		concepto='".$_POST[concepto]."',
		impuestoporc=".$_POST[impuesto].",
		formulo=".$_POST[idformulo].",
		reviso=".$_POST[idreviso].",
		autorizo=".$_POST[idautorizo].",
		usuario=".$_SESSION[IDUSUARIO].",fecha=CURRENT_TIMESTAMP()";
		mysql_query(str_replace("''",'null', $sql), $conexion) or die($sql);
		$folio = mysql_insert_id();
		
		if($_POST[registros]!=0){
			$_POST[seleccionados] = split(",",$_POST[seleccionados]);
			
			for($j=0;$j<$_POST[registros];$j++){
			
					$i = $_POST[seleccionados][$j];
						
					$sql="INSERT INTO notacreditodetalle set 
					folionotacredito=".$folio.",
					cantidad=".$_POST["detalle_CANTIDAD"][$i].",
					unidad='".$_POST["detalle_UNIDAD"][$i]."',
					descripcion='".$_POST["detalle_DESCRIPCION"][$i]."',
					precio=".substr(str_replace(',','',$_POST["detalle_PRECIO"][$i]),2).",
					importe=".substr(str_replace(',','',$_POST["detalle_IMPORTE"][$i]),2).",
					usuario=".$_SESSION[IDUSUARIO].",
					fecha=current_timestamp(),
					sucursal = ".$_SESSION[IDSUCURSAL]."";
					$d = mysql_query(str_replace("''",'null', $sql), $conexion) or die($sql);
					
					$cadenaregistros .= "{cantidad:'".$_POST["detalle_CANTIDAD"][$i].
									"',unidad:'".$_POST["detalle_UNIDAD"][$i].
									"',descripcion:'".$_POST["detalle_DESCRIPCION"][$i].
									"',precio:'".str_replace('$','',$_POST["detalle_PRECIO"][$i]).
									"',importe:'".str_replace('$','',$_POST["detalle_IMPORTE"][$i])."'},";
				
					}
			$cadenaregistros = substr($cadenaregistros,0,strlen($cadenaregistros)-1);
		}
		$mensaje="Los datos han sido guardados correctamente";
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="../../javascript/shortcut.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/ClaseTabla.js"></script>
<link href="../estilos_estandar.css" />
<script src="../javascript/ajax.js"></script>

<script>

var tabla1 	= new ClaseTabla();
	var u=document.all;
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[			
			{nombre:"ID", medida:4,tipo:"oculto", alineacion:"center", datos:"id"},
			{nombre:"CANTIDAD", medida:90, alineacion:"center", datos:"cantidad"},
			{nombre:"UNIDAD", medida:80, alineacion:"center", datos:"unidad"},			
			{nombre:"DESCRIPCION", medida:200, alineacion:"left",datos:"descripcion"},
			{nombre:"PRECIO", medida:100, tipo:"moneda",alineacion:"right",datos:"precio"},
			{nombre:"IMPORTE", medida:100, tipo:"moneda",alineacion:"right",datos:"importe"}
		],
		filasInicial:10,
		alto:150,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});

	window.onload = function(){
		tabla1.create();
		if(u.accion.value ==""){
			obtenerGeneral();
		}else{
			obtenerGrid();
		}
		u.img_eliminar.style.visibility="hidden";
	}
	
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","notacredito_con.php?accion=4&valram="+Math.random());
	}
	
	function mostrarGeneral(datos){	
		var row = datos.split(",");
		u.folio.value = row[0];
		u.impuesto.value = row[1];
	}
	
	function obtenerFolionotascredito(folio){
		u.folio.value = folio;
		consultaTexto("mostrarDatosEncabezados","notacredito_con.php?accion=5&folio="+folio);
	}
	
	function mostrarDatosEncabezados(datos){
		if (datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.fecha.value			= obj.principal.fecha;
			u.guia.value			= obj.principal.guia; 
			u.idcliente.value		= obj.principal.cliente;
			u.impuesto.value		= obj.principal.impuesto;			
			u.concepto.value		= obj.principal.concepto;
			u.cliente.value			= obj.principal.nombre;
			u.direccion.value		= obj.principal.direccion;
			u.rfc.value				= obj.principal.rfc;
			u.ciudad.value			= obj.principal.ciudad;
			u.estado.value			= obj.principal.estado;
			var subtotal=0;
				tabla1.clear();				
				for(var i=0;i<obj.detalle.length;i++){
					var obj		 	   		= new Object();
					obj.id 					= obj.detalle[i].id;
					obj.cantidad 			= obj.detalle[i].cantidad;
					obj.unidad	 	   		= obj.detalle[i].unidad;
					obj.descripcion   		= obj.detalle[i].descripcion;
					obj.precio		   		= obj.detalle[i].precio;
					obj.importe 			= obj.detalle[i].importe;
					subtotal += parseFloat(objeto[i].importe);
					tabla1.add(obj);
				}
				u.subtotal.value= convertirMoneda(subtotal);
				u.iva.value= convertirMoneda(parseFloat(subtotal) * parseFloat(u.impuesto.value));
				u.total.value= convertirMoneda((parseFloat(subtotal)+(parseFloat(subtotal) * parseFloat(u.impuesto.value))));
				var importe=0;
				importe= parseFloat(subtotal) + (parseFloat(subtotal) * parseFloat(u.impuesto.value));
				importe= importe.toFixed(2);
				covertirNumLetras(importe);
			
			u.guardar.style.visibility="hidden";
			u.btnAgregar.style.visibility="hidden";
		}
	}
	
	function mostrarNotasDetalle(datos){	
		if (datos!=0) {
					
			}else{
				tabla1.clear();
				u.subtotal.value= 0;
				u.iva.value= 0;
				u.total.value=0;
				alerta("No existieron datos con los filtros seleccionados","¡Atención!","guia");
			}
		}
	
	function obtenerGrid(){
		var datosPersona = <? if($cadenaregistros!=""){echo "[".$cadenaregistros."]";}else{echo "0";} ?>;	
		if(datosPersona!=0){			
			for(var i=0; i<datosPersona.length;i++){
				tabla1.add(datosPersona[i]);
			}
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

	function validar(){
		if(tabla1.getRecordCount()==0){
			alerta("Debe seleccionar por lo menos un detalle","¡Atención!","guia");
		/*}else if (u.guia.value==""){
			alerta("Debe Capturar Guia","¡Atención!","guia");*/
		}else if(u.idcliente.value==""){
			alerta("Debe Capturar el Cliente","¡Atención!","idcliente");		
		}else if (u.concepto.value==""){
			alerta("Debe Capturar el Concepto","¡Atención!","concepto");
		}else if (u.idformulo.value==""){
			alerta("Debe Capturar Quien Formulo","¡Atención!","idformulo");
		}else if (u.idreviso.value==""){
			alerta("Debe Capturar Quien Reviso","¡Atención!","idreviso");
		}else if (u.idautorizo.value==""){
			alerta("Debe Capturar Quien Autorizo","¡Atención!","idautorizo");		
		}else{
				u.seleccionados.value=="";
				var cuantos=0;
				for(var i=0; i<tabla1.getRecordCount(); i++){
						u.seleccionados.value += (u.seleccionados.value!="")?(","+i):i;
						cuantos++;
				}
				
				if (cuantos!=""){
					u.registros.value = cuantos;
					u.accion.value = "grabar";
					u.guardar.style.visibility="hidden";
					u.img_eliminar.style.visibility="hidden";
					u.btnAgregar.style.visibility="hidden";
					document.form1.submit();
					
				}else{
					alerta("Debe seleccionar por lo menos una factura","¡Atención!","guia");
				}
				
		}
	}

	function pedirCliente(datos){
		u.idcliente.value = datos;
		if (u.idcliente.value!=""){
			consultaTexto("mostrarCliente", "notacredito_con.php?accion=2&cliente="+u.idcliente.value+"&valram="+Math.random());
		}else{
			alerta3("Debe Capturar el Codigo del Cliente", "¡Atencion!","idcliente");
		}
	}
	
	function mostrarCliente(datos){
		if(datos!=0){
			var obj = eval(convertirValoresJson(datos));
			u.idcliente.value	= obj[0].id;
			u.cliente.value 	= obj[0].cliente;
			u.direccion.value 	= obj[0].direccion;
			u.rfc.value 		= obj[0].rfc;
			u.ciudad.value 		= obj[0].ciudad;
			u.estado.value 		= obj[0].estado;
			u.concepto.focus();
		}else{
			alerta3("No Existen Datos Con Este Cliente", "¡Atencion!","idcliente");
			limpiarCliente();
		}
	}
	
	function limpiarCliente(){
		u.idcliente.value 	= "";
		u.cliente.value 	= "";
		u.direccion.value 	= "";
		u.rfc.value 		= "";
		u.ciudad.value 		= "";
		u.estado.value 		= "";
	}
	
		var nav4 = window.Event ? true : false;
	function Numeros(evt){ 
		// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46, ',' = 44 
		var key = nav4 ? evt.which : evt.keyCode; 
		return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
	}
	
	function trim(cadena,caja){
		for(i=0;i<cadena.length;)
		{
			if(cadena.charAt(i)==" ")
				cadena=cadena.substring(i+1, cadena.length);
			else
				break;
		}
	
		for(i=cadena.length-1; i>=0; i=cadena.length-1)
		{
			if(cadena.charAt(i)==" ")
				cadena=cadena.substring(0,i);
			else
				break;
		}
		
		document.getElementById(caja).value=cadena;
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
			/*ACA ESTA EL CAMBIO*/
			if (frm.elements[i+1].disabled ==true )    
				tabular(e,frm.elements[i+1]);
			else if (frm.elements[i+1].readOnly ==true )    
				tabular(e,frm.elements[i+1]);				
			else frm.elements[i+1].focus();
			return false;
	} 

	function agregarDatos(variable){	
		var u = document.all;
		if(tabla1.getRecordCount()>0){
			alerta3("Debe de registrar por lo menos una descripición","¡Atencion!");
		}else{
			tabla1.add(variable);					
			u.img_eliminar.style.visibility="visible";
			MostrarTotales();
		}
	}
	
	function MostrarTotales(){
			var vig = ""; var ven = ""; var tot = "";
			v_vig = 0; v_ven = 0; v_tot = 0; 
			
			vig = tabla1.getValuesFromField("importe",",").split(",");
			
			for(var i=0;i<vig.length;i++){
				v_vig = parseFloat(vig[i]) + parseFloat(v_vig);
			}
			u.subtotal.value 	= v_vig;
			u.subtotal.value 	= convertirMoneda(u.subtotal.value);
			u.iva.value			= convertirMoneda(parseFloat(v_vig) * parseFloat(u.impuesto.value));
			u.total.value		= convertirMoneda((parseFloat(v_vig)+(parseFloat(v_vig) * parseFloat(u.impuesto.value))));
			
			esNan('subtotal');
			esNan('iva');
			esNan('total');
			covertirNumLetras(u.total.value);	
			$importe=0;
			$importe=parseFloat(v_vig) + (parseFloat(v_vig) * parseFloat(u.impuesto.value));
				if (u.total.value==0){
					u.letras.value	="";
				}else{
					$importe=$importe.toFixed(2);
					covertirNumLetras($importe);
				}
				
	}
	
	function esNan(caja){
		if (document.getElementById(caja).value.replace('$ ','').replace(/,/g,'')=="NaN"){
				document.getElementById(caja).value	= 0;
		}
	}
	
	function cargarGuia(guia){
		u = document.all;
		u.guia.value = guia;
		cargarDatosGuia();
	}
	
	function cargarDatosGuia(){
		if (u.guia.value!=""){
			consultaTexto("mostrarDatosGuia", "notacredito_con.php?accion=3&guia="+u.guia.value+"&valram="+Math.random());
		}else{
			alerta3("Debe de Capturar una guia", "¡Atencion!","guia");
		}
	}
	
	function mostrarDatosGuia(datos){
		if (datos!=0){
			var obj = eval(convertirValoresJson(datos));
			u.idcliente.value	= obj[0].id;
			u.cliente.value 	= obj[0].cliente;
			u.direccion.value 	= obj[0].direccion;
			u.rfc.value 		= obj[0].rfc;
			u.ciudad.value 		= obj[0].ciudad;
			u.estado.value 		= obj[0].estado;	
			u.cliente.focus();		
		}else{
			alerta3("No existe datos con esta guia", "¡Atencion!","guia");
			limpiarCliente();
			u.guia.value="";
		}
	}
	
	function limpiarTodo(){
			u.folio.value 			= "";
			//u.fecha.value			= "";
			u.guia.value			= "";
			u.idcliente.value		= "";
			u.cliente.value			= "";
			u.direccion.value		= "";
			u.ciudad.value			= "";
			u.rfc.value				= "";
			u.estado.value			= "";
			u.concepto.value		= "";
			u.subtotal.value		= "0";
			u.iva.value				= "0";
			u.total.value			= "0";
			u.letras.value			= "";
			u.idformulo.value		= "";
			u.idreviso.value		= "";
			u.idautorizo.value		= "";
			u.formulo.value			= "";
			u.reviso.value			= "";
			u.autorizo.value		= "";
			tabla1.clear();
			u.guardar.style.visibility="visible"
			u.img_eliminar.style.visibility="hidden";
			u.btnAgregar.style.visibility="visible";
			
			BorraTemporal();
			u.accion.value = "";
	}
	
	function borrarFila(){
		if (tabla1.getSelectedIndex()==""){
				alerta("Debe Seleccionar la fila a eliminar","¡Atención!","guia");	
			}else{
				if(tabla1.getValSelFromField('cantidad','CANTIDAD')!=""){		
					confirmar('¿Esta seguro de Eliminar el detalle seleccionado?',
					'','eliminarFila()','');	
				}else{
					alerta3("Debe Seleccionar la fila a eliminar","¡Atención!");	
				}
		}
	}
	
	function eliminarFila(){
		var arr = tabla1.getSelectedRow();
		consultaTexto("borroFila","notacredito_con.php?accion=7&id="+arr.id+"&valram="+Math.random());
	}
	
	function borroFila(datos){
		if(datos.indexOf("ok")>-1){
			tabla1.deleteById(tabla1.getSelectedIdRow());
			if(tabla1.getRecordCount()==0){
				u.img_eliminar.style.visibility="hidden";
			}
			MostrarTotales();
		}else{
			alerta3("Hubo un error al eliminar "+datos,"¡Atención!");
		}
	}
	function muestraResultado(){
		consultaTexto("mostrarNotasDetalle","notacredito_con.php?accion=7&valram="+Math.random());	
	}
	
	function CalcularImporte(){
		/*
		if (u.cantidad.value!="") {
			u.importe.value=convertirMoneda(u.importe.value);
		}*/
	}
	
	function AgregarDetalle(){
		if(u.cantidad.value==""){
			alerta2('Debe Capturar Cantidad','¡Atención!','cantidad'); 
		}else if(u.descripcion.value==""){ 
			alerta2('Debe Capturar Descripción','¡Atención!','descripcion');
		}else if(u.importe.value==""){
			alerta2('Debe Capturar importe','¡Atención!','importe');	
		}else{ 	
		
			var arr = new Array();
			arr[0] = u.cantidad.value;
			arr[1] = "";
			arr[2] = u.descripcion.value;
			arr[3] = "0";
			arr[4] = u.importe.value.replace(/,/g,'').replace("$ ","");
		
			consultaTexto("mostrarNotasDetalle","notacredito_con.php?accion=1&arre="+arr);	
			limpiardetalle();
			u.img_eliminar.style.visibility="visible";
		}
	}
	
	function limpiardetalle(){
		u.cantidad.value		= "";
		u.descripcion.value		= "";
		u.importe.value			= "";
	}
	
// Función modulo, regresa el residuo de una división 
	function mod(dividendo , divisor) 
	{ 
	  ////////////////////se modifico porque te redondeabba el valor de 28.75 a 29.00
	  ///////////////////mario peraza lga 09/octubre/2009
	  dividendo= parseInt(dividendo)
	  /////////////////////////SE MODIFICO/////////////////////////////////////
	  /////////////////////////SE MODIFICO/////////////////////////////////////
	  resDiv = dividendo / divisor ;  
	
	  parteEnt = Math.floor(resDiv);       // Obtiene la parte Entera de resDiv 
	
	  parteFrac = resDiv - parteEnt ;      // Obtiene la parte Fraccionaria de la división
	  
	  modulo = Math.round(parteFrac * divisor);  // Regresa la parte fraccionaria * la división (modulo) 
	
	  return modulo; 
	} // Fin de función mod
	
	// Función ObtenerParteEntDiv, regresa la parte entera de una división
	function ObtenerParteEntDiv(dividendo , divisor) 
	{ 
	  resDiv = dividendo / divisor ;  
	  parteEntDiv = Math.floor(resDiv); 
	  return parteEntDiv; 
	} // Fin de función ObtenerParteEntDiv
	
	// function fraction_part, regresa la parte Fraccionaria de una cantidad
	function fraction_part(dividendo , divisor) 
	{ 
	  resDiv = dividendo / divisor ;  
	  f_part = Math.floor(resDiv); 
	  return f_part; 
	} // Fin de función fraction_part
	
	
	// function string_literal conversion is the core of this program 
	// converts numbers to spanish strings, handling the general special 
	// cases in spanish language. 
	function string_literal_conversion(number) 
	{   
	   // first, divide your number in hundreds, tens and units, cascadig 
	   // trough subsequent divisions, using the modulus of each division 
	   // for the next. 
	
	   centenas = ObtenerParteEntDiv(number, 100); 
	   
	   number = mod(number, 100); 
	
	   decenas = ObtenerParteEntDiv(number, 10); 
	   number = mod(number, 10); 
	
	   unidades = ObtenerParteEntDiv(number, 1); 
	   number = mod(number, 1);  
	   string_hundreds="";
	   string_tens="";
	   string_units="";
	   // cascade trough hundreds. This will convert the hundreds part to 
	   // their corresponding string in spanish.
	   if(centenas == 1){
		  string_hundreds = "ciento ";
	   } 
	   
	   
	   if(centenas == 2){
		  string_hundreds = "doscientos ";
	   }
		
	   if(centenas == 3){
		  string_hundreds = "trescientos ";
	   } 
	   
	   if(centenas == 4){
		  string_hundreds = "cuatrocientos ";
	   } 
	   
	   if(centenas == 5){
		  string_hundreds = "quinientos ";
	   } 
	   
	   if(centenas == 6){
		  string_hundreds = "seiscientos ";
	   } 
	   
	   if(centenas == 7){
		  string_hundreds = "setecientos ";
	   } 
	   
	   if(centenas == 8){
		  string_hundreds = "ochocientos ";
	   } 
	   
	   if(centenas == 9){
		  string_hundreds = "novecientos ";
	   } 
	   
	 // end switch hundreds 
	
	   // casgade trough tens. This will convert the tens part to corresponding 
	   // strings in spanish. Note, however that the strings between 11 and 19 
	   // are all special cases. Also 21-29 is a special case in spanish. 
	   if(decenas == 1){
		  //Special case, depends on units for each conversion
		  if(unidades == 1){
			 string_tens = "once";
		  }
		  
		  if(unidades == 2){
			 string_tens = "doce";
		  }
		  
		  if(unidades == 3){
			 string_tens = "trece";
		  }
		  
		  if(unidades == 4){
			 string_tens = "catorce";
		  }
		  
		  if(unidades == 5){
			 string_tens = "quince";
		  }
		  
		  if(unidades == 6){
			 string_tens = "dieciseis";
		  }
		  
		  if(unidades == 7){
			 string_tens = "diecisiete";
		  }
		  
		  if(unidades == 8){
			 string_tens = "dieciocho";
		  }
		  
		  if(unidades == 9){
			 string_tens = "diecinueve";
		  }
	   } 
	   //alert("STRING_TENS ="+string_tens);
	   
	   if(decenas == 2){
		  string_tens = "veinti";
	   }
	   if(decenas == 3){
		  string_tens = "treinta";
	   }
	   if(decenas == 4){
		  string_tens = "cuarenta";
	   }
	   if(decenas == 5){
		  string_tens = "cincuenta";
	   }
	   if(decenas == 6){
		  string_tens = "sesenta";
	   }
	   if(decenas == 7){
		  string_tens = "setenta";
	   }
	   if(decenas == 8){
		  string_tens = "ochenta";
	   }
	   if(decenas == 9){
		  string_tens = "noventa";
	   }
	   
		// Fin de swicth decenas
	
	
	   // cascades trough units, This will convert the units part to corresponding 
	   // strings in spanish. Note however that a check is being made to see wether 
	   // the special cases 11-19 were used. In that case, the whole conversion of 
	   // individual units is ignored since it was already made in the tens cascade. 
	
	   if (decenas == 1) 
	   { 
		  string_units="";  // empties the units check, since it has alredy been handled on the tens switch 
	   } 
	   else 
	   { 
		  if(unidades == 1){
			 string_units = "un";
		  }
		  if(unidades == 2){
			 string_units = "dos";
		  }
		  if(unidades == 3){
			 string_units = "tres";
		  }
		  if(unidades == 4){
			 string_units = "cuatro";
		  }
		  if(unidades == 5){
			 string_units = "cinco";
		  }
		  if(unidades == 6){
			 string_units = "seis";
		  }
		  if(unidades == 7){
			 string_units = "siete";
		  }
		  if(unidades == 8){
			 string_units = "ocho";
		  }
		  if(unidades == 9){
			 string_units = "nueve";
		  }
		   // end switch units 
	   } // end if-then-else 
	   
	
	//final special cases. This conditions will handle the special cases which 
	//are not as general as the ones in the cascades. Basically four: 
	
	// when you've got 100, you dont' say 'ciento' you say 'cien' 
	// 'ciento' is used only for [101 >= number > 199] 
	if (centenas == 1 && decenas == 0 && unidades == 0) 
	{ 
	   string_hundreds = "cien " ; 
	}  
	
	// when you've got 10, you don't say any of the 11-19 special 
	// cases.. just say 'diez' 
	if (decenas == 1 && unidades ==0) 
	{ 
	   string_tens = "diez " ; 
	} 
	
	// when you've got 20, you don't say 'veinti', which is used 
	// only for [21 >= number > 29] 
	if (decenas == 2 && unidades ==0) 
	{ 
	  string_tens = "veinte " ; 
	} 
	
	// for numbers >= 30, you don't use a single word such as veintiuno 
	// (twenty one), you must add 'y' (and), and use two words. v.gr 31 
	// 'treinta y uno' (thirty and one) 
	if (decenas >=3 && unidades >=1) 
	{ 
	   string_tens = string_tens+" y "; 
	} 
	
	// this line gathers all the hundreds, tens and units into the final string 
	// and returns it as the function value.
	final_string = string_hundreds+string_tens+string_units;
	
	
	return final_string ; 
	
	} //end of function string_literal_conversion()================================ 
	
	// handle some external special cases. Specially the millions, thousands 
	// and hundreds descriptors. Since the same rules apply to all number triads 
	// descriptions are handled outside the string conversion function, so it can 
	// be re used for each triad. 
	
	
	function covertirNumLetras(number)
	{
	   
	  //number = number_format (number, 2);
	   number1=number;
	   //settype (number, "integer");
	   cent = number1.split('.');   
	   centavos = cent[1];
	 
	   if (centavos == 0 || centavos == undefined){
	   centavos = "00";}
	
	   if (number == 0 || number == "") 
	   { // if amount = 0, then forget all about conversions, 
		  centenas_final_string=" cero "; // amount is zero (cero). handle it externally, to 
		  // function breakdown 
	  } 
	   else 
	   { 
	   
	   	
		 millions  = ObtenerParteEntDiv(number, 1000000); // first, send the millions to the string 
		
		 number = mod(number, 1000000);           // conversion function 
		
		 
		 if (millions != 0)
		  {                      
		  // This condition handles the plural case 
			 if (millions == 1) 
			 {              // if only 1, use 'millon' (million). if 
				descriptor= " millon ";  // > than 1, use 'millones' (millions) as 
				} 
			 else 
			 {                           // a descriptor for this triad. 
				  descriptor = " millones "; 
				} 
		  } 
		  else 
		  {    
			 descriptor = " ";                 // if 0 million then use no descriptor. 
		  } 
		  millions_final_string = string_literal_conversion(millions)+descriptor; 
			  
		  
		  thousands = ObtenerParteEntDiv(number, 1000);  // now, send the thousands to the string 
			number = mod(number, 1000);            // conversion function.
			
		  //print "Th:".thousands;
		 if (thousands != 1) 
		  {                   // This condition eliminates the descriptor 
			 thousands_final_string =string_literal_conversion(thousands) + " mil "; 
		   //  descriptor = " mil ";          // if there are no thousands on the amount 
		  } 
		  if (thousands == 1)
		  {
			 thousands_final_string = " mil "; 
		 }
		  if (thousands < 1) 
		  { 
			 thousands_final_string = " "; 
		  } 
	  
		  // this will handle numbers between 1 and 999 which 
		  // need no descriptor whatsoever. 
	
		 centenas  = number;               
		 centenas_final_string = string_literal_conversion(centenas) ; 
		  
	   } //end if (number ==0) 
	
	   /* Concatena los millones, miles y cientos*/
	   cad = millions_final_string+thousands_final_string+centenas_final_string; 
	   
	   /* Convierte la cadena a Mayúsculas*/
	   cad = cad.toUpperCase();       
	
	   if (centavos.length>2)
	   {   
		  if(centavos.substring(2,3)>= 5){
			 centavos = centavos.substring(0,1)+(parseInt(centavos.substring(1,2))+1).toString();
		  }   else{
			centavos = centavos.substring(0,2);
		   }
	   }
	
	   /* Concatena a los centavos la cadena "/100" */
	   if (centavos.length==1)
	   {
		  centavos = centavos+"0";
	   }
	   centavos = centavos+ "/100"; 
	
	
	   /* Asigna el tipo de moneda, para 1 = PESO, para distinto de 1 = PESOS*/
	   if (number == 1)
	   {
		  moneda = " PESO ";  
	   }
	   else
	   {
		  moneda = " PESOS ";  
	   }
	   /* Regresa el número en cadena entre paréntesis y con tipo de moneda y la fase M.N.*/
	   var importe = cad+moneda+centavos+" M.N.";
	  
	   //alert(importe);
	   u.letras.value=importe;
		}
		
	function BuscarEmpleadoFormulo(id){
		u.idformulo.value=id;
		consultaTexto("mostrarEmpleadoFormulo", "notacredito_con.php?accion=10&idempleado="+u.idformulo.value+"&valram="+Math.random());
	}
	
	function BuscarEmpleadoReviso(id){
		u.idreviso.value=id;
		consultaTexto("mostrarEmpleadoReviso", "notacredito_con.php?accion=10&idempleado="+u.idreviso.value+"&valram="+Math.random());
	}
	
	function BuscarEmpleadoAutorizo(id){
		u.idautorizo.value=id;
	consultaTexto("mostrarEmpleadoAutorizo", "notacredito_con.php?accion=10&idempleado="+u.idautorizo.value+"&valram="+Math.random());
	}
	
	function mostrarEmpleadoFormulo(datos){	
		if (datos!=0){
			var obj = eval(convertirValoresJson(datos));
			u.formulo.value			= obj[0].empleado;
		}else{
			u.formulo.value			= "";
		}
	}
	
	function mostrarEmpleadoReviso(datos){	
		if (datos!=0){
			var obj = eval(convertirValoresJson(datos));
			u.reviso.value			= obj[0].empleado;
		}else{
			u.reviso.value			="";
		}
	}
	
	function mostrarEmpleadoAutorizo(datos){	
		if (datos!=0){
			var obj = eval(convertirValoresJson(datos));
			u.autorizo.value		= obj[0].empleado;
		}else{
			u.autorizo.value			="";
		}
	}

</script>
	
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">

	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:175px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:0.9em;
		z-index:100;
	}
	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */
		margin:1px;		
		padding:1px;
		cursor:pointer;
		font-size:0.9em;
	}
	#ajax_listOfOptions .optionDiv{	/* Div for each item in list */
		
	}
	#ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */
		background-color:#317082;
		color:#FFF;
	}
	#ajax_listOfOptions_iframe{
		background-color:#F00;
		position:absolute;
		z-index:5;
	}
	
	form{
		display:inline;
	}
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
-->
</style>
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.style31 {font-size: 9px;
	color: #464442;
}
.style31 {font-size: 9px;
	color: #464442;
}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="601" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="617" class="FondoTabla Estilo4">NOTAS DE CR&Eacute;DITO</td>
  </tr>
  <tr>
    <td height="98">
      <table width="597" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="4"></td>
          </tr>
        
        <tr align="center">
          <td colspan="4"><div align="left">
            <table width="599" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td>&nbsp;</td>
                <td><a href="../menu/webministator.php"></a></td>
                <td>Fecha:</td>
                <td><span class="Tablas">
                  <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
                </span></td>
                <td>Folio:</td>
                <td><input name="folio" type="text" class="Tablas" id="folio" style="width:70px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
                  <img src="../img/Buscar_24.gif" width="24" height="23" style="cursor:pointer" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarFolionotacredito.php?funcion=obtenerFolionotascredito', 625, 550, 'ventana', 'Busqueda')" /></td>
              </tr>
              <tr>
                <td colspan="6" class="FondoTabla Estilo4">Datos Cliente </td>
                </tr>
              <tr>
                <td width="70">Guia: </td>
                <td width="236"><span class="Tablas">
                  <input name="guia" type="text" class="Tablas" id="guia" style="width:140px" onKeyPress="if(event.keyCode=='13'){cargarDatosGuia(this.value)};" value="<?=$guia ?>" />
                  <img src="../img/Buscar_24.gif" width="24" height="23" style="cursor:pointer" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarguianotacredito.php?funcion=cargarGuia', 570, 470, 'ventana', 'Busqueda')" /></span></td>
                <td width="32">&nbsp;</td>
                <td width="113">&nbsp;</td>
                <td width="36">&nbsp;</td>
                <td width="112">&nbsp;</td>
              </tr>
              <tr>
                <td height="27">A Favor de: </td>
                <td colspan="5"><span class="Tablas">
                  <input name="idcliente" type="text" class="Tablas" id="idcliente" style="width:100px" value="<?=$idcliente ?>" onKeyPress="if(event.keyCode=='13'){pedirCliente(this.value);};"/>
                  <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen2.php?funcion=pedirCliente', 625, 450, 'ventana', 'Buscar Cliente')	  " />
                  <input name="cliente" type="text" class="Tablas" id="cliente" style="width:370px;background:#FFFF99" value="<?=$cliente ?>" readonly=""/>
                </span></td>
                </tr>
              <tr>
                <td>Direcci&oacute;n:</td>
                <td colspan="5"><span class="Tablas">
                  <input name="direccion" type="text" class="Tablas" id="direccion" style="width:500px;background:#FFFF99" onKeyPress="if(event.keyCode=='13'){obtenerNotaCredito(this.value)};return tabular(event,this)" value="<?=$direccion ?>" readonly=""/>
                </span></td>
                </tr>
              <tr>
                <td height="22">RFC:</td>
                <td colspan="2"><span class="Tablas">
                  <input name="rfc" type="text" class="Tablas" id="rfc" style="width:180px;background:#FFFF99" onKeyPress="if(event.keyCode=='13'){obtenerNotaCredito(this.value)};return tabular(event,this)" value="<?=$rfc ?>" readonly=""/>
                </span></td>
                <td colspan="3">Ciudad:<span class="Tablas">
                  <input name="ciudad" type="text" class="Tablas" id="ciudad" style="width:70px;background:#FFFF99" onKeyPress="if(event.keyCode=='13'){obtenerNotaCredito(this.value)};return tabular(event,this)" value="<?=$ciudad ?>" readonly=""/>
                Estado:
                <input name="estado" type="text" class="Tablas" id="estado" style="width:80px;background:#FFFF99" onKeyPress="if(event.keyCode=='13'){obtenerNotaCredito(this.value)};return tabular(event,this)" value="<?=$estado ?>" readonly=""/>
                </span></td>
                </tr>
              <tr>
                <td colspan="6" class="FondoTabla Estilo4">Detalle</td>
              </tr>
            </table>
          </div></td>
        </tr>
        
        <tr align="center">
          <td colspan="4">		</td>
        </tr>
        <tr align="center">
          <td colspan="4">    <table width="596" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="51"><div align="right">Cantidad:</div></td>
              <td width="43"><input name="cantidad" type="text" class="Tablas" id="cantidad" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$cantidad ?>" size="5" maxlength="5" /></td>
              <td width="65"><div align="right">Descripci&oacute;n:</div></td>
              <td width="202"><input name="descripcion" type="text" class="Tablas" id="descripcion" onKeyDown="return tabular(event,this)" value="<?=$descripcion ?>" size="40" maxlength="100" /></td>
              <td width="46"><div align="right">Importe:</div></td>
              <td width="67"><div align="left"><span class="Tablas">
                <input name="importe" type="text" class="Tablas" id="importe"  onBlur="CalcularImporte()" onKeyPress="return Numeros(event)" onKeyDown="CalcularImporte(event);" value="<?=$importe ?>" size="10" maxlength="15" />
              </span></div></td>
              <td width="122"><img src="../img/Boton_Agregari.gif" name="btnAgregar" width="70" height="20" id="btnAgregar" style=":<? if($_POST[accion]=='grabar'){?>visibility:hidden<? }?>;cursor:pointer" onClick="AgregarDetalle()" /></td>
            </tr>
          </table>
            <table width="580" id="detalle" border="0" cellpadding="0" cellspacing="0">
              </table></td>
        </tr>
        <tr align="center">
          <td width="53" rowspan="3"><div align="left">Concepto:</div></td>
          <td width="366"><div align="left"><span class="Tablas">
            <input name="letras" type="text" class="Tablas" id="letras" style="text-align:center;width:363px;background:#FFFF99" value="<?=$letras?>" size="0" readonly=""/>
          </span></div></td>
          <td width="60"><div align="right">SUBTOTAL:</div></td>
          <td width="120"><div align="left"><span class="Tablas">
            <input name="subtotal" type="text" class="Tablas" id="subtotal" style="text-align:right;width:100px;background:#FFFF99" value="<?=$subtotal ?>" readonly=""/>
          </span></div></td>
        </tr>
        <tr align="center">
          <td width="366" rowspan="2"><span class="Tablas">
            <textarea name="concepto" class="Tablas" rows="3" id="concepto" style="width:365px; text-transform:uppercase" ><?=$concepto ?>
                  </textarea>
          </span></td>
          <td><div align="right">IVA:</div></td>
          <td><div align="left"><span class="Tablas">
            <input name="iva" type="text" class="Tablas" id="iva" style="text-align:right;width:100px;background:#FFFF99" value="<?=$iva ?>" readonly=""/>
          </span></div></td>
        </tr>
        <tr align="center">
          <td><div align="right">TOTAL:</div></td>
          <td><div align="left"><span class="Tablas">
            <input name="total" type="text" class="Tablas" id="total" style="text-align:right;width:100px;background:#FFFF99" value="<?=$total ?>" readonly=""/>
          </span></div></td>
        </tr>
        <tr align="center">
          <td colspan="4"><table width="599" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td  colspan="11" class="FondoTabla Estilo4">&nbsp;</td>
              </tr>
              <tr>
                <td width="44">Formulo:</td>
                <td width="154"><span class="Tablas">
                  <input name="formulo" type="text" class="Tablas" id="formulo" style="width:100px" value="<?=$formulo ?>" onKeyPress="if(event.keyCode=='13'){pedirCliente(this.value);};"/>
                  <img src="../img/Buscar_24.gif" width="24" height="23" style="cursor:pointer" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarEmpleadoGen.php?funcion=BuscarEmpleadoFormulo', 600, 430, 'ventana', 'Busqueda')" /><a href="../menu/webministator.php">
                  <input name="idformulo" type="hidden" id="idformulo" value="<?=$idformulo ?>">
                  </a></span></td>
                <td width="193">Reviso:<span class="Tablas">
                  <input name="reviso" type="text" class="Tablas" id="reviso" style="width:100px" value="<?=$reviso ?>" onKeyPress="if(event.keyCode=='13'){pedirCliente(this.value);};"/>
                  <img src="../img/Buscar_24.gif" width="24" height="23" style="cursor:pointer" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarEmpleadoGen.php?funcion=BuscarEmpleadoReviso', 600, 430, 'ventana', 'Busqueda')" /><a href="../menu/webministator.php">
                  <input name="idreviso" type="hidden" id="idreviso" value="<?=$idreviso ?>">
                  </a></span></td>
                <td width="47">Autorizo:</td>
                <td width="149"><span class="Tablas">
                  <input name="autorizo" type="text" class="Tablas" id="autorizo" style="width:100px" value="<?=$autorizo ?>" onKeyPress="if(event.keyCode=='13'){pedirCliente(this.value);};"/>
                  <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('../buscadores_generales/buscarEmpleadoGen.php?funcion=BuscarEmpleadoAutorizo', 600, 430, 'ventana', 'Busqueda')" /><a href="../menu/webministator.php">
                  <input name="idautorizo" type="hidden" id="idautorizo" value="<?=$idautorizo ?>">
                  </a></span></td>
                <td width="12">&nbsp;</td>
              </tr>
              <tr>
                <td  colspan="11" class="FondoTabla Estilo4">&nbsp;</td>
                </tr>
              
          </table></td>
        </tr>
        <tr>
          <td colspan="4"><table width="237" align="center">
            <tr>
              <td width="229"><table width="229" border="0">
                  <tr>
                    <td width="75" ><div id="guardar" style=":<? if($_POST[accion]=='grabar'){?>visibility:hidden<? }?>" class="ebtn_guardar" onClick="validar()" ></div></td>
                    <td width="70"><div class="ebtn_nuevo" id="nuevo" onClick="limpiarTodo()"/></td>
                    <td width="70"><img  id="img_eliminar" src="../img/Boton_Eliminar.gif" alt="Eliminar" width="70" height="20" style="cursor:pointer" onClick="borrarFila()" /></td>
                  </tr>
              </table></td>
              </tr>
          </table>
            </td>
        </tr>
        <tr>
          <td colspan="4"><p>
                <center>
                  
                  <input name="impuesto" type="hidden" id="impuesto" value="<?=$impuesto ?>">
                  <input name="registros" type="hidden" id="registros" value="<?=$registropersona ?>">
				  <input name="seleccionados" type="hidden" id="seleccionados" >
                  <input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
                 
                </center>
              </p></td>
          </tr>
      </table></td>
  </tr>

  </table>
</td>
  </tr>
        
        <tr>
          <td>&nbsp;</td>
  </tr>
        <tr>
          <td width="653">&nbsp;</td>
  </tr>
      </table>
    </td>
  </tr>
</table>

<p>&nbsp;</p>
</form>
</body>
</html>

<?
	if ($mensaje!=""){
		echo "<script>
			info('".$mensaje."');
		</script>";

	}
?>