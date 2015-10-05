<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	require_once('recoleccion_conj.php');
	$link = Conectarse('webpmm');
	if($_POST['accion']==""){
		$idsucursal = $_GET['idsucorigen'];
		$_POST[sucursalant] = $_GET['idsucorigen'];
		$folio_hidden = $_GET['folio'];
		$idsucursal2 = $_GET['sucursal'];
		$_POST[estado_hidden] = $_GET['estado'];
		$hora=date("H:i:s");
		if($folio_hidden==""){
			$_POST['folio'] = obtenerFolioRecoleccion($idsucursal,'');
		}
		$fecha_hidden = $_GET['fecha'];
		$confirFecha = date("d/m/Y");
	}else if($_POST['accion']=="grabar"){
		$idsucursal = $_POST['idsucursal'];
		$folio = obtenerFolioRecoleccion($idsucursal,'');
		$s = "INSERT INTO recoleccion
		 (folio,sucursal,fecharegistro,estado,origen,destino,npedidos,dirigido,chnombre,llama,
		 telefono,comentarios,cliente,calle, numero, crucecalles, cp, colonia, poblacion,
		 municipio, telefono2, sector,horario,horario2,hrcomida,hrcomida2, unidad, usuario,fecha)
		 VALUES
		 ('".$folio."','".$_POST['idsucursal']."',
		 '".cambiaf_a_mysql(str_replace("-","/",$_POST[fecha]))."','".$_POST['estado_hidden']."',
		 '".trim($_POST['origen_hidden'])."', '".trim($_POST['destino_hidden'])."', '".$_POST['npedidos']."',
		 UCASE('".$_POST['dirigido']."'), 
		 ".(($_POST['chNombre']=="")? 0 : 1).",UCASE('".$_POST['llama']."'), '".$_POST['telefono']."',
		 UCASE('".$_POST['comentarios']."'),'".$_POST['cliente']."',UCASE('".$_POST['calle']."'), 
		 UCASE('".$_POST['numero']."'),UCASE('".$_POST['crucecalles']."'),'".$_POST['cp']."',
		 UCASE('".$_POST['colonia']."'), UCASE('".$_POST['poblacion']."'),UCASE('".$_POST['municipio']."'),
		 '".$_POST['telefono2']."',UCASE('".$_POST['sector']."'), '".$_POST['horario_hidden']."',
		 '".$_POST['horario2_hidden']."','".$_POST['comida_hidden']."','".$_POST['comida2_hidden']."',
		 UCASE('".$_POST['unidad']."'), UCASE('".$_SESSION[NOMBREUSUARIO]."'), current_timestamp())";
		 //die($s);
		 $f = mysql_query($s,$link) or die(mysql_error($link).$s);
		 $_POST['folio'] = $folio;
		 $_POST['accion'] = "modificar";		 
		 if (isset($_POST['registroMercancia'])>0){
			for($j=0;$j<$_POST['registroMercancia'];$j++){
				$sqldetalleb=mysql_query("INSERT INTO recolecciondetalle
				(recoleccion,sucursal,cantidad,iddescripcion,descripcion,contenido,peso,
				largo,ancho,alto,volumen,pesototal,pesounit, usuario,fecha)VALUES
				('".$folio."','".$_POST[idsucursal]."',
				UCASE('".$_POST["detalle_CANT"][$j]."'),
				UCASE('".trim($_POST["detalle_ID"][$j])."'),
				UCASE('".trim($_POST["detalle_DESCRIPCION"][$j])."'),
				UCASE('".trim($_POST["detalle_CONTENIDO"][$j])."'),
				UCASE('".$_POST["detalle_PESO"][$j]."'),
				UCASE('".$_POST["detalle_LARGO"][$j]."'),
				UCASE('".$_POST["detalle_ANCHO"][$j]."'),
				UCASE('".$_POST["detalle_ALTO"][$j]."'),
				UCASE('".$_POST["detalle_VOLUMEN"][$j]."'),
				UCASE('".$_POST["detalle_PESO_TOTAL"][$j]."'),
				UCASE('".$_POST["detalle_PESO_UNIT"][$j]."'),
				UCASE('".$_SESSION[NOMBREUSUARIO]."'), current_timestamp())", $link);
				$cadenaMercancia .= "{cantidad:'".$_POST["detalle_CANT"][$j].
								"',id:'".trim($_POST["detalle_ID"][$j]).
								"',descripcion:'".trim($_POST["detalle_DESCRIPCION"][$j]).
								"',contenido:'".trim($_POST["detalle_CONTENIDO"][$j]).
								"',peso:'".$_POST["detalle_PESO"][$j].
								"',largo:'".$_POST["detalle_LARGO"][$j].
								"',ancho:'".$_POST["detalle_ANCHO"][$j].
								"',alto:'".$_POST["detalle_ALTO"][$j].
								"',pesototal:'".$_POST["detalle_PESO_TOTAL"][$j].
								"',pesounit:'".$_POST["detalle_PESO_UNIT"][$j].
								"',volumen:'".$_POST["detalle_VOLUMEN"][$j]."'},";
			}
			$cadenaMercancia = substr($cadenaMercancia,0,strlen($cadenaMercancia)-1);
		}
		$mensaje ='Los datos han sido guardados correctamente';
		$guardo = "SI";
	}else if($_POST['accion']=="modificar"){
		$idsucursal = $_POST['idsucursal'];		
		$s = "UPDATE recoleccion SET 
		 ".(($idsucursal!=$_POST[sucursalant])?"folio='".obtenerFolioRecoleccion($idsucursal,'')."'," : "")."
		 sucursal='".$_POST['idsucursal']."',		 
		 estado='".$_POST['estado_hidden']."',
		 origen='".trim($_POST['origen_hidden'])."',destino='".trim($_POST['destino_hidden'])."',
		 npedidos='".$_POST['npedidos']."',dirigido=UCASE('".$_POST['dirigido']."'),
		 chnombre=".(($_POST['chNombre']=="")? 0 : 1).",llama=UCASE('".$_POST['llama']."'),
		 telefono='".$_POST['telefono']."',comentarios=UCASE('".$_POST['comentarios']."'),
		 cliente='".$_POST['cliente']."', calle=UCASE('".$_POST['calle']."'),
		 numero=UCASE('".$_POST['numero']."'), crucecalles=UCASE('".$_POST['crucecalles']."'),
		 cp='".$_POST['cp']."', colonia=UCASE('".$_POST['colonia']."'),
		 poblacion=UCASE('".$_POST['poblacion']."'), municipio=UCASE('".$_POST['municipio']."'),
		 telefono2='".$_POST['telefono2']."', horario='".$_POST['horario_hidden']."',
		 sector=UCASE('".$_POST['sector']."'), horario2='".$_POST['horario2_hidden']."',
		 hrcomida='".$_POST['comida_hidden']."', hrcomida2='".$_POST['comida2_hidden']."',
		 unidad=UCASE('".$_POST['unidad']."'), usuario=UCASE('".$_SESSION[NOMBREUSUARIO]."'),
		 fecha=current_timestamp() WHERE folio='".$_POST['folioant']."' AND sucursal=".$_POST[sucursalant]."";
		 $f = mysql_query($s,$link) or die(mysql_error($link).$s);
		
		$s = mysql_query("DELETE FROM recolecciondetalle 
		WHERE recoleccion='".$_POST['folio']."' AND sucursal=".$_POST[idsucursal]."",$link);
		
		 if (isset($_POST['registroMercancia'])>0){		 	
			for($j=0;$j<$_POST['registroMercancia'];$j++){				
				$sqldetalleb=mysql_query("INSERT INTO recolecciondetalle
				(recoleccion, sucursal, cantidad,iddescripcion,descripcion,contenido,peso,
				largo,ancho,alto,volumen,pesototal,pesounit, usuario,fecha)VALUES
				('".$_POST['folio']."', '".$_POST[idsucursal]."',
				UCASE('".$_POST["detalle_CANT"][$j]."'),
				UCASE('".trim($_POST["detalle_ID"][$j])."'),
				UCASE('".trim($_POST["detalle_DESCRIPCION"][$j])."'),
				UCASE('".trim($_POST["detalle_CONTENIDO"][$j])."'),
				UCASE('".$_POST["detalle_PESO"][$j]."'),
				UCASE('".$_POST["detalle_LARGO"][$j]."'),
				UCASE('".$_POST["detalle_ANCHO"][$j]."'),
				UCASE('".$_POST["detalle_ALTO"][$j]."'),
				UCASE('".$_POST["detalle_VOLUMEN"][$j]."'),
				UCASE('".$_POST["detalle_PESO_TOTAL"][$j]."'),
				UCASE('".$_POST["detalle_PESO_UNIT"][$j]."'),
				UCASE('".$_SESSION[NOMBREUSUARIO]."'), current_timestamp())", $link);
				$cadenaMercancia .= "{cantidad:'".$_POST["detalle_CANT"][$j].
								"',id:'".trim($_POST["detalle_ID"][$j]).
								"',descripcion:'".trim($_POST["detalle_DESCRIPCION"][$j]).
								"',contenido:'".trim($_POST["detalle_CONTENIDO"][$j]).
								"',peso:'".$_POST["detalle_PESO"][$j].
								"',largo:'".$_POST["detalle_LARGO"][$j].
								"',ancho:'".$_POST["detalle_ANCHO"][$j].
								"',alto:'".$_POST["detalle_ALTO"][$j].
								"',pesototal:'".$_POST["detalle_PESO_TOTAL"][$j].
								"',pesounit:'".$_POST["detalle_PESO_UNIT"][$j].																																
								"',volumen:'".$_POST["detalle_VOLUMEN"][$j]."'},";
			}
			$cadenaMercancia = substr($cadenaMercancia,0,strlen($cadenaMercancia)-1);
		}
		$mensaje ='Los cambios han sido guardados correctamente';
		$guardo = "SI";
	}else if($_POST['accion']=="transmitir"){		
		$idsucursal = $_POST['idsucursal'];	
		$s = "UPDATE recoleccion SET
		".(($idsucursal!=$_POST[sucursalant])?"folio='".obtenerFolioRecoleccion($idsucursal,'')."'," : "")."
		estado='TRANSMITIDO', transmitida='SI', unidad=UCASE('".$_POST['unidad']."')
		WHERE folio='".$_POST['folio']."' AND sucursal=".$_POST[idsucursal]."";
		$_POST['estado_hidden'] = "TRANSMITIDO";
		$f = mysql_query($s,$link) or die(mysql_error($link).$s);
		$idsucursal = $_POST['idsucursal'];				
		$cadenaMercancia = obtenerCadena($_POST['folio'],$_POST['idsucursal']);	
		$mensaje ='La Recoleccion cambio a estado TRANSMITIDO correctamente';
		$confirFecha = date("d/m/Y");		
	}else if($_POST['accion']=="realizado"){
		$idsucursal = $_POST['idsucursal'];	
		$s = "UPDATE recoleccion SET fecharecoleccion=CURRENT_DATE(),
		estado='REALIZADO', multiple=".(($_POST['multiple']=="")? '0' : '1').", 
		realizo='SI' WHERE folio='".$_POST['folio']."' AND sucursal=".$_POST[idsucursal]."";		
		$f = mysql_query($s,$link) or die(mysql_error($link).$s);
		
		$_POST['estado_hidden'] = "REALIZADO";
		
		$coma = ",";
		$recolecciones_hidden = substr($_POST['recolecciones_hidden'],0,strlen($_POST['recolecciones_hidden'])-1); 
		$lista=split($coma,$recolecciones_hidden);		
		if (count($lista)>0){
			for ($i=0;$i<count($lista);$i++){
				$sqlins=mysql_query("INSERT INTO recolecciondetallefoliorecoleccion
				(recoleccion,sucursal,foliosrecolecciones,usuario,fecha)
				VALUES('".$_POST['folio']."',".$_POST[idsucursal].",UCASE('".$lista[$i]."'),'".$_SESSION[NOMBREUSUARIO]."',current_timestamp())",$link);				
			}
		}
		//CAMBIA ESTADO A SOLICITUD TELEFONICA
		$s = "SELECT * FROM solicitudtelefonica 
		WHERE folioatencion='".$_POST['folio']."' AND sucursal=".$_POST[idsucursal]."";
		$r = mysql_query($s,$link) or die($s);
		if(mysql_num_rows($r)>0){
			$s = "UPDATE solicitudtelefonica SET estado='SOLUCIONADO'
			WHERE folioatencion='".$_POST['folio']."' AND sucursal=".$_POST[idsucursal]."";
			mysql_query($s,$link) or die($s);
		}
		
		$mensaje ='La Recoleccion cambio a estado REALIZADO correctamente';
		$empresariales_hidden = substr($_POST['empresariales_hidden'],0,strlen($_POST['empresariales_hidden'])-1); 
		$list=split($coma,$empresariales_hidden);
		if (count($list)>0){
			for ($i=0;$i<count($list);$i++){
				$sqlinsr=mysql_query("INSERT INTO recolecciondetallefolioempresariales (recoleccion,sucursal,foliosempresariales,usuario,fecha) VALUES('".$_POST['folio']."',".$_POST[idsucursal].",UCASE('".$list[$i]."'),'".$_SESSION[NOMBREUSUARIO]."',current_timestamp())",$link);				
			}
		}
		
		$cadenaMercancia = obtenerCadena($_POST['folio'],$_POST['idsucursal']);
		
	}else if($_POST['accion']=="reprogramar"){
		$idsucursal = $_POST['idsucursal'];	
		$s = "UPDATE recoleccion SET fecharegistro='".cambiaf_a_mysql(str_replace("-","/",$_POST[fecha]))."',
		estado='NO TRANSMITIDO', transmitida='NO'
		WHERE folio='".$_POST['folio']."' AND sucursal=".$_POST[idsucursal]."";
		$f = mysql_query($s,$link) or die(mysql_error($link).$s);
		
		$s = mysql_query("INSERT INTO recoleccionmotivoreprogramacion
		(recoleccion,sucursal,fecharegistro,motivo,notificar,observaciones, usuario, fecha) VALUES 
		('".$_POST['folio']."', ".$idsucursal.",'".cambiaf_a_mysql(str_replace("-","/",$_POST[fecha]))."',
		'".$_POST['motivoreprogramar']."',UCASE('".$_POST['notificarreprogramar']."'),
		UCASE('".$_POST['observacionesreprogramar']."'),
		'".$_SESSION[NOMBREUSUARIO]."',current_timestamp())",$link);
		
		$_POST['estado_hidden'] = "NO TRANSMITIDO";		
		
		$cadenaMercancia = obtenerCadena($_POST['folio'],$_POST['idsucursal']);
		$mensaje ='La Recoleccion cambio a estado NO TRANSMITIDO correctamente';
		
	}else if($_POST['accion']=="cancelar"){
		$idsucursal = $_POST['idsucursal'];	
		$s = "UPDATE recoleccion SET estado='CANCELADO'
		WHERE folio='".$_POST['folio']."' AND sucursal=".$_POST[idsucursal]."";
		$f = mysql_query($s,$link) or die(mysql_error($link).$s);
		
		$s = mysql_query("INSERT INTO recoleccionmotivocancelacion
		(recoleccion,sucursal,fecharegistro,motivo,notificar,usuario,fecha) VALUES 
		('".$_POST['folio']."', ".$idsucursal.",'".cambiaf_a_mysql(str_replace("-","/",$_POST[fecha]))."',
		'".$_POST['motivo']."',UCASE('".$_POST['notificaciones']."'),
		'".$_SESSION[NOMBREUSUARIO]."',current_timestamp())",$link);		
		
		$_POST['estado_hidden'] = "CANCELADO";
		
		$cadenaMercancia = obtenerCadena($_POST['folio'],$_POST['idsucursal']);
		$mensaje ='La Recoleccion cambio a estado CANCELADO correctamente';
	}
	
	function obtenerCadena($folio, $sucursal){
		$link=Conectarse('webpmm');
		$sqlp=mysql_query("SELECT cantidad,iddescripcion,descripcion,contenido,peso,
		largo,ancho,alto,volumen,pesototal,pesounit FROM recolecciondetalle
		WHERE recoleccion='".$folio."' AND sucursal=".$sucursal."",$link);
		$nump  = mysql_num_rows($sqlp);
		if($nump>0){
			while($rp=mysql_fetch_object($sqlp)){
				$cadenaMercancia .= "{cantidad:'".$rp->cantidad.
								"',id:'".$rp->iddescripcion.
								"',descripcion:'".$rp->descripcion.
								"',contenido:'".$rp->contenido.
								"',peso:'".$rp->peso.
								"',largo:'".$rp->largo.
								"',ancho:'".$rp->ancho.
								"',alto:'".$rp->alto.
								"',pesototal:'".$rp->pesototal.
								"',pesounit:'".$rp->pesounit.
								"',volumen:'".$rp->volumen."'},";
			}
			$cadenaMercancia = substr($cadenaMercancia,0,strlen($cadenaMercancia)-1);
		}
		return $cadenaMercancia;
	}
	
	$s = "SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion,':',cd.id),CONCAT(cd.descripcion,' - ',cs.prefijo,':',cd.id)) AS descripcion,
	cd.sucursal	FROM catalogodestino cd 
	INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id
	ORDER BY descripcion";	
	$r = mysql_query($s,$link) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".cambio_texto($f[0])."'".','.$desc; 	
		}
		$desc = "'VARIOS:0',".$desc;		
		$desc=substr($desc, 0, -1);		
	}
	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></LINK>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script>
	var u = document.all;
	var tabla1 	= new ClaseTabla();
	var v_multiple = "";
	var v_sucursal = "";
	var v_destino	= "";
	var mens = new ClaseMensajes();
	var combo1 = "<select name='origen' id='origen' onChange='obtenerDiaRecoleccion(this.value)' class='Tablas' style='width:130px;' onKeyPress='return tabular(event,this)'>";
	var txtOrigen = '<input name="origen" type="text" class="Tablas" id="origen" style="width:100px" value="<?=$_POST[origen] ?>" onKeyUp="ajax_showOptions(this,\'getCountriesByLetters\',event,\'ajax-list-sucursal.php\')" onKeyPress="if(event.keyCode==13){obtenerDiaRecoleccion(document.all.origen_hidden.value);}"/>';
	mens.iniciar('../javascript',false);
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"CANT", medida:45, alineacion:"right", datos:"cantidad"},
			{nombre:"ID", medida:2, alineacion:"right", tipo:"oculto", datos:"id"},
			{nombre:"DESCRIPCION", medida:150, alineacion:"left", datos:"descripcion"},
			{nombre:"CONTENIDO", medida:150, alineacion:"left", datos:"contenido"},
			{nombre:"PESO_TOTAL", medida:45, alineacion:"right", datos:"pesototal"},
			{nombre:"LARGO", medida:40, alineacion:"right",  datos:"largo"},
			{nombre:"ANCHO", medida:40, alineacion:"right",  datos:"ancho"},
			{nombre:"ALTO", medida:40, alineacion:"right",  datos:"alto"},
			{nombre:"PESO_UNIT", medida:4, alineacion:"right", tipo:"oculto",  datos:"pesounit"},
			{nombre:"PESO", medida:4, alineacion:"right", tipo:"oculto",  datos:"peso"},
			{nombre:"VOLUMEN", medida:40, alineacion:"right", datos:"volumen"}
		],
		filasInicial:10,
		alto:150,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();		
		u.Eliminar.style.visibility = "hidden";		
		if(u.folio_hidden.value==""){
			obtenerDatos();
		}else{
			obtenerRecoleccionMercancia();
		}
		u.abajo.style.display="none";
		obtenerDetalles();
		validarEstados();
		if(u.colEstado.innerHTML == "REALIZADO"){
			obtenerFolios();
		}				
	}	
	function obtenerDetalles(){
		var datosMercancia	 = <? if($cadenaMercancia!=""){echo "[".$cadenaMercancia."]";}else{echo "0";} ?>;
		if(datosMercancia!=0){
			for(var i=0; i<datosMercancia.length;i++){
				tabla1.add(datosMercancia[i]);
			}
		}		
	}
	function obtenerDatos(){
		consultaTexto("mostrarDatos","recoleccion_conj.php?accion=2&idsucursal="+u.idsucursal.value+"&valor="+Math.random());
	}
	function mostrarDatos(datos){
		var objeto = eval(convertirValoresJson(datos));
		if(objeto!=0){
			u.sucursal.value	= objeto[0].descripcion;		
			u.fecha.value   	= objeto[0].fecha;
			u.destino_h.value 	= objeto[0].id;
		}else{
			alerta3('Error al cargar datos principales','¡Atención!');
		}
		u.destino.focus();
	consultaTexto("mostrarOrigen1","recoleccion_conj.php?accion=11&sucursal="+u.idsucursal.value+"&descripcion="+u.sucursal.value+"&valor="+Math.random());		
	}
	function mostrarOrigen1(datos){
		var objeto = eval(convertirValoresJson(datos));		
		if(objeto.length==1){
			u.celOrigen.innerHTML = txtOrigen;
			u.origen_hidden.value	= objeto[0].id;
			u.origen.value			= objeto[0].descripcion;
		}else{
			u.celOrigen.innerHTML = combo1;
			var combo = u.origen;
			combo.options.length = null;
			uOpcion = document.createElement("OPTION");
			uOpcion.value="";
			uOpcion.text="SELECCIONAR";			
			combo.add(uOpcion);			
			for(i=0;i<objeto.length;i++){	
				uOpcion = document.createElement("OPTION");
				uOpcion.value	=	objeto[i].id;
				uOpcion.text	=	objeto[i].descripcion;
				combo.add(uOpcion);
			}
			uOpcion = document.createElement("OPTION");
			uOpcion.value=0;
			uOpcion.text="VARIOS";			
			combo.add(uOpcion);			
			combo.value = u.destino_h.value;
			u.origen_hidden.value = u.destino_h.value;
			if('<?=$_POST[origen_hidden]?>'!=""){
				combo.value = '<?=$_POST[origen_hidden]?>';
				u.origen_hidden.value = '<?=$_POST[origen_hidden]?>';
			}
		}
		consulta("mostrarGenerales","recoleccion_con.php?accion=2&sucursal="+u.idsucursal.value+"&valor="+Math.random());
	}
	function mostrarGenerales(datos){
			var v_horario = datos.getElementsByTagName('horario').item(0).firstChild.data;
		var v_fecha = datos.getElementsByTagName('fecha').item(0).firstChild.data;
		if(v_horario < u.hora.value){
			u.fecha.value	= v_fecha;
		}
		if(u.mensaje.value==""){
			consultaTexto("obtenerFolio","recoleccion_conj.php?accion=13&idsucursal="+u.idsucursal.value+"&valor="+Math.random());
		}
		if(v_sucursal == "1"){
			consultaTexto("obtenerFolio","recoleccion_conj.php?accion=13&idsucursal="+u.idsucursal.value+"&valor="+Math.random());
		}
		
		var fi = u.fecha_hidden.value.split("/");
		var ff = u.fecha.value.split("-");
		var initDate = new Date(fi[2],fi[1],fi[0]);
		var endDate = new Date(ff[2],ff[1],ff[0]);
		
		if(initDate > endDate){
			u.fecha.value = u.fecha_hidden.value;
			obtenerFolioxFecha(u.fecha.value);
		}
	}
	function agregarDatos(objeto){
		if(u.index.value!=""){
			tabla1.deleteById(tabla1.getSelectedIdRow());
			tabla1.add(objeto);
			u.index.value	= "";			
		}else{
			tabla1.add(objeto);
			u.Eliminar.style.visibility ="visible";
		}		
	}
	function ModificarFila(){
		if(u.colEstado.innerHTML=="NO TRANSMITIDO" || u.colEstado.innerHTML==""){
			var obj = tabla1.getSelectedRow();		
			if(tabla1.getValSelFromField("cantidad","CANT")!=""){
			u.index.value	= tabla1.getSelectedIndex();
			abrirVentanaFija('datosMercanciaRecoleccion.php?funcion=agregarDatos&cantidad='+obj.cantidad
			+'&id='+obj.id
			+'&descripcion='+obj.descripcion
			+'&contenido='+obj.contenido
			+'&peso='+obj.peso
			+'&largo='+obj.largo
			+'&ancho='+obj.ancho
			+'&alto='+obj.alto
			+'&pesototal='+obj.pesototal
			+'&pesounit='+obj.pesounit
			+'&volumen='+obj.volumen, 460, 410, 'ventana', 'Datos Mercancia','ponerFoco();');	
			}
		}
	}
	function eliminarFila(){
		if(tabla1.getValSelFromField('cantidad','CANT')!=""){
	confirmar('¿Esta seguro de Eliminar la Fila?','','borrarFila()','');
		}
	}
	function borrarFila(){
		tabla1.deleteById(tabla1.getSelectedIdRow());
	  if(tabla1.getRecordCount()==0){		
		  u.Eliminar.style.visibility = "hidden";		
	  }
	}
	function obtenerClienteBusqueda(id){
		u.cliente.value = id;
		consulta("mostrarCliente","recoleccion_con.php?accion=1&cliente="+id+"&valor="+Math.random());
	}
	function obtenerCliente(e,id){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 && id!=""){
		consulta("mostrarCliente","recoleccion_con.php?accion=1&cliente="+id+"&valor="+Math.random());
		}
	}
	function mostrarCliente(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		limpiarCliente();
		if(con>0){
			u.nombre.value	= datos.getElementsByTagName('nombre').item(0).firstChild.data;
			var endir = datos.getElementsByTagName('dir').item(0).firstChild.data;
		if(endir==1){
		u.celda_des_calle.innerHTML ='<input name="calle" type="text" class="Tablas" id="calle" style="width:280px;background:#FFFF99" value="<?=$_POST[calle] ?>" readonly=""/>';
		u.numero.value =datos.getElementsByTagName('numero').item(0).firstChild.data;
		u.cp.value =datos.getElementsByTagName('cp').item(0).firstChild.data;
		u.colonia.value =datos.getElementsByTagName('colonia').item(0).firstChild.data;
		u.poblacion.value =datos.getElementsByTagName('poblacion').item(0).firstChild.data;
		u.municipio.value = datos.getElementsByTagName('municipio').item(0).firstChild.data;
		u.crucecalles.value =datos.getElementsByTagName('crucecalles').item(0).firstChild.data;
		u.telefono2.value =datos.getElementsByTagName('telefono').item(0).firstChild.data;
		u.calle.value =datos.getElementsByTagName('calle').item(0).firstChild.data;
			}else if(endir>1){
			var comb = "<select name='calle' class='Tablas' style='width:280px;font:tahoma; font-size:9px' onchange='"
			+"document.all.numero.value=this.options[this.selectedIndex].numero;"
			+"document.all.cp.value=this.options[this.selectedIndex].cp;"
			+"document.all.colonia.value=this.options[this.selectedIndex].colonia;"
			+"document.all.poblacion.value=this.options[this.selectedIndex].poblacion;"
			+"document.all.municipio.value=this.options[this.selectedIndex].municipio;"
			+"document.all.crucecalles.value=this.options[this.selectedIndex].crucecalles;"
			+"document.all.telefono2.value=this.options[this.selectedIndex].telefono;"
			+" cambiarSector(document.all.cp.value,document.all.colonia.value);'>";
				
				for(var i=0; i<endir; i++){
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
						u.municipio.value 	= v_municipio;
						u.crucecalles.value	= v_cruce;
						u.telefono2.value 	= v_telefono;					
					}else if(v_fact=="SI"){
						u.numero.value 		= v_numero;
						u.cp.value 			= v_cp;
						u.colonia.value 	= v_colonia;
						u.poblacion.value 	= v_poblacion;						
						u.municipio.value 	= v_municipio;
						u.crucecalles.value	= v_cruce;
						u.telefono2.value 	= v_telefono;
					}
					
					comb += "<option "+ ((v_fact=="SI")? "selected " : "" ) +" value='"+v_calle+"' numero='"+v_numero+"'" 
					+"cp='"+v_cp+"' colonia='"+v_colonia+"'"
					+" poblacion='"+v_poblacion+"' telefono='"+v_telefono+"'"
					+" municipio='"+v_municipio+"' crucecalles='"+v_cruce+"'"
					+" telefono='"+v_telefono+"'>"
					+v_calle+"</option>";					
				}
				comb += "</select>";
				u.celda_des_calle.innerHTML = comb;
			}
			cambiarSector(u.cp.value,u.colonia.value);
			consultaTexto("mostrarHorario","recoleccion_conj.php?accion=12&cliente="+u.cliente.value+"&valor="+Math.random());
			u.h1.focus();
		}else{			
			alerta('El numero de cliente no existe','¡Atención!','cliente');			
		}
	}
	function mostrarHorario(datos){
		if(datos.indexOf("no encontro")<0){
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
		u.municipio.value 	= ""; u.crucecalles.value	= ""; 
		u.telefono2.value 	= ""; u.sector.value		= "";
		u.h1.value			= "00"; u.h2.value			= "00";
		u.h3.value			= "00"; u.h4.value			= "00";
		u.c1.value			= "00"; u.c2.value			= "00";
		u.c3.value			= "00"; u.c4.value			= "00";				
		document.all.celda_des_calle.innerHTML ='<input name="calle" type="text" class="Tablas" id="calle" style="width:165px;background:#FFFF99" value="<?=$_POST[calle] ?>" readonly=""/>';
	}
	function cambiarSector(cp,colonia){
		consultaTexto("mostrarSector","recoleccion_conj.php?accion=4&cp="+cp+"&col="+colonia);
	}
	function mostrarSector(datos){
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
		if(objeto !=0){
			u.idsector.value = objeto[0].id;
			u.sector.value   = objeto[0].descripcion;
		}else{
			u.idsector.value = "";
			u.sector.value   = "";
		}
	}
	function obtenerUnidadBusqueda(id){
		u.unidad.value = id;		
	}
	function obtenerUnidad(e,id){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 && id!=""){
	consultaTexto("mostrarUnidad","recoleccion_conj.php?accion=1&unidad="+id+"&sucursal="+u.idsucursal.value+"&valor="+Math.random());
		}
	}
	function mostrarUnidad(datos){
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
		if(objeto == 0){
			u.unidad.value = "";
			alerta('El numero de Unidad no existe','¡Atención!','unidad');
		}
	}
	function insertarRecoleccion(caja, valor, va){
		if(caja.value!=""){
			for(var i=0; i<va.options.length; i++){
				if(va.options[i].text==valor){
			alerta3("El folio "+ caja.value+"  ya fue agregado","¡Atencion!");
					caja.value="";
					return false;
				}
			}
			var opcion = new Option(caja.value);
			va.options[va.options.length] = opcion;
			u.recolecciones_hidden.value += caja.value+",";
			caja.value="";
		}
	}
	function borrarRecoleccion(va){
		if(va.options.selectedIndex>-1){
		var frase = u.recolecciones_hidden.value.replace(u.recolecciones.options[u.recolecciones.selectedIndex].text,"");
		u.recolecciones_hidden.value = frase.replace(",,",",");
			if(u.recolecciones_hidden.value.substring(0,1)==","){
				u.recolecciones_hidden.value = u.recolecciones_hidden.value.substring(1,u.recolecciones_hidden.value.legth);
			}		
			va.options[va.options.selectedIndex] = null;
			va.value = "";
		}
	}
	function insertarEmpresarial(caja, valor, va){
		if(caja.value!=""){
			for(var i=0; i<va.options.length; i++){
				if(va.options[i].text==valor){
			alerta3("El folio "+ caja.value+"  ya fue agregado","¡Atencion!");
					caja.value="";
					return false;
				}
			}
			var opcion = new Option(caja.value);
			va.options[va.options.length] = opcion;
			u.empresariales_hidden.value += caja.value+",";
			caja.value="";
		}
	}
	function borrarEmpresarial(va){
		if(va.options.selectedIndex>-1){
		var frase = u.empresariales_hidden.value.replace(u.empresariales.options[u.empresariales.selectedIndex].text,"");
		u.empresariales_hidden.value = frase.replace(",,",",");
			if(u.empresariales_hidden.value.substring(0,1)==","){
				u.empresariales_hidden.value = u.empresariales_hidden.value.substring(1,u.empresariales_hidden.value.legth);
			}
			va.options[va.options.selectedIndex] = null;
			va.value = "";
		}
	}
	function habilitarTerceros(){
		if(u.chNombre.checked == true){
			u.llama.disabled = false;
			u.llama.style.backgroundColor='';
			u.telefono.disabled = false;
			u.telefono.style.backgroundColor='';
			u.comentarios.disabled = false;
			u.comentarios.style.backgroundColor='';
			u.llama.focus();
		}else{
			u.llama.value = "";
			u.llama.disabled = true;
			u.llama.style.backgroundColor='#FFFF99';
			u.telefono.value = "";
			u.telefono.disabled = true;
			u.telefono.style.backgroundColor='#FFFF99';
			u.comentarios.value = "";
			u.comentarios.disabled = true;
			u.comentarios.style.backgroundColor='#FFFF99';			
		}
	}
	function limpiar(){	
		u.folio.value		= ""; 		u.mensaje.value		= "";
		u.numero.value 		= ""; 		u.cp.value 			= "";
		u.colonia.value 	= ""; 		u.poblacion.value 	= "";
		u.telefono.value 	= ""; 		u.municipio.value 	= "";
		u.crucecalles.value	= ""; 		u.telefono2.value 	= "";
		u.sector.value		= ""; 		u.h1.value			= "00";
		u.h2.value			= "00"; 	u.origen.value		= "";
		u.h3.value			= "00"; 	u.h4.value			= "00";
		u.destino.value		= ""; 		u.chNombre.checked	= false;
		u.npedidos.value	= ""; 		u.dirigido.value		= "";
		u.unidad.value		= ""; 		
		u.destino_hidden.value	= ""; 	u.index.value		= "";
		u.c1.value			= "00"; 	u.c2.value			= "00";
		u.c3.value			= "00"; 	u.c4.value			= "00";
		u.horario_hidden.value	= ""; 	u.horario2_hidden.value	= "";
		u.comida_hidden.value	= ""; 	u.comida2_hidden.value	= "";	
		u.estado_hidden.value	= ""; 	u.index.value		= "";
		u.llama.value			= ""; 	u.telefono.value	= "";
		u.comentarios.value	= ""; 		u.cliente.value	= "";
		u.nombre.value		= ""; 		u.idsector.value	= "";
		u.hora.value	= "";			u.accion.value	= "";
		u.registroMercancia.value = ""; u.folio_hidden.value	= "";
		u.motivo.value	= "";			u.desmotivo.value	= "";
		u.notificaciones.value	= "";   u.motivoreprogramar.value	= "";
		u.desmotivoreprogramar.value	= ""; u.notificacionesreprogramar.value	= "";
		u.observacionesreprogramar.value	= ""; u.fecha_hidden.value	= "";
		u.confirFecha.value	= ""; u.recolecciones_hidden.value	= "";
		u.empresariales_hidden.value	= ""; u.mensaje.value	= "";	
		u.idsucursal2.value	= "";	u.abajo.style.display="none";
		u.destino.disabled = false;
		u.origen.disabled = false;
		validarEstados();
			
			
		if(u.colEstado.innerHTML != "NO TRANSMITIDO"){
			u.recolecciones.options.length = 0;
			u.empresarial.options.length = 0;
		}
		u.colEstado.innerHTML = "";		
	    tabla1.clear();
		habilitarTerceros();
		obtenerDatos();
		document.all.celda_des_calle.innerHTML ='<input name="calle" type="text" class="Tablas" id="calle" style="width:280px;background:#FFFF99" value="<?=$calle ?>" readonly=""/>'; 
		consultaTexto("obtenerFolio","recoleccion_conj.php?accion=13&idsucursal="+u.idsucursal.value+"&valor="+Math.random());
		document.getElementById('origen_hidden').value	= "";
	}
	function obtenerFolio(datos){
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
		u.folio.value = datos;
	}
	
	function obtener(id){
		u.folio.value = id;
		consultaTexto("mostrarTodo","recoleccion_conj.php?accion=6&folio="+id+"&idsucursal="+u.idsucursal.value+"&valor="+Math.random());
	}
	
	function mostrarTodo(datos){
		var objeto = eval(convertirValoresJson(datos));
		u.folio.value		= objeto[0].folio;
		u.estado_hidden.value=objeto[0].estado;
		u.colEstado.innerHTML	= objeto[0].estado;
		u.idsucursal.value  = objeto[0].sucursal;
		u.sucursal.value	= objeto[0].dessuc;
		u.sucursalant.value = objeto[0].sucursal;
		u.folioant.value	= objeto[0].folio;
	
	if(objeto[0].estado=="REALIZADO"){
		u.fecha.value	= objeto[0].fecharecoleccion;		
	}else{
		u.fecha.value	= objeto[0].fecharegistro;
	}
	
	u.origen_hidden.value = objeto[0].origen;
	
	if(u.origen_hidden.value==0){
	u.origen.value		= "VARIOS";
	}else{
	u.origen.value		= objeto[0].desori;
	}	
	
	u.destino_hidden.value = objeto[0].destino;
	v_destino = objeto[0].destino;
	if(u.destino_hidden.value==0){
		u.destino.value		= "VARIOS";
	}else{
		u.destino.value		= objeto[0].desdes;
	}	
	u.npedidos.value	= objeto[0].npedidos;
	u.dirigido.value	= objeto[0].dirigido;
	if(objeto[0].chnombre==1){u.chNombre.checked = true;}else{u.chNombre.checked = false;}
	habilitarTerceros();
	u.llama.value		= objeto[0].llama;
	u.telefono.value	= objeto[0].telefono;
	u.comentarios.value	= objeto[0].comentarios;
	u.cliente.value		= objeto[0].cliente;
	u.nombre.value		= objeto[0].ncliente;
	u.calle.value		= objeto[0].calle;
	u.numero.value		= objeto[0].numero;
	u.crucecalles.value	= objeto[0].crucecalles;
	u.cp.value			= objeto[0].cp;
	u.colonia.value		= objeto[0].colonia;
	u.poblacion.value	= objeto[0].poblacion;
	u.municipio.value	= objeto[0].municipio;
	u.telefono2.value	= objeto[0].telefono2;
	var hor				= objeto[0].horario.split(":");
	u.h1.value			= hor[0];
	u.h2.value			= hor[1];
	var hor2			= objeto[0].horario2.split(":");
	u.h3.value			= hor2[0];
	u.h4.value			= hor2[1];	
	var com				= objeto[0].hrcomida.split(":");
	u.c1.value			= com[0];
	u.c2.value			= com[1];	
	var	com2			= objeto[0].hrcomida2.split(":");
	u.c3.value			= com2[0];
	u.c4.value			= com2[1];	
	u.sector.value		= objeto[0].sector;
	u.unidad.value		= objeto[0].unidad;
	v_multiple			= objeto[0].multiple;
	if(objeto[0].estado=="NO TRANSMITIDO"){
		u.accion.value	= "modificar";
	}
	
	if(objeto[0].estado=="REALIZADO" || objeto[0].estado=="TRANSMITIDO"){
		u.destino.readOnly = true;
		u.origen.readOnly = true;
	}
	
consultaTexto("mostrarMercancia","recoleccion_conj.php?accion=7&folio="+u.folio_hidden.value+"&idsucursal="+u.idsucursal.value+"&valor="+Math.random()); 
	}
	function obtenerRecoleccionMercancia(){
consultaTexto("mostrarTodo","recoleccion_conj.php?accion=6&folio="+u.folio_hidden.value+"&idsucursal="+u.idsucursal2.value+"&valor="+Math.random());		
	}
	function mostrarMercancia(datos){
		var objeto = eval(convertirValoresJson(datos));
		tabla1.setJsonData(objeto);		

		if(u.estado_hidden.value == "REALIZADO"){			
			u.multiple.disabled = true; 
			u.recolecciones.readOnly = true;
			u.empresarial.readOnly = true;
			u.folior.disabled = true;
			u.guias.disabled  = true;
			
			if(v_multiple!=""){
				u.multiple.checked = true;
			}
			
			consultaTexto("mostrarDisplay","recoleccion_conj.php?accion=10&folio="+u.folio.value+"&valor="+Math.random()+"&idsucursal="+u.idsucursal.value);
		}
	}
	function mostrarDisplay(datos){
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
		if(objeto[0].recolecciones[0] != undefined){
			agregarValores(u.recolecciones,objeto[0].recolecciones);
		}
		if(objeto[0].empresariales[0] != undefined){
			agregarValores(u.empresarial,objeto[0].empresariales);
		}
	}
	function agregarValores(combo,objeto){
		combo.options.length = 0;
		var opcion;
		for(var i=0; i<objeto.length; i++){
			opcion = new Option(objeto[i].folio);
			combo.options[combo.options.length] = opcion;
		}
	}
	function validar(){
		if(u.origen.value==""){
			alerta('Debe capturar Origen','¡Atención!','origen');
			return false;
		}
		if(u.destino.value==""){
			alerta('Debe capturar Destino','¡Atención!','destino');
			return false;
		}
		if(tabla1.getRecordCount()==0){
			alerta3('Debe capturar por lo menos una Mercancia al detalle','¡Atención!');
			return false;
		}
		if(u.chNombre.checked == true){
			if(u.llama.value == ""){
				alerta('Debe capturar Nombre de quien llama','¡Atención!','llama');
				return false;
			}else if(u.telefono.value == ""){
				alerta('Debe capturar Telefono','¡Atención!','telefono');
				return false;
			}else if(u.comentarios.value == ""){
				alerta('Debe capturar Comentarios','¡Atención!','comentarios');
				return false;
			}		
		}
		if(u.cliente.value==""){
			alerta('Debe capturar Cliente','¡Atención!','cliente');
			return false;
		}
		if(u.h1.value =="00" || u.h3.value =="00"){
			alerta('Debe capturar Horario','¡Atención!', ((u.h1.value=="")? 'h1' : 'h3' ));
			return false;
		}		
		
		if(u.accion.value == ""){
				u.destino_hidden.value	  = ((u.destino_hidden.value=="no")?v_destino:u.destino_hidden.value);
				u.registroMercancia.value = tabla1.getRecordCount();			
				u.horario_hidden.value    = u.h1.value+':'+u.h2.value;
				u.horario2_hidden.value   = u.h3.value+':'+u.h4.value;
				u.comida_hidden.value     = u.c1.value+':'+u.c2.value;
				u.comida2_hidden.value    = u.c3.value+':'+u.c4.value;
				u.estado_hidden.value	  = "NO TRANSMITIDO";
				u.accion.value 		      = "grabar";
				document.form1.submit();
		}else if(u.accion.value == "modificar"){
				u.destino_hidden.value	  = ((u.destino_hidden.value=="no")?v_destino:u.destino_hidden.value);
				u.registroMercancia.value = tabla1.getRecordCount();
				u.horario_hidden.value    = u.h1.value+':'+u.h2.value;
				u.horario2_hidden.value   = u.h3.value+':'+u.h4.value;
				u.comida_hidden.value     = u.c1.value+':'+u.c2.value;
				u.comida2_hidden.value    = u.c3.value+':'+u.c4.value;
				u.estado_hidden.value	  = "NO TRANSMITIDO";
				u.accion.value 			  = "modificar";
				document.form1.submit();	
		}		
	}	
	
	function transmitir(){
		if(u.unidad.value==""){
			alerta('Debe capturar Unidad','¡Atención!','unidad');
			return false;
		}else{
			u.estado_hidden.value = "TRANSMITIDO";	
			u.accion.value = "transmitir";
			document.form1.submit();
		}
	}
	
	function realizar(){		
		if(u.multiple.checked==true || u.multiple.checked==false){
			if(u.recolecciones.options.length == 0 && u.empresarial.options.length == 0){
				alerta3('Debe capturar al menos un Folio de Recolección o una Guía Empresarial','¡Atención!');
				return false;
			}
		}
		u.destino_hidden.value	  = ((u.destino_hidden.value=="no")?v_destino:u.destino_hidden.value);
		u.estado_hidden.value = "REALIZADO";
		u.accion.value = "realizado";
		document.form1.submit();
	}
	function confirmarCancelar(){
		abrirVentanaFija('motivosCancelacion.php?motivo='+u.motivo.value+'&notificacion='+u.notificaciones.value, 525, 418, 'ventana', 'Busqueda');
	}
	function cancelar(id,motivo,notificacion){
		u.destino_hidden.value	  = ((u.destino_hidden.value=="no")?v_destino:u.destino_hidden.value);
		u.estado_hidden.value = "CANCELADO";
		u.motivo.value = id;
		u.desmotivo.value = motivo;
		u.notificaciones.value = notificacion;
		u.accion.value = "cancelar";
		document.form1.submit();
	}
	function confirmarReprogramacion(){
		var fi = u.fecha.value.split("-");
		var ff = u.confirFecha.value.split("/");
		var initDate = new Date(fi[2],fi[1],fi[0]);
		var endDate = new Date(ff[2],ff[1],ff[0]);
		
		if(initDate <= endDate){
			alerta3('Debe capturar una fecha mayor a la actual','¡Atención!');
			return false;
		}else{
		abrirVentanaFija("motivosReprogramacion.php?motivo="+u.motivoreprogramar.value+"&notificacion="+u.notificacionesreprogramar.value+"&observaciones="+u.observacionesreprogramar.value, 525, 418, "ventana", "Busqueda");
		}
	}
	function Reprogramacion(id,motivo,notificacion,observaciones){
		u.destino_hidden.value	  = ((u.destino_hidden.value=="no")?v_destino:u.destino_hidden.value);
		u.estado_hidden.value = "NO TRANSMITIDO";
		u.motivoreprogramar.value = id;
		u.desmotivoreprogramar.value = motivo;
		u.notificacionesreprogramar.value = notificacion;
		u.observacionesreprogramar.value  = observaciones;		
		u.accion.value = "reprogramar";
		document.form1.submit();
	}
	
	function validarEstados(){
		if(u.estado_hidden.value==""){
			u.d_guardar.style.visibility="visible";
			u.d_transmitir.style.visibility="hidden";
			u.d_realizado.style.visibility="hidden";
			u.d_cancelado.style.visibility="hidden";
			u.d_reprogramado.style.visibility="hidden";		

		}else if(u.estado_hidden.value=="NO TRANSMITIDO"){		
			u.d_transmitir.style.visibility="visible";
			u.d_cancelado.style.visibility="visible";
			u.d_reprogramado.style.visibility="hidden";
			u.d_realizado.style.visibility="hidden";
		
		}else if(u.estado_hidden.value=="TRANSMITIDO"){			
			u.abajo.style.display="";
			u.d_transmitir.style.visibility="hidden";
			u.d_guardar.style.visibility="hidden";
			u.d_realizado.style.visibility="visible";
			u.d_cancelado.style.visibility="visible";
			u.d_reprogramado.style.visibility="visible";
			u.destino.disabled = true;
			u.origen.disabled = true;
			
		}else if(u.estado_hidden.value=="REPROGRAMADA"){
			u.d_transmitir.style.visibility="visible";
			u.d_cancelado.style.visibility="visible";
			u.d_reprogramado.style.visibility="hidden";
			
		}else if(u.estado_hidden.value=="REALIZADO"){			
			u.abajo.style.display="";
			u.d_transmitir.style.visibility="hidden"
			u.d_guardar.style.visibility="hidden";
			u.d_realizado.style.visibility="hidden";
			u.d_cancelado.style.visibility="hidden";
			u.d_reprogramado.style.visibility="hidden";
			u.d_nuevo.style.visibility="visible";
			u.destino.disabled = true;
			u.origen.disabled = true;
		}else if(u.estado_hidden.value=="CANCELADO"){			
			u.abajo.style.display="none";
			u.d_transmitir.style.visibility="hidden"
			u.d_guardar.style.visibility="hidden";
			u.d_realizado.style.visibility="hidden";
			u.d_cancelado.style.visibility="hidden";
			u.d_reprogramado.style.visibility="hidden";
			u.d_nuevo.style.visibility="visible";	
		}		 
	}
	var nav4 = window.Event ? true : false;
	
	function Numeros(evt){ 
		// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46, ',' = 44 
	var key = nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
	
	}
	
	function obtenerSucursal(id,descripcion,sucursal){
		u.idsucursal.value = sucursal;
		v_sucursal = "1";
		u.folioant.value = u.folio.value;
		u.destino_h.value = id;
		consultaTexto("mostrarSucursal","recoleccion_conj.php?accion=8&sucursal="+sucursal+"&valor="+Math.random());
	}
	function mostrarSucursal(datos){
		var objeto = eval(convertirValoresJson(datos));
		u.sucursal.value	= objeto[0].descripcion;
		consultaTexto("mostrarOrigen1","recoleccion_conj.php?accion=11&sucursal="+u.idsucursal.value+"&descripcion="+u.sucursal.value+"&valor="+Math.random());
	}
	
	function devolverCliente(valor){		
		limpiarCliente();
		u.cliente.value = valor;
		consulta("mostrarCliente","recoleccion_con.php?accion=1&cliente="+valor+"&valor="+Math.random());
	}		
	function obtenerFolios(){
		if(u.multiple.checked == true){		
		consultaTexto("mostrarDisplay","recoleccion_conj.php?accion=10&folio="+u.folio.value+"&idsucursal="+u.idsucursal.value+"&valor="+Math.random());
		}
	}	
	function ponerFoco(){
		u.npedidos.focus();
	}
	function obtenerFolioxFecha(fecha){
		var fi = fecha.split("/");
		var ff = u.confirFecha.value.split("/");
		var initDate = new Date(fi[2],fi[1],fi[0]);
		var endDate = new Date(ff[2],ff[1],ff[0]);
	
	if(initDate < endDate){
		alerta3('La Fecha no debe ser menor a la actual','¡Atención!');
		u.fecha.value	= u.confirFecha.value;
		return false;
	}
	consultaTexto("obtenerFolio","recoleccion_conj.php?accion=16&idsucursal="+u.idsucursal.value+"&fecha="+fecha+"&valor="+Math.random());
	
	}
	function obtenerDiaRecoleccion(destino){
		u.origen_hidden.value = destino;
		if(destino!="" && destino!=0){
			consultaTexto("obtenerDatosDestino","recoleccion_conj.php?accion=3&destino="+destino+"&fecha="+u.fecha.value+"&valor="+Math.random());
		}
	}
	
	function obtenerDatosDestino(datos){
		var objeto = eval(datos);
		if(objeto[0].todasemana==1){
			return false;
		}
		if(objeto[0].dia==0){
			u.origen.value ="";
			alerta('El origen seleccionado no hace recolección el dia '+u.fecha.value,'¡Atención!','origen');			
		}
		/*if(objeto[0].todasemana!=0){
			abrirVentanaFija('datosMercanciaRecoleccion.php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Mercancia');
			return false;
		}
		if(objeto[0].dia==1){
			abrirVentanaFija('datosMercanciaRecoleccion.php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Mercancia');
		}else{
			alerta('El destino no hace recolección el dia '+u.fecha.value,'¡Atención!','destino');		
		}*/
	}
	
	function cambiarPagina(){		
		setTimeout("cambiarPaginaOut()",5000);
	}
	
	function cambiarPaginaOut(){
		document.location.href='recoleccionMercancia.php';
	}
	
	var desc = new Array(<?php echo $desc; ?>);
	
	function obtenerfecha(){
		var fi = u.fecha.value.split("-");
		var ff = u.confirFecha.value.split("/");
		var initDate = new Date(fi[2],fi[1],fi[0]);
		var endDate = new Date(ff[2],ff[1],ff[0]);
		
		//alert(f1+"//"+f2);
		
		//initDate = new Date(2007, 10, 14); 
		//endDate = new Date(2007, 11, 10); 
		alert(initDate);
		alert(endDate);
		if(initDate <= endDate){ 
		// este bloque se ejecuta en caso de no ser valido 
		alert('Fechas no validas'); 
		return false; //si lo usaras para envio de formulario; 
		}

		
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
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
.Estilo4 {font-size: 12px}
-->
</style>
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">  
<table width="576" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="572" class="FondoTabla Estilo4">RECOLECCI&Oacute;N</td>
  </tr>
  <tr>
    <td><table width="232" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
    </table>
      <table width="572" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="3" onClick="obtenerfecha()"></td>
          <td width="262" align="right"><label></label>
            <img src="../img/Boton_Cliente.gif" width="70" height="20" align="absbottom" style="cursor:pointer" onClick="mens.popup('../catalogos/cliente/client.php?recoleccion=1', 630, 550, 'v1', 'Catálogo Cliente');"></td>
          </tr>
        <tr>
          <td colspan="4"><div align="left"><span class="Tablas"> </span>
            <table width="580" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="37"><span class="Tablas">Folio:</span></td>
                <td width="118"><span class="Tablas">
                  <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$_POST['folio']; ?>" readonly=""/>
                </span><span class="Tablas">
                <input name="folioant" type="hidden" id="folioant" value="<?=$_POST[folioant] ?>">
                <input name="destino_h" type="hidden" id="destino_h" value="<?=$_POST[destino_h] ?>">
                </span></td>
                <td width="72" align="right"><span class="Tablas">
                  <input name="idsucursal" id="idsucursal" type="hidden" value="<?=$idsucursal ?>">
                  <input name="sucursalant" type="hidden" id="sucursalant" value="<?=$_POST[sucursalant] ?>">
                  Sucursal:</span></td>
                <td width="108"><span class="Tablas">
                  <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$_POST[sucursal] ?>" readonly=""/>
                </span></td>
                <td width="34"><span class="Tablas"><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="if(document.all.colEstado.innerHTML!='TRANSMITIDO' && document.all.colEstado.innerHTML!='REALIZADO'){abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 550, 450, 'ventana', 'Busqueda')}"></span></td>
                <td width="47">Estado:</td>
                <td width="164" id="colEstado" style="font:tahoma; font-size:15px; font-weight:bold"><?=$_POST['estado_hidden'];?></td>
              </tr>
            </table>
          </div>
            <div align="right"></div><div align="right"></div><div align="right"></div>            </td>
        </tr>
        
        <tr>
          <td colspan="4"><table width="580" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="36"><span class="Tablas">Fecha:</span></td>
              <td width="129"><span class="Tablas">
                <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$_POST[fecha] ?>" readonly="" onChange="obtenerFolioxFecha(this.value)"/>
                <span class="Estilo6 Tablas">
                <!-- <img src="../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onClick="if(document.all.colEstado.innerHTML!='CANCELADO' && document.all.colEstado.innerHTML!='REALIZADO'){displayCalendar(document.all.fecha,'dd/mm/yyyy',this);}" />!-->
                <img src="../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onClick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this);" /></span></span></td>
              <td width="42" align="right"><span class="Tablas">Origen:</span><span class="Tablas">
                <input name="origen_hidden" type="hidden" id="origen_hidden" value="<?=$_POST[origen_hidden] ?>">
              </span></td>
              <td width="153" id="celOrigen"><span class="Tablas">
                <input name="origen" type="text" class="Tablas" id="origen" style="width:100px" value="<?=$_POST[origen] ?>" onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event,'ajax-list-sucursal.php')" onKeyPress="if(event.keyCode==13){obtenerDiaRecoleccion(document.all.origen_hidden.value);}"
       />
              </span></td>
              <td width="17"> <input name="estado_hidden" type="hidden" id="estado_hidden" value="<?=$_POST[estado_hidden]; ?>"></td>
              <td width="42">Destino:</td>
              <td width="140"><span class="Tablas">
                <input name="destino" type="text" class="Tablas" id="destino" style="width:130px" value="<?=$_POST[destino] ?>" 
				autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.destino_hidden.value=this.codigo; abrirVentanaFija('datosMercanciaRecoleccion.php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Mercancia','ponerFoco();');}" onBlur="if(this.value!=''){document.all.destino_hidden.value = this.codigo; if(this.codigo==undefined){document.all.destino_hidden.value ='no'}}" />
                <input name="destino_hidden" type="hidden" id="destino_hidden" value="<?=$_POST[destino_hidden] ?>">
              </span></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td colspan="4" class="FondoTabla Estilo4">Mercancía</td>
          </tr>
        
        <tr>
          <td colspan="4"><table width="570" id="detalle" border="0" align="center" cellpadding="0" cellspacing="0">
            
        </table></td>
        </tr>
        
        <tr>
          <td colspan="4" align="right">
            <table width="150" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><div id="Eliminar" class="ebtn_eliminar" onClick="eliminarFila();"></div></td>
                <td><div class="ebtn_agregar" onClick="if(u.colEstado.innerHTML=='NO TRANSMITIDO' || u.colEstado.innerHTML==''){abrirVentanaFija('datosMercanciaRecoleccion.php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Mercancia','ponerFoco();')}"></div></td>
              </tr>
            </table>
            <input name="index" type="hidden" id="index">          </td>
        </tr>
        
        <tr>
          <td width="78">No. Pedido<span class="Tablas">:</span></td>
          <td><span class="Tablas">
            <input name="npedidos" type="text" class="Tablas" id="npedidos" style="width:150px" onKeyPress="return Numeros(event);" onKeyUp="if(event.keyCode==13){document.all.dirigido.focus();}" value="<?=$_POST[npedidos] ?>" maxlength="7" />
          </span></td>
          <td width="79">Dirigir con<span class="Tablas">:</span></td>
          <td><span class="Tablas">
            <input name="dirigido" type="text" class="Tablas" id="dirigido" style="width:250px" onKeyPress="if(event.keyCode==13){document.all.chNombre.focus();}" value="<?=$_POST[dirigido] ?>" maxlength="70" />
          </span></td>
        </tr>
        
        <tr>
          <td colspan="4" class="FondoTabla Estilo4">Datos Terceros </td>
        </tr>
        <tr>
          <td colspan="4"><label>
            <input name="chNombre" type="checkbox" id="chNombre" style="width:13px" value="1" onClick="habilitarTerceros();" <? if($_POST['chNombre']=="1"){echo "checked";} ?> />
          </label>
            Nombre de Quien Llama:<span class="Tablas">
                    <input name="llama" type="text" class="Tablas" id="llama" style="width:200px; background:#FFFF99" value="<?=$_POST[llama] ?>" disabled="disabled" onKeyPress="if(event.keyCode==13){document.all.telefono.focus();}" />
                    &nbsp;&nbsp;&nbsp;&nbsp;Telefono:
                    <input name="telefono" type="text" class="Tablas" id="telefono" style="width:150px; background:#FFFF99" disabled="disabled" value="<?=$_POST[telefono] ?>" onKeyPress="if(event.keyCode==13){document.all.comentarios.focus();}" />
          </span></td>
          </tr>
        
        <tr>
          <td valign="top">Comentarios:
            <label></label></td>
          <td colspan="3"><textarea  class="Tablas" name="comentarios" onKeyPress="if(event.keyCode==13){document.all.cliente.focus();}" cols="40" disabled="disabled" id="comentarios" style="background:#FFFF99; text-transform:uppercase"><?=$_POST[comentarios] ?></textarea></td>
        </tr>
        
        <tr>
          <td colspan="4" class="FondoTabla Estilo4">Cliente</td>
        </tr>
        <tr>
          <td colspan="4"><table width="580" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="79"># Cliente:</td>
              <td width="175"><span class="Tablas">
                <input name="cliente" type="text" class="Tablas" id="cliente" style="width:60px" onKeyPress="obtenerCliente(event,this.value)" value="<?=$_POST[cliente] ?>" maxlength="5" />
                <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick=            "abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda', 625, 418, 'ventana', 'Busqueda')">&nbsp;&nbsp;&nbsp;&nbsp;<img src="../img/Boton_Agregarchico.gif" alt="Agregar Direcci&oacute;n" name="b_remitente_dir" align="absbottom" id="b_remitente_dir" style="cursor:hand" onClick="if(document.all.cliente.value==''){ alerta('Proporcione el id del cliente','&iexcl;Atencion!','cliente') }else{ abrirVentanaFija('../buscadores_generales/agregarDireccion.php?funcion=devolverCliente('+document.all.cliente.value+')&idcliente='+document.all.cliente.value, 460, 395, 'ventana', 'DATOS DIRECCION')}"></span></td>
              <td colspan="4"><span class="Tablas">
                <input name="nombre" type="text" class="Tablas" id="nombre" style="width:285px;background:#FFFF99" value="<?=$_POST[nombre] ?>" readonly=""/>
              </span></td>
              </tr>
            <tr>
              <td>Calle:                </td>
              <td colspan="3" id="celda_des_calle"><span class="Tablas">
                <input name="calle" type="text" class="Tablas" id="calle" style="width:280px;background:#FFFF99" value="<?=$_POST[calle] ?>" readonly=""/>
              </span></td>
              <td width="195"><span class="Tablas">
                Numero:
                  <input name="numero" type="text" class="Tablas" id="numero" style="width:120px;background:#FFFF99" value="<?=$_POST[numero] ?>" readonly=""/>
              </span></td>
              <td width="3">&nbsp;</td>
            </tr>
            <tr>
              <td>Colonia:</td>
              <td><span class="Tablas">
                <input name="colonia" type="text" class="Tablas" id="colonia" style="width:165px;background:#FFFF99" value="<?=$_POST[colonia] ?>" readonly=""/>
              </span></td>
              <td width="23">&nbsp;</td>
              <td width="96">C.P.:</td>
              <td><span class="Tablas">
                <input name="cp" type="text" class="Tablas" id="cp" style="width:165px;background:#FFFF99" value="<?=$_POST[cp] ?>" readonly=""/>
              </span></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Cruce de Calles:</td>
              <td colspan="5"><span class="Tablas">
                <input name="crucecalles" type="text" class="Tablas" id="crucecalles" style="width:460px;background:#FFFF99" value="<?=$_POST[crucecalles] ?>" readonly=""/>
              </span></td>
              </tr>
            <tr>
              <td>Poblacion:</td>
              <td><span class="Tablas">
                <input name="poblacion" type="text" class="Tablas" id="poblacion" style="width:165px;background:#FFFF99" value="<?=$_POST[poblacion] ?>" readonly=""/>
              </span></td>
              <td>&nbsp;</td>
              <td>Mun./Deleg.:</td>
              <td><span class="Tablas">
                <input name="municipio" type="text" class="Tablas" id="municipio" style="width:165px;background:#FFFF99" value="<?=$_POST[municipio] ?>" readonly=""/>
              </span></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Telefono:</td>
              <td><span class="Tablas">
                <input name="telefono2" type="text" class="Tablas" id="telefono2" style="width:165px;background:#FFFF99" value="<?=$_POST[telefono2] ?>" readonly=""/>
              </span></td>
              <td>&nbsp;</td>
              <td>Hrio. Recolecci&oacute;n:</td>
              <td><span class="Tablas">
                <select name="h1" size="1" onKeyPress="if(event.keyCode==13){document.all.h2.focus();}" class="Tablas" id="h1">				
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
              <select name="h3" size="1" onKeyPress="if(event.keyCode==13){document.all.h4.focus();}" class="Tablas" id="select3">
                <? for($h=0;$h<24;$h++){ ?>
                <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>" <? if($h==$_POST['h3']){echo "selected";}else{echo "00";} ?>>
                <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                </option>
                <? }?>
              </select>
