<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "select * from catalogosucursal where id = $_SESSION[IDSUCURSAL]";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$nombresucursal = $f->descripcion;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/ajax.js"></script>
<script language="javascript" src="../javascript/ClaseMensajes.js"></script>
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<script language="javascript" src="../javascript/DataSet.js"></script>
<script language="javascript" src="../javascript/funciones.js"></script>
<script language="javascript" src="../javascript/jquery-1.4.js"></script>
<style type="text/css">
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
.Estilo4 {font-size: 12px}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
</style>
<script>
	var mens = new ClaseMensajes();
	var dataSet1 = new DataSet();
	var tabla2 = new ClaseTabla();
	mens.iniciar("../javascript");
	
	function ge(id){
		return document.getElementById(id);
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	
	tabla2.setAttributes({
		nombre:"detalleleido",
		campos:[
			{nombre:"SEL", medida:30, alineacion:"center", tipo:'checkbox', datos:"seleccion", onClick:'seleccionar'},
			{nombre:"FOLIO", medida:80, alineacion:"center", datos:"guia"},
			{nombre:"CLIENTE", medida:100, alineacion:"left", datos:"cliente"},
			{nombre:"FISICO", medida:75, alineacion:"center", datos:"fisico"},
			{nombre:"TIPO", medida:30, alineacion:"center", datos:"tipoalmacen"},
			{nombre:"P", medida:4, tipo:'oculto', alineacion:"center", datos:"tipopago"},
			{nombre:"T", medida:4, tipo:'oculto', alineacion:"center", datos:"tipo"},
			{nombre:"FACT", medida:30, alineacion:"center", datos:"factura"},
			{nombre:"COMP", medida:30, alineacion:"center", datos:"estadoguia"},
			{nombre:"IMPORTE", medida:80,tipo:"moneda", alineacion:"right", datos:"importe"}
		],
		filasInicial:20,
		alto:220,
		seleccion:false,
		ordenable:true,
		nombrevar:"tabla2"
	});
	
	function limpiar(){
		tabla2.clear();
		ge('inventarioactual').innerHTML="";
		ge('saldoactual').innerHTML="";
		ge('saldofinal').innerHTML="";
		
		ge('ajustestotal').innerHTML="";
		ge('ajustestotalseleccionado').innerHTML="";
		ge('ajustesrestante').innerHTML="";
		
		ge('celdaactualizar').style.display='';
		ge('celdaguardar').style.display='';
		ge('celdaguardar').style.display='';
		ge('celdaCerrar').style.display='none';
		ge('yaGuardado').value = "";
	}
	
	function seleccionar(datos,indice){
		var fila = tabla2.getRowByIndex(indice);
		fila.seleccion = ((datos)?'S':'1');
		dataSet1.actualizarRegistro(fila,dataSet1.indice+1,indice);
		
		var total=0;
		
		for(var i=0; i<dataSet1.totalRegistros; i++){
			if(dataSet1.registros[i].seleccion=='S'){
				total += parseFloat(dataSet1.registros[i].importe);
			}
		}
		
		ge('ajustestotalseleccionado').innerHTML = "$ " + numcredvar(total.toString());
		var totalgeneral = parseFloat(ge('ajustestotal').innerHTML.replace("$ ","").replace(/,/g,""));
		
		var restante = totalgeneral-total;
		
		ge('ajustesrestante').innerHTML = "$ " + numcredvar(restante.toString());
	}
	
	window.onload = function(){
		tabla2.create();
		dataSet1.crear({
			'paginasDe':60,
			'objetoTabla':tabla2,
			'objetoPaginador':document.getElementById('celdapaginado'),
			'nombreVariable':'dataSet1',
			'ubicacion':'../',
			'funcionOrdenar':function(a,b){
				return parseInt(a.guia.toString().substring(0,12)+a.guia.toString().charCodeAt(12))-
				parseInt(b.guia.toString().substring(0,12)+b.guia.toString().charCodeAt(12))
			}
		});
		
		
		consultaTexto("respuestaDatos","auditoriasucursalnuevo_con.php?sucursalseleccionada="+ge('sucursal').value+"&accion=1&val="+Math.random());
	}
	
	function consultarCambioSucursal(){
		consultaTexto("respuestaDatos","auditoriasucursalnuevo_con.php?sucursalseleccionada="+ge('sucursal').value+"&accion=1&val="+Math.random());
	}
	
	function respuestaDatos(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			if(datos.indexOf('sin cerrar')<0)
				mens.show("A",datos);
			else{
				pedirDatosAuditoria(datos.split(',')[1]);
				return false;
			}
		}
		limpiar()
		if(obj.anterior.fecha==""){
			ge('label1').innerHTML = "INVENTARIO ANTERIOR";
			ge('label2').innerHTML = "CARTERA ANTERIOR";
			ge('fechadel').innerHTML = obj.anterior.factual;
			//ge('fechaal').innerHTML = obj.anterior.factual;
		}else{
			/*ge('label1').innerHTML = "INVENTARIO AL "+obj.anterior.fechaanterior;
			ge('label2').innerHTML = "CARTERA AL "+obj.anterior.fechaanterior;
			ge('fechadel').innerHTML = obj.anterior.fecha;*/
			ge('label1').innerHTML = "INVENTARIO AL "+obj.anterior.fechaanterior;
			ge('label2').innerHTML = "CARTERA AL "+obj.anterior.fechaanterior;
			ge('fechadel').innerHTML = obj.anterior.factual;
			//ge('fechaal').innerHTML = obj.anterior.factual;
		}
		
		ge('folioauditoria').innerHTML = obj.anterior.folioauditoria;
		
		ge('saldoanterior').innerHTML = "$ " + numcredvar(obj.anterior.saldoanterior);
		ge('inventarioal').innerHTML = "$ " + numcredvar(obj.anterior.inventarioal);
		ge('carteraal').innerHTML = "$ " + numcredvar(obj.anterior.carteraal);
		
		ge('liquidaciones').innerHTML = "$ " + numcredvar(obj.liquidaciones);
		
		ge('depositos').innerHTML = "$ " + numcredvar(obj.depositos);
		ge('facturascanceladas').innerHTML = "$ " + numcredvar(obj.facturascanceladas);
		ge('guiascanceladas').innerHTML = "$ " + numcredvar(obj.guiascanceladas);
		ge('notascredito').innerHTML = "$ " + numcredvar(obj.notacredito);
		
		ge('inventariosistema').innerHTML = "$ " + numcredvar(obj.inventariosistema);
		ge('carterasistema').innerHTML = "$ " + numcredvar(obj.carterasistema);
		
		var saldocontable = parseFloat(obj.anterior.saldoanterior)+
			parseFloat(obj.anterior.inventarioal)+
		  	parseFloat(obj.anterior.carteraal)+
		   	parseFloat(obj.liquidaciones)-
			
			parseFloat(obj.depositos)-
			parseFloat(obj.facturascanceladas)-
			parseFloat(obj.guiascanceladas)-
			parseFloat(obj.notacredito);
		
		ge('saldocontable').innerHTML = "$ " + numcredvar(saldocontable.toString());
	}
	
	function pedirRecolectado(){
		consultaTexto("respuestaRec","auditoriasucursalnuevo_con.php?sucursalseleccionada="+ge('sucursal').value+"&accion=2&val="+Math.random());
	}
	
	function respuestaRec(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		dataSet1.setJsonData(obj.registros);
		
		ge('saldoactual').innerHTML = "$ " + numcredvar(obj.cartera);
		ge('inventarioactual').innerHTML = "$ " + numcredvar(obj.inventario);
		
		var saldocontable = parseFloat(ge('saldoanterior').innerHTML.replace("$ ","").replace(/,/g,""))+
			parseFloat(ge('inventarioal').innerHTML.replace("$ ","").replace(/,/g,""))+
		  	parseFloat(ge('carteraal').innerHTML.replace("$ ","").replace(/,/g,""))+
		   	parseFloat(ge('liquidaciones').innerHTML.replace("$ ","").replace(/,/g,""))-
			
			parseFloat(ge('depositos').innerHTML.replace("$ ","").replace(/,/g,""))-
			parseFloat(ge('facturascanceladas').innerHTML.replace("$ ","").replace(/,/g,""))-
			parseFloat(ge('guiascanceladas').innerHTML.replace("$ ","").replace(/,/g,""))-
			parseFloat(ge('notascredito').innerHTML.replace("$ ","").replace(/,/g,""))-
			
			parseFloat(obj.cartera)-
			parseFloat(obj.inventario);
			
		ge('saldofinal').innerHTML = "$ " + numcredvar(saldocontable.toFixed(2).toString());
		ge('ajustestotal').innerHTML = "$ " + numcredvar(saldocontable.toFixed(2).toString());
		ge('ajustestotalseleccionado').innerHTML = "$ 0.00";
		ge('ajustesrestante').innerHTML = "$ " + numcredvar(saldocontable.toFixed(2).toString());
		
		//for(var i=0; i<tabla2.getRecordCount(); i++){
		//	document.all['detalleleido_SEL'][i].checked = true;
		//}
	}
	
	//ventanas individuales
	function validaConsultado(){
		if(ge('yaGuardado').value == "1")
			return "&folio="+ge('folioauditoria').innerHTML;
		else
			return "";
	}
	
	function mostrarLiquidaciones(){
		var agregarfolio = validaConsultado();
		mens.popup("auditoriasucursal_liquidaciones.php?sucursalseleccionada="+ge('sucursal').value+agregarfolio,700,400,'','Liquidaciones');
	}
	
	function mostrarDepositos(){
		var agregarfolio = validaConsultado();
		mens.popup("auditoriasucursal_depositos.php?sucursalseleccionada="+ge('sucursal').value+agregarfolio,500,400,'','Deposito');
	}
	
	function mostrarNotascredito(){
		var agregarfolio = validaConsultado();
		mens.popup("auditoriasucursal_notascredito.php?sucursalseleccionada="+ge('sucursal').value+agregarfolio,500,400,'','Nota Crédito');
	}
	
	function mostrarFacturascanceladas(){
		var agregarfolio = validaConsultado();
		mens.popup("auditoriasucursal_facturascanceladas.php?sucursalseleccionada="+ge('sucursal').value+agregarfolio,600,400,'','Facturas Canceladas');
	}
	
	function mostrarGuiascanceladas(){
		var agregarfolio = validaConsultado();
		mens.popup("auditoriasucursal_guiascanceladas.php?sucursalseleccionada="+ge('sucursal').value+agregarfolio,600,400,'','Guias Canceladas');
	}
	
	function mostrarSaldoFinal(){
		var agregarfolio = validaConsultado();
		mens.popup("auditoriasucursal_saldofinal.php?sucursalseleccionada="+ge('sucursal').value+agregarfolio,600,400,'','Saldo final');
	}
	
	function mostrarCarteraSistema(){
		var agregarfolio = validaConsultado();
		mens.popup("auditoriasucursal_carterasistema.php?sucursalseleccionada="+ge('sucursal').value+agregarfolio,600,400,'','Cartera Sistema');
	}
	
	function mostrarInventarioSistema(){
		var agregarfolio = validaConsultado();
		mens.popup("auditoriasucursal_inventariosistema.php?sucursalseleccionada="+ge('sucursal').value+agregarfolio,600,400,'','Inventario Sistema');
	}
	
	//guardar
	function guardar(valor){
		
		if(valor==1){
			var cerrar="&cerrar=1";
		}else{
			var cerrar="";
		}
		
		var saldoanterior = ge('saldoanterior').innerHTML.replace("$ ","").replace(/,/g,"");
		var inventarioal = ge('inventarioal').innerHTML.replace("$ ","").replace(/,/g,"");
		var carteraal = ge('carteraal').innerHTML.replace("$ ","").replace(/,/g,"");
		
		var liquidaciones = ge('liquidaciones').innerHTML.replace("$ ","").replace(/,/g,"");
		var depositos = ge('depositos').innerHTML.replace("$ ","").replace(/,/g,"");
		var facturascanceladas = ge('facturascanceladas').innerHTML.replace("$ ","").replace(/,/g,"");
		var guiascanceladas = ge('guiascanceladas').innerHTML.replace("$ ","").replace(/,/g,"");
		var notasdecredito = ge('notascredito').innerHTML.replace("$ ","").replace(/,/g,"");
		var saldocontable = ge('saldocontable').innerHTML.replace("$ ","").replace(/,/g,"");
		
		var inventariosistema = ge('inventariosistema').innerHTML.replace("$ ","").replace(/,/g,"");
		var carterasistema = ge('carterasistema').innerHTML.replace("$ ","").replace(/,/g,"");
		
		var inventarioalcierre = ge('inventarioactual').innerHTML.replace("$ ","").replace(/,/g,"");
		var carteraalcierre = ge('saldoactual').innerHTML.replace("$ ","").replace(/,/g,"");
		var saldofinal = ge('saldofinal').innerHTML.replace("$ ","").replace(/,/g,"");
		
		var ajustestotalseleccionado = ge('ajustestotalseleccionado').innerHTML.replace("$ ","").replace(/,/g,"");
		var ajustesrestante = ge('ajustesrestante').innerHTML.replace("$ ","").replace(/,/g,"");
		
		var folios = "";
		var guiasnopermitidas = "";
		
		for(var i=0; i<dataSet1.totalRegistros; i++){
			var obj = dataSet1.registros[i];
			if(obj.seleccion=='S'){
				if(obj.tipopago=='CR' && obj.tipo=='V' && obj.factura=='0'){
					guiasnopermitidas += ((guiasnopermitidas!="")?",":"");
					guiasnopermitidas += obj.guia;
				}
				folios += ((folios!="")?",":"");
				folios += obj.guia;
			}
		}
		
		/*for(var i=0; i<tabla2.getRecordCount(); i++){
			if(document.all['detalleleido_SEL'][i].checked){
				if(document.all['detalleleido_P'][i].value=='CR' && document.all['detalleleido_T'][i].value=='V' 
				&& document.all['detalleleido_FACT'][i].value=='0'){
					guiasnopermitidas += ((guiasnopermitidas!="")?",":"");
					guiasnopermitidas += document.all['detalleleido_FOLIO'][i].value.replace("$ ","").replace(/,/g,"");
				}
				folios += ((folios!="")?",":"");
				folios += document.all['detalleleido_FOLIO'][i].value.replace("$ ","").replace(/,/g,"");
			}
		}*/
		if(guiasnopermitidas!=""){
			mens.show("A","Las guias:<br>"+guiasnopermitidas.substring(0,98)+"...<br>No pueden ser pagadas, realice primero una factura","¡ATENCION!");
			return false;
		}
		
		$.ajax({
		   type: "POST",
		   url: "auditoriasucursalnuevo_con.php",
		   data: "sucursalseleccionada="+ge('sucursal').value+"&accion=9"+
				  "&yaGuardado="+ge('yaGuardado').value+"&folioauditoria="+ge('folioauditoria').innerHTML+cerrar+
				  "&saldoanterior="+saldoanterior+"&inventarioal="+inventarioal+"&carteraal="+carteraal+"&ajustesal=0"+
				  "&liquidaciones="+liquidaciones+"&depositos="+depositos+"&facturascanceladas="+facturascanceladas+
				  "&guiascanceladas="+guiascanceladas+"&notasdecredito="+notasdecredito+"&saldocontable="+saldocontable+
				  "&inventariosistema="+inventariosistema+"&carterasistema="+carterasistema+
				  "&inventarioalcierre="+inventarioalcierre+"&carteraalcierre="+carteraalcierre+"&saldofinal="+saldofinal+
				  "&ajustestotalseleccionado="+ajustestotalseleccionado+"&ajustesrestante="+ajustesrestante+
				  "&foliosajustes="+folios,
		   success: function(datos){
			 if(datos.indexOf("_ok_")>-1){
					mens.show("I","Los datos fueron guardados correctamente","¡ATENCIÓN!");
				}else{
					mens.show("A",datos);
				}
		   }
		 });
		
		/*consultaTexto("resGuardar","auditoriasucursalnuevo_con.php?sucursalseleccionada="+ge('sucursal').value+"&accion=9"+
					  "&yaGuardado="+ge('yaGuardado').value+"&folioauditoria="+ge('folioauditoria').innerHTML+cerrar+
					  "&saldoanterior="+saldoanterior+"&inventarioal="+inventarioal+"&carteraal="+carteraal+"&ajustesal=0"+
					  "&liquidaciones="+liquidaciones+"&depositos="+depositos+"&facturascanceladas="+facturascanceladas+
					  "&guiascanceladas="+guiascanceladas+"&notasdecredito="+notasdecredito+"&saldocontable="+saldocontable+
					  "&inventariosistema="+inventariosistema+"&carterasistema="+carterasistema+
					  "&inventarioalcierre="+inventarioalcierre+"&carteraalcierre="+carteraalcierre+"&saldofinal="+saldofinal+
					  "&ajustestotalseleccionado="+ajustestotalseleccionado+"&ajustesrestante="+ajustesrestante+
					  "&foliosajustes="+folios+
					  "&val="+Math.random());*/
	}
	function resGuardar(datos){
		if(datos.indexOf("_ok_")>-1){
			mens.show("I","Los datos fueron guardados correctamente","¡ATENCIÓN!");
		}else{
			mens.show("A",datos);
		}
	}
	
	//cargar folios guardados
	function pedirDatosAuditoria(valor){
		consultaTexto("resDatosAuditorias","auditoriasucursalnuevo_con.php?sucursalseleccionada="+ge('sucursal').value+"&accion=10&folio="+valor+
					  "&val="+Math.random());
	}
	
	function resDatosAuditorias(datos){
		try{
			var obj = eval(convertirValoresJson(datos));
		}catch(e){
			mens.show("A",datos);
		}
		limpiar();
		ge('yaGuardado').value = "1";
		if(obj.anterior.fecha==""){
			ge('label1').innerHTML = "INVENTARIO ANTERIOR";
			ge('label2').innerHTML = "CARTERA ANTERIOR";
			ge('fechadel').innerHTML = "ANTERIOR";
		}else{
			ge('label1').innerHTML = "INVENTARIO AL "+obj.anterior.fechaanterior;
			ge('label2').innerHTML = "CARTERA AL "+obj.anterior.fechaanterior;
			ge('fechadel').innerHTML = obj.anterior.fechaauditoria;
		}
		
		ge('folioauditoria').innerHTML = obj.anterior.folioauditoria;
		
		ge('saldoanterior').innerHTML = "$ " + numcredvar(obj.anterior.saldoanterior.toString());
		ge('inventarioal').innerHTML = "$ " + numcredvar(obj.anterior.inventarioal.toString());
		ge('carteraal').innerHTML = "$ " + numcredvar(obj.anterior.carteraal.toString());
		
		ge('liquidaciones').innerHTML = "$ " + numcredvar(obj.liquidaciones.toString());
		
		ge('depositos').innerHTML = "$ " + numcredvar(obj.depositos.toString());
		ge('facturascanceladas').innerHTML = "$ " + numcredvar(obj.facturascanceladas.toString());
		ge('guiascanceladas').innerHTML = "$ " + numcredvar(obj.guiascanceladas.toString());
		ge('notascredito').innerHTML = "$ " + numcredvar(obj.notacredito.toString());
		
		var saldocontable = parseFloat(obj.anterior.saldoanterior)+
			parseFloat(obj.anterior.inventarioal)+
		  	parseFloat(obj.anterior.carteraal)+
		   	parseFloat(obj.liquidaciones)-
			
			parseFloat(obj.depositos)-
			parseFloat(obj.facturascanceladas)-
			parseFloat(obj.guiascanceladas)-
			parseFloat(obj.notacredito);
		
		
		ge('ajustestotal').innerHTML = "$ " + numcredvar(obj.anterior.saldofinal.toString());
		ge('ajustestotalseleccionado').innerHTML = "$ " + numcredvar(obj.anterior.ajustestotalseleccionado.toString());
		ge('ajustesrestante').innerHTML = "$ " + numcredvar(obj.anterior.ajustesrestante.toString());
		
		ge('saldocontable').innerHTML = "$ " + numcredvar(saldocontable.toString());
		
		ge('inventariosistema').innerHTML	= "$ " + numcredvar(obj.anterior.inventariosistema.toString());
		ge('carterasistema').innerHTML		= "$ " + numcredvar(obj.anterior.carterasistema.toString());
		
		ge('inventarioactual').innerHTML	= "$ " + numcredvar(obj.anterior.inventariocierre.toString());
		ge('saldoactual').innerHTML			= "$ " + numcredvar(obj.anterior.carteracierre.toString());
		ge('saldofinal').innerHTML			= "$ " + numcredvar(obj.anterior.saldofinal.toString());
		
		//alert(obj.faltsob);
		dataSet1.setJsonData(obj.faltsob);
		for(var i=0; i<tabla2.getRecordCount(); i++){
			if(obj.faltsob[i].seleccionado == 'S'){
				document.all["detalleleido_SEL"][i].checked = true;
			}
		}
		
		ge('celdaactualizar').style.display='none';
		if(obj.anterior.cerrado == 'N'){
			ge('celdaCerrar').style.display='';
		}else{
			for(var i=0; i<tabla2.getRecordCount(); i++){
				document.all["detalleleido_SEL"][i].disabled = true;
			}
		}
	}
	
	function buscarGuia(valor){
		/*for(var i=0; i<tabla2.getRecordCount(); i++){
			if(document.all["detalleleido_FOLIO"][i].value == valor){
				document.all["detalleleido_SEL"][i].checked = false;
			}
		}*/
		var indice = dataSet1.buscarYMostrar(valor,'guia');
		if(indice != false || indice==0){
			document.all.buscarGuia_txt.value = "";
			var fila = dataSet1.registros[indice];
			fila.seleccion = 'S';
			dataSet1.actualizarRegistroSinMostrar(fila,1,indice);
		}else{
			alerta("No se encontro el numero de guia", "¡Atencion!","guia");
		}
		dataSet1.refrescar();
		//mens.show("A","Guia o factura no encontrada","¡Atención!");
	}
	
