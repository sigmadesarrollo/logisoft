<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	
	if($_POST['accion']==""){		
		$s = "SELECT obtenerFolio('entregasocurre',".$_SESSION[IDSUCURSAL].") AS folio";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		$folio = $f->folio;
		//Sucursal
		$suc = mysql_query("SELECT descripcion,prefijo FROM catalogosucursal WHERE id=".$_SESSION[IDSUCURSAL]."",$l); 
		$rsuc = @mysql_fetch_array($suc); 
		$id_sucursal=$_SESSION[IDSUCURSAL];
		$sucursal =$rsuc[0];
		//$sucursal 	 =$rsuc['prefijo'];
	}else if($_POST['accion']=="limpiar"){
		$s = "SELECT obtenerFolio('entregasocurre',".$_SESSION[IDSUCURSAL].") AS folio";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		$folio = $f->folio;
		//Sucursal
		$suc = mysql_query("SELECT descripcion,prefijo FROM catalogosucursal WHERE id=".$_SESSION[IDSUCURSAL]."",$l); 
		$rsuc = @mysql_fetch_array($suc); 
		$id_sucursal=$_SESSION[IDSUCURSAL];
		$sucursal =$rsuc[0];
		//$sucursal 	 =$rsuc['prefijo'];
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
	
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.Estilo4 {font-size: 12px}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
-->
</style>
<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script language="javascript" src="../javascript/jquery-1.4.js"></script>
<script>
	var u		= document.all;
	var tabla1  = new ClaseTabla();	
	//celdabotones
	var botonesguardar = '<table width="177">'
       	    	+'<tr>'
                	+'<td><div class="ebtn_guardar"></div></td>'
               +' </tr>'
            +'</table>';
	var botonesnuevo = '<table width="177">'
       	    	+'<tr>'
                   + '<td><div class="ebtn_nuevo" ></div></td>'
               +' </tr>'
            +'</table>';
	
	tabla1.setAttributes({
		nombre:"tablalista",
		campos:[
			{nombre:"S", medida:20, alineacion:"center",onClick:"Total",tipo:"checkbox", datos:"sel"},
			{nombre:"No_GUIA", medida:80, alineacion:"center", datos:"guia"},
			{nombre:"ORIGEN", medida:39, alineacion:"left", datos:"origen"},
			{nombre:"FECHA", medida:60, alineacion:"center", datos:"fecha"},
			{nombre:"REMITENTE", medida:85, alineacion:"left", datos:"remitente"},
			{nombre:"DESTINATARIO", medida:85, alineacion:"left", datos:"destinatario"},
			{nombre:"TIPO_FLETE", medida:69, alineacion:"center", datos:"tipoflete"},
			{nombre:"ESTADO", medida:74, tipo:"oculto",alineacion:"left", datos:"estado"},
			{nombre:"IMPORTE", medida:60, tipo:"moneda", alineacion:"right", datos:"importe"},
			{nombre:"TIPOPAGO", medida:6, tipo:"oculto", alineacion:"right", datos:"tipopago"}
		],
		filasInicial:15,
		alto:200,
		seleccion:false,
		ordenable:false,
		eventoDblClickFila:"SeleccionarFila()",
		nombrevar:"tabla1"
	});
	

	
	window.onload = function(){
		u.nguia.focus();
		tabla1.create();
	}
	
	function pedirGuiasPGuia(tvalor){
		u.cliente.value	="";
		limpiarDatos();
		u.nguia.value	= tvalor;
		consultaTexto("mostrarGuias","entregaocurre_con.php?accion=1&buscar=1&folioguia="+tvalor+"&otros="+Math.random());
	}
	
	function pedirGuiasPCliente(tvalor){
		u.nguia.value	="";
		limpiarDatos();
		u.cliente.value	=tvalor;
		consultaTexto("mostrarGuias","entregaocurre_con.php?accion=1&buscar=0&cliente="+tvalor+"&otros="+Math.random());
	}
	
	function mostrarGuias(datos){
		
		try{
			var objeto = eval(datos);
		}catch(e){
			alerta3("Error "+datos,"¡ATENCIO!");
			return false;
		}
			if(objeto.guias.length>0){
				tabla1.setJsonData(objeto.guias);
				
				/*for(i=0;i<tabla1.getRecordCount();i++){
					document.all["tablalista_S"][i].checked=true;
				}*/
				
			}
			u.cliente.value = objeto.cliente.idcliente;
			u.nombre.value = objeto.cliente.cliente;
			Total();
		
	}
	
	function validarDatos(){
		<?=$cpermiso->verificarPermiso(329,$_SESSION[IDUSUARIO]);?>;		
		if(u.sucursal.value==""){
			alerta("Seleccione la sucursal para poder continuar","¡Atencion!","sucursal");
			return false;
		}
		if(u.cliente.value == ""){
			alerta("Seleccione el cliente para poder continuar","¡Atencion!","cliente");
			return false;
		}		
		
		if(!tabla1.getRecordCount()>0){
			alerta("No hay guias seleccionadas","¡Atencion!");
			return false;
		}
		if(u.precibe.value==""){
			alerta("Proporcione la persona que recibe","¡Atencion!","precibe");
			return false;
		}
		if(u.identificacion.value==""){
			alerta("Proporcione el tipo de identificacion","¡Atencion!","identificacion");
			return false;
		}
		if(u.nidentificacion.value==""){
			alerta("Proporcione el numero de identificacion","¡Atencion!","nidentificacion");
			return false;
		}		
		
		if(u.cargado.value!='SI' && tabla1.getValSelFromField("guia","S")==""){
			alerta3("No hay guias seleccionadas","¡Atencion!");	
			return false;
		}
		return true;
	}
	
function Total(){	
	var total=0.0;	
	for(i=0;i<tabla1.getRecordCount();i++){
		/*if(document.all["tablalista_S"][i].checked==true && document.all["tablalista_TIPO_FLETE"][i].value=='POR COBRAR' && document.all["tablalista_TIPOPAGO"][i].value=='CONTADO'){
			
		}*/
		total +=parseFloat(document.all["tablalista_IMPORTE"][i].value.replace("$ "," ").replace(/,/g,""));
	}
	//COBRAR CONTADO
	if(!isNaN(total)){
		u.total.value= parseFloat(total).toFixed(2);
		u.total.value = "$ "+numcredvar(u.total.value);
	}else{
		u.total.value= "$ "+numcredvar(0);
	}
	/***/
	u.efectivo.value	="";
	u.cheque.value		="";
	u.banco.value		="";
	u.ncheque.value		="";
	u.tarjeta.value		="";
	u.transferencia.value="";
	/***/
}

function obtenerSucursal(id,sucursal){
	u.id_sucursal.value	= id;
	u.sucursal.value 	= sucursal;
}

	function ejecutarSubmit(){
			var folios = new Array();	
			var conta = 0;
			for(i=0;i<tabla1.getRecordCount();i++){
				if(document.all["tablalista_S"][i].checked==true){
					folios[conta]=document.all["tablalista_No_GUIA"][i].value;
					conta++;
				}
			}
			if(u.cargado.value=='SI'){
				var accion = 4;
				var elfolio = "&folio="+u.folio.value;
			}else{		
				var accion = 2;
				var elfolio = "";
			}
			
			 
			if (document.getElementById("recepcion").checked) {
				u.recepcion.value="SI";
				if (document.getElementById("chkliste").checked) {
					u.chkliste.value="SI";
				}else{
					u.chkliste.value="NO";
				}
				if (document.getElementById("cartaporte").checked) {
					u.cartaporte.value="SI";
				}else{
					u.cartaporte.value="NO";
				}
				if (document.getElementById("contrarecibocarga").checked) {
					u.contrarecibocarga.value="SI";
				}else{
					u.contrarecibocarga.value="NO";
				}
				if (document.getElementById("facturascarga").checked) {
					u.facturascarga.value="SI";
				}else{
					u.facturascarga.value="NO";
				}
				if (document.getElementById("recibomaniobras").checked) {
					u.recibomaniobras.value="SI";
				}else{
					u.recibomaniobras.value="NO";
				}
				 
			}else{
				u.recepcion.value="NO";
				u.chkliste.value="NO";
				u.cartaporte.value="NO";
				u.contrarecibocarga.value="NO";
				u.facturascarga.value="NO";
				u.recibomaniobras.value="NO";
			}
			/*consultaTexto("resGuardar","entregaocurre_con.php?accion="+accion+elfolio+"&folios="+folios
			+"&idsucursal="+u.id_sucursal.value
			+"&sucursal="+u.sucursal.value
			+"&nguia="+u.nguia.value
			+"&cliente="+u.cliente.value
			+"&nombre="+u.nombre.value
			+"&total="+u.total.value.replace("$ ","").replace(/,/,"")
			+"&precibe="+u.precibe.value
			+"&nidentificacion="+u.nidentificacion.value
			+"&identificacion="+u.identificacion.value
			+"&efectivo="+u.efectivo.value.replace("$ ","").replace(/,/,"")
			+"&cheque="+u.cheque.value.replace("$ ","").replace(/,/,"")
			+"&banco="+u.banco.value.replace("$ ","").replace(/,/,"")
			+"&ncheque="+u.ncheque.value
			+"&nc="+u.nc.value.replace("$ ","").replace(/,/,"")
			+"&nc_folio="+u.nc_folio.value.replace("$ ","").replace(/,/,"")
			+"&tarjeta="+u.tarjeta.value.replace("$ ","").replace(/,/,"")
			+"&transferencia="+u.transferencia.value.replace("$ ","").replace(/,/,"")
			+"&mathrand="+Math.random()
			+"&fechahora="+fechahora()
			+"&firma="+((u.firma.value=="")?0:u.firma.value));*/
			
			var envioinformacion = "accion="+accion+elfolio+"&folios="+folios
			+"&idsucursal="+u.id_sucursal.value
			+"&recepcion="+u.recepcion.value
			+"&chkliste="+u.chkliste.value
			+"&cartaporte="+u.cartaporte.value
			+"&contrarecibocarga="+u.contrarecibocarga.value
			+"&facturascarga="+u.facturascarga.value
			+"&recibomaniobras="+u.recibomaniobras.value
			+"&sucursal="+u.sucursal.value
			+"&nguia="+u.nguia.value
			+"&cliente="+u.cliente.value
			+"&nombre="+u.nombre.value
			+"&observacion="+u.observacion.value
			+"&total="+u.total.value.replace("$ ","").replace(/,/,"")
			+"&precibe="+u.precibe.value
			+"&nidentificacion="+u.nidentificacion.value
			+"&identificacion="+u.identificacion.value
			+"&efectivo="+u.efectivo.value.replace("$ ","").replace(/,/,"")
			+"&cheque="+u.cheque.value.replace("$ ","").replace(/,/,"")
			+"&banco="+u.banco.value.replace("$ ","").replace(/,/,"")
			+"&ncheque="+u.ncheque.value
			+"&nc="+u.nc.value.replace("$ ","").replace(/,/,"")
			+"&nc_folio="+u.nc_folio.value.replace("$ ","").replace(/,/,"")
			+"&tarjeta="+u.tarjeta.value.replace("$ ","").replace(/,/,"")
			+"&transferencia="+u.transferencia.value.replace("$ ","").replace(/,/,"")
			+"&mathrand="+Math.random()
			+"&fechahora="+fechahora()
			+"&firma="+((u.firma.value=="")?0:u.firma.value);
			crearLoading();
			$.ajax({
			   type: "POST",
			   url: "entregaocurre_conjson.php",			   
			   data: envioinformacion,
			   success: resGuardar
			 });
			return false;
	}

	function resGuardar(datos){
		ocultarLoading();
		if(datos.indexOf("guardado")>-1){
			var row = datos.split(",");
			check = document.getElementById("recepcion");
			document.all.folio.value = row[1];
			info("La informacion ha sido guardada","");
			window.open("imprimirEntregaocurre.php?folio="+document.all.folio.value);
			if (check.checked) {
				window.open("imprimirEntregaocurre2.php?folio="+document.all.folio.value);
			}
			u.guardar.style.visibility="hidden";
			//u.guardado.value = 1;
		}else{
			if(datos.indexOf("Existen guias")>-1){
				alerta3("Existen guias que ya han sido entregadas, actualiza la informacion","Atencion");
				return false;
			}else{
				alerta3("Hubo un error "+datos,"¡Atencion!");
				u.guardar.style.visibility="visible";
			}
		}
	}

function limpiar(){
	u.folio.value	="";
	u.id_sucursal.value="";
	u.sucursal.value="";
	u.observacion.value="";
	u.nguia.value	="";
	u.cliente.value ="";
	u.nombre.value	="";
	u.total.value	="";
	u.precibe.value	="";
	u.identificacion.value	="";
	u.nidentificacion.value	="";
	u.efectivo.value="";
	u.cheque.value	="";
	u.banco.value	="";
	u.ncheque.value	="";
	u.tarjeta.value	="";
	u.transferencia.value="";
	u.guardar.style.visibility="visible";
	u.firma.value = "";
	tabla1.clear(); 
	u.accion.value	="limpiar";
	document.getElementById("recepcion").checked=false;
	document.getElementById("chkliste").checked=false;
	document.getElementById("cartaporte").checked=false;
	document.getElementById("contrarecibocarga").checked=false;
	document.getElementById("facturascarga").checked=false;
	document.getElementById("recibomaniobras").checked=false;
	document.form1.submit();
}

function limpiarDatos(){
	u.nguia.value	="";
	u.cliente.value ="";
	u.nombre.value	="";
	u.observacion.value	="";
	u.total.value	="";
	u.precibe.value	="";
	u.identificacion.value	="";
	u.nidentificacion.value	="";
	u.efectivo.value="";
	u.cheque.value	="";
	u.banco.value	="";
	u.ncheque.value	="";
	u.tarjeta.value	="";
	u.transferencia.value="";
	u.guardar.style.visibility="visible";
	u.firma.value = "";
	document.getElementById("recepcion").checked=false;
	document.getElementById("chkliste").checked=false;
	document.getElementById("cartaporte").checked=false;
	document.getElementById("contrarecibocarga").checked=false;
	document.getElementById("facturascarga").checked=false;
	document.getElementById("recibomaniobras").checked=false;
	tabla1.clear(); 
}

function perdirEntrega(folio){
	u.folio.value = folio;
	consultaTexto("MostrarPedirCliente", "entregaocurre_con.php?accion=3&folio="+folio+"&sucursal="+document.all.sucursal.value+"&math="+Math.random());
}

function MostrarPedirCliente(datos){
	if(datos.indexOf("no encontro")<0){		
		var obj = eval(convertirValoresJson(datos));
		u.cargado.value 	= "SI";
		u.cliente.value 	= obj.datoscliente.cliente;
		u.nombre.value 		= obj.datoscliente.nombre;
		u.observacion.value = obj.datoscliente.observacion;
		u.folio.value 		= obj.datoscliente.folio;
		u.nguia.value 		= obj.datoscliente.nguia;
		u.precibe.value		= obj.datoscliente.personaquerecibe;
		u.identificacion.value	= obj.datoscliente.tipodeidentificacion;
		u.nidentificacion.value	= obj.datoscliente.numeroidentificacion;
		tabla1.setJsonData(obj.datostabla);
		
		var total=0;	
		for(i=0;i<tabla1.getRecordCount();i++){
			u['tablalista_S'][i].checked=true;
			if(u["tablalista_S"][i].checked==true && u["tablalista_TIPO_FLETE"][i].value=='POR COBRAR' && u["tablalista_TIPOPAGO"][i].value=='CONTADO'){
				total +=parseFloat(u["tablalista_IMPORTE"][i].value.replace("$ "," ").replace(/,/g,""));
			}
		}
	
		if(!isNaN(total)){
			u.total.value= parseFloat(total).toFixed(2);
			u.total.value = "$ "+numcredvar(u.total.value);
		}else{
			u.total.value= "$ "+numcredvar(0);
		}
		
		u.cliente.readOnly = true;
		u.cliente.style.backgroundColor = '#FFFF99';
		u.nguia.readOnly = true;
		u.nguia.style.backgroundColor = '#FFFF99';
		u.precibe.readOnly = true;
		u.precibe.style.backgroundColor = '#FFFF99';
		u.identificacion.disabled = true;
		u.nidentificacion.readOnly = true;
		u.nidentificacion.style.backgroundColor = '#FFFF99';
		
		if (obj.datoscliente.recepcion =='SI') {
				document.getElementById("recepcion").checked=true;
				 document.getElementById("content").style.display='block';
				if (obj.datoscliente.chkliste =='SI') {
					document.getElementById("chkliste").checked=true;
				}else{
					document.getElementById("chkliste").checked=false;
				}
				if (obj.datoscliente.cartaporte =='SI') {
					document.getElementById("cartaporte").checked=true;
				}else{
					document.getElementById("cartaporte").checked=false;
				}
				if (obj.datoscliente.contrarecibocarga =='SI') {
					document.getElementById("contrarecibocarga").checked=true;
				}else{
					document.getElementById("contrarecibocarga").checked=false;
				}
				if (obj.datoscliente.facturascarga =='SI') {
					document.getElementById("facturascarga").checked=true;
				}else{
					document.getElementById("facturascarga").checked=false;
				}
				if (obj.datoscliente.recibomaniobras =='SI') {
					document.getElementById("recibomaniobras").checked=true;
				}else{
					document.getElementById("recibomaniobras").checked=false;
				}
				 
			}
		u.buscarcliente.style.display = 'none';
		u.obtenerfirmaimg.style.display = 'none';
		u.buscarguia.style.display = 'none';
		
		if(obj.datoscliente.modificable=='SI')
			u.guardar.style.display = "";
		else
			u.guardar.style.display = "none";
		//alert(obj.datoscliente.registradoalmacen);
		
	}else{
		alerta("El folio de entrega no existe","¡Atención!","folio");
	}
}

function mostrarformapago(){
		var u=document.all;
		u.efectivo.value= 0;
		u.cheque.value	= 0;
		u.banco.value	= 0;
		u.ncheque.value	= 0;
		u.tarjeta.value	= 0;
		u.transferencia.value= 0;
		u.nc.value		= 0;
		u.nc_folio.value= 0;
		if(u.total.value.replace("$ ","").replace(/,/g,"")=="0.00" || u.total.value==""){
			ejecutarSubmit();
		}else{
			abrirVentanaFija('formapago.php?total='+u.total.value.replace("$","").replace(/,/g,"")+'&cliente='+u.cliente.value, 600, 400, 'ventana', 'Forma de Pago');
		}

	}
	
	function SeleccionarFila(){
		var i=tabla1.getSelectedIndex();
		if(document.all["tablalista_S"][i].checked==false){
			document.all["tablalista_S"][i].checked=true;
		}else{
			document.all["tablalista_S"][i].checked=false;
		}
		Total();
	}

	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	
	function agregarFirma(){
		abrirVentanaFija('../pruebas/prueba.php?funcion=obtenerFirma', 780, 600, 'ventana', 'Firma Cliente');
	}
	
	function obtenerFirma(datos){
		u.firma.value = datos;
	}
    function showContent() {
        element = document.getElementById("content");
        check = document.getElementById("recepcion");
		//check1 = document.getElementById("CartaPorte");
        if (check.checked) {
            element.style.display='block';
			//check1.checked=true;
        }
        else {
            element.style.display='none';
        }
    }
	
</script>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="619" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="615" class="FondoTabla Estilo4">ENTREGAS Y EVIDENCIAS</td>
  </tr>
  <tr>
    <td><table width="615" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="21" colspan="2">
        <table width="615" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="348"><div align="right">
              <input type="hidden" name="cargado" value="" />
              <input name="id_sucursal" type="hidden" id="id_sucursal"  value="<?=$id_sucursal ?>"/>
              Folio:              </div></td>
            <td width="78"><input name="folio" type="text" class="Tablas" id="folio" style="width:70px;" value="<?=$folio ?>" onkeypress="if(event.keyCode=='13'){perdirEntrega(document.all.folio.value);}"/></td>
            <td width="42"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarFoliosOcurre.php?funcion=perdirEntrega&sucursal='+document.all.sucursal.value+'&tipo=OCURRE', 600, 550, 'ventana', 'Buscar')"></div></td>
            <td width="42">Sucursal:</td>
            <td width="80"><input name="sucursal" class="Tablas" type="text" id="sucursal" value="<?=$sucursal ?>" style="width:80px; background-color:#FFFF99"  readonly=""/></td>
            <td width="25"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 600, 550, 'ventana', 'Sucursales')" style="visibility:<?=(($_SESSION[IDSUCURSAL]!=1)?'hidden':'visible')?>"></div></td>
          </tr>
        </table>        </td>
      </tr>
      
      <tr>
        <td colspan="2"><table width="615" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="59">Carta Porte:</td>
              <td width="127"><input name="nguia" class="Tablas" type="text" id="nguia" value="<?=$nguia ?>" onkeypress="if(event.keyCode==13 && this.readOnly!=true){pedirGuiasPGuia(this.value)}" /></td>
              <td width="428"><div id="buscarguia" class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarGuiasEmpresariales_VentanillaGen2.php?funcion=pedirGuiasPGuia&tipo=1', 600, 550, 'ventana', 'Buscar')"></div></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="70" class="Tablas">#Cliente: </td>
              <td width="60" class="Tablas"><input name="cliente" type="text" class="Tablas" id="cliente" style="width:50px;" value="<?=$cliente ?>" onkeypress="if(event.keyCode==13 && this.readOnly!=true){pedirGuiasPCliente(this.value)}"/></td>
              <td width="40" class="Tablas"><div id="buscarcliente" class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=pedirGuiasPCliente', 600, 450, 'ventana', 'Buscar')"></div></td>
              <td width="59"><span class="Tablas">
                Nombre:
                
                &nbsp;</span></td>
              <td width="289"><span class="Tablas">
                <input name="nombre" type="text" class="Tablas" id="nombre" style="width:280px;background:#FFFF99" value="<?=$nombre ?>" readonly=""/>
              </span></td>
              <td width="97"><img id="obtenerfirmaimg" src="../img/Boton_Firma.gif" width="105" height="20" align="absbottom" style="cursor:pointer" onclick="agregarFirma()" /></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2">
        	<table cellpadding="0" cellspacing="0" border="0" id="tablalista"></table>        </td>
      </tr>
      <tr>
        <td colspan="2"><div align="right">Total:
            <input name="total" type="text" class="Tablas" id="total" style="width:70px;background:#FFFF99" value="<?=$total ?>" readonly=""/>
        </div></td>
      </tr>
      <tr>
        <td width="116">Persona que Recibe:</td>
        <td width="499"><input name="precibe" style="width:250px" class="Tablas" type="text" id="precibe" value="<?=$precibe ?>" /></td>
      </tr>
      <tr>
        <td>Tipo Identificaci&oacute;n:</td>
        <td><select name="identificacion" class="Tablas" style="width:160px;" >
          <option value="" selected="selected">.:: IDENTIFICACION ::.</option>
          <option value="CREDENCIAL DE ELECTOR">CREDENCIAL DE ELECTOR</option>
          <option value="PASAPORTE">PASAPORTE</option>
          <option value="CARTILLA MILITAR">CARTILLA MILITAR</option>
          <option value="CEDULA PROFESIONAL">CEDULA PROFESIONAL</option>
        </select>
          &nbsp;&nbsp;No. Identificacion:
          <input name="nidentificacion" class="Tablas" type="text" style="width:80px" id="nidentificacion" value="<?=$nidentificacion ?>" /></td>
      </tr>
      <tr>
        <td height="42" colspan="2">
            <p><b>Observacion</b></p>
              <textarea class="Tablas" name="observacion" rows="4" id="observacion" style="width:350px; text-transform:uppercase" ><?=$observacion ?></textarea>
        </td>

      </tr>   
      <tr>
        <td colspan="2">
            <b>Se recepciono envio</b>
            <input name="recepcion" type="checkbox" id="recepcion" onclick="javascript:showContent()"  />
        </td>

      </tr>      
      <tr>
        <td colspan="2">
        <div id="content" style="display: none;">
           <ul class="listaPermisos">
                    <li><b>Check list</b>
                    </li>
                	<li><input  type="checkbox" name="chkliste" id="chkliste" value="" />Check list de evidencias
                    </li>
                    <li><input  type="checkbox" name="cartaporte" id="cartaporte" value="" />Carta Porte
                    </li>
                    <li><input type="checkbox" name="contrarecibocarga" id="contrarecibocarga" value=""  />Contra Recibo de Carga
                    </li>
                    <li><input type="checkbox" name="facturascarga" id="facturascarga" value="" />Facturas de Carga
                    </li>
                    <li><input type="checkbox" name="recibomaniobras" id="recibomaniobras" value="" />Recibo de Maniobras
                    </li>&nbsp;
		  </ul>
         </div>  
        </td>

      </tr>
      <tr>
        <td colspan="2" id="celdabotones">
        	<input name="efectivo" type="hidden" id="efectivo" />
        	<input name="cheque" type="hidden" id="cheque" />
        	<input name="banco" type="hidden" id="banco" />
        	<input name="ncheque" type="hidden" id="ncheque" />
        	<input name="tarjeta" type="hidden" id="tarjeta" />
        	<input name="transferencia" type="hidden" id="transferencia" />
            <input type="hidden" name="nc_folio">
            <input type="hidden" name="nc">
        	<input name="accion" type="hidden" id="accion" />
        	<input name="firma" type="hidden" id="firma" />
        	<table width="168" border="0" align="right">
              <tr>
                <td width="75" >
                	<!--div id="guardar" class="ebtn_guardar" onclick="if(validarDatos()){ mostrarformapago();}" ></div-->
					<div id="guardar" class="ebtn_guardar" onclick="if(validarDatos()){ confirmar('Las guias de ocurre que sean por COBRAR CONTADO, no podran ser traspasadas a credito ya que afectan a los cortes','¡Atencion!','mostrarformapago()','');}" ></div>					
				</td>
                <td width="70"><div id="nuevo" class="ebtn_nuevo" onclick="confirmar('&iquest;Desea limpiar los datos?','&iexcl;Atencion!','limpiar()','')"></div></td>
              </tr>
            </table>
            </td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
</html>