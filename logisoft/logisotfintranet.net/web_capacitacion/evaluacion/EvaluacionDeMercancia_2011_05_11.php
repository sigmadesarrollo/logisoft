<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$link=Conectarse('webpmm');
	
	$s = "SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion,':',cd.id),CONCAT(cd.descripcion,' - ',cs.prefijo,':',cd.id)) AS descripcion,
	cd.sucursal	FROM catalogodestino cd 
	INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id";	
	$r = mysql_query($s,$link) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".$f[0]."'".','.$desc; 	
		}
		$desc = substr($desc, 0, -1);
	}
	 
	 if($_POST[accion]=="grabar"){
		$error = 0;
		
		//mysql_query("BEGIN",$link);
			//$s = "select prefijo from catalogosucursal where id = '$_SESSION[IDSUCURSAL]'";
			//$r = mysql_query($s,$link) or die($s);
			//$f = mysql_fetch_object($r);
			
			//$s = 'INSERT INTO zzz_suc_'.$f->prefijo.' VALUES (null,"I")';
			//mysql_query($s,$link) or die($s);
			//$nuevofolio = mysql_insert_id($link);
		
		
		
			/*$s = "INSERT INTO evaluacionmercancia SET
			folio = obtenerFolio('evaluacionmercancia',$_SESSION[IDSUCURSAL]),
			fechaevaluacion = current_date,
			estado = 'GUARDADO',
			guiaempresarial = UCASE('".$_POST[NGuias]."'), 
			recoleccion = ".(($_POST[NRecoleccion]=="")?0:$_POST[NRecoleccion]).",
			destino = '".$_POST[countryid]."', 
			sucursaldestino = '".$_POST[SucDestino] ."', 
			bolsaempaque = ".((!empty($_POST[BolsaEmpaque]))?1:0).",
			cantidadbolsa = ".((!empty($_POST[CantidadEmpaque]))?$_POST[CantidadEmpaque]:0).",
			totalbolsaempaque = ".((!empty($_POST[TotalEmpaque]))? str_replace("$ ", "", $_POST[TotalEmpaque]) : 0).", 
			emplaye = ".((!empty($_POST[Emplaye]))?1:0).",
			totalemplaye = ".((!empty($_POST[TotalEmplaye]))? str_replace("$ ", "", $_POST[TotalEmplaye]):0).", 
			sucursal = ".$_SESSION[IDSUCURSAL].", 
			entrega = '".$_POST[entrega]."', 
			usuario = '".$_SESSION[NOMBREUSUARIO]."', 
			fecha = current_timestamp()";*/
			
			$s = "INSERT INTO evaluacionmercancia
			(folio,fechaevaluacion,estado,guiaempresarial,recoleccion,destino,sucursaldestino,bolsaempaque,
			cantidadbolsa,totalbolsaempaque,emplaye,totalemplaye,sucursal,entrega,usuario,fecha)
			VALUES (obtenerFolio('evaluacionmercancia',$_SESSION[IDSUCURSAL]),
				CURRENT_DATE,
				'GUARDADO',
				UCASE('".$_POST[NGuias]."'), 
				".(($_POST[NRecoleccion]=="")?0:$_POST[NRecoleccion]).",
				'".$_POST[countryid]."', 
				'".$_POST[SucDestino] ."', 
				".((!empty($_POST[BolsaEmpaque]))?1:0).",
				".((!empty($_POST[CantidadEmpaque]))?$_POST[CantidadEmpaque]:0).",
				".((!empty($_POST[TotalEmpaque]))? str_replace("$ ", "", $_POST[TotalEmpaque]) : 0).", 
				".((!empty($_POST[Emplaye]))?1:0).",
				".((!empty($_POST[TotalEmplaye]))? str_replace("$ ", "", $_POST[TotalEmplaye]):0).", 
				".$_SESSION[IDSUCURSAL].", 
				'".$_POST[entrega]."', 
				'".$_SESSION[NOMBREUSUARIO]."', 
				current_timestamp())";
			
			
			
			$result = mysql_query($s,$link) or die($s);			
			$folio = mysql_insert_id();	
			$_POST[Estado] = "GUARDADO";
			$s = "SELECT folio FROM evaluacionmercancia WHERE id = ".$folio."";
			$result = mysql_query($s,$link) or die($s); 
			if(!$result)
				$error = 1;
			$fx = mysql_fetch_object($result);
			
			//$fx->folio = $nuevofolio;
			
	 	for($i = 0; $i<$_POST[registros]; $i++){
			
			$_POST["tablaguias_CONTENIDO"][$i] = str_replace("|","",$_POST["tablaguias_CONTENIDO"][$i]);
			$_POST["tablaguias_ID"][$i] = str_replace("|","",$_POST["tablaguias_ID"][$i]);
			
			$result=mysql_query("INSERT INTO evaluacionmercanciadetalle SET
			evaluacion = ".$fx->folio.", cantidad = '".$_POST["tablaguias_CANT"][$i]."', descripcion = '".$_POST["tablaguias_ID"][$i]."',
			contenido = UCASE('".trim($_POST["tablaguias_CONTENIDO"][$i])."'), peso = '".$_POST["tablaguias_PESO_KG"][$i]."',
			largo = '".$_POST["tablaguias_LARGO"][$i]."', ancho = '".$_POST["tablaguias_ANCHO"][$i]."',
			alto = '".$_POST["tablaguias_ALTO"][$i]."', volumen = '".$_POST["tablaguias_P_VOLU"][$i]."',
			pesototal = '".$_POST["tablaguias_P_TOTAL"][$i]."', pesounit = '".$_POST["tablaguias_UNIT"][$i]."',
			idusuario = ".$_SESSION[IDUSUARIO].", usuario = '".$_SESSION[NOMBREUSUARIO]."', fecha = CURRENT_TIMESTAMP(),
			sucursal = ".$_SESSION[IDSUCURSAL].", espesototal = '".$_POST["tablaguias_EPT"][$i]."'",$link);
			if(!$result)
				$error = 1;
				
			$detalle .= "{
				cantidad:'".$_POST["tablaguias_CANT"][$i]."', id:'".$_POST["tablaguias_ID"][$i]."',
				descripcion:'".trim($_POST["tablaguias_DESCRIPCION"][$i])."', contenido:'".$_POST["tablaguias_CONTENIDO"][$i]."',
				peso:'".$_POST["tablaguias_PESO_KG"][$i]."', largo:'".$_POST["tablaguias_LARGO"][$i]."',
				ancho:'".$_POST["tablaguias_ANCHO"][$i]."', alto:'".$_POST["tablaguias_ALTO"][$i]."',
				pesototal:'".$_POST["tablaguias_P_TOTAL"][$i]."', volumen:'".$_POST["tablaguias_P_VOLU"][$i]."',
				pesounit:'".$_POST["tablaguias_UNIT"][$i]."', fecha:'".$_POST["tablaguias_FECHA"][$i]."',
				espesototal:'".$_POST["tablaguias_EPT"][$i]."'},";
		}
		
		$s = "INSERT INTO seguimiento_guias 
		SET guia = $fx->folio, ubicacion = $_SESSION[IDSUCURSAL],
		estado = 'EVALUACION', unidad=null, fecha=CURRENT_DATE, hora = CURRENT_TIME,
		usuario = $_SESSION[IDUSUARIO]";
		$result = mysql_query($s,$link) or die($s);
		if(!$result)
				$error = 1;
		
		$s = "update guiasventanillaclientes set estado='APLICADA' where id = '".$_POST[NGuias]."'";
		$result = mysql_query($s,$link) or die($s);
		if(!$result)
				$error = 1;
		
		$s = "UPDATE asignacionpapeletasrecdetalle SET utilizado = 1 WHERE folios IN ('".$_POST[NRecoleccion]."')";
		$result = mysql_query($s,$link) or die($s);
		if(!$result)
				$error = 1;
		
		if($error){
			//mysql_query("ROLLBACK",$link);
			$mensajeError = "Error al Registrar";
		}else{
			//mysql_query("COMMIT",$link);
			$mensaje = "Los datos han sido guardados correctamente con el folio #".$fx->folio;
			$_POST[accion] = "modificar";	
		}
		
		$detalle = substr($detalle,0,strlen($detalle)-1);
		
	 }
	
	if($_POST[accion]=="limpiar"){
		$_POST[NGuias] = "";
		$_POST[NRecoleccion] = "";
		$_POST[countryid] = "";
		$_POST[SucDestino] = "";
		$_POST[BolsaEmpaque] = "";
		$_POST[CantidadEmpaque] = "";
		$_POST[TotalEmpaque] = "";
		$_POST[Emplaye] = "";
		$_POST[TotalEmplaye] = "";		
		$_POST[entrega] = "";
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="../javascript/ClaseTabla.js" ></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<OBJECT ID="Metodos" style="visibility:hidden"
CLASSID="CLSID:21B8DA59-7F02-40B9-A5E9-FC848C3DB134"
CODEBASE="../activexs/Impresion.CAB#version=1,1,0,0">
</OBJECT>
<script language="javascript">
	String.prototype.pad = function(l, s, t){
		return s || (s = " "), (l -= this.length) > 0 ? (s = new Array(Math.ceil(l / s.length)
			+ 1).join(s)).substr(0, t = !t ? l : t == 1 ? 0 : Math.ceil(l / 2))
			+ this + s.substr(0, l - t) : this;
	};

	var u = document.all;
	var tabla1 	= new ClaseTabla();
	var mens = new ClaseMensajes();
	var sucursalorigen 	= 0;
	mens.iniciar('../javascript',false);
	var img = '<img id="btnCancelar" src="../img/Boton_Cancelar.gif" alt="Guardar" width="70" height="20" onClick="mens.show(\'C\',\'¿Realmente desea cancelar la Orden de Embarque?\', \'\', \'\',\'Cancelar();\')" style="cursor:pointer;" id="cancelar" />';
	var tabla_valt1 	= "";	
	var img_imprimir = '<img src="../img/Boton_Imprimir.gif" id="i_imprimir" alt="Imprimir" width="70" height="20" onClick="Imprimir();" style="cursor:pointer"  />';
	var img_guardar = '<img src="../img/Boton_Guardar.gif" alt="Guardar" name="i_guardar" width="70" height="20" id="i_guardar" style="cursor:pointer;" onClick="Validar();"  />';
	var bandera = true;
	var tablaven = '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="25%">No. Recoleccion: </td><td width="22%"><input name="NRecoleccion" type="text" class="Tablas" id="NRecoleccion" size="10" onkeypress="if(event.keyCode==13){ validarFolioRecoleccion(this.value);}else{return solonumeros(event)}" onkeyup="return habilitar(event,this.name)" onblur="if(this.value!=\'\'){validarFolioRecoleccion(this.value)}"/></td><td width="18%">&nbsp;</td><td width="35%"><input name="NGuias" type="text" class="Tablas" id="NGuias" style="visibility:hidden" /></td></tr></table>';
	
	var tablaemp = '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="25%">No. Recoleccion: </td><td width="22%"><input name="NRecoleccion" type="text" class="Tablas" id="NRecoleccion" size="10" onkeypress="if(event.keyCode==13){ validarFolioRecoleccion(this.value);}else{return solonumeros(event)}" onkeyup="return habilitar(event,this.name)"onblur="if(this.value!=\'\'){validarFolioRecoleccion(this.value)}" /></td><td width="18%">Guia Emp.:</td><td width="35%"><input name="NGuias" type="text" class="Tablas" id="NGuias" onkeydown="return tabular(event,this)" style="width:100px;" onkeyup="return habilitar(event,this.name)" onblur="u.prepagada.value = 0; if(this.value!=\'\'){solicitarDatosConve(this.value)}else{u.BolsaEmpaque.disabled = false; u.CantidadEmpaque.readOnly = false; u.CantidadEmpaque.style.backgroundColor=\'\'; u.Emplaye.disabled = false;}" /></td></tr></table>';
	
tabla1.setAttributes({
		nombre:"tablaguias",
		campos:[
			{nombre:"CANT", medida:45, alineacion:"right", datos:"cantidad"},
			{nombre:"ID", medida:2, alineacion:"right", tipo:"oculto", datos:"id"},
			{nombre:"DESCRIPCION", medida:140, alineacion:"left", datos:"descripcion"},
			{nombre:"CONTENIDO", medida:140, alineacion:"left", datos:"contenido"},
			{nombre:"PESO_KG", medida:45, alineacion:"right", datos:"peso"},
			{nombre:"LARGO", medida:40, alineacion:"right",  datos:"largo"},
			{nombre:"ANCHO", medida:40, alineacion:"right",  datos:"ancho"},
			{nombre:"ALTO", medida:40, alineacion:"right",  datos:"alto"},
			{nombre:"P_TOTAL", medida:50, alineacion:"right", datos:"pesototal"},			
			{nombre:"P_VOLU", medida:40, alineacion:"right", datos:"volumen"},
			{nombre:"UNIT", medida:4, alineacion:"right", tipo:"oculto", datos:"pesounit"},
			{nombre:"FECHA", medida:4, alineacion:"right", tipo:"oculto", datos:"fecha"},
			{nombre:"EPT", medida:4, alineacion:"right", tipo:"oculto", datos:"espesototal"}
		],
		filasInicial:10,
		alto:150,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		u.esventanilla.innerHTML = (('<?=$_GET[vieneempresarial]?>'!="")?tablaemp:tablaven);
		tabla1.create();		
		u.img_eliminar.style.visibility="hidden";
		u.NRecoleccion.focus();
		obtenerDetalles();
	}
	
	function obtenerDetalles(){
		var datosTabla = <? if($detalle!=""){echo "[".$detalle."]";}else{echo "0";} ?>;
		if(datosTabla!=0){			
			for(var i=0; i<datosTabla.length; i++){
				tabla1.add(datosTabla[i]);
			}
			u.cel.innerHTML = img_imprimir;
		}else{
			obtenerGenerales();
		}
	}
	
	function obtenerGenerales(){
		consultaTexto("mostrarGenerales","evaluacionMercancia_con.php?accion=3&bolsa=1&emplaye=2&sd="+Math.random());
	}
	
	function mostrarGenerales(datos){
		var obj = eval(convertirValoresJson(datos));
		u.costobolsa.value		= obj.datos.bolsaempaque;
		u.fechaevaluacion.value = obj.datos.fecha;
		u.folio.value 			= obj.datos.folio;
		u.costoemplaye.value	= obj.datos.emplaye;	
		u.costoextra.value		= obj.datos.costoextra;
		u.limite.value			= obj.datos.limite;
		u.porcada.value			= obj.datos.porcada;
		u.hsucursal.value		= obj.datos.sucursal;
	}	
	function ModificarFila(){
		if(u.Estado.value==""){
			var obj = tabla1.getSelectedRow();
			if(tabla1.getValSelFromField("cantidad","CANT")!=""){
			u.indice.value = tabla1.getSelectedIdRow();
			abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?&cantidad='+obj.cantidad
					+'&id='+obj.id
					+'&descripcion='+obj.descripcion
					+'&contenido='+obj.contenido
					+'&peso='+obj.peso
					+'&largo='+obj.largo
					+'&ancho='+obj.ancho
					+'&alto='+obj.alto
					+'&pesototal='+obj.pesototal
					+'&volumen='+obj.volumen
					+'&pesounit='+obj.pesounit
					+'&fechahora='+obj.fecha				
					+'&funcion=agregarDatos&eliminar=1&esmodificar=si&espesototal='+obtenerSiPesoTotal(), 400, 350, 'ventana', 'Datos Evaluación');	
			}
		}
	}
	
	function trim(cadena,caja){
		for(i=0;i<cadena.length;){
			if(cadena.charAt(i)==" ")
				cadena=cadena.substring(i+1, cadena.length);
			else
				break;
		}
		for(i=cadena.length-1; i>=0; i=cadena.length-1){
			if(cadena.charAt(i)==" ")
				cadena=cadena.substring(0,i);
			else
				break;
		}
		document.getElementById(caja).value=cadena;
	}
	function Cancelar(){
		<?=$cpermiso->verificarPermiso('346,436',$_SESSION[IDUSUARIO]);?>;
		if(u.Estado.value=="EN GUIA"){
		mens.show("A","El folio de la Evaluación #"+u.folio.value+" no se puede cancelar, porque ya fue documentada en una guía","¡Atención!");
			return false;
		}else{
			abrirVentanaFija('clavesecundaria.php?idusuario=<?=$idusuario ?>&modulo=<?=$modulo?>&usuario=<?=$usuario?>&cancelar=cancelar', 370, 340, 'ventana', 'Inicio de Sesión Secundaria');	
		}
	}
	function ConfirmarCancelar(can){
		if(can!=""){
			consultaTexto("registroCancelar","evaluacionMercancia_con.php?accion=7&folio="+u.folio.value);
		}
	}
	function registroCancelar(datos){
		if(datos.indexOf("ok")>-1){
			mens.show("I","La Evaluación se ha cancelado satisfactoriamente.","");
			u.Estado.value = 'CANCELADA';
			document.getElementById('guiacancelada').style.display = '';
			u.btnCancelar.style.visibility='hidden';
			u.i_guardar.style.visibility='hidden';
		}else{
			mens.show("A","Hubo un error al cancelar "+datos,"¡Atención!");
		}
	}
	function Obtener(folio){
		u.folio.value=folio;
		consultaTexto("mostrarEvaluacion","evaluacionMercancia_con.php?accion=8&evaluacion="+folio+"&sd="+Math.random());
	}
	function mostrarEvaluacionDatos(folio){
		consultaTexto("mostrarEvaluacion","evaluacionMercancia_con.php?accion=8&evaluacion="+folio+"&sd="+Math.random());
	}
	function mostrarEvaluacion(datos){
		if(datos.indexOf("no encontro")<0){
			var obj 					= eval(convertirValoresJson(datos));
			u.fechaevaluacion.value		= obj[0].fechaevaluacion;
			if(obj[0].estado=='ENGUIA')
				u.Estado.value			= 'EN GUIA';
			else
				u.Estado.value			= obj[0].estado;
			if(u.Estado.value == 'CANCELADA'){
				document.getElementById('guiacancelada').style.display = '';			
				u.i_guardar.style.visibility='hidden';
			}
			u.NGuias.value				= obj[0].guiaempresarial;
			u.NRecoleccion.value		= ((obj[0].recoleccion==0)?"":obj[0].recoleccion);
			u.countryid.value			= obj[0].destino;
			u.country.value				= obj[0].descripciondestino;
			u.SucDestino.value			= obj[0].sucursaldestino;
			u.BolsaEmpaque.checked		= ((obj[0].bolsaempaque==1)?true:false);
			u.Emplaye.checked			= ((obj[0].emplaye==1)?true:false);			
			u.CantidadEmpaque.value		= ((obj[0].cantidadbolsa=="0")?"":obj[0].cantidadbolsa);
			u.TotalEmpaque.value		= "$ "+((obj[0].totalbolsaempaque=="0")?"":obj[0].totalbolsaempaque);
			u.TotalEmplaye.value		= "$ "+((obj[0].totalemplaye=="0")?"":obj[0].totalemplaye);
			u.cel.innerHTML 			= img;
			u.entrega.value				= obj[0].entrega;
			if(obj[0].entrega=="1"){
				u.precioead.value			= "$ "+numcredvar(obj[0].precioead);
			}
			u.img_eliminar.style.visibility	="hidden";
			u.btnAgregar.style.visibility	="hidden";
			HabilitarRecoleccion();
			HabilitarEmpresarial();
			if(u.Estado.value=='CANCELADA'){
				u.btnCancelar.style.visibility='hidden';
			}else{
				u.btnCancelar.style.visibility='visible';
			}
			u.accion.value = "modificar";
			consultaTexto("mostrarDetalle","evaluacionMercancia_con.php?accion=9&evaluacion="+u.folio.value+"&sd="+Math.random());
		}else{
			mens.show("A","El folio de Evaluación de Mercancia no existe o no corresponde a la sucursal de "+u.hsucursal.value,"¡Atención!","folio");			
			limpiar();
		}
	}
	function mostrarDetalle(datos){
		tabla1.clear();
		var obj = eval(convertirValoresJson(datos));
		tabla1.setJsonData(obj);
	}
	function Validar(){	
		<?=$cpermiso->verificarPermiso('345,435',$_SESSION[IDUSUARIO]);?>;
		if(u.prepagada.value=="1" && u.subdestino.value=="0"){
			mens.show("A","Las guias prepagadas no pueden ser enviadas a destinos que no son sucursales","¡ATENCION!");
			return false;
		}
		
		if(u.prepagada.value==1 && tabla1.getRecordCount()>1){
			mens.show("A","Solo puede agregar un articulo a la evaluacion de mercancia","¡ATENCION!");
			return false;
		}
		
		if(u.prepagada.value==1 && parseFloat(document.all['tablaguias_CANT'][0].value)>1){
			mens.show("A","Solo puede agregar un articulo a la evaluacion de mercancia","¡ATENCION!");
			return false;
		}
		
		if(u.BolsaEmpaque.checked==true && u.CantidadEmpaque.value==""){
			mens.show("A","Por favor especifique la cantidad para el servicio envase","¡ATENCION!");
			return false;
		}
		
		u.registros.value = tabla1.getRecordCount();	
		if( u.countryid.value==undefined || u.country.value==""){
			mens.show("A","Debe capturar Destino","!Atenci&oacute;¡","country");
			return false;
		}		
		if(tabla1.getRecordCount()==0){
			mens.show("A",'Debe Capturar por lo menos una Evaluación al detalle','¡Atención!');
			return false;
		}else{
			abrirVentanaFija('clavesecundaria.php?idusuario=<?=$idusuario ?>&modulo=<?=$modulo?>&usuario='+u.user.value, 370, 340, 'ventana', 'Inicio de Sesión Secundaria');		
		}	
	}
	
	function registrar(autorizar){
		if(u.accion.value=="modificar"){
			if(u.Estado.value == "GUARDADO"){
				consultaTexto("modificarEvaluacion","evaluacionMercancia_con.php?accion=16&iddestino="+u.countryid.value
				+"&destino="+u.SucDestino.value+"&folio="+u.folio.value);
			}else{
				mens.show("A",'No se puede modificar el destino por que la evaluacion '+((u.Estado.value=='CANCELADA')?' fue cancelada':' ya fue registra en una guia'),'¡Atención!');
				return false;
			}
		}else{		
			if(autorizar=="SI"){
				u.i_guardar.style.visibility = "hidden";
				u.img_eliminar.style.visibility	="hidden";
				u.btnAgregar.style.visibility	="hidden";
				/*var arr = new Array();						
				arr[0] = "GUARDADO";
				arr[1] = u.NGuias.value;
				arr[2] = ((u.NRecoleccion.value=="")? 0 : u.NRecoleccion.value);
				arr[3] = u.countryid.value;
				arr[4] = u.SucDestino.value;
				arr[5] = ((u.BolsaEmpaque.checked==true)?1:0);
				arr[6] = ((u.CantidadEmpaque.value=="")? 0 : u.CantidadEmpaque.value);
				arr[7] = ((u.TotalEmpaque.value=="")? 0 : u.TotalEmpaque.value.replace("$ ",""));
				arr[8] = ((u.Emplaye.checked==true)?1:0);
				arr[9] = ((u.TotalEmplaye.value=="")? 0 : u.TotalEmplaye.value.replace("$ ",""));
				arr[10] = '<?=$_SESSION[IDSUCURSAL]?>';
				arr[11] = u.entrega.value;*/
				//consultaTexto("registroEvaluacion","evaluacionMercancia_con.php?accion=5&arre="+arr);
				u.registros.value = tabla1.getRecordCount();
				u.accion.value = "grabar";
				document.form1.submit();
				
			}
		}
	}
	function registroEvaluacion(datos){
		if(datos.indexOf("guardo")>-1){
			var row = datos.split(",");
			mens.show("I","Los datos han sido guardados correctamente con el folio #"+row[1],"");
			u.folio.value = row[1];
			u.cel.innerHTML = img_imprimir;
			u.Estado.value = "GUARDADO";
			u.accion.value = "modificar";
			u.i_guardar.style.visibility = "visible";
		}else{
			u.i_guardar.style.visibility = "visible";
			mens.show("A","Hubo un Error al guardar "+datos,"¡Atención!");
		}
	}
	
	function modificarEvaluacion(datos){
		if(datos.indexOf("ok")>-1){
			mens.show("I","Los datos han sido guardados correctamente","");
		}else{
			u.i_guardar.style.visibility = "visible";
			mens.show("A","Hubo un Error al guardar "+datos,"¡Atención!");
		}
	}
	
	function limpiar(){	
		u.folio.value				="";
		u.Estado.value				="";
		u.country.value				="";
		u.SucDestino.value			="";
		u.CantidadEmpaque.value		="";
		u.TotalEmpaque.value		="";
		u.TotalEmplaye.value		="";
		u.NRecoleccion.value		="";
		u.NGuias.value				="";
		u.fechaevaluacion.value		="";
		u.costoemplayeextra.value	="";
		u.totalpeso.value			="";
		u.msg1.value				="";
		u.user.value				="";
		u.fechahora.value			="";	
		u.countryid.value			=""; 
		u.iddestino.value			="";
		u.totalvol.value			="";
		u.registros.value			="";
		u.sucursalorigen.value		="";
		u.accion.value				="";
		u.indice.value 				="";
		u.BolsaEmpaque.checked		=false;
		u.Emplaye.checked			=false;
		u.prepagada.value			=0;
		tabla1.clear();
		u.cel.innerHTML				=	"";
		u.img_eliminar.style.visibility="hidden";
		u.btnAgregar.style.visibility	="visible";
		u.NGuias.style.backgroundColor	='';
		u.NGuias.disabled				=false;
		u.NRecoleccion.style.backgroundColor='';
		u.NRecoleccion.disabled			=false;
		u.BolsaEmpaque.disabled = false; 
		u.CantidadEmpaque.readOnly = false; 
		u.CantidadEmpaque.style.backgroundColor=''; 
		u.Emplaye.disabled = false;
		u.subdestino.value = "";
		u.precioead.value = "";
		u.precioead_h.value = "";
		u.entrega.value = "";
		u.accion.value = "limpiar";
		bandera = true;
		//obtenerGenerales();
		u.NRecoleccion.focus();
		document.all.countryid.value = '';
		document.all.country.codigo = '';
		document.getElementById('guiacancelada').style.display = 'none';
		u.i_guardar.style.visibility='visible';
		document.form1.submit();
	}	
	function devolverDestino(){
		if(u.Estado.value=='CANCELADA'){
			return false;
		}
		if(u.countryid.value==""){
			setTimeout("devolverDestino()",500);
		}else{
			if(isNaN(u.countryid.value)){
				mens.show("A","Debe capturar bien el Destino","¡Atención!","country");
				return false;
			}
			consultaTexto("mostrarDestino", "evaluacionMercancia_con.php?accion=1&destino="+u.countryid.value);
			return false;
		}
		//mens.show("A","mostrarDestino", "evaluacionMercancia_con.php?accion=1&destino="+u.countryid.value);
		//alert("evaluacionMercancia_con.php?accion=1&destino="+u.countryid.value);
	}
	function mostrarDestino(datos){
		var obj = eval(datos);
		var row = new Array();
		row[1] = obj.principal.subdestinos;
		row[2] = ((obj.principal.costoead=="")? 0 : obj.principal.costoead);
		u.SucDestino.value = obj.principal.descripcion;
		u.subdestino.value = obj.principal.subdestinos;
		u.precioead_h.value = obj.principal.costoead;
		
		if(u.prepagada.value=="1" && u.subdestino.value=="0"){
			mens.show("A","Las guias prepagadas no pueden ser enviadas a destinos que no son sucursales","¡Atencion!");
			u.SucDestino.value = "";
			u.country.value = "";
			u.subdestino.value = "";
			return false;
		}
		
		if(u.Estado.value == ""){
			if(u.entrega.value=="2" && row[1]=="0"){			
				u.entrega.value = "1";
				u.precioead.value = "$ "+numcredvar(u.precioead_h.value);
				mens.show('C','No hay Sucursal en el Destino seleccionado. No se puede realizar Entregas Ocurre, el costo EAD es de:$ '+numcredvar(u.precioead_h.value)+' ¿Desea continuar con la captura?','','','confirmacion()');
				
			}else if(u.entrega.value=="1" && row[2]!=""){
				u.precioead.value = "$ "+numcredvar(u.precioead_h.value);
				if(bandera == true){
					abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?funcion=agregarDatos&espesototal='+obtenerSiPesoTotal(), 400, 350, 'ventana', 'Datos Evaluaci&oacute;n','alCerrar();');
				}
			}else if(u.entrega.value=="2" && row[1]=="1"){
				if(bandera == true){
					abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?funcion=agregarDatos&espesototal='+obtenerSiPesoTotal(), 400, 350, 'ventana', 'Datos Evaluaci&oacute;n','alCerrar();');
				}
			}else{			
				u.entrega.focus();
			}
		}
		
		if(obj.principal.notificacion==1){
			mens.show("A",obj.principal.notificaciones,"¡Atencion!","entrega");
		}
	}
	
	function validarEntrega(tipo){		
		if(tipo=="2" && u.subdestino.value=="0"){		
			u.entrega.value = 1;
			u.precioead.value = "$ "+numcredvar(u.precioead_h.value);
			mens.show('C','No hay Sucursal en el Destino seleccionado. No se puede realizar Entregas Ocurre, el costo EAD es de:$ '+numcredvar(u.precioead_h.value)+' ¿Desea continuar con la captura?','','','confirmacion()');
		
		}else if(tipo=="1" && u.precioead_h.value!=""){			
			u.precioead.value = "$ "+numcredvar(u.precioead_h.value);
			if(bandera == true){
			abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?funcion=agregarDatos&espesototal='+obtenerSiPesoTotal(), 400, 350, 'ventana', 'Datos Evaluaci&oacute;n','alCerrar();');
			}
		}else if(tipo=="2" && u.subdestino.value=="1"){		
			u.precioead.value = "";
			if(bandera == true){
			abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?funcion=agregarDatos&espesototal='+obtenerSiPesoTotal(), 400, 350, 'ventana', 'Datos Evaluaci&oacute;n','alCerrar();');
			}
		}else if(tipo==""){
			u.precioead.value = "";
		}
	}
	
	function confirmacion(){
		abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?funcion=agregarDatos&espesototal='+obtenerSiPesoTotal(), 400, 350, 'ventana', 'Datos Evaluaci&oacute;n','alCerrar();');
	}
	
	function alCerrar(){
		if(tabla1.getRecordCount()==0){
			u.entrega.value = "";		
			u.entrega.focus();
		}else{
			bandera = false;
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
	
	function ObtenerPrecioBolsa(){
		 if(u.BolsaEmpaque.checked==true){	 	
			u.CantidadEmpaque.focus();
		 }else{
	  		u.TotalEmpaque.value="";
			u.CantidadEmpaque.value="";
		 }
	}
	function ObtenerPrecioEmplaye(){ 
		if(u.Emplaye.checked==true){
			if(tabla1.getRecordCount()==0){
				mens.show('A','Debe Capturar por lo menos una Evaluaci&oacute;n al detalle','Atencin!','Emplaye');
				u.Emplaye.checked=false;
			}else{
				CalcularEmplaye();
			}
		}else{
			u.TotalEmplaye.value	="";
		}	
	}	
	function CalcularEmplaye(){		
		var tot = ""; var vol = "";
		v_tot = 0; v_vol = 0;
		
		tot = tabla1.getValuesFromField("pesototal",",").split(",");
		vol = tabla1.getValuesFromField("volumen",",").split(",");				
		
		for(var i=0;i<tot.length;i++){		
			v_tot = parseFloat(tot[i]) + parseFloat(v_tot);					
		}
		u.totalpeso.value = v_tot;		
		
		for(var i=0;i<vol.length;i++){
			v_vol = parseFloat(vol[i]) + parseFloat(v_vol);						
		}
		u.totalvol.value = v_vol;		
		
	if(u.Emplaye.checked==true){	
		if(parseFloat(u.totalpeso.value) > parseFloat(u.totalvol.value)){
			if(parseFloat(u.totalpeso.value) <= parseFloat(u.limite.value)){
				u.TotalEmplaye.value = "$ "+u.costoemplaye.value;
			}else{
		var kgextra=parseFloat(u.totalpeso.value) - parseFloat(u.limite.value);
				u.TotalEmplaye.value="$ "+(parseFloat(u.costoemplaye.value) + parseFloat(((kgextra / parseFloat(u.porcada.value)) * parseFloat(u.costoextra.value))));
				}
				if(u.TotalEmplaye.value=='NaN'){
					u.TotalEmplaye.value="";
				}			
			}else{ 
				if(parseFloat(u.totalvol.value)<=parseFloat(u.limite.value)){
					u.TotalEmplaye.value="$ "+parseFloat(u.costoemplaye.value);
				}else{
				var kgextra=parseFloat(u.totalvol.value)-parseFloat(u.limite.value);
				u.TotalEmplaye.value="$ "+(parseFloat(u.costoemplaye.value) + parseFloat(((kgextra / parseFloat(u.porcada.value)) * parseFloat(u.costoextra.value))));
				}
				if(u.TotalEmplaye.value=='NaN'){
					u.TotalEmplaye.value="";
				}
			}
		}else{	
			u.TotalEmplaye.value="";	
		}
	}
	function ObtenerTotalBolsaFoco(){
		if(u.CantidadEmpaque.value!=""){
			u.TotalEmpaque.value="$ "+(parseFloat(u.costobolsa.value) * parseFloat(u.CantidadEmpaque.value)); 
		}
		if(u.TotalEmpaque.value=='NaN'){
			u.TotalEmpaque.value="";
		}
	}
	function ObtenerTotalBolsa(e,caja){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla==13){ 
			u.TotalEmpaque.value= "$ "+(parseFloat(u.costobolsa.value) * parseFloat(caja));
		}
		if(u.TotalEmpaque.value=='NaN'){
			u.TotalEmpaque.value="";
		}
	} 
 	
	function borrarFila(){
		if(tabla1.getValSelFromField('cantidad','CANT')==""){
			mens.show('A','Debe Seleccionar la fila a eliminar','¡Atención!');
		}else{
			mens.show('C','¿Esta seguro de Eliminar la mercancia seleccionada?','','','eliminoFila()');
		}
	}
	function eliminarFila(){		
		//consultaTexto("eliminoFila","evaluacionMercancia_con.php?accion=6&fecha="+tabla1.getSelectedRow().fecha+"&val="+Math.random());
	}
	
	function eliminoFila(){
		//if(datos.indexOf("ok")>-1){
			tabla1.deleteById(tabla1.getSelectedIdRow());
			if(tabla1.getRecordCount()==0){
				u.img_eliminar.style.visibility="hidden";
				u.Emplaye.checked = false;
				u.totalpeso.value = "";		
				u.totalvol.value = "";
				return false;
			}
			if(u.Emplaye.checked==true){
				ObtenerPrecioEmplaye();
			}
		/*}else{
			mens.show("A","Hubo un error al eliminar la fila "+datos,"¡Atención!");
		}*/
	}
	
	function agregarDatos(variable){	
		var u = document.all;
		if(tabla1.getRecordCount()>6){
			mens.show("A","Solo puede registrar 6 articulos","¡Atención!");
		}else{
			if(u.indice.value==""){			
				tabla1.add(variable);				
				if(u.Emplaye.checked==true){
					ObtenerPrecioEmplaye();
				}
			}else{
				tabla1.updateRowById(tabla1.getSelectedIdRow(), variable);				
				u.indice.value = "";
				if(u.Emplaye.checked==true){
					ObtenerPrecioEmplaye();
				}
			}
			u.img_eliminar.style.visibility="visible";
		}
		if(u.prepagada.value==1){
			var v_tot=0;
			var v_vol=0;
			tot = tabla1.getValuesFromField("pesototal",",").split(",");
			vol = tabla1.getValuesFromField("volumen",",").split(",");				
			
			for(var i=0;i<tot.length;i++){		
				v_tot = parseFloat(tot[i]) + parseFloat(v_tot);					
			}
			u.totalpeso.value = v_tot;		
			
			for(var i=0;i<vol.length;i++){
				v_vol = parseFloat(vol[i]) + parseFloat(v_vol);						
			}
			u.totalvol.value = v_vol;
			
			var pesomax = (v_vol>v_tot)?v_vol:v_tot;
			if(parseFloat(pesomax)>parseFloat(u.limitekg.value)){
				mens.show("A","Ha sobrepasado el limite de kg configurado en el convenio: "+u.limitekg.value+" KG","¡Atención!");
			}
		}
	}   
	
	function ObtenerPesoVolumen(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;		
			if(con>0){
				u.totalpeso.value=datos.getElementsByTagName('peso').item(0).firstChild.data;	
				u.totalvol.value=datos.getElementsByTagName('volumen').item(0).firstChild.data;
			}
	}
	function habilitar(e,nombre){
		tecla = (u) ? e.keyCode : e.which;
		if(nombre=="NRecoleccion"){
			if(tecla==8 && document.getElementById(nombre).value==""){
				document.getElementById('NGuias').style.backgroundColor='';
				document.getElementById('NGuias').disabled=false;			
			}else if(document.getElementById(nombre).value!=""){
				document.getElementById('NGuias').style.backgroundColor='#FFFF99';
				document.getElementById('NGuias').disabled=true;
			}
		}else if(nombre=="NGuias"){
			if(tecla==8 && document.getElementById(nombre).value==""){
				document.getElementById('NRecoleccion').style.backgroundColor='';		
				document.getElementById('NRecoleccion').disabled=false;
			}else if(document.getElementById(nombre).value!=""){
				document.getElementById('NRecoleccion').style.backgroundColor='#FFFF99';
				document.getElementById('NRecoleccion').disabled=true;
			}
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
		else frm.elements[i+1].focus();
		return false;
	} 
	function HabilitarRecoleccion(){	
		if(u.NRecoleccion.value!=""){	
			u.NGuias.style.backgroundColor='#FFFF99';	
			u.NGuias.disabled=true;
		}
	}
	function HabilitarEmpresarial(){
		if(u.NGuias.value!=""){	
			u.NRecoleccion.style.backgroundColor='#FFFF99';
			u.NRecoleccion.disabled=true;
		}
	}
	function Imprimir(){
		<?
			$permitido = true;
			
			$s = "SELECT impevaluaciones FROM configuracion_impresoras WHERE usuario = '$_SESSION[IDUSUARIO]'";
			$rxy = mysql_query($s,$link) or die($s);
			if(mysql_num_rows($rxy)>0){
				$fxy = mysql_fetch_object($rxy);
				echo "var met = new ActiveXObject('Impresion.Metodos');
				met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impevaluaciones)."');";
			}else{
				$s = "SELECT impevaluaciones FROM configuracion_impresoras WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
				$rxy = mysql_query($s,$link) or die($s);
				$fxy = mysql_fetch_object($rxy);
				echo "var met = new ActiveXObject('Impresion.Metodos');
				met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impevaluaciones)."');";
			}
		?>
		
		if(u.entrega.value!=0){
			var entrega = ((u.entrega.value==1)?"EAD":"OCURRE");
		}
		var laUrl = document.URL;			
			if(laUrl.indexOf('pmmintranet.net')>-1){
				var direccion = laUrl.substr(0,laUrl.indexOf('pmmintranet.net')+15+((laUrl.indexOf('web_pruebas')>-1)?13:((laUrl.indexOf('web_capacitacionPruebas')>-1)?25:18)));
			}else{
				var direccion = laUrl.substr(0,
							laUrl.indexOf('pmmintranet.com')+15+((laUrl.indexOf('web_pruebas')>-1)?13:((laUrl.indexOf('web_capacitacionPruebas')>-1)?25:18)));
			}
		
			window.open(direccion+"fpdf/reportes/ordenEmbarque.php?evaluacion="+u.folio.value
			+"&sucursal=<?=$_SESSION[IDSUCURSAL]?>&empleado=<?=$_SESSION[IDUSUARIO]?>");		
	}	
	function obtenerDestino(id,destino,sucursal){
		u.country.value=destino;
		u.countryid.value=id;		
		consultaTexto("mostrarDestino", "evaluacionMercancia_con.php?accion=1&destino="+id);
	}
	function mostrarSucursal(datos){
		var obj = eval(convertirValoresJson(datos));
		u.SucDestino.value = obj[0].descripcion;
		u.entrega.focus();
	}
	function ObtenerSucursalOrigen(){
		if('<?=$_SESSION[IDSUCURSAL]?>'!=""){
			sucursalorigen = '<?=$_SESSION[IDSUCURSAL]?>';
			u.sucursalorigen.value = sucursalorigen;
		}
	}
	function BuscarEvaluacion(){
		abrirVentanaFija('buscarEvaluacion.php?tipo=evaluacion&sucursal=<?=$_SESSION[IDSUCURSAL]?>', 550, 450, 'ventana', 'Busqueda')
	}
	function solicitarDatosConve(valor){
		if(valor.length!=13){
			if(valor.length<=10){
				valor = "999"+valor.toString().pad(9,"0",0)+"A";
				u.NGuias.value = valor;
				consultaTexto("resSolicitarDatosConve","evaluacionMercancia_con.php?accion=10&folio="+valor+"&random="+Math.random());
			}else{
				mens.show("A","El folio de guia empresarial esta compuesto por 13 caracteres","¡Atención!");
				u.NGuias.value = "";
			}
		}else{
			consultaTexto("resSolicitarDatosConve","evaluacionMercancia_con.php?accion=10&folio="+valor+"&random="+Math.random());
		}
	}
	
	function resSolicitarDatosConve(datos){
			u.limitekg.value = "";
			u.BolsaEmpaque.checked = false;
			u.CantidadEmpaque.value = '';
			u.Emplaye.checked = false;
			u.TotalEmpaque.value = '';
			u.TotalEmplaye.value = '';
			u.BolsaEmpaque.disabled = false;
			u.CantidadEmpaque.readOnly = false;
			u.CantidadEmpaque.style.backgroundColor='';
			u.Emplaye.disabled = false;
		var objeto = eval(datos);
		if(objeto.encontro=="0"){
			mens.show("A","El folio de guia empresarial no existe", "¡Atención!", "NGuias");
			u.NGuias.value = "";
		}else if(objeto.encontro=="1" && objeto.prepagadas=="SI"){
			u.prepagada.value = 1;
			u.limitekg.value = objeto.limitekg;
			u.BolsaEmpaque.disabled = true;
			u.CantidadEmpaque.readOnly = true;
			u.CantidadEmpaque.style.backgroundColor='#FFFF99';
			u.Emplaye.disabled = true;
		}else if(objeto.encontro=="-2"){
			mens.show("I",objeto.mensaje, "¡Atención!");
			u.NGuias.value = "";
		}else if(objeto.encontro=="-1"){
			mens.show("I","El folio que desea utilizar esta bloqueado", "¡Atención!");
			u.NGuias.value = "";
		}
	}	
	
	function validarFolioRecoleccion(rec){
		if(rec!="" && 1==2){
			consultaTexto("validoRecoleccion","evaluacionMercancia_con.php?accion=18&foliorecoleccion="+rec+"&val="+Math.random());
		}else{
			u.country.focus();
		}
	}
	
	function validoRecoleccion(datos){
		if(datos.indexOf("no existe")>-1){
			mens.show("A","El Folio de Recolección no existe","¡Atención!","NRecoleccion");			
			return false;
		}
	
		if(datos.indexOf("utilizado")>-1){
			mens.show("A","El Folio de Recolección ya fue utilizado","¡Atención!","NRecoleccion");
			return false;
		}
		
		if(datos.indexOf("no registrado")>-1){
			mens.show("A","El Folio de Recolección no ha sido registrado en una solicitud de recolección","¡Atención!","NRecoleccion");
			return false;
		}
		
		if(datos.indexOf("ok")>-1){
			document.getElementById('country').focus();
		}
	}
	
	var desc = new Array(<?php echo $desc; ?>);
	
	function obtenerSiPesoTotal(){
		for(var i=0; i<tabla1.getRecordCount();i++){
			if(document.all['tablaguias_EPT'][i].value==1)
				return 1;
		}
		return 0;
	}
</script>
<title>Evaluaci&oacute;n de Mercancias</title>
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<link href="FondoTabla.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
</head>
<body >
<form id="form1" name="form1" method="post" >
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td><label></label>
        <table width="640" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
        <tr>
          <td class="FondoTabla">EVALUACI&Oacute;N MERCANC&Iacute;A
            <input type="hidden" name="limitekg" /></td>
        </tr>
        <tr>
          <td><div id="txtResult"><table width="639" height="247" border="0" align="center" cellpadding="0" cellspacing="0">            
            <tr>
              <th width="612" height="64" scope="row"><table width="638" height="76" border="0" cellspacing="0" class="Tablas">
                <tr>
                  <td width="142" height="19" class="Tablas"><label>
                    Folio:
                      <input name="folio" type="text" class="Tablas" id="folio" value="<?=$fx->folio?>" size="10" style="text-align:right;" onKeyPress="if(event.keyCode==13){mostrarEvaluacionDatos(this.value);}"  >
                      <img src="../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="BuscarEvaluacion()">
                      <input name="folio_hidden" type="hidden" id="folio_hidden" />
                  </label></td>
                  <td width="35" class="Tablas">Fecha: </td>
                  <td width="178" class="Tablas"><input name="fechaevaluacion" type="text" class="Tablas" id="fechaevaluacion" style="background:#FFFF99" value="<?=$_POST[fechaevaluacion] ?>" size="15" readonly=""  >                    
                  <label></label></td>
                  <td colspan="2" class="Tablas">Estado:&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                    <input name="Estado" type="text" class="Tablas" id="Estado" style="background:#FFFF99" value="<?=$_POST[Estado] ?>" size="15" readonly="">
                    </label></td>
                  </tr>
                
                <tr>
                  <td height="19" colspan="3" class="Tablas" id="esventanilla"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="25%">No. Recoleccion: </td>
                      <td width="22%"><input name="NRecoleccion" type="text" class="Tablas" id="NRecoleccion" onblur="if(this.value!=''){validarFolioRecoleccion(this.value)}" onkeypress="if(event.keyCode==13){ validarFolioRecoleccion(this.value);}else{return solonumeros(event)}" onkeyup="return habilitar(event,this.name)" value="<?=$_POST[NRecoleccion] ?>" size="10" /></td>
                      <td width="18%">Guia Emp.:</td>
                      <td width="35%"><input name="NGuias" type="text" class="Tablas" id="NGuias" style="width:100px;" onblur="u.prepagada.value = 0; if(this.value!=''){solicitarDatosConve(this.value)}else{u.BolsaEmpaque.disabled = false; u.CantidadEmpaque.readOnly = false; u.CantidadEmpaque.style.backgroundColor=''; u.Emplaye.disabled = false;}" onkeydown="return tabular(event,this)" onkeyup="return habilitar(event,this.name)" value="<?=$_POST[NGuias] ?>" /></td>
                    </tr>
                  </table></td>
                  <td colspan="2" class="Tablas">Destino:
                    <input name="country" type="text" class="Tablas" id="country" style="font-size:9px; text-transform:uppercase"  value="<?=$_POST[country] ?>" size="35" maxlength="60" autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.countryid.value=this.codigo; setTimeout('devolverDestino()',500);}" onkeydown="if(event.keyCode==9){document.all.countryid.value=this.codigo; devolverDestino();}" onblur="if(this.value!=''){document.all.countryid.value=this.codigo; devolverDestino();}" >
                    <img src="../img/Buscar_24.gif" alt="Buscar Destino" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscarDestinoEvaluacion.php', 600, 500, 'ventana', 'Busqueda')"></td></tr>
                <tr>
                  <td height="19" colspan="2" class="Tablas">
				  	<input type="hidden" id="country_hidden" name="countryid" value="<?=$_POST[countryid] ?>">
                    <input name="iddestino" type="hidden" id="iddestino" >
                    <input type="hidden" name="prepagada">
                    T. Entrega:<label>
                    <select name="entrega" id="entrega" class="Tablas" onkeypress="if(event.keyCode==13 || event.keyCode==9){validarEntrega(this.value)}" onkeydown="if(event.keyCode==9){validarEntrega(this.value);}" onclick="validarEntrega(this.value);">
                      <option value="">SELECCIONAR</option>
                      <option value="1" <?=(($_POST[entrega]==1)? "selected": "")?>>EAD</option>
                      <option value="2" <?=(($_POST[entrega]==2)? "selected": "")?>>OCURRE</option>
                    </select>
                    </label></td>
                  <td class="Tablas">Tarifa minima EAD:
                    <input name="precioead" type="text" class="Tablas" id="precioead" onkeypress="return solonumeros(event)" onkeydown="return tabular(event,this)" onkeyup="return habilitar(event,this.name)" value="<?=$_POST[precioead] ?>" size="10" /></td>
                  <td width="73" class="Tablas">Suc Destino:</td>
                  <td width="200" class="Tablas"><div id="txtDestino">
                    <input name="SucDestino" type="text" class="Tablas" id="SucDestino" style="background:#FFFF99" value="<?=$_POST[SucDestino] ?>" size="28" readonly="">
                  </div></td>
                </tr>
              </table></th>
              </tr>
            <tr>
              <th scope="row"><table width="638" border="0" cellpadding="0">
                <tr>
                  <td><table id="tablaguias" width="638" border="0" cellpadding="0" cellspacing="0"></table></td>
                </tr>
                <tr>
                  <th scope="row"><label></label>
                    <table width="150" border="0" align="right">
                      <tr>
                        <td width="70"><img  id="img_eliminar" src="../img/Boton_Eliminar.gif" alt="Eliminar" width="70" height="20" style="cursor:pointer" onClick="borrarFila()" /></td>
                        <td width="95"><img id="btnAgregar" src="../img/Boton_Agregari.gif" width="70" height="20" style="cursor:pointer" onClick="if(tabla1.getRecordCount()==1 && document.all.prepagada.value==1){mens.show('A','Solo puede registrar 1 articulo para las guias prepagadas','¡Atención!');}else{abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?funcion=agregarDatos&espesototal='+obtenerSiPesoTotal(), 400, 350, 'ventana', 'Datos Evaluaci&oacute;n');}" /></td>
                      </tr>
                    </table>                  </th>
                </tr>
              </table></th>
            </tr>
            <tr>
              <th height="87" scope="row"><table width="638" height="73" border="0" cellpadding="0" cellspacing="0" class="Tablas">
                <tr>
                  <td height="12" colspan="4" class="FondoTabla" scope="row">Servicios </td>
                  </tr>
                <tr>
                  <td height="20" colspan="3" class="Tablas" scope="row"><label></label>
                    <input name="BolsaEmpaque" type="checkbox" class="Txt" id="BolsaEmpaque" value="1" onClick="ObtenerPrecioBolsa()" <? if($_POST[BolsaEmpaque]==1){echo 'checked';} ?>>
                    Envase
                    <input name="CantidadEmpaque" type="text"  class="Tablas" id="CantidadEmpaque" onKeyPress="ObtenerTotalBolsa(event,this.value)" onBlur="ObtenerTotalBolsaFoco()" value="<?=$_POST[CantidadEmpaque] ?>" size="5" maxlength="5" >
                    <input name="TotalEmpaque" type="text" class="Tablas" style="background:#FFFF99; text-align:right" id="TotalEmpaque" value="<?=$_POST[TotalEmpaque] ?>" size="10" readonly="readonly"  >
                    <input name="costobolsa" type="hidden" id="costobolsa" value="<?=$costobolsa ?>"></td>
                  <td width="333"><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
                    <tr>
					<td width="45%"  align="right" id="cel">&nbsp;</td>
                      <td width="28%" align="right"><img src="../img/Boton_Guardar.gif" alt="Guardar" name="i_guardar" width="70" height="20" id="i_guardar" style="cursor:pointer;" onClick="Validar();"  /></td>
					  <td width="27%" align="right"><img src="../img/Boton_Nuevo.gif" style="cursor:pointer" alt="Nuevo" width="70" height="20" onClick="mens.show('C','Perdera la informaci&oacute;n capturada, ¿Desea continuar?', '', '', 'limpiar();')"  /></td>
                    </tr>
                  </table>				   </td>
                </tr>
                <tr>
                  <td height="20" colspan="3" class="Tablas" scope="row">