</script>
</head>
<body>


<form id="form1" name="form1" method="post" action="">
  <table width="778" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="503" class="FondoTabla Estilo4">AUDITORIA</td>
    </tr>
    <tr>
      <td>
      	<table border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="343">
      	<table width="343" border="0" cellpadding="0" cellspacing="0" align="center">
        	<tr>
            	<td colspan="3">
                	<table width="337" border="0" cellpadding="0" cellspacing="0">
                    	<tr>
                        	<td width="42">FOLIO:</td>
                        	<td width="54" id="folioauditoria"></td>
                        	<td width="30">
                            <div class="ebtn_buscar" onClick="mens.popup('../buscadores_generales/buscarFolioAuditoria.php?sucursalseleccionada='+ge('sucursal').value+'&funcion=pedirDatosAuditoria', 570, 470, 'ventana', 'Busqueda')"></div>
                            </td>
                        	<td width="6"></td>
                            <td width="63">SUCURSAL:</td>
                            <td width="142">
							<select name="sucursal" style="width:140px; font-family:Verdana, Geneva, sans-serif; font-size:12px" 
                            onchange="consultarCambioSucursal()">
							<?
                                $s = "select * from catalogosucursal order by descripcion";
                                $r = mysql_query($s,$l) or die($s);
                                while($f = mysql_fetch_object($r)){
                            ?>
                                    <option <? if($_SESSION[IDSUCURSAL]==$f->id){echo "selected";}?> value="<?=$f->id?>"><?=strtoupper(utf8_encode($f->descripcion))?></option>		
                            <?
                                }
                            ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
           	</tr>
            <tr>
            	<td width="128"><input type="hidden" name="yaGuardado" id="yaGuardado" /></td>
            	<td width="265"></td>
            	<td width="1"></td>
            </tr>
        	<tr>
            	<td width="128">&nbsp;SALDO INICIAL:</td>
            	<td width="265"></td>
            	<td width="1"></td>
            </tr>
        	<tr>
        	  <td colspan="3">
              	<table width="337" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="45">&nbsp;</td>
                        <td width="172">SALDO ANTERIOR:</td>
                        <td width="120" id="saldoanterior" style="text-align:right"></td>
                    </tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td id="label1">INVENTARIO AL</td>
                	  <td id="inventarioal" style="text-align:right"></td>
               	  </tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td id="label2">CARTERA AL</td>
                	  <td id="carteraal" style="text-align:right"></td>
               	  </tr>
                </table>
              </td>
       	  </tr>
        	<tr>
        	  <td>&nbsp;</td>
        	  <td></td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td colspan="2">
              	<table width="337" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="46">&nbsp;FECHA:</td>
                    	<td width="133" id="fechadel"></td>                        
                    	<td width="26">&nbsp;</td>
                    	<td width="160" id="fechaal"></td>
                    </tr>
                </table>
              </td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td>&nbsp;MAS CARGOS:</td>
        	  <td></td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td colspan="3"><table width="338" border="0" cellpadding="0" cellspacing="0">
        	    <tr>
        	      <td width="44">&nbsp;</td>
        	      <td width="170">LIQUIDACIONES</td>
        	      <td width="124" id="liquidaciones" style="text-align:right; text-decoration:underline; cursor:pointer" onclick="mostrarLiquidaciones()"></td>
       	        </tr>
      	    </table></td>
       	  </tr>
        	<tr>
        	  <td>&nbsp;</td>
        	  <td></td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td>&nbsp;MENOS ABONOS</td>
        	  <td></td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td colspan="3">
              		<table width="338" border="0" cellpadding="0" cellspacing="0">
        	    <tr>
        	      <td width="44">&nbsp;</td>
        	      <td width="170">DEPOSITOS</td>
        	      <td width="124" id="depositos" style="text-align:right; text-decoration:underline; cursor:pointer"  onclick="mostrarDepositos()"></td>
        	      </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td>FACTURAS CANCELADAS</td>
        	      <td id="facturascanceladas" style="text-align:right; text-decoration:underline; cursor:pointer" onclick="mostrarFacturascanceladas()"></td>
        	      </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td>GUIAS CANCELADAS</td>
        	      <td id="guiascanceladas" style="text-align:right; text-decoration:underline; cursor:pointer" onclick="mostrarGuiascanceladas()"></td>
        	      </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td>NOTAS DE CR&Eacute;DITO</td>
        	      <td id="notascredito" style="text-align:right; text-decoration:underline; cursor:pointer" onclick="mostrarNotascredito()"></td>
        	      </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td colspan="2"><HR /></td>
        	      </tr>
                  <tr>
        	      <td>&nbsp;</td>
        	      <td>CARTERA SISTEMA</td>
        	      <td id="carterasistema" style="text-align:right; text-decoration:underline; cursor:pointer" onclick="mostrarCarteraSistema()"></td>
        	      </tr>
                  <tr>
        	      <td>&nbsp;</td>
        	      <td>INVENTARIO SISTEMA</td>
        	      <td id="inventariosistema" style="text-align:right; text-decoration:underline; cursor:pointer" onclick="mostrarInventarioSistema()"></td>
        	      </tr>
                  <tr>
        	      <td>&nbsp;</td>
        	      <td colspan="2"><hr /></td>
        	      </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td>SALDO CONTABLE</td>
        	      <td id="saldocontable" style="text-align:right"></td>
        	      </tr>
                </table>
              </td>
       	  </tr>
        	<tr>
        	  <td>&nbsp;</td>
        	  <td></td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td colspan="2">&nbsp;COMPROBACI&Oacute;N DE SALDO</td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td colspan="2">
			  <table width="342" border="0" cellpadding="0" cellspacing="0">
        	    <tr>
        	      <td width="42">&nbsp;</td>
        	      <td width="178">INVENTARIO AL CIERRE</td>
        	      <td width="122" id="inventarioactual" style="text-align:right"></td>
       	        </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td>CARTERA AL CIERRE</td>
        	      <td id="saldoactual" style="text-align:right"></td>
       	        </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td colspan="2"><hr /></td>
       	        </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td>SALDO FINAL</td>
        	      <td id="saldofinal" style="text-align:right; text-decoration:underline; cursor:pointer" onclick="mostrarSaldoFinal()"></td>
       	        </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td>&nbsp;</td>
        	      <td></td>
       	        </tr>
      	    </table></td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td colspan="2" align="center">&nbsp;</td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td colspan="2">&nbsp;</td>
        	  <td></td>
      	  </tr>
        </table>
        		</td>
            	<td width="430" valign="top">
                	<table width="430" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td>Ajustes</td>
                      </tr>
                      <tr>
                    	  <td height="223"><table id="detalleleido" border="0" cellpadding="0" cellspacing="0"></table>&nbsp;</td>
                  	  </tr>
                      <tr>
                    	  <td id="celdapaginado" style="border:1px #000 solid"></td>
                  	  </tr>
                      <tr>
                    	  <td>
                          	<table width="332" align="center">
                            	<tr>
                                	<td width="66">Buscar:</td>
                                    <td width="137"><input type="text" name="buscarGuia_txt" id="buscarGuia_txt" style="width:130px" onkeypress="if(event.keyCode==13){ buscarGuia(this.value) }" /></td>
                                    <td width="113"><img src="../img/Boton_Buscar.gif" onclick="buscarGuia(ge('buscarGuia_txt').value)" /></td>
                                </tr>
                            </table>
                        </td>
                  	  </tr>
                      <tr>
                        <td>
                        	<table width="428">
                            	<tr>
                                	<td width="57">&nbsp;</td>
                                    <td width="81">&nbsp;</td>
                                    <td width="147" align="right">TOTAL</td>
                                    <td width="123" id="ajustestotal" align="right">&nbsp;</td>
                                </tr>
                            	<tr>
                            	  <td>&nbsp;</td>
                            	  <td>&nbsp;</td>
                            	  <td align="right">SELECCIONADO</td>
                            	  <td align="right" id="ajustestotalseleccionado">&nbsp;</td>
                       	      </tr>
                            	<tr>
                            	  <td>&nbsp;</td>
                            	  <td>&nbsp;</td>
                            	  <td align="right">SALDO RESTANTE</td>
                            	  <td align="right" id="ajustesrestante">&nbsp;</td>
                          	  </tr>
                            </table>
                        </td>
                      </tr>
                    </table>
                </td>
            </tr>
        	<tr>
        	  <td colspan="2" align="center"><table>
        	    <tr>
        	      <td width="85" id="celdaactualizar"><img src="../img/Boton_Actualizar2.gif" style="cursor:pointer" onclick="pedirRecolectado()"/></td>
        	      <td width="75"><img src="../img/boton_limpiar.gif" style="cursor:pointer" onclick="document.location.href = '';document.location.href = 'auditoriasucursalnuevo.php'" /></td>
        	      <td width="78" id="celdaguardar"><img src="../img/Boton_Guardar.gif" style="cursor:pointer" onclick="guardar(0)"/></td>
                  <td width="78" id="celdaCerrar" style=" display:none"><img src="../img/Boton_Cerrarauditoria.gif" style="cursor:pointer;" onclick="guardar(1)"/></td>
      	      </tr>
      	    </table></td>
       	  </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
</body>
</html>