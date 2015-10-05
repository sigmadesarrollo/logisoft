<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	require_once('recoleccion_conj.php');
	$link=Conectarse('webpmm');
	$cliente	= $_POST['cliente']; 
	$nombre		= $_POST['nombre'];
	$calle		= $_POST['calle']; 
	$numero		= $_POST['numero']; 
	$colonia	= $_POST['colonia']; 
	$cp			= $_POST['cp'];
	$crucecalles= $_POST['crucecalles'];
	$poblacion	= $_POST['poblacion'];
	$municipio	= $_POST['municipio'];
	$telefono	= $_POST['telefono'];
	$accion		= $_POST['accion'];
	$detalle 	= $_POST['detalle'];
	$registros  = $_POST['registros'];
	$usuario	= $_SESSION[NOMBREUSUARIO];
	
if($accion=="grabar"){

	$sql_limpiar=mysql_query("DELETE FROM configurarrecoleccionesprogramadas 
	WHERE idcliente = '$_POST[cliente]' ",$link)or die("Error en la line ".__LINE__);
	//INSERTAR TABLA DETALLE
		for($i=0;$i<$registros;$i++){
			$sqlins=mysql_query("INSERT INTO configurarrecoleccionesprogramadas 
			(idcliente,iddireccion,direccion,idsucursal,idorigen,origen,iddestino,
			 destino,dia,sector,horario,horario2,hrcomida,hrcomida2,fecharegistro,usuario,fecha) VALUES 	( 
				".trim($_POST["tabladetalle_IDCLIENTE"][$i]).",
				'".trim($_POST["tabladetalle_IDDIRECCION"][$i])."', 
				UCASE('".$_POST["tabladetalle_DIRECCION"][$i]."'), 
				'".trim($_POST["tabladetalle_IDSUCURSAL"][$i])."',
				'".trim($_POST["tabladetalle_IDORIGEN"][$i])."', 
				UCASE('".$_POST["tabladetalle_ORIGEN"][$i]."'),
				'".trim($_POST["tabladetalle_IDDESTINO"][$i])."',
				UCASE('".$_POST["tabladetalle_DESTINO"][$i]."'), 
				'".$_POST["tabladetalle_DIA"][$i]."',
				'".$_POST["tabladetalle_SECTOR"][$i]."',				
				'".(($_POST["tabladetalle_HORARIO"][$i]=='00:00' && $_POST[horario_hidden]!='00:00')?$_POST[horario_hidden]:$_POST["tabladetalle_HORARIO"][$i])."',				
				'".(($_POST["tabladetalle_HORARIO2"][$i]=='00:00' && $_POST[horario2_hidden]!='00:00')?$_POST[horario2_hidden]:$_POST["tabladetalle_HORARIO2"][$i])."',				
				'".(($_POST["tabladetalle_HRCOMIDA"][$i]=='00:00' && $_POST[comida_hidden]!='00:00')?$_POST[comida_hidden]:$_POST["tabladetalle_HRCOMIDA"][$i])."',				
				'".(($_POST["tabladetalle_HRCOMIDA2"][$i]=='00:00' && $_POST[comida2_hidden]!='00:00')?$_POST[comida2_hidden]:$_POST["tabladetalle_HRCOMIDA2"][$i])."',
				UCASE('".$_POST["tabladetalle_FECHAREGISTRO"][$i]."'),							
				'$usuario', CURRENT_TIMESTAMP())",$link)or die(mysql_error($link).$sqlins);
				
			$detalle .= "{
				idcliente:'".trim($_POST["tabladetalle_IDCLIENTE"][$i])."',
				iddireccion:'".trim($_POST["tabladetalle_IDDIRECCION"][$i])."',
				direccion:'".$_POST["tabladetalle_DIRECCION"][$i]."',
				idsucursal:'".trim($_POST["tabladetalle_IDSUCURSAL"][$i])."',
				idorigen:'".trim($_POST["tabladetalle_IDORIGEN"][$i])."',
				origen:'".$_POST["tabladetalle_ORIGEN"][$i]."',
				iddestino:'".trim($_POST["tabladetalle_IDDESTINO"][$i])."',
				destino:'".$_POST["tabladetalle_DESTINO"][$i]."',
				sector:'".$_POST["tabladetalle_SECTOR"][$i]."',				
				horario:'".(($_POST["tabladetalle_HORARIO"][$i]=='00:00' && $_POST[horario_hidden]!='00:00')?$_POST[horario_hidden]:$_POST["tabladetalle_HORARIO"][$i])."',				
				horario2:'".(($_POST["tabladetalle_HORARIO2"][$i]=='00:00' && $_POST[horario2_hidden]!='00:00')?$_POST[horario2_hidden]:$_POST["tabladetalle_HORARIO2"][$i])."',				
				hrcomida:'".(($_POST["tabladetalle_HRCOMIDA"][$i]=='00:00' && $_POST[comida_hidden]!='00:00')?$_POST[comida_hidden]:$_POST["tabladetalle_HRCOMIDA"][$i])."',				
				hrcomida2:'".(($_POST["tabladetalle_HRCOMIDA2"][$i]=='00:00' && $_POST[comida2_hidden]!='00:00')?$_POST[comida2_hidden]:$_POST["tabladetalle_HRCOMIDA2"][$i])."',
				dia:'".$_POST["tabladetalle_DIA"][$i]."',
				fecharegistro:'".$_POST["tabladetalle_FECHAREGISTRO"][$i]."'},";				
		}
		$detalle = substr($detalle,0,strlen($detalle)-1);
	
	$mensaje	="Los datos han sido guardados correctamente";
	$accion		="grabar";	
	
	}else if($accion=="cancelar"){	
		$sql_del=mysql_query("DELETE FROM configurarrecoleccionesprogramadas 
		WHERE idcliente='".$_POST['cliente']."'",$link);
		$mensaje ="Los datos han sido cancelados correctamente";
		$accion	 ="grabar";
	}
	function insertarRecoleccion($folio,$sucursal, $dias, $destino){
		$link=Conectarse('webpmm');
		//$folio = obtenerFolioRecoleccion($sucursal);
		$s = mysql_query("INSERT INTO recoleccion
		 (folio, fecharegistro, estado, sucursal, destino, cliente, calle, numero, crucecalles, cp, colonia, poblacion,
		 municipio, telefono2, sector, horario, horario2, hrcomida, hrcomida2, diasprogramados, usuario, fecha) 
		 VALUES ('".$folio."',CURRENT_DATE(), 'NO TRANSMITIDO', ".$sucursal.", ".$destino.", '".$_POST['cliente']."',
		  UCASE('".$_POST['calle']."'),
		 UCASE('".$_POST['numero']."'), UCASE('".$_POST['crucecalles']."'), '".$_POST['cp']."', 
		 UCASE('".$_POST['colonia']."'), UCASE('".$_POST['poblacion']."'), UCASE('".$_POST['municipio']."'),
		 '".$_POST['telefono']."', UCASE('".$_POST['sector']."'),'".$_POST[horario_hidden]."','".$_POST[horario2_hidden]."',
		 '".$_POST[comida_hidden]."','".$_POST[comida2_hidden]."','".$dias."', '".$_SESSION[NOMBREUSUARIO]."',
		 CURRENT_TIMESTAMP())",$link) or die(mysql_error($link)); 
		 
		$sq = mysql_query("INSERT INTO recolecciondetalle (recoleccion, sucursal,cantidad,iddescripcion,
		descripcion,contenido,peso,
		largo,ancho,alto,volumen,pesototal,usuario,fecha) VALUES
		(".$folio.",".$sucursal.",1,8,'ENVASE(S)','DOCUMENTO',1,1,1,1,1,1,'$_SESSION[NOMBREUSUARIO]',
		CURRENT_TIMESTAMP())",$link) or die(mysql_error($link)); 
	}
	
	$s = "SELECT CONCAT(prefijo,' - ',descripcion,':',id) AS sucursal FROM catalogosucursal ORDER BY prefijo";
	$r = mysql_query($s,$link) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$suc= "'".cambio_texto($f[0])."'".','.$suc; 	
		}
		$suc = $suc;		
		$suc = substr($suc, 0, -1);		
	}
	
	$s = "SELECT IF(cd.subdestinos=1, CONCAT(cs.prefijo,' - ',cd.descripcion,':',cd.id),CONCAT(cd.descripcion,' - ',cs.prefijo,':',cd.id)) AS descripcion, cd.sucursal FROM catalogodestino cd
	INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id
	ORDER BY descripcion";	
	$r = mysql_query($s,$link) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc; 	
		}
		$desc = "'VARIOS:0',".$desc;		
		$desc=substr($desc, 0, -1);		
	}
	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script src="../Recoleccion1/select.js"></script>
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
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/funciones.js"></script>
<script>
	var u = document.all;
	var tabla1 = new ClaseTabla();
	var modificar = 0;
	var v_direccion	= "";
	var v_iddireccion = "";
	var objDias		= "";
	var bandera 	= true;
	var mens 		= new ClaseMensajes();
	var v_sucursal	= "";
	var v_destino	= "";
	var btn_agregar = '<div id="d_agregar" class="ebtn_agregar" onClick="agregarVar()"></div>';
	var btn_modificar = '<div id="d_modificar" class="ebtn_agregar" onClick="agregarVar()"></div>';
	mens.iniciar('../javascript',false);
	tabla1.setAttributes({
		nombre:"tabladetalle",
		campos:[
			{nombre:"IDCLIENTE", medida:4,tipo:"oculto",alineacion:"center", datos:"idcliente"},
			{nombre:"IDDIRECCION", medida:4, alineacion:"left", tipo:"oculto", datos:"iddireccion"},
			{nombre:"DIRECCION", medida:140, alineacion:"left", datos:"direccion"},
			{nombre:"IDSUCURSAL", medida:4, tipo:"oculto", alineacion:"center", datos:"idsucursal"},
			{nombre:"ORIGEN", medida:100, alineacion:"left", datos:"origen"},
			{nombre:"IDORIGEN", medida:4, tipo:"oculto", alineacion:"center", datos:"idorigen"},
			{nombre:"DESTINO", medida:100, alineacion:"left", datos:"destino"},
			{nombre:"IDDESTINO", medida:4, alineacion:"left", tipo:"oculto", datos:"iddestino"},
			{nombre:"SECTOR", medida:4, alineacion:"left", tipo:"oculto", datos:"sector"},
			{nombre:"HORARIO", medida:4, alineacion:"left", tipo:"oculto", datos:"horario"},
			{nombre:"HORARIO2", medida:4, alineacion:"left", tipo:"oculto", datos:"horario2"},
			{nombre:"HRCOMIDA", medida:4, alineacion:"left", tipo:"oculto", datos:"hrcomida"},
			{nombre:"HRCOMIDA2", medida:4, alineacion:"left", tipo:"oculto", datos:"hrcomida2"},
			{nombre:"DIA", medida:80, alineacion:"left", datos:"dia"},
			{nombre:"FECHAREGISTRO", medida:4, tipo:"oculto", alineacion:"left", datos:"fecharegistro"}
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
		u.cliente.focus();
		tabla1.create();
		u.d_eliminar.style.visibility = "hidden";
		obtenerDetalles();
	}
	
	function obtenerDetalles(){
		var datosTabla = <? if($detalle!=""){echo "[".$detalle."]";}else{echo "0";} ?>;
		if(datosTabla!=0){			
			for(var i=0; i<datosTabla.length; i++){
				tabla1.add(datosTabla[i]);
			}
		}
		
		if(tabla1.getRecordCount()>0){
			u.d_eliminar.style.visibility = "visible";
		}
		
	}

	function EliminarFila(){
		if(document.all.eliminar.value!=""){
			if(tabla1.getValSelFromField("direccion","DIRECCION")!=""){
				confirmar('¿Esta seguro de Eliminar la Fila?', '', 'elimino();', '');
			}
		}else{
			mens.show('A','Seleccione la fila a eliminar','¡Atención!','');
		}
	}
	
	function elimino(){
		tabla1.deleteById(document.all.eliminar.value);
	}
	
	function ModificarFila(){
		var obj = tabla1.getSelectedRow();
		if(tabla1.getValSelFromField("direccion","DIRECCION")!=""){
			u.tdAgregar.innerHTML = btn_modificar;
			modificar = 1;
			u.cliente.value	= obj.idcliente;
			u.sucursal_hidden.value= obj.idorigen;
			u.sucursal.value= obj.origen;
			u.destino.value = obj.destino;
			u.destino_hidden.value = obj.iddestino;
			v_sucursal = obj.idsucursal;
			v_destino  = obj.iddestino;
			
			var semana	= obj.dia;
			if(semana == "L,M,MI,J,V,S"){
				u.checkbox1.checked = true;
			}else{
				semana = semana.split(",");
				for(var f=1;f<=7;f++){
					for(var i=0;i<=semana.length;i++){
						if(document.getElementById('checkbox'+f).value==semana[i] ){
							document.getElementById('checkbox'+f).checked=true;
							break;
						}else{
							document.getElementById('checkbox'+f).checked=false;
						}
					}
				}
			}
			BloquearDias();
			document.all.modificarfila.value = tabla1.getSelectedIdRow();
			consulta("mostrarCliente","consultaRecoleccion.php?accion=1&cliente="+u.cliente.value);
			//u.sucursal.value = v_sucursal;
			u.sucursal.focus();
		}
	}

	function agregarVar(){
		if(u.cliente.value=="" && u.calle.value=="" ){
			mens.show('A','Debe Capturar Cliente','¡Atención!','cliente');
			return false;	
		}

		if(u.sucursal_hidden.value=="" || u.sucursal_hidden.value==undefined){
			mens.show('A','Debe capturar Sucursal','¡Atención!','sucursal');
			return false;	
		}

		if(u.destino_hidden.value=="" || u.destino_hidden.value==undefined){
			mens.show('A','Debe capturar Destino','¡Atención!','destino');
			return false;
		}	
		
		var mensaje = "";
		
		if(objDias.todasemana == 0 && objDias.lunes == 0 && objDias.martes == 0 && objDias.miercoles == 0 && objDias.jueves == 0 && objDias.viernes == 0 && objDias.sabado == 0){
			bandera=true;
			mens.show('A','El destino seleccionado no realiza recolecciones','¡Atención!');			
			return false;
		}else{
				if(objDias.todasemana != 1){
					if(objDias.lunes == 0 && u.checkbox2.checked == true){
						mensaje = "Lunes,";			
					}
					
					if(objDias.martes == 0 && u.checkbox3.checked == true){
						if(mensaje!=""){
							mensaje += "Martes,";
						}else{
							mensaje = "Martes,";
						}
					}
					
					if(objDias.miercoles == 0 && u.checkbox4.checked == true){
						if(mensaje!=""){
							mensaje += "Miercoles,";
						}else{
							mensaje = "Miercoles,";
						}
					}
					
					if(objDias.jueves == 0 && u.checkbox5.checked == true){
						if(mensaje!=""){
							mensaje += "Jueves,";
						}else{
							mensaje = "Jueves,";
						}
					}
					
					if(objDias.viernes == 0 && u.checkbox6.checked == true){
						if(mensaje!=""){
							mensaje += "Viernes,";
						}else{
							mensaje = "Viernes,";
						}
					}
					
					if(objDias.sabado == 0 && u.checkbox7.checked == true){
						if(mensaje!=""){
							mensaje += "Sabado,";
						}else{
							mensaje = "Sabado,";
						}
					}
				}
			}
		if(mensaje!=""){
			bandera=true;
			mens.show('A','El destino seleccionado no realiza recolecciones los dias '+mensaje.substring(0,mensaje.length-1),'¡Atención!');			
			return false;
		}
		
		if(u.checkbox1.checked==false && u.checkbox2.checked ==false && u.checkbox3.checked==false  && u.checkbox4.checked==false && u.checkbox5.checked==false 	&& u.checkbox6.checked==false && u.checkbox7.checked==false) {
			mens.show('A','Debe capturar Dia de Recolección','¡Atención!');
			return false;
		}
		
		if(u.modificarfila.value==""){
			var dia	= tabla1.getValuesFromField("dia",":");
			var obdestino	= tabla1.getValuesFromField("destino",":");
			
			if(document.getElementById('checkbox1').checked == true && obdestino.indexOf(u.destino.value)>-1){
				if(dia != ""){
					mens.show('A','No se puede insertar Toda Semana','¡Atención!');
					return false;
				}
			}else if(dia.indexOf('L,M,MI,J,V,S')!=-1 && obdestino.indexOf(u.destino.value)>-1){
					mens.show('A','No se puede insertar Toda Semana','¡Atención!');
					return false;
			}
				
			var registros = tabla1.getRecordCount();
			var sucursal  = tabla1.getValuesFromField("sucursal",":");
			for(var i=0;i<registros;i++){			
				if(sucursal.indexOf(u.sucursal.value)!=-1){
					for(var i=1;i<=7;i++){
						if(document.getElementById('checkbox'+i).checked == true && obdestino.indexOf(u.destino.value)>-1) {
							if(dia.indexOf(document.getElementById('checkbox'+i).value)!=-1){
								mens.show('A','Ya existe : '+document.getElementById('checkbox'+i).value,'¡Atención!');
								return false;
							}
						}
					}	
				}
			}
		}
		
			var semana="";
			if(document.getElementById('checkbox1').checked == true){
				semana="L,M,MI,J,V,S";
			}else{
				for(var i=2;i<=7;i++){
					if (document.getElementById('checkbox'+i).checked == true) {
							semana += document.getElementById('checkbox'+i).value +",";
						}
				}semana=semana.substr(0,semana.length-1);
			}
		
			u.horario_hidden.value = u.h1.value+":"+u.h2.value;
			u.horario2_hidden.value= u.h3.value+":"+u.h4.value;
			u.comida_hidden.value  = u.c1.value+":"+u.c2.value;
			u.comida2_hidden.value = u.c3.value+":"+u.c4.value;
		
			var registro 	= new Object();
			registro.idcliente 	= u.cliente.value;
			registro.direccion 	= ((v_direccion!="") ? v_direccion : u.calle.value );
			registro.idorigen = u.sucursal_hidden.value;
			registro.origen 	= u.sucursal.value;
			registro.dia 		= semana;
			registro.iddestino	= u.destino_hidden.value;
			registro.destino	= u.destino.value;
			registro.iddireccion= ((v_iddireccion!="") ? v_iddireccion : u.iddireccion.value );
			registro.horario	= u.horario_hidden.value;
			registro.horario2	= u.horario2_hidden.value;
			registro.hrcomida	= u.comida_hidden.value;
			registro.hrcomida2	= u.comida2_hidden.value;
			registro.sector		= u.sector.value;
			registro.idsucursal	= u.idsucursal.value;			
		if(u.modificarfila.value==""){
			registro.fecharegistro = fechahora(registro.fecharegistro);
			tabla1.add(registro);
		}else{			
			if(u.sucursal_hidden.value=="no"){			
				registro.idsucursal = v_sucursal;
			}
			if(u.destino_hidden.value=="no"){
				registro.iddestino = v_destino;
			}
			registro.fecharegistro = u.fechahora.value;
			tabla1.updateRowById(tabla1.getSelectedIdRow(), registro);
			u.tdAgregar.innerHTML = btn_agregar;
		}
		/*******/
		u.d_eliminar.style.visibility = "visible";
		
		u.sucursal.value			="";	u.destino.value		= "";
		u.destino_hidden.value		= "";	u.sucursal_hidden.value		= "";
		u.checkbox1.checked			=false;	u.checkbox2.checked=false;
		u.checkbox3.checked=false;			u.checkbox4.checked=false;
		u.checkbox5.checked=false;			u.checkbox6.checked=false;
		u.checkbox7.checked=false;			u.modificarfila.value="";
		u.checkbox1.disabled=false;			u.checkbox2.disabled=false;
		u.checkbox3.disabled=false;			u.checkbox4.disabled=false;
		u.checkbox5.disabled=false;			u.checkbox6.disabled=false;
		u.checkbox7.disabled=false;			u.eliminar.value	="";
		u.modificarfila.value="";			u.oculto.value		= "";
		v_direccion			= "";			v_iddireccion		= "";
		v_sucursal = ""; 					v_destino = "";
		u.idsucursal.value="";
		bandera=true;
	}

	function obtenerClienteBusqueda(id){
		u.cliente.value = id;
		consulta("mostrarCliente","consultaRecoleccion.php?accion=1&cliente="+id+"&valor="+Math.random());
	}
	function obtenerCliente(e,id){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 && id!=""){		
		consulta("mostrarCliente","consultaRecoleccion.php?accion=1&cliente="+id+"&valor="+Math.random());
		}
	}
	function mostrarCliente(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		limpiarCliente();
		if(con>0){
		u.nombre.value	= datos.getElementsByTagName('nombre').item(0).firstChild.data;
		
		var endir = datos.getElementsByTagName('dir').item(0).firstChild.data;
		if(endir==1){
				u.celda_des_calle.innerHTML ='<input name="calle" type="text" class="Tablas" id="calle" style="width:280px;background:#FFFF99" value="<?=$calle ?>" readonly=""/>';
				u.numero.value =datos.getElementsByTagName('numero').item(0).firstChild.data;
				u.cp.value =datos.getElementsByTagName('cp').item(0).firstChild.data;
				u.colonia.value =datos.getElementsByTagName('colonia').item(0).firstChild.data;
				u.poblacion.value =datos.getElementsByTagName('poblacion').item(0).firstChild.data;
				u.municipio.value = datos.getElementsByTagName('municipio').item(0).firstChild.data;
				u.crucecalles.value =datos.getElementsByTagName('crucecalles').item(0).firstChild.data;
				u.telefono.value =datos.getElementsByTagName('telefono').item(0).firstChild.data;
				u.calle.value =datos.getElementsByTagName('calle').item(0).firstChild.data;
				u.iddireccion.value =datos.getElementsByTagName('id').item(0).firstChild.data;
		}else if(endir>1){
				var comb = "<select name='calle' class='Tablas' style='width:280px;font:tahoma; font-size:9px' onchange='"
				+"document.all.numero.value=this.options[this.selectedIndex].numero;"
				+"document.all.cp.value=this.options[this.selectedIndex].cp;"
				+"document.all.colonia.value=this.options[this.selectedIndex].colonia;"
				+"document.all.poblacion.value=this.options[this.selectedIndex].poblacion;"
				+"document.all.municipio.value=this.options[this.selectedIndex].municipio;"
				+"document.all.crucecalles.value=this.options[this.selectedIndex].crucecalles;"
				+"document.all.telefono.value=this.options[this.selectedIndex].telefono;"
				+"document.all.iddireccion.value=this.options[this.selectedIndex].id;"
				+" cambiarSector(document.all.cp.value,document.all.colonia.value);'>";
				
				for(var i=0; i<endir; i++){
				
				v_id 		= datos.getElementsByTagName('id').item(i).firstChild.data;	
				v_calle 		= datos.getElementsByTagName('calle').item(i).firstChild.data;
				v_numero		= datos.getElementsByTagName('numero').item(i).firstChild.data;
				v_cp 			= datos.getElementsByTagName('cp').item(i).firstChild.data;
				v_colonia		= datos.getElementsByTagName('colonia').item(i).firstChild.data;
				v_poblacion 	= datos.getElementsByTagName('poblacion').item(i).firstChild.data;
				v_municipio 	= datos.getElementsByTagName('municipio').item(i).firstChild.data;
				v_cruce			= datos.getElementsByTagName('crucecalles').item(i).firstChild.data;
				v_telefono 		= datos.getElementsByTagName('telefono').item(i).firstChild.data;
				v_fact			= datos.getElementsByTagName('facturacion').item(i).firstChild.data;

		
					if(i==0){					
						u.numero.value 		= v_numero;
						u.cp.value 			= v_cp;
						u.colonia.value 	= v_colonia;
						u.poblacion.value 	= v_poblacion;
						u.telefono.value 	= v_telefono;
						u.municipio.value 	= v_municipio;
						u.crucecalles.value	= v_cruce;
						u.telefono.value 	= v_telefono;
						u.iddireccion.value	= v_id;					
					}else if(v_fact=="SI"){
						u.numero.value 		= v_numero;
						u.cp.value 			= v_cp;
						u.colonia.value 	= v_colonia;
						u.poblacion.value 	= v_poblacion;
						u.telefono.value 	= v_telefono;
						u.municipio.value 	= v_municipio;
						u.crucecalles.value	= v_cruce;
						u.telefono.value 	= v_telefono;
						u.iddireccion.value	= v_id;							
					}
					
					comb += "<option "+ ((v_fact=="SI")? "selected " : "" ) +" value='"+v_calle+"' numero='"+v_numero+"'" 
					+"cp='"+v_cp+"' colonia='"+v_colonia+"'"
					+" poblacion='"+v_poblacion+"' telefono='"+v_telefono+"'"
					+" municipio='"+v_municipio+"' crucecalles='"+v_cruce+"' iddireccion='"+v_id+"'"
					+" telefono='"+v_telefono+"'>"
					+v_calle+"</option>";					
				}
				comb += "</select>";
				u.celda_des_calle.innerHTML = comb;				
			}
			
			cambiarSector(u.cp.value,u.colonia.value);				
							
			if(modificar != "1"){
				obtenerDetalle(u.cliente.value);
				consultaTexto("mostrarHorario","recoleccion_conj.php?accion=12&cliente="+u.cliente.value+"&valor="+Math.random());
			}			
			u.accion.value="grabar";			
		}else{			
			mens.show('A','El numero de cliente no existe','¡Atención!','cliente');			
		}			
	}
	function obtenerDetalle(cliente){	
		consultaTexto("mostrarDetalle","consultaRecoleccionJson.php?accion=2&cliente="+cliente+"&valor="+Math.random());
	}
	function mostrarDetalle(datos){		
		var objeto = eval(convertirValoresJson(datos));
		tabla1.setJsonData(objeto);
		
		if(tabla1.getRecordCount()>0){
			u.d_eliminar.style.visibility = "visible";
		}		
	}
	function mostrarHorario(datos){
		if(datos.indexOf("no encontro") < 0){			
			var objeto = eval(datos);
				var hor	   = objeto[0].horario.split(":");
				u.h1.value = hor[0];
				u.h2.value = hor[1];
				var hor2   = objeto[0].horario2.split(":");
				u.h3.value = hor2[0];
				u.h4.value = hor2[1];	
				var com	   = objeto[0].hrcomida.split(":");
				u.c1.value = com[0];
				u.c2.value = com[1];	
				var	com2   = objeto[0].hrcomida2.split(":");
				u.c3.value = com2[0];
				u.c4.value = com2[1];
				if(u.h1.value=="00" && u.h2.value=="00" && u.h3.value=="00" && u.h4.value=="00"){
					u.h1.focus();		
				}else{
					u.sucursal.select();
				}
		}else{
			u.h1.focus();
		}
	}
	
	function limpiarCliente(){
		u.numero.value 		= ""; u.cp.value 			= "";
		u.colonia.value 	= ""; u.poblacion.value 	= "";
		u.telefono.value 	= ""; u.municipio.value 	= "";
		u.crucecalles.value	= ""; u.telefono.value 	= "";
		u.sector.value		= ""; 
		document.all.celda_des_calle.innerHTML ='<input name="calle" type="text" class="Tablas" id="calle" style="width:280px;background:#FFFF99" value="<?=$calle ?>" readonly=""/>';
	}
		
	var nav4 = window.Event ? true : false;
	function Numeros(evt){ 
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46, ',' = 44 
	var key = nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
	}	
	
	function borrarDescripciones(nombrecaja){
		if(nombrecaja =="cliente" && u.cliente.value ==""){
			u.clienteb.value	= "";
			u.calle.value 		= "";
			u.numero.value 		= "";
			u.colonia.value 	= "";
			u.cp.value 			= "";
			u.ccalles.value 	= "";
			u.poblacion.value 	= "";
			u.municipio.value 	= "";
			u.telefono.value 	= "";
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
		else if(frm.elements[i+1].readOnly ==true )
			tabular(e,frm.elements[i+1]);
		else frm.elements[i+1].focus();
		return false;
	}

function foco(nombrecaja){
	if(nombrecaja=="cliente"){
		u.oculto.value="1";
	}
}

function BloquearDias(){
	if(document.getElementById('checkbox1').checked == true){
		document.getElementById('checkbox2').disabled = true;
		document.getElementById('checkbox3').disabled = true;		
		document.getElementById('checkbox4').disabled = true;
		document.getElementById('checkbox5').disabled = true;
		document.getElementById('checkbox6').disabled = true;
		document.getElementById('checkbox7').disabled = true;
		
		document.getElementById('checkbox2').checked = false;
		document.getElementById('checkbox3').checked = false;		
		document.getElementById('checkbox4').checked = false;
		document.getElementById('checkbox5').checked = false;
		document.getElementById('checkbox6').checked = false;
		document.getElementById('checkbox7').checked = false;
	}else{
		document.getElementById('checkbox2').disabled = false;
		document.getElementById('checkbox3').disabled = false;		
		document.getElementById('checkbox4').disabled = false;
		document.getElementById('checkbox5').disabled = false;
		document.getElementById('checkbox6').disabled = false;
		document.getElementById('checkbox7').disabled = false;
		document.getElementById('checkbox1').checked = false;
		document.getElementById('checkbox1').disabled = true;
	}
}


function limpiar(){
		u.cliente.value		= ""; u.nombre.value		= "";
		u.numero.value 		= ""; u.cp.value 			= "";
		u.colonia.value 	= ""; u.poblacion.value 	= "";
		u.telefono.value 	= ""; u.municipio.value 	= "";
		u.crucecalles.value	= ""; u.telefono.value 		= "";
		u.sector.value		= ""; u.sucursal.value		="";
		u.h1.value			= "00"; u.h2.value			= "00";
		u.h3.value			= "00"; u.h4.value			= "00";
		u.c1.value			= "00"; u.c2.value			= "00";
		u.c3.value			= "00"; u.c4.value			= "00";
		u.calle.value		= ""; u.destino.value		= "";
		u.idsucursal.value	="";
		u.sucursal_hidden.value="";
		u.checkbox1.disabled=false;		
		u.checkbox2.disabled=false;
		u.checkbox3.disabled=false;
		u.checkbox4.disabled=false;
		u.checkbox5.disabled=false;
		u.checkbox6.disabled=false;
		u.checkbox7.disabled=false;
		modificar = 0;
		v_direccion			= "";
		v_iddireccion		= "";
		u.destino_hidden.value		= "";
		u.checkbox1.checked =false; u.checkbox2.checked =false;
		u.checkbox3.checked =false; u.checkbox4.checked =false;
		u.checkbox5.checked =false;	u.checkbox6.checked =false;
		u.checkbox7.checked =false;	u.eliminar.value	="";
		u.modificarfila.value="";	u.oculto.value		="";
		u.accion.value		="";	u.fechahora.value	= "";
		objDias = ""; bandera=true;
		u.tdAgregar.innerHTML = btn_agregar;
		document.all.celda_des_calle.innerHTML ='<input name="calle" type="text" class="Tablas" id="calle" style="width:280px;background:#FFFF99" value="<?=$calle ?>" readonly=""/>';
		u.calle.value		= "";
		tabla1.clear();
		u.cliente.focus();
}

	function validar(){	
		if(u.cliente.value=="" && u.calle.value=="" ){
			mens.show('A','Debe capturar Cliente','¡Atención!','cliente');
			return false;
		}else if(u.h1.value =="00" || u.h3.value =="00"){
			mens.show('A','Debe capturar Horario','¡Atención!', ((u.h1.value=="00")? 'h1' : 'h3' ));
			return false;
		}else if(tabla1.getRecordCount()==0){
			mens.show('A','Debe agregar por lo menos una recolección','¡Atención!');
			return false;			
		}	
		if(document.getElementById('accion').value=="grabar"){
			u.registros.value 		  = tabla1.getRecordCount();
			u.horario_hidden.value    = u.h1.value+':'+u.h2.value;
			u.horario2_hidden.value   = u.h3.value+':'+u.h4.value;
			u.comida_hidden.value     = u.c1.value+':'+u.c2.value;
			u.comida2_hidden.value    = u.c3.value+':'+u.c4.value;
			document.form1.submit();
		}
	}


	function cambiarSector(cp,colonia){
		consultaTexto("mostrarSector","consultaRecoleccionJson.php?accion=1&cp="+cp+"&col="+colonia);
	}

	function mostrarSector(datos){
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
			if(objeto !=0){
				//u.idsector.value = objeto[0].id;
				u.sector.value   = objeto[0].descripcion;
			}
	}

	function Cancelar(){
		document.getElementById('accion').value="cancelar";
		document.form1.submit();
	}
	function enviar(){
		u.registros.value = tabla1.getRecordCount();
		document.all.accion.value=1;
		document.form1.submit();
	}
	function desactivarSemana(){
		if(u.checkbox2.checked==true){u.checkbox1.disabled = true;}
		if(u.checkbox3.checked==true){u.checkbox1.disabled = true;}
		if(u.checkbox4.checked==true){u.checkbox1.disabled = true;}
		if(u.checkbox5.checked==true){u.checkbox1.disabled = true;}
		if(u.checkbox6.checked==true){u.checkbox1.disabled = true;}
		if(u.checkbox7.checked==true){u.checkbox1.disabled = true;}
		if(u.checkbox2.checked==false && u.checkbox3.checked==false
		   && u.checkbox4.checked==false && u.checkbox5.checked==false
		   && u.checkbox6.checked==false && u.checkbox7.checked==false){
			u.checkbox1.disabled = false;
		}
	}
	function obtenerDireccion(){
	//alerta3("mostrarDireccionSucursal","consultaRecoleccionJson.php?accion=3&sucursal="+u.sucursal.value+"&cliente="+u.cliente.value);	
	consultaTexto("mostrarDireccionSucursal","consultaRecoleccionJson.php?accion=3&sucursal="+u.sucursal.value+"&cliente="+u.cliente.value);
	}
	function mostrarDireccionSucursal(datos){		
		var objeto = eval(datos);
		v_direccion 	= objeto[0].calle;
		v_iddireccion	= objeto[0].id;
	}
	
	function obtenerDiasRecoleccion(destino){
		destino = ((destino=="no")?v_destino:destino);
		destino = ((destino==undefined)?v_destino:destino);
		if(destino!=0){
			consultaTexto("mostrarDiasRecoleccion","consultaRecoleccionJson.php?accion=4&destino="+destino);
		}
	}
	
	function mostrarDiasRecoleccion(datos){
		if(bandera==true){
			bandera = false;
			if(datos.indexOf("no encontro")<0){				
				var obj = eval("("+datos+")");
				objDias = obj;
				var mensaje = "";
				u.idsucursal.value = obj.sucursal;
				if(obj.todasemana == 0 && obj.lunes == 0 && obj.martes == 0 && obj.miercoles == 0 && obj.jueves == 0 && obj.viernes == 0 && obj.sabado == 0){
					mens.show('A','El destino seleccionado no realiza recolecciones','¡Atención!');
				}else{
					if(obj.todasemana != 1){				
						if(obj.lunes == 0 && u.checkbox2.checked == true){
							mensaje = "Lunes,";			
						}
						
						if(obj.martes == 0 && u.checkbox3.checked == true){
							if(mensaje!=""){
								mensaje += "Martes,";
							}else{
								mensaje = "Martes,";
							}
						}
						
						if(obj.miercoles == 0 && u.checkbox4.checked == true){
							if(mensaje!=""){
								mensaje += "Miercoles,";
							}else{
								mensaje = "Miercoles,";
							}
						}
						
						if(obj.jueves == 0 && u.checkbox5.checked == true){
							if(mensaje!=""){
								mensaje += "Jueves,";
							}else{
								mensaje = "Jueves,";
							}
						}
						
						if(obj.viernes == 0 && u.checkbox6.checked == true){
							if(mensaje!=""){
								mensaje += "Viernes,";
							}else{
								mensaje = "Viernes,";
							}
						}
						
						if(obj.sabado == 0 && u.checkbox7.checked == true){
							if(mensaje!=""){
								mensaje += "Sabado,";
							}else{
								mensaje = "Sabado,";
							}
						}
					}
				}
				if(mensaje!=""){
	mens.show('A','El destino seleccionado no realiza recolecciones los dias '+mensaje.substring(0,mensaje.length-1),'¡Atención!');					
				}else{
					bandera=true;
				}
			}else{
				bandera=true;
			}
		}		
	}
	
	var desc = new Array(<?php echo $desc; ?>);
	var suc  = new Array(<?php echo $suc; ?>);
	
</script>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="Tablas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="530" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="490" class="estilo_relleno Estilo4">CONFIGURADOR DE RECOLECCIONES</td>
    </tr>
    <tr>
      <td><table width="528" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td colspan="5"><table width="571" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="79"># Cliente:</td>
                <td width="175"><span class="Tablas">
                  <input name="cliente" type="text" class="Tablas" id="cliente" style="width:60px" value="<?=$cliente ?>" onKeyPress="obtenerCliente(event,this.value)" />
                  <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick=            "abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda', 625, 418, 'ventana', 'Busqueda')"></span></td>
                <td colspan="4"><span class="Tablas">
                  <input name="nombre" type="text" class="Tablas" id="nombre" style="width:285px;background:#FFFF99" value="<?=$nombre ?>" readonly=""/>
                </span></td>
              </tr>
              <tr>
                <td>Calle:</td>
                <td colspan="3" id="celda_des_calle"><span class="Tablas">
                  <input name="calle" type="text" class="Tablas" id="calle" style="width:280px;background:#FFFF99" value="<?=$calle ?>" readonly=""/>
                </span></td>
                <td width="194"><span class="Tablas">
                  Numero:
                    <input name="numero" type="text" class="Tablas" id="numero" style="width:120px;background:#FFFF99" value="<?=$numero ?>" readonly=""/>
                </span></td>
                <td width="4">&nbsp;</td>
              </tr>
              <tr>
                <td>Colonia:</td>
                <td><span class="Tablas">
                  <input name="colonia" type="text" class="Tablas" id="colonia" style="width:165px;background:#FFFF99" value="<?=$colonia ?>" readonly=""/>
                </span></td>
                <td width="23">&nbsp;</td>
                <td width="96">C.P.:</td>
                <td><span class="Tablas">
                  <input name="cp" type="text" class="Tablas" id="cp" style="width:165px;background:#FFFF99" value="<?=$cp ?>" readonly=""/>
                </span></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>Cruce de Calles:</td>
                <td colspan="5"><span class="Tablas">
                  <input name="crucecalles" type="text" class="Tablas" id="crucecalles" style="width:460px;background:#FFFF99" value="<?=$crucecalles ?>" readonly=""/>
                </span></td>
              </tr>
              <tr>
                <td>Poblaci&oacute;n:</td>
                <td><span class="Tablas">
                  <input name="poblacion" type="text" class="Tablas" id="poblacion" style="width:165px;background:#FFFF99" value="<?=$poblacion ?>" readonly=""/>
                </span></td>
                <td>&nbsp;</td>
                <td>Mun./Deleg.:</td>
                <td><span class="Tablas">
                  <input name="municipio" type="text" class="Tablas" id="municipio" style="width:165px;background:#FFFF99" value="<?=$municipio ?>" readonly=""/>
                </span></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>Telefono:</td>
                <td><span class="Tablas">
                  <input name="telefono" type="text" class="Tablas" id="telefono" style="width:165px;background:#FFFF99" value="<?=$telefono ?>" readonly=""/>
                </span></td>
                <td>&nbsp;</td>
                <td>Hrio. Recolecci&oacute;n:</td>
                <td><span class="Tablas">
                  <select name="h1" size="1" onkeypress="if(event.keyCode==13){document.all.h2.focus();}" class="Tablas" id="h1">
                    <? for($h=0;$h<24;$h++){ ?>
                    <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>" <? if($h==$_POST['h1']){echo "selected";}else{echo "00";} ?>>
                    <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                    </option>
                    <? }?>
                  </select>
:
<select name="h2" size="1" onKeyPress="if(event.keyCode==13){document.all.h3.focus();}" class="Tablas" id="h2">
  <? for($m=0;$m<60;$m++){ ?>
  <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>"
				   <? if($m==$_POST['h2']){echo "selected";}else{echo "00";} ?>>
  <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
  </option>
  <? }?>
</select>
a
<select name="h3" size="1" onKeyPress="if(event.keyCode==13){document.all.h4.focus();}" class="Tablas" id="select4">
  <? for($h=0;$h<24;$h++){ ?>
  <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>" <? if($h==$_POST['h3']){echo "selected";}else{echo "00";} ?>>
  <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
  </option>
  <? }?>
</select>
:
<select name="h4" size="1" onKeyPress="if(event.keyCode==13){document.all.c1.focus();}" class="Tablas" id="select5">
  <? for($m=0;$m<60;$m++){ ?>
  <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>"
				   <? if($m==$_POST['h4']){echo "selected";}else{echo "00";} ?>>
  <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
  </option>
  <? }?>
</select>
                </span></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>Sector:</td>
                <td><span class="Tablas">
                  <input name="sector" type="text" class="Tablas" id="sector" style="width:165px;background:#FFFF99" value="<?=$sector ?>" readonly=""/>
                </span></td>
                <td>&nbsp;</td>
                <td>Hrio. Comida:</td>
                <td><span class="Tablas">
                  <select name="c1" size="1" onKeyPress="if(event.keyCode==13){document.all.c2.focus();}" class="Tablas" id="c1">
                    <? for($h=0;$h<24;$h++){ ?>
                    <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>" <? if($h==$_POST['c1']){echo "selected";}else{echo "00";} ?>>
                    <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                    </option>
                    <? }?>
                  </select>
:
<select name="c2" size="1" onKeyPress="if(event.keyCode==13){document.all.c3.focus();}" class="Tablas" id="select6">
  <? for($m=0;$m<60;$m++){ ?>
  <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>"
				   <? if($m==$_POST['c2']){echo "selected";}else{echo "00";} ?>>
  <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
  </option>
  <? }?>
</select>
a
<select name="c3" size="1" onKeyPress="if(event.keyCode==13){document.all.c4.focus();}" class="Tablas" id="select7">
  <? for($h=0;$h<24;$h++){ ?>
  <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>" <? if($h==$_POST['c3']){echo "selected";}else{echo "00";} ?>>
  <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
  </option>
  <? }?>
</select>
:
<select name="c4" size="1" onKeyPress="if(event.keyCode==13){document.all.sucursal.focus();}" class="Tablas" id="select8">
  <? for($m=0;$m<60;$m++){ ?>
  <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>"
				   <? if($m==$_POST['c4']){echo "selected";}else{echo "00";} ?>>
  <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
  </option>
  <? }?>
</select>
                </span></td>
                <td>&nbsp;</td>
              </tr>
              
            </table></td>
          </tr>
          <tr>
            <td colspan="7" class="FondoTabla">Datos Recolecci&oacute;n</td>
          </tr>
          <tr>
            <td colspan="7"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="60">&nbsp;</td>
                <td width="171" colspan="-1">&nbsp;</td>
                <td width="86">&nbsp;</td>
                <td width="254">&nbsp;</td>
              </tr>
              <tr>
                <td>Origen:</td>
                <td colspan="3"><span class="Tablas">
                  <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:200px" value="<?=$_POST[sucursal] ?>" 
				autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.destino_hidden.value=this.codigo; obtenerDiasRecoleccion(this.codigo);}" onBlur="if(this.value!=''){document.all.sucursal_hidden.value = this.codigo; if(this.codigo==undefined){document.all.sucursal_hidden.value='no'} if(bandera==true){obtenerDiasRecoleccion(this.codigo);}}"/>
                  </span>
                  <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$_POST[sucursal_hidden] ?>">                <input name="idsucursal" type="hidden" id="idsucursal" value="<?=$_POST[idsucursal] ?>"></td></tr>
              
              <tr>
                <td>Destino:</td>
                <td colspan="3"><span class="Tablas">
                  <input name="destino" type="text" class="Tablas" id="destino" style="width:200px" value="<?=$_POST[destino] ?>" 
				autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.destino_hidden.value=this.codigo;}" onBlur="if(this.value!=''){document.all.destino_hidden.value = this.codigo; if(this.codigo==undefined){document.all.destino_hidden.value='no'}}"/>
                </span><span class="Tablas">
                <input name="destino_hidden" type="hidden" id="destino_hidden" value="<?=$_POST[destino_hidden] ?>">
                </span></td>
              </tr>
              <tr>
                <td colspan="3"><input name="checkbox1" type="checkbox" id="checkbox1" value="TODAS" onClick="BloquearDias()"/>