:
<select name="h4" size="1" onKeyPress="if(event.keyCode==13){document.all.c1.focus();}" class="Tablas" id="select4">
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
              <td>Sector:                
                <input name="idsector" type="hidden" id="idsector" value="<?=$_POST[idsector]; ?>"></td>
              <td><span class="Tablas">
                <input name="sector" type="text" class="Tablas" id="sector" style="width:165px;background:#FFFF99" value="<?=$_POST[sector] ?>" readonly=""/>
              </span></td>
              <td>&nbsp;</td>
              <td><script>habilitarTerceros();</script>
                Hrio. Comida:</td>
              <td><span class="Tablas">
                <select name="c1" size="1" onKeyPress="if(event.keyCode==13){document.all.c2.focus();}" class="Tablas" id="c1">
                  <? for($h=0;$h<24;$h++){ ?>
                  <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>" <? if($h==$_POST['c1']){echo "selected";}else{echo "00";} ?>>
                  <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                  </option>
                  <? }?>
                </select>
:
<select name="c2" size="1" onKeyPress="if(event.keyCode==13){document.all.c3.focus();}" class="Tablas" id="select2">
  <? for($m=0;$m<60;$m++){ ?>
  <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>"
				   <? if($m==$_POST['c2']){echo "selected";}else{echo "00";} ?>>
  <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
  </option>
  <? }?>