<input name="Emplaye" type="checkbox" class="Txt" id="Emplaye" value="1" onClick="ObtenerPrecioEmplaye();" <? if($_POST[Emplaye]==1){echo 'checked';} ?> >                    
Emplaye &nbsp;&nbsp;&nbsp;&nbsp;
                    
                    <input name="TotalEmplaye" type="text" class="Tablas" id="TotalEmplaye" style="background:#FFFF99; text-align:right" value="<?=$_POST[TotalEmplaye] ?>" size="10" readonly="readonly">
                    <input name="costoemplaye" type="hidden" id="costoemplaye" value="<?=$_POST[costoemplaye] ?>">
                    <input name="costoemplayeextra" type="hidden" id="costoemplayeextra" value="<?=$_POST[costoemplayeextra] ?>"></td>
                  <td><label></label></td>
                </tr>
                <tr>
                  <td width="36" height="12" scope="row"><div id="txtBolsa"></div>
                  <div id="txtEmplaye"></div></td>
                  <td width="254" scope="row"><input name="fechahora" type="hidden" id="fechahora" value="<?=$fechahora; ?>">
                    <input name="user" type="hidden" id="user" value="<?=$usuario ?>">
                    <input name="msg1" type="hidden" id="msg1" value="<?=$_POST[msg] ?>">                   
					 <input name="sucursalorigen" type="hidden" id="sucursalorigen" value="<?=$sucursalorigen ?>">
                    <input name="registros" type="hidden" id="registros" value="<?=$_POST[registros] ?>">
                    <input name="totalpeso" type="hidden" id="totalpeso" value="<?=$totalpeso ?>">
                    <input name="totalvol" type="hidden" id="totalvol" value="<?=$totalvol ?>">
                    <input name="limite" type="hidden" id="limite" value="<?=$limite ?>">
                    <input name="costoextra" type="hidden" id="costoextra" value="<?=$costoextra ?>">
                    <input name="porcada" type="hidden" id="porcada" value="<?=$porcada ?>"></td>
                  <td width="43" scope="row"><input name="accion" type="hidden" id="accion" value="<?=$_POST[accion] ?>"></td>
                  <td><input name="indice" type="hidden" id="indice" value="">
                    <input name="hsucursal" type="hidden" id="hsucursal">
                    <input name="subdestino" type="hidden" id="subdestino" />
                    <input name="precioead_h" type="hidden" id="precioead_h" /></td>
                </tr>
              </table></th>
            </tr>
          </table></div></td>
        </tr>
      </table>        </td>
    </tr>
  </table> 
</form>
<div id="guiacancelada" style="position:absolute; left: 100px; top: 117px; display:none">
	<img src="../img/evaluacion cancelada.gif" />
</div>
	<?
		if (!empty($mensaje)){
			echo "<script language='javascript' type='text/javascript'>mens.show('I','".$mensaje."','');</script>";
		}else if(!empty($mensajeError)){
			echo "<script language='javascript' type='text/javascript'>mens.show('A','".$mensaje."','¡Atención!');</script>";
		}
	?>
</body>
</html>