<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	$hora	= date("H:i:s");
	$fecha	= date("d/m/Y");
	
	$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS descripcion
	FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL]."";	
	$r = mysql_query($s,$l) or die($s); $fs = mysql_fetch_object($r);
	
	$s = "SELECT CONCAT(prefijo,' - ',descripcion,':',id) AS descripcion
	FROM catalogosucursal ORDER BY descripcion";	
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
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../javascript/ajaxlist/ajax.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></LINK>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script>
	var tabla1 		= new ClaseTabla();
	var	u			= document.all;
	var movioFecha	= "";
	var inicio		= 30;
	var sepaso		= 0;
	var cont		= 0;
	var totalDatos	= 0;
	var hr			= new Date();
	var v_suc		= "<?=$_SESSION[IDSUCURSAL] ?>";
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"FOLIO", medida:50, onDblClick:"verRecoleccion", alineacion:"left", datos:"folio"},
			{nombre:"CLIENTE", medida:170, alineacion:"left", datos:"cliente"},
			{nombre:"DIRECCION", medida:170, onDblClick:"verDireccion", alineacion:"left", datos:"direccion"},
			{nombre:"TRANSMITIDA", medida:50, alineacion:"center",  datos:"transmitida"},
			{nombre:"REALIZO", medida:50, alineacion:"center",  datos:"realizo"},
			{nombre:"UNIDAD", medida:50, alineacion:"left", datos:"unidad"},
			{nombre:"FECHA", medida:80, alineacion:"center",  datos:"fecha"},
			{nombre:"HORARIO", medida:4, alineacion:"left", tipo:"oculto", datos:"horario"},
			{nombre:"TELEFONO", medida:80, alineacion:"left", datos:"telefono"},
			{nombre:"FOLIO RECOLECCION/EMPRESARIAL", medida:160, alineacion:"left", datos:"folios"},					
			{nombre:"MOTIVOS", medida:160, alineacion:"left", datos:"motivos"},
			{nombre:"GUIA", medida:90, alineacion:"left", datos:"guia"},
			{nombre:"COLORCAN", medida:4, alineacion:"left", tipo:"oculto", datos:"colorcan"},
			{nombre:"COLORREP", medida:4, alineacion:"left", tipo:"oculto", datos:"colorrep"},
			{nombre:"FREGISTRO", medida:4, alineacion:"left", tipo:"oculto", datos:"fecharegistro"},
			{nombre:"SUCURSAL", medida:4, alineacion:"left", tipo:"oculto", datos:"sucursal"},
			{nombre:"ESTADO", medida:4, alineacion:"left", tipo:"oculto", datos:"estado"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		//eventoDblClickFila:"verRecoleccion()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();		
		verificarRecolecciones();
	}
	
	function verDireccion(){
		var obj = tabla1.getSelectedRow();
		abrirVentanaFija("direccionRecoleccion.php?folio="+obj.folio
		+"&sucursal="+obj.sucursal, 600, 350, "ventana", "Dirección");
	}
	
	function verificarRecolecciones(){
		consultaTexto("obtenerRecolecciones","recoleccion_conj.php?accion=14&valor="+Math.random());
	}
	
	function obtenerRecolecciones(datos){
		consultaTexto("obtenerTotal","recoleccion_conj.php?accion=5&tipo=0&sucursal="+u.idSucOrigen.value
		+"&cliente="+u.cliente_hidden.value+"&folio="+u.folio.value+"&fecha="+u.fecha.value+"&valor="+Math.random());			
	}
	
	function obtenerTotal(datos){
		u.total.value = datos;
		u.contadordes.value = datos;
		u.mostrardes2.value = datos;
		u.totaldes.value = "00";
		if(u.contadordes.value > 30){
			u.paginado.style.visibility = "visible";
			u.d_atrasdes.style.visibility = "hidden";
			u.primero.style.visibility = "hidden";
			totalDatos = parseInt(u.contadordes.value / 30);
		}else{
			u.paginado.style.visibility = "hidden";
		}
		if(movioFecha=="SI"){
			//var sucursal = ((u.sucursal_hidden.value!="")? "&sucursal="+u.sucursal_hidden.value : "");
			var sucursal = "&sucursal="+u.sucursal_hidden.value;
			//var fecha 	 = ((u.fecha.value!=u.fecha_hidden.value)? "&fecha="+u.fecha.value : "");			
			var fecha 	 = "&fecha="+u.fecha.value;			
			consultaTexto("mostrarRecoleccionxFecha","recoleccion_conj.php?accion=15&tipo=1&inicio=0"+sucursal+fecha
			+"&cliente="+u.cliente_hidden.value+"&folio="+u.folio.value);	
		}else{
			//var sucursal = ((u.sucursal_hidden.value!="")? "&sucursal="+u.sucursal_hidden.value : "");
			var sucursal = "&sucursal="+u.sucursal_hidden.value;
			consultaTexto("mostrarRecoleccion","recoleccion_conj.php?accion=5&tipo=1&inicio=0"+sucursal+"&cliente="+u.cliente_hidden.value
			+"&folio="+u.folio.value+"&fecha="+u.fecha.value);
		}
	}
	
	function mostrarRecoleccion(datos){
		tabla1.clear();
		canceladas = 0;
		realizadas = 0;
		transmitidas = 0;
		var objeto = eval(convertirValoresJson(datos));
		for(var i=0;i<objeto.length;i++){
			var registro 		   = new Object();
			registro.folio 		   = objeto[i].folio;
			registro.cliente 	   = objeto[i].cliente;
			registro.direccion 	   = objeto[i].direccion;
			registro.transmitida   = objeto[i].transmitida;
			registro.realizo 	   = objeto[i].realizo;
			registro.unidad 	   = objeto[i].unidad;
			registro.fecha 		   = ((objeto[i].fecha!="00/00/0000")?objeto[i].fecha : "");
			registro.horario 	   = objeto[i].horario;
			registro.telefono 	   = objeto[i].telefono;
			registro.folios 	   = objeto[i].folios;
			registro.sucursal 	   = objeto[i].sucursal;
			registro.motivos 	   = objeto[i].motivos;
			registro.colorcan 	   = objeto[i].colorcan;
			registro.colorrep 	   = objeto[i].colorrep;
			registro.fecharegistro = objeto[i].fecharegistro;
			registro.estado		   = objeto[i].estado;
			registro.guia		   = objeto[i].guia;
			tabla1.add(registro);			

			if(objeto[i].colorrep!=""){
				color(objeto[i].colorrep, i);
			}
			
			if(objeto[i].colorcan!=""){
				color(objeto[i].colorcan, i);
			}
			
			var f1 = u.fecha.value.split("/");
			var f2 = objeto[i].fecharegistro.split("/");
			
			if(f1[0].substr(0,1)=="0"){
				f1[0] = f1[0].substr(1,1);
			}
			if(f1[1].substr(0,1)=="0"){
				f1[1] = f1[1].substr(1,1);
			}
			
			if(f2[0].substr(0,1)=="0"){
				f2[0] = f2[0].substr(1,1);
			}
			if(f2[1].substr(0,1)=="0"){
				f2[1] = f2[1].substr(1,1);
			}
			
			f1 = new Date(f1[2],f1[1],f1[0]);
			f2 = new Date(f2[2],f2[1],f2[0]);

			if(objeto[i].estado == "CANCELADO" && f2 >= f1){
				canceladas++;
			}			
			if(objeto[i].estado != "CANCELADO" && objeto[i].realizo=="SI" && f2 >= f1){
				realizadas++;
			}			
			if(objeto[i].estado != "CANCELADO" && objeto[i].realizo=="NO" && f2 >= f1){
				transmitidas++;
			}
		}
		
		u.totalc.value	= canceladas;
		u.totalr.value	= realizadas;
		u.totals.value	= transmitidas;
		//u.total.value	= canceladas + realizadas + transmitidas;		
	}	
	
	function paginacion(tipo){
		if(tipo == "atras"){
			u.d_sigdes.style.visibility = "visible";
			u.d_ultimo.style.visibility = "visible";
			u.totaldes.value = parseFloat(u.totaldes.value) - inicio;
			if(parseFloat(u.totaldes.value) <= "1"){
				u.totaldes.value = "00";
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
				if(u.ultimo.value == "SI"){
					var con = parseInt(u.contadordes.value / 30) - 1;
					u.totaldes.value = con * 30;
					u.ultimo.value = "";
					
					if(movioFecha=="SI"){
						var sucursal = ((u.sucursal_hidden.value!="")? "&sucursal="+u.sucursal_hidden.value : "");
						var fecha 	 = ((u.fecha.value!=u.fecha_hidden.value)? "&fecha="+u.fecha.value : "");
						consultaTexto("mostrarRecoleccionxFecha","recoleccion_conj.php?accion=15&tipo=1&inicio="+u.totaldes.value
						+sucursal+fecha);
					}else{
						consultaTexto("mostrarRecoleccion","recoleccion_conj.php?accion=5&tipo=1&inicio="+u.totaldes.value+"&fecha="+u.fecha.value
						+"&cliente="+u.cliente_hidden.value+"&folio="+u.folio.value+"&sucursal="+u.idSucOrigen.value+"&valor="+Math.random());
					}				
			
				}else{
					u.d_atrasdes.style.visibility = "hidden";
					u.primero.style.visibility = "hidden";					
					if(movioFecha=="SI"){
						
						var sucursal = ((u.sucursal_hidden.value!="")? "&sucursal="+u.sucursal_hidden.value : "");
						var fecha 	 = ((u.fecha.value!=u.fecha_hidden.value)? "&fecha="+u.fecha.value : "");
						consultaTexto("mostrarRecoleccionxFecha","recoleccion_conj.php?accion=15&tipo=1&inicio=0"+sucursal+fecha);
					}else{
						if(u.idSucOrigen.value == 1 || (u.idSucOrigen.value!=1 && u.sucursal_hidden.value!=u.idSucOrigen.value)){
							var sucursal = ((u.sucursal_hidden.value!="")? "&sucursal="+u.sucursal_hidden.value : "");
							consultaTexto("mostrarRecoleccion","recoleccion_conj.php?accion=5&tipo=1&inicio=0&sucursal="+sucursal
							+"&cliente="+u.cliente_hidden.value+"&folio="+u.folio.value+"&valor="+Math.random()+"&fecha="+u.fecha.value);
						}else{
							consultaTexto("mostrarRecoleccion","recoleccion_conj.php?accion=5&tipo=1&inicio=0&sucursal="+u.idSucOrigen.value
							+"&cliente="+u.cliente_hidden.value+"&folio="+u.folio.value+"&valor="+Math.random()+"&fecha="+u.fecha.value);
						}
					}
				}
			}else{
				if(sepaso!=0){
					u.mostrardes.value = sepaso;
					sepaso = 0;
				}
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
				if(u.ultimo.value == "SI"){
					var con = parseInt(u.contadordes.value / 30) - 1;
					u.totaldes.value = con * 30;
					u.ultimo.value = "";
				}
				if(movioFecha=="SI"){
						var sucursal = ((u.sucursal_hidden.value!="")? "&sucursal="+u.sucursal_hidden.value : "");
						var fecha 	 = ((u.fecha.value!=u.fecha_hidden.value)? "&fecha="+u.fecha.value : "");
					consultaTexto("mostrarRecoleccionxFecha","recoleccion_conj.php?accion=15&tipo=1&inicio="+u.totaldes.value+sucursal+fecha);
				}else{
					if(u.idSucOrigen.value == 1 || (u.idSucOrigen.value!=1 && u.sucursal_hidden.value!=u.idSucOrigen.value)){
						var sucursal = ((u.sucursal_hidden.value!="")? "&sucursal="+u.sucursal_hidden.value : "");
						consultaTexto("mostrarRecoleccion","recoleccion_conj.php?accion=5&tipo=1&inicio="+u.totaldes.value+"&sucursal="+sucursal
						+"&cliente="+u.cliente_hidden.value+"&folio="+u.folio.value+"&valor="+Math.random()+"&fecha="+u.fecha.value);
					}else{
						consultaTexto("mostrarRecoleccion","recoleccion_conj.php?accion=5&tipo=1&inicio="+u.totaldes.value+"&sucursal="+u.idSucOrigen.value
						+"&cliente="+u.cliente_hidden.value+"&folio="+u.folio.value+"&valor="+Math.random()+"&fecha="+u.fecha.value);
					}
				}
			}
		}else{
			cont++;
			u.d_atrasdes.style.visibility = "visible";
			u.primero.style.visibility = "visible";
			u.totaldes.value = inicio + parseFloat(u.totaldes.value);
			if(parseFloat(u.totaldes.value) > parseFloat(u.contadordes.value)){
				u.totaldes.value = parseFloat(u.totaldes.value) - inicio;
				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;
				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){
					u.mostrardes.value = u.contadordes.value;
				}
				u.d_sigdes.style.visibility = "hidden";
				u.d_ultimo.style.visibility = "hidden";
			}else{
				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;
				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){
					sepaso	=	u.mostrardes.value;
					u.mostrardes.value = u.contadordes.value;
				}
				if(cont>=totalDatos){
					u.d_sigdes.style.visibility = "hidden";
					u.d_ultimo.style.visibility = "hidden";
					cont = 0;
				}
				if(movioFecha=="SI"){
					var sucursal = ((u.sucursal_hidden.value!="")? "&sucursal="+u.sucursal_hidden.value : "");
					var fecha 	 = ((u.fecha.value!=u.fecha_hidden.value)? "&fecha="+u.fecha.value : "");
					consultaTexto("mostrarRecoleccionxFecha","recoleccion_conj.php?accion=15&tipo=1&inicio="+u.totaldes.value+sucursal+fecha);
				}else{
					if(u.idSucOrigen.value == 1 || (u.idSucOrigen.value!=1 && u.sucursal_hidden.value!=u.idSucOrigen.value)){
						var sucursal = ((u.sucursal_hidden.value!="")? "&sucursal="+u.sucursal_hidden.value : "");
						consultaTexto("mostrarRecoleccion","recoleccion_conj.php?accion=5&tipo=1&sucursal="+sucursal
						+"&inicio="+u.totaldes.value+"&cliente="+u.cliente_hidden.value+"&folio="+u.folio.value+"&valor="+Math.random()+"&fecha="+u.fecha.value);
					}else{
						consultaTexto("mostrarRecoleccion","recoleccion_conj.php?accion=5&tipo=1&sucursal="+u.idSucOrigen.value
						+"&inicio="+u.totaldes.value+"&cliente="+u.cliente_hidden.value+"&folio="+u.folio.value+"&valor="+Math.random()+"&fecha="+u.fecha.value);
					}
				}
			}
		}
	}	

	function obtenerPrimero(){
		u.totaldes.value = "00";
		u.d_sigdes.style.visibility = "visible";
		u.d_ultimo.style.visibility = "visible";
		u.d_atrasdes.style.visibility = "hidden";
		u.primero.style.visibility = "hidden";
		if(movioFecha=="SI"){
			var sucursal = ((u.sucursal_hidden.value!="")? "&sucursal="+u.sucursal_hidden.value : "");
			var fecha 	 = ((u.fecha.value!=u.fecha_hidden.value)? "&fecha="+u.fecha.value : "");
			consultaTexto("mostrarRecoleccionxFecha","recoleccion_conj.php?accion=15&tipo=1&inicio="+u.totaldes.value+sucursal+fecha);
		}else{
			if(u.idSucOrigen.value == 1 || (u.idSucOrigen.value!=1 && u.sucursal_hidden.value!=u.idSucOrigen.value)){
				var sucursal = ((u.sucursal_hidden.value!="")? "&sucursal="+u.sucursal_hidden.value : "");
				consultaTexto("mostrarRecoleccion","recoleccion_conj.php?accion=5&tipo=1&inicio="+u.totaldes.value
				+"&sucursal="+sucursal+"&cliente="+u.cliente_hidden.value+"&folio="+u.folio.value+"&valor="+Math.random()+"&fecha="+u.fecha.value);
			}else{
				consultaTexto("mostrarRecoleccion","recoleccion_conj.php?accion=5&tipo=1&inicio="+u.totaldes.value
				+"&sucursal="+u.idSucOrigen.value+"&cliente="+u.cliente_hidden.value+"&folio="+u.folio.value+"&valor="+Math.random()+"&fecha="+u.fecha.value);
			}
		}
	}

	function obtenerUltimo(){
		u.ultimo.value = "SI";
		u.d_sigdes.style.visibility = "hidden";
		u.d_ultimo.style.visibility = "hidden";
		u.d_atrasdes.style.visibility = "visible";
		u.primero.style.visibility = "visible";
		if(movioFecha=="SI"){
			var sucursal = ((u.sucursal_hidden.value!="")? "&sucursal="+u.sucursal_hidden.value : "");
			var fecha 	 = ((u.fecha.value!=u.fecha_hidden.value)? "&fecha="+u.fecha.value : "");
			consultaTexto("mostrarRecoleccionxFecha","recoleccion_conj.php?accion=ultimorecfecha&tipo=1"+sucursal+fecha);		
		}else{
			if(u.idSucOrigen.value == 1 || (u.idSucOrigen.value!=1 && u.sucursal_hidden.value!=u.idSucOrigen.value)){
				var sucursal = ((u.sucursal_hidden.value!="")? "&sucursal="+u.sucursal_hidden.value : "");
				consultaTexto("mostrarRecoleccion","recoleccion_conj.php?accion=ultimorecoleccion&tipo=1&sucursal="+sucursal
				+"&valor="+Math.random());
			}else{
				consultaTexto("mostrarRecoleccion","recoleccion_conj.php?accion=ultimorecoleccion&tipo=1&sucursal="+u.idSucOrigen.value
				+"&valor="+Math.random());
			}
		}
	}
	
	function verRecoleccion(){
		var obj = tabla1.getSelectedRow();
		if(obj.folio!=""){
			document.location.href='rec.php?accion=&folio='+obj.folio+'&sucursal='+obj.sucursal+'&estado='+obj.estado;		
		}
	}
	
	function traerAlcliente(valor){
		ocultarBuscador();
		obtenerClienteBusqueda(valor);
	}
	
	function Numeros(evnt){
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
		}
	}
	
	function obtenerClienteBusqueda(id){
		u.cliente_hidden.value = id;
		consulta("mostrarCliente","recoleccion_con.php?accion=1&cliente="+id);
	}
	function mostrarCliente(datos){
		u.cliente.value	= datos.getElementsByTagName('nombre').item(0).firstChild.data;
		refrescar();
	}
	function buscarFolio(folio){
		refrescar();
	}
	function devolverSucursal(){
		if(u.sucursal_hidden.value==""){
			setTimeout("devolverSucursal()",500);
		}else{
			if(u.sucursal_hidden.value == undefined || u.sucursal_hidden.value == "undefined" || u.sucursal_hidden.value == "no"){
				u.sucursal_hidden.value = v_suc;
			}
			consultaTexto("obtenerTotal", "recoleccion_conj.php?accion=5&tipo=0&sucursal="+u.sucursal_hidden.value
			+"&fecha="+u.fecha.value+"&cliente="+u.cliente_hidden.value+"&folio="+u.folio.value+"&valor="+Math.random());
		}
	}
	function devolverCliente(){
		if(u.cliente_hidden.value==""){
			setTimeout("devolverCliente()",500);
		}else{		
			consultaTexto("obtenerTotal", "recoleccion_conj.php?accion=5&tipo=0&sucursal="+u.sucursal_hidden.value
			+"&fecha="+u.fecha.value+"&cliente="+u.cliente_hidden.value+"&folio="+u.folio.value+"&valor="+Math.random());
		}
	}	
	function mostrarSucursal(datos){
		var objeto = eval(convertirValoresJson(datos));
		u.sucursal_hidden.value	= u.idSucOrigen.value;
		u.sucursal.value	= objeto.principal.descripcion;
	}
	
	function refrescar(){
		movioFecha = "";
		//u.fecha.value = '<?=$fecha; ?>';
		if(u.cliente.value=="")
			u.cliente_hidden.value="";
		if(u.sucursal.value="")
			u.sucursal_hidden.value	= "";			
		consultaTexto("obtenerTotal","recoleccion_conj.php?tipo=0&accion=5&sucursal="+u.sucursal_hidden.value+"&cliente="+u.cliente_hidden.value
		+"&fecha="+u.fecha.value+"&folio="+u.folio.value+"&valor="+Math.random());
	}
	
	function obtenerRecoleccionFiltro(){
		var sucursal = ((u.sucursal_hidden.value!="")? "&sucursal="+u.sucursal_hidden.value : "");
		var fecha 	 = ((u.fecha.value!=u.fecha_hidden.value)? "&fecha="+u.fecha.value : "");
		//movioFecha = "SI";
		consultaTexto("obtenerTotal","recoleccion_conj.php?accion=15&tipo=0"+sucursal+fecha);			
	}
	
	function mostrarRecoleccionxFecha(datos){
		//alerta3(datos,"");
		tabla1.clear();
		canceladas = 0;
		realizadas = 0;
		transmitidas = 0;
		var objeto = eval(convertirValoresJson(datos));
		for(var i=0;i<objeto.length;i++){
			var registro 		   = new Object();
			registro.folio 		   = objeto[i].folio;
			registro.cliente 	   = objeto[i].cliente;
			registro.direccion 	   = objeto[i].direccion;
			registro.transmitida   = objeto[i].transmitida;
			registro.realizo 	   = objeto[i].realizo;
			registro.unidad 	   = objeto[i].unidad;
			registro.fecha 		   = ((objeto[i].fecha!="00/00/0000")?objeto[i].fecha : "");
			registro.horario 	   = objeto[i].horario;
			registro.telefono 	   = objeto[i].telefono;
			registro.folios 	   = objeto[i].folios;
			registro.sucursal 	   = objeto[i].sucursal;
			registro.motivos 	   = objeto[i].motivos;
			registro.colorcan 	   = objeto[i].colorcan;
			registro.colorrep 	   = objeto[i].colorrep;
			registro.fecharegistro = objeto[i].fecharegistro;
			registro.guia 		   = objeto[i].guia;
			tabla1.add(registro);
			if(objeto[i].colorcan!=""){
				color(objeto[i].colorcan, i);
			}
			var fi = objeto[i].fecharegistro.split("/");
			var ff = u.fecha_hidden.value.split("/");
			var initDate = new Date(fi[2],fi[1],fi[0]);
			var endDate = new Date(ff[2],ff[1],ff[0]);

			if(objeto[i].colorrep!="" && initDate <= endDate){
				color(objeto[i].colorrep, i);
			}
			
			var f1 = u.fecha.value.split("/");
			var f2 = objeto[i].fecharegistro.split("/");
			f1 = new Date(f1[2],f1[1],f1[0]);
			f2 = new Date(f2[2],f2[1],f2[0]);			
			
			if(objeto[i].estado == "CANCELADO" && f2 >= f1){
				canceladas++;
			}
			
			if(objeto[i].estado != "CANCELADO" && objeto[i].realizo=="SI" && f2 >= f1){
				realizadas++;
			}
			
			if(objeto[i].estado != "CANCELADO" && objeto[i].realizo=="NO" && f2 >= f1){
				transmitidas++;
			}
		}
			u.totalc.value	= canceladas;
			u.totalr.value	= realizadas;
			u.totals.value	= transmitidas;
			u.total.value	= canceladas + realizadas + transmitidas;
	}
	
	function color(color, fila){		
		switch(color){
		case "ROJO":
			tabla1.setColorById('#FF0000','detalle_id'+fila);
		break;

		case "AZUL":
			tabla1.setColorById('#0000FF','detalle_id'+fila);	
		break;

		case "AMARILLO":
			tabla1.setColorById('#FFFF00','detalle_id'+fila);
		break;

		case "MORADO":
			tabla1.setColorById('#9900FF','detalle_id'+fila);
		break;

		case "ROSA":
			tabla1.setColorById('#FF33FF','detalle_id'+fila);
		break;
		
		case "VERDE":
			tabla1.setColorById('#009933','detalle_id'+fila);
		break;
		
		case "GRIS":
			tabla1.setColorById('#666666','detalle_id'+fila);
		break;
		
		case "CAFE":
			tabla1.setColorById('#663300','detalle_id'+fila);
		break;
		
		case "NARANJA":
			tabla1.setColorById('#FF9900','detalle_id'+fila);
		break;
		
		}
	}
	
	function imprimirReporte(){
		if(tabla1.getRecordCount()>0){
			if(document.URL.indexOf("web_capacitacionPruebas/")>-1){		
				var v_dir = "https://www.pmmintranet.net/web_capacitacionPruebas/recoleccion/";
			}else if(document.URL.indexOf("web_capacitacion/")>-1){
				var v_dir = "https://www.pmmintranet.net/web_capacitacion/recoleccion/";
			}else if(document.URL.indexOf("web_pruebas/")>-1){
				var v_dir = "https://www.pmmintranet.net/web_pruebas/recoleccion/";
			}
			window.open(v_dir+"recMercanciaExcel.php?sucursal="+u.sucursal_hidden.value
			+"&fecha="+u.fecha.value+"&cliente="+u.cliente_hidden.value+"&folio="+u.folio.value+"&valor="+Math.random());
		}else{
			mens.show("A","Debe generar el reporte","¡Atención!","sucursal");
		}
	}
	
	var desc = new Array(<?php echo $desc; ?>);
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>