</select>
 a
 <select name="c3" size="1" onKeyPress="if(event.keyCode==13){document.all.c4.focus();}" class="Tablas" id="select5">
   <? for($h=0;$h<24;$h++){ ?>
   <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>" <? if($h==$_POST['c3']){echo "selected";}else{echo "00";} ?>>
   <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
   </option>
   <? }?>
 </select>
:
<select name="c4" size="1" onKeyPress="if(event.keyCode==13){document.all.unidad.focus();}" class="Tablas" id="select6">
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
          <td colspan="4" class="FondoTabla Estilo4">Datos Recolecci&oacute;n </td>
        </tr>
        <tr>
          <td>Unidad: </td>
          <td width="161"><span class="Tablas">
            <input name="unidad" type="text" class="Tablas" id="unidad" style="width:120px" onKeyPress="obtenerUnidad(event,this.value)" value="<?=$_POST['unidad']; ?>" maxlength="30" />
            <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick=            "abrirVentanaFija('../buscadores_generales/buscarUnidadxSucGen.php?funcion=obtenerUnidadBusqueda&sucursal='+document.all.idsucursal.value, 550, 450, 'ventana', 'Busqueda')" ></span></td>
          <td colspan="2"><input name="hora" type="hidden" id="hora" value="<?=$hora ?>">
            <input name="accion" type="hidden" id="accion" value="<?=$_POST[accion] ?>">
            <input name="horario_hidden" type="hidden" id="horario_hidden" value="<?=$_POST[horario_hidden] ?>">
            <input name="registroMercancia" type="hidden" id="registroMercancia" value="<?=$_POST[registroMercancia] ?>">
            <input name="folio_hidden" type="hidden" id="folio_hidden" value="<?=$folio_hidden ?>">
            <input name="motivo" type="hidden" id="motivo" value="<?=$_POST[motivo]; ?>">
            <input name="desmotivo" type="hidden" id="desmotivo" value="<?=$_POST[desmotivo]; ?>">
            <input name="notificaciones" type="hidden" id="notificaciones" value="<?=$_POST[notificaciones]; ?>">
            <input name="motivoreprogramar" type="hidden" id="motivoreprogramar" value="<?=$_POST[motivoreprogramar]; ?>">
            <input name="desmotivoreprogramar" type="hidden" id="desmotivoreprogramar" value="<?=$_POST[desmotivoreprogramar]; ?>">
            <input name="notificacionesreprogramar" type="hidden" id="notificacionesreprogramar" value="<?=$_POST[notificacionesreprogramar]; ?>">
            <input name="observacionesreprogramar" type="hidden" id="observacionesreprogramar" value="<?=$_POST[observacionesreprogramar]; ?>">
            <input name="fecha_hidden" type="hidden" id="fecha_hidden" value="<?=$fecha_hidden ?>">
            <input name="confirFecha" type="hidden" id="confirFecha" value="<?=$confirFecha ?>">
            <input name="horario2_hidden" type="hidden" id="horario2_hidden" value="<?=$horario2_hidden ?>">
            <input name="comida_hidden" type="hidden" id="comida_hidden" value="<?=$comida_hidden ?>">
            <input name="comida2_hidden" type="hidden" id="comida2_hidden" value="<?=$comida2_hidden ?>"></td>
        </tr>
        
        
        <tr>
          <td colspan="4"><table width="571" id="abajo" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="2"><input type="checkbox" name="multiple" value="1" <? if($_POST[multiple] == "1"){echo "checked";} ?>/>
Recolección Múltiple</td>
              </tr>
            <tr>
              <td><table width="266" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="9" height="16" class="formato_columnas_izq"></td>
                  <td width="250"class="formato_columnas" align="center">FOLIO RECOLECCION </td>
                  <td width="9"class="formato_columnas_der"></td>
                </tr>
                <tr>
                  <td colspan="12"><table width="266" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td colspan="11"><span class="Tablas">
                          <input name="folior" type="text" class="Tablas" id="folior" style="width:150px" onKeyPress="if(event.keyCode==13){insertarRecoleccion(folior, folior.value, document.all.recolecciones);}" value="<?=$_POST[folior] ?>" maxlength="5" />
                        </span></td>
                        <td width="95"><div class="ebtn_agregar" onClick="insertarRecoleccion(folior, folior.value, document.all.recolecciones)"></div></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="12"><select name="recolecciones" size="7" id="recolecciones" style="width:265px" onDblClick="borrarRecoleccion(this)">
                                    </select></td>
                </tr>
              </table>
                <input name="recolecciones_hidden" type="hidden" id="recolecciones_hidden" value="<?=$_POST[recolecciones_hidden] ?>"></td>
              <td><table width="266" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="9" height="16"   class="formato_columnas_izq"></td>
                  <td width="250"class="formato_columnas" align="center">GUIAS EMPRESARIALES </td>
                  <td width="9"class="formato_columnas_der"></td>
                </tr>
                <tr>
                  <td colspan="12"><table width="266" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td colspan="11"><span class="Tablas">
                          <input name="guias" type="text" class="Tablas" id="guias" style="width:150px" onKeyPress="if(event.keyCode==13){insertarEmpresarial(guias, guias.value, document.all.empresarial);}" value="<?=$_POST[guias] ?>" maxlength="15"/>
                        </span></td>
                        <td><div class="ebtn_agregar" onClick="insertarEmpresarial(guias, guias.value, document.all.empresarial)"></div></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="12"><select name="empresarial" size="7" id="empresarial" style="width:265px" onDblClick="borrarEmpresarial(this)">
                                    </select><input name="empresariales_hidden" type="hidden" value="<?=$_POST[empresariales_hidden] ?>"></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="4"><table width="447" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
  			    <td width="85"><div id="d_guardar" class="ebtn_guardar" onClick="validar();"></div></td>
                <td width="85"><div id="d_transmitir" class="ebtn_transmitir" onClick="confirmar('¿Esta seguro de Transmitir la Recolección?', '', 'transmitir();', '');"></div></td>
                <td width="85"><div id="d_realizado" class="ebtn_realizado" onClick="confirmar('¿Esta seguro que desea Realizar la Recolección?', '', 'realizar();', '');"></div></td>
                <td width="85"><div id="d_cancelado" class="ebtn_cancelado" onClick="confirmar('¿Esta seguro de Cancelar la Recolección?', '', 'confirmarCancelar();', '');"></div></td>
                <td width="96"><div id="d_reprogramado" class="ebtn_reprogramado" onClick="confirmar('¿Esta seguro de Reprogramar la Recolección?', '', 'confirmarReprogramacion();', '');"></div></td>
                <td width="96"><div id="d_nuevo" class="ebtn_nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')"></div></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td height="11" colspan="4"><label>
            <input name="mensaje" type="hidden" id="mensaje" value="<?=$mensaje ?>">
            <input name="idsucursal2" type="hidden" id="idsucursal2" value="<?=$idsucursal2 ?>">
          </label></td>
        </tr>
      </table>
     </td>
  </tr>
</table>
</form>
</body>
</html>
<? 
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>
		info('".$mensaje."', 'Operación realizada correctamente');
	</script>";
	if($guardo == "SI"){
		echo "<script language='javascript' type='text/javascript'>
			cambiarPagina();
		</script>";
	}
}
?>