Toda Semana
  <input name="checkbox2" type="checkbox" id="checkbox2" onClick="desactivarSemana();" value="L"/>
L
<input name="checkbox3" type="checkbox" id="checkbox3" onClick="desactivarSemana();" value="M" />
M
<input name="checkbox4" type="checkbox" id="checkbox4" onClick="desactivarSemana();" value="MI" />
MI
<input name="checkbox5" type="checkbox" id="checkbox5" onClick="desactivarSemana();" value="J" />
J
<input name="checkbox6" type="checkbox" id="checkbox6" onClick="desactivarSemana();" value="V" />
V
<input name="checkbox7" type="checkbox" id="checkbox7"  onClick="desactivarSemana();"value="S" />
S</td>
                <td><table width="157" height="34" border="0" align="left" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="70"><div id="d_eliminar" class="ebtn_eliminar" onClick="EliminarFila()"></div></td>
                    <td width="16" align="right" id="tdAgregar"><div id="d_agregar" class="ebtn_agregar" onClick="agregarVar()"></div></td>
                  </tr>
                </table></td>
              </tr>
              
            </table></td>
          </tr>
          <tr>
            <td colspan="7">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="7"><table cellpadding="0" cellspacing="0" id="tabladetalle" >
                                    </table></td>
          </tr>
          <tr>
            <td colspan="7">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="7"><table width="154"  align="right">
              <tr>
                <td width="34" height="23"><div class="ebtn_cancelar" onClick="Cancelar();"></div></td>
                <td width="34"><div class="ebtn_guardar" onClick="validar()"></div></td>
                <td width="72"><div class="ebtn_nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')"></div></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="7" align="center"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
              <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>">
              <input name="eliminar" type="hidden" id="eliminar">
              <input name="modificarfila" type="hidden" id="modificarfila">
              <input name="hiddencliente" type="hidden" id="hiddencliente">
              <input name="registros" type="hidden" id="registros">
              <input name="horario2_hidden" type="hidden" id="horario2_hidden" value="<?=$horario2_hidden ?>">
              <input name="comida_hidden" type="hidden" id="comida_hidden" value="<?=$comida_hidden ?>">
              <input name="comida2_hidden" type="hidden" id="comida2_hidden" value="<?=$comida2_hidden ?>">
              <input name="horario_hidden" type="hidden" id="horario_hidden" value="<?=$_POST[horario_hidden] ?>">
              <input name="iddireccion" type="hidden" id="iddireccion" value="<?=$_POST[iddireccion] ?>">
			  <input name="fechahora" type="hidden" id="fechahora" value="<?=$_POST[fechahora] ?>"></td>
          </tr>
          
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
<?
if ($mensaje!=""){
echo "<script language='javascript' type='text/javascript'>mens.show('I','".$mensaje."', 'Operación realizada correctamente');</script>";
}
?>