<style type="text/css">
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:250px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:1em;
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
</style><link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="800" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="668" class="FondoTabla Estilo4">RECOLECCI&Oacute;N DE MERCANC&Iacute;A</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
      
        <tr>
          <td colspan="7">
		    <img src="../img/Boton_Nueva_Recoleccion.gif" width="130" height="20" onClick="document.location.href='rec.php?accion=&idsucorigen=<?=$_SESSION[IDSUCURSAL] ?>&fecha='+document.all.fecha.value" style="cursor:pointer">		   </td>
          </tr>
        <tr>
        <td>Fecha: 
          <input name="fecha_hidden" type="hidden" id="fecha_hidden" value="<?=$fecha ?>"></td>
        <td width="89"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:80px;background:#FFFF99" value="<?=$fecha ?>" onChange="obtenerRecoleccionFiltro();" readonly=""/></td>
        <td width="93"><span class="Estilo6 Tablas"><img src="../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onClick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)" />
          <input name="idSucOrigen" type="hidden" id="idSucOrigen" value="<?=$_SESSION[IDSUCURSAL]?>">
        </span></td>
        <td width="68">Sucursal:</td>
        <td width="190"><span class="Tablas">
          <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:170px" value="<?=$fs->descripcion?>" autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.sucursal_hidden.value=this.codigo; devolverSucursal()}" onBlur="if(this.value!=''){document.all.sucursal_hidden.value = this.codigo; if(this.codigo==undefined){document.all.sucursal_hidden.value ='no'}}"/>
        </span></td>
        <td>Folio:</td>
        <td width="242"><input name="folio" type="text" class="Tablas" onKeyPress="if(event.keyCode==13){buscarFolio(this.value);}" id="folio" style="width:80px" value="<?=$folio ?>"/></td>
        </tr>
      <tr>
        <td width="76">Cliente:
          <input name="cliente_hidden" type="hidden" id="cliente_hidden" value="<?=$idcliente ?>"></td>
        <td colspan="4"><input name="cliente" type="text" class="Tablas" id="cliente" style="width:250px" value="<?=$cliente ?>" onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event,'ajax-list-cliente.php')" onKeyPress="if(event.keyCode==13){devolverCliente();}" />
          <span class="Tablas"><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" 
		  onclick="mostrarBuscador()">&nbsp;</span></td>
        <td width="38"><input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$_SESSION[IDSUCURSAL] ?>">
          <br /></td>
        <td><span class="Tablas"><img id="img_refrescar" src="../img/Boton_Actualizar2.gif" align="absbottom" style="cursor:pointer" onClick="refrescar();"></span></td>
        </tr>
      
      <tr>
        <td colspan="7">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="7"></td>
      </tr>
      <tr>
        <td colspan="7"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><div id="txtDir" style=" height:270px; width:790px; overflow:auto" align=left><table width="850" id="detalle" border="0" cellspacing="0" cellpadding="0">          
        </table></div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="7"><table width="600" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="95">Total Canceladas:</td>
            <td width="62"><input name="totalc" type="text" class="Tablas" id="totalc" style="width:40px;background:#FFFF99; text-align:right" value="<?=$totalc ?>" readonly=""/></td>
            <td width="107">Total Recolectadas:<br /></td>
            <td width="61"><input name="totalr" type="text" class="Tablas" id="totalr" style="width:40px;background:#FFFF99; text-align:right" value="<?=$totalr ?>" readonly=""/></td>
            <td width="114">Total Sin Recolectar:<br /></td>
            <td width="60"><input name="totals" type="text" class="Tablas" id="totals" style="width:40px;background:#FFFF99; text-align:right" value="<?=$totals ?>" readonly=""/></td>
            <td width="39">Total:<br /></td>
            <td width="62"><input name="total" type="text" class="Tablas" id="total" style="width:40px;background:#FFFF99; text-align:right" value="<?=$total ?>" readonly=""/></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="7">
		<div id="paginado" align="center" style="visibility:hidden">
              <input name="totaldes" type="hidden" id="totaldes" value="00" />
              <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />
              <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="obtenerPrimero()" /> <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onClick="paginacion('atras')" /> <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onClick="paginacion('siguiente')" /> <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onClick="obtenerUltimo()" />
              <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" />
              <input name="mostrardes2" class="Tablas" type="hidden" id="mostrardes2" value="<?=$tdes; ?>" />
              <input name="ultimo" class="Tablas" type="hidden" id="ultimo" />
          </div>		</td>
      </tr>
      <tr>
            <td colspan="7" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte()" style="margin-right:5px; margin-bottom:5px"></div></td>
          </tr>
    </table></td></tr>
</table>
</form>
</body>
</html>
<?
	$raiz = "../";
	$funcion = "traerAlcliente";
	$nombreBuscador = "buscadorClientes";
	$funcionMostrar = "mostrarBuscador";
	$funcionOcultar = "ocultarBuscador";
	include("../buscadores_generales/buscadorIncrustado.php");
?>