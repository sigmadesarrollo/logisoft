<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_POST['accion']==""){
		$idsucursal = $_GET['idsucorigen'];
		$_POST[sucursalant] = $_GET['idsucorigen'];
		$folio_hidden = $_GET['folio'];
		$idsucursal2 = $_GET['sucursal'];
		$_POST[estado_hidden] = $_GET['estado'];
		$hora=date("H:i:s");		
		$fecha_hidden = $_GET['fecha'];
		$confirFecha = date("d/m/Y");
	}
	
	$s = "SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion,':',cd.id),CONCAT(cd.descripcion,' - ',cs.prefijo,':',cd.id)) AS descripcion,
	cd.sucursal	FROM catalogodestino cd
	INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id
	ORDER BY descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".cambio_texto($f[0])."'".','.$desc; 	
		}
		$desc = "'VARIOS:0',".$desc;		
		$desc=substr($desc, 0, -1);		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
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
			{nombre:"VOLUMEN", medida:40, alineacion:"right", datos:"volumen"},
			{nombre:"FECHA", medida:4, alineacion:"right", tipo:"oculto", datos:"fecha"}
		],
		filasInicial:10,
		alto:150,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		u.abajo.style.display = "none";
		tabla1.create();		
		u.Eliminar.style.visibility = "hidden";
		obtenerDatos();
		validarEstados();
	}
	
	function obtenerDatos(){		
		consultaTexto("mostrarDatos","recoleccion_consultas.php?accion=0&sucursal="+u.idsucursal.value
		+"&valor="+Math.random());
	}
	function mostrarDatos(datos){
		var obj = eval(convertirValoresJson(datos));
		u.sucursal.value	= obj.principal.descripcion;		
		u.fecha.value   	= obj.principal.fecha;
		u.destino_h.value 	= obj.principal.id;
		u.folio.value		= obj.principal.folio;		
		
		if(obj.origen.length==1){
			u.celOrigen.innerHTML = txtOrigen;
			u.origen_hidden.value	= obj.origen[0].id;
			u.origen.value			= obj.origen[0].destino;
		}else{
			u.celOrigen.innerHTML = combo1;
			var combo = u.origen;
			combo.options.length = null;
			uOpcion = document.createElement("OPTION");
			uOpcion.value="";
			uOpcion.text="SELECCIONAR";			
			combo.add(uOpcion);			
			for(i=0;i<obj.origen.length;i++){	
				uOpcion = document.createElement("OPTION");
				uOpcion.value	=	obj.origen[i].id;
				uOpcion.text	=	obj.origen[i].destino;
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
		
		var v_horario 		= obj.horarios.horariolimite;
		var v_fecha 		= obj.horarios.fechasig;
		if(v_horario < u.hora.value){
			u.fecha.value	= v_fecha;
		}		
		var fi = u.fecha_hidden.value.split("/");
		var ff = u.fecha.value.split("/");
		var initDate = new Date(fi[2],fi[1],fi[0]);
		var endDate = new Date(ff[2],ff[1],ff[0]);
		
		if(initDate > endDate){
			u.fecha.value = u.fecha_hidden.value;
			obtenerFolioxFecha(u.fecha.value);
		}
		
	}
	
	function obtenerFolioxFecha(fecha){
		var fi = fecha.split("/");
		var ff = u.confirFecha.value.split("/");
		var initDate = new Date(fi[2],fi[1],fi[0]);
		var endDate = new Date(ff[2],ff[1],ff[0]);
	
		if(initDate < endDate){
			mens.show('A','La Fecha no debe ser menor a la actual','¡Atención!');
			u.fecha.value	= u.confirFecha.value;
			return false;
		}
		consultaTexto("obtenerFolio","recoleccion_conj.php?accion=13&idsucursal="+u.idsucursal.value
		+"&fecha="+fecha+"&valor="+Math.random());
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
			+'&fechahora='+obj.fecha
			+'&volumen='+obj.volumen+'&esmodificar=si', 460, 410, 'ventana', 'Datos Mercancia','ponerFoco();');	
			}
		}
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
	function ponerFoco(){
		u.npedidos.focus();
	}
	var desc = new Array(<?php echo $desc; ?>);
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Recolecciones</title>

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="576" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="572" class="FondoTabla Estilo4">RECOLECCI&Oacute;N</td>
    </tr>
    <tr>
      <td><table width="232" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr> </tr>
      </table>
          <table width="572" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="3" onclick="obtenerfecha()"></td>
              <td width="262" align="right"><label></label>
                  <img src="../img/Boton_Cliente.gif" width="70" height="20" align="absbottom" style="cursor:pointer" onclick="mens.popup('../catalogos/cliente/client.php?recoleccion=1', 630, 550, 'v1', 'Cat&aacute;logo Cliente');" /></td>
            </tr>
            <tr>
              <td colspan="4"><div align="left"><span class="Tablas"> </span>
                      <table width="580" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="37"><span class="Tablas">Folio:</span></td>
                          <td width="118"><span class="Tablas">
                            <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$_POST['folio']; ?>" readonly=""/>
                            </span><span class="Tablas">
                            <input name="folioant" type="hidden" id="folioant" value="<?=$_POST[folioant] ?>" />
                            <input name="destino_h" type="hidden" id="destino_h" value="<?=$_POST[destino_h] ?>" />
                          </span></td>
                          <td width="72" align="right"><span class="Tablas">
                            <input name="idsucursal" id="idsucursal" type="hidden" value="<?=$idsucursal ?>" />
                            <input name="sucursalant" type="hidden" id="sucursalant" value="<?=$_POST[sucursalant] ?>" />
                            Sucursal:</span></td>
                          <td width="108"><span class="Tablas">
                            <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$_POST[sucursal] ?>" readonly=""/>
                          </span></td>
                          <td width="34"><span class="Tablas"><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="if(document.all.colEstado.innerHTML!='TRANSMITIDO' &amp;&amp; document.all.colEstado.innerHTML!='REALIZADO'){abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 550, 450, 'ventana', 'Busqueda')}" /></span></td>
                          <td width="47">Estado:</td>
                          <td width="164" id="colEstado" style="font:tahoma; font-size:15px; font-weight:bold"><?=$_POST['estado_hidden'];?></td>
                        </tr>
                      </table>
              </div>
                  <div align="right"></div>
                <div align="right"></div>
                <div align="right"></div></td>
            </tr>
            <tr>
              <td colspan="4"><table width="580" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="36"><span class="Tablas">Fecha:</span></td>
                    <td width="129">
                      <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$_POST[fecha] ?>" readonly="" onchange="obtenerFolioxFecha(this.value)"/>                      
                      <img src="../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onClick="if(document.all.colEstado.innerHTML!='CANCELADO' && document.all.colEstado.innerHTML!='REALIZADO'){displayCalendar(document.all.fecha,'dd/mm/yyyy',this);}" />
                     </td>
                    <td width="42" align="right"><span class="Tablas">Origen:</span><span class="Tablas">
                      <input name="origen_hidden" type="hidden" id="origen_hidden" value="<?=$_POST[origen_hidden] ?>" />
                    </span></td>
                    <td width="153" id="celOrigen"><span class="Tablas">
                      <input name="origen" type="text" class="Tablas" id="origen" style="width:100px" value="<?=$_POST[origen] ?>" onkeyup="ajax_showOptions(this,'getCountriesByLetters',event,'ajax-list-sucursal.php')" onkeypress="if(event.keyCode==13){obtenerDiaRecoleccion(document.all.origen_hidden.value);}">
                    </span></td>
                    <td width="17"><input name="estado_hidden" type="hidden" id="estado_hidden" value="<?=$_POST[estado_hidden]; ?>" /></td>
                    <td width="42">Destino:</td>
                    <td width="140"><span class="Tablas">
                      <input name="destino" type="text" class="Tablas" id="destino" style="width:130px" value="<?=$_POST[destino] ?>" 
				autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.destino_hidden.value=this.codigo; abrirVentanaFija('datosMercanciaRecoleccion.php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Mercancia','ponerFoco();');}" onblur="if(this.value!=''){document.all.destino_hidden.value = this.codigo; if(this.codigo==undefined){document.all.destino_hidden.value ='no'}}" />
                      <input name="destino_hidden" type="hidden" id="destino_hidden" value="<?=$_POST[destino_hidden] ?>" />
                    </span></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="4" class="FondoTabla Estilo4">Mercanc&iacute;a</td>
            </tr>
            <tr>
              <td colspan="4"><table width="570" id="detalle" border="0" align="center" cellpadding="0" cellspacing="0">
              </table></td>
            </tr>
            <tr>
              <td colspan="4" align="right"><table width="150" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><div id="Eliminar" class="ebtn_eliminar" onclick="eliminarFila();"></div></td>
                    <td><div class="ebtn_agregar" onclick="if(u.colEstado.innerHTML=='NO TRANSMITIDO' || u.colEstado.innerHTML==''){abrirVentanaFija('datosMercanciaRecoleccion.php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Mercancia','ponerFoco();')}"></div></td>
                  </tr>
                </table>
                  <input name="index" type="hidden" id="index" />
              </td>
            </tr>
            <tr>
              <td width="78">No. Pedido<span class="Tablas">:</span></td>
              <td><span class="Tablas">
                <input name="npedidos" type="text" class="Tablas" id="npedidos" style="width:150px" onkeypress="return solonumeros(event);" onkeyup="if(event.keyCode==13){document.all.dirigido.focus();}" value="<?=$_POST[npedidos] ?>" maxlength="7" />
              </span></td>
              <td width="79">Dirigir con<span class="Tablas">:</span></td>
              <td><span class="Tablas">
                <input name="dirigido" type="text" class="Tablas" id="dirigido" style="width:250px" onkeypress="if(event.keyCode==13){document.all.chNombre.focus();}" value="<?=$_POST[dirigido] ?>" maxlength="70" />
              </span></td>
            </tr>
            <tr>
              <td colspan="4" class="FondoTabla Estilo4">Datos Terceros </td>
            </tr>
            <tr>
              <td colspan="4"><label>
                <input name="chNombre" type="checkbox" id="chNombre" style="width:13px" value="1" onclick="habilitarTerceros();" <? if($_POST['chNombre']=="1"){echo "checked";} ?> />
                </label>
                Nombre de Quien Llama:<span class="Tablas">
                  <input name="llama" type="text" class="Tablas" id="llama" style="width:200px; background:#FFFF99" value="<?=$_POST[llama] ?>" disabled="disabled" onkeypress="if(event.keyCode==13){document.all.telefono.focus();}" />
                  &nbsp;&nbsp;&nbsp;&nbsp;Telefono:
                  <input name="telefono" type="text" class="Tablas" id="telefono" style="width:150px; background:#FFFF99" disabled="disabled" value="<?=$_POST[telefono] ?>" onkeypress="if(event.keyCode==13){document.all.comentarios.focus();}" />
                </span></td>
            </tr>
            <tr>
              <td valign="top">Comentarios:
                <label></label></td>
              <td colspan="3"><textarea  class="Tablas" name="comentarios" onkeypress="if(event.keyCode==13){document.all.cliente.focus();}" cols="40" disabled="disabled" id="comentarios" style="background:#FFFF99; text-transform:uppercase"><?=$_POST[comentarios] ?>
    </textarea></td>
            </tr>
            <tr>
              <td colspan="4" class="FondoTabla Estilo4">Cliente</td>
            </tr>
            <tr>
              <td colspan="4"><table width="580" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="79"># Cliente:</td>
                    <td width="175"><span class="Tablas">
                      <input name="cliente" type="text" class="Tablas" id="cliente" style="width:60px" onkeypress="obtenerCliente(event,this.value)" value="<?=$_POST[cliente] ?>" maxlength="5" />
                      <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick=            "abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda', 625, 418, 'ventana', 'Busqueda')" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="../img/Boton_Agregarchico.gif" alt="Agregar Direcci&oacute;n" name="b_remitente_dir" align="absbottom" id="b_remitente_dir" style="cursor:hand" onclick="if(document.all.cliente.value==''){ mens.show('A','Proporcione el id del cliente','&iexcl;Atencion!','cliente') }else{ abrirVentanaFija('../buscadores_generales/agregarDireccion.php?funcion=devolverCliente('+document.all.cliente.value+')&amp;idcliente='+document.all.cliente.value, 460, 395, 'ventana', 'DATOS DIRECCION')}" /></span></td>
                    <td colspan="4"><span class="Tablas">
                      <input name="nombre" type="text" class="Tablas" id="nombre" style="width:285px;background:#FFFF99" value="<?=$_POST[nombre] ?>" readonly=""/>
                    </span></td>
                  </tr>
                  <tr>
                    <td>Calle: </td>
                    <td colspan="3" id="celda_des_calle"><span class="Tablas">
                      <input name="calle" type="text" class="Tablas" id="calle" style="width:280px;background:#FFFF99" value="<?=$_POST[calle] ?>" readonly=""/>
                    </span></td>
                    <td width="195"><span class="Tablas"> Numero:
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
                      <select name="h1" size="1" onkeypress="if(event.keyCode==13){document.all.h2.focus();}" class="Tablas" id="h1">
                        <? for($h=0;$h<24;$h++){ ?>
                        <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>" <? if($h==$_POST['h1']){echo "selected";}else{echo "00";} ?>>
                        <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                        </option>
                        <? }?>
                      </select>
                      :
                      <select name="h2" size="1" onkeypress="if(event.keyCode==13){document.all.h3.focus();}" class="Tablas" id="h2">
                        <? for($m=0;$m<60;$m++){ ?>
                        <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>"
				   <? if($m==$_POST['h2']){echo "selected";}else{echo "00";} ?>>
                          <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                        </option>
                        <? }?>
                      </select>
                      a
                      <select name="h3" size="1" onkeypress="if(event.keyCode==13){document.all.h4.focus();}" class="Tablas" id="select3">
                        <? for($h=0;$h<24;$h++){ ?>
                        <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>" <? if($h==$_POST['h3']){echo "selected";}else{echo "00";} ?>>
                          <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                        </option>
                        <? }?>
                      </select>
                      :
                      <select name="h4" size="1" onkeypress="if(event.keyCode==13){document.all.c1.focus();}" class="Tablas" id="select4">
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
                      <input name="idsector" type="hidden" id="idsector" value="<?=$_POST[idsector]; ?>" /></td>
                    <td><span class="Tablas">
                      <input name="sector" type="text" class="Tablas" id="sector" style="width:165px;background:#FFFF99" value="<?=$_POST[sector] ?>" readonly=""/>
                    </span></td>
                    <td>&nbsp;</td>
                    <td>
                      Hrio. Comida:</td>
                    <td><span class="Tablas">
                      <select name="c1" size="1" onkeypress="if(event.keyCode==13){document.all.c2.focus();}" class="Tablas" id="c1">
                        <? for($h=0;$h<24;$h++){ ?>
                        <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>" <? if($h==$_POST['c1']){echo "selected";}else{echo "00";} ?>>
                        <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                        </option>
                        <? }?>
                      </select>
                      :
                      <select name="c2" size="1" onkeypress="if(event.keyCode==13){document.all.c3.focus();}" class="Tablas" id="select2">
                        <? for($m=0;$m<60;$m++){ ?>
                        <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>"
				   <? if($m==$_POST['c2']){echo "selected";}else{echo "00";} ?>>
                          <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                        </option>
                        <? }?>
                      </select>
                      a
                      <select name="c3" size="1" onkeypress="if(event.keyCode==13){document.all.c4.focus();}" class="Tablas" id="select5">
                        <? for($h=0;$h<24;$h++){ ?>
                        <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>" <? if($h==$_POST['c3']){echo "selected";}else{echo "00";} ?>>
                          <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                        </option>
                        <? }?>
                      </select>
                      :
                      <select name="c4" size="1" onkeypress="if(event.keyCode==13){document.all.unidad.focus();}" class="Tablas" id="select6">
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
                <input name="unidad" type="text" class="Tablas" id="unidad" style="width:120px" onkeypress="obtenerUnidad(event,this.value)" value="<?=$_POST['unidad']; ?>" maxlength="30" />
                <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick=            "abrirVentanaFija('../buscadores_generales/buscarUnidadxSucGen.php?funcion=obtenerUnidadBusqueda&amp;sucursal='+document.all.idsucursal.value, 550, 450, 'ventana', 'Busqueda')"></span></td>
              <td colspan="2"><input name="hora" type="hidden" id="hora" value="<?=$hora ?>" />
                  <input name="accion" type="hidden" id="accion" value="<?=$_POST[accion] ?>" />
                  <input name="horario_hidden" type="hidden" id="horario_hidden" value="<?=$_POST[horario_hidden] ?>" />
                  <input name="registroMercancia" type="hidden" id="registroMercancia" value="<?=$_POST[registroMercancia] ?>" />
                  <input name="folio_hidden" type="hidden" id="folio_hidden" value="<?=$folio_hidden ?>" />
                  <input name="motivo" type="hidden" id="motivo" value="<?=$_POST[motivo]; ?>" />
                  <input name="desmotivo" type="hidden" id="desmotivo" value="<?=$_POST[desmotivo]; ?>" />
                  <input name="notificaciones" type="hidden" id="notificaciones" value="<?=$_POST[notificaciones]; ?>" />
                  <input name="motivoreprogramar" type="hidden" id="motivoreprogramar" value="<?=$_POST[motivoreprogramar]; ?>" />
                  <input name="desmotivoreprogramar" type="hidden" id="desmotivoreprogramar" value="<?=$_POST[desmotivoreprogramar]; ?>" />
                  <input name="notificacionesreprogramar" type="hidden" id="notificacionesreprogramar" value="<?=$_POST[notificacionesreprogramar]; ?>" />
                  <input name="observacionesreprogramar" type="hidden" id="observacionesreprogramar" value="<?=$_POST[observacionesreprogramar]; ?>" />
                  <input name="fecha_hidden" type="hidden" id="fecha_hidden" value="<?=$fecha_hidden ?>" />
                  <input name="confirFecha" type="hidden" id="confirFecha" value="<?=$confirFecha ?>" />
                  <input name="horario2_hidden" type="hidden" id="horario2_hidden" value="<?=$horario2_hidden ?>" />
                  <input name="comida_hidden" type="hidden" id="comida_hidden" value="<?=$comida_hidden ?>" />
                  <input name="comida2_hidden" type="hidden" id="comida2_hidden" value="<?=$comida2_hidden ?>" /></td>
            </tr>
            <tr>
              <td colspan="4"><table width="571" id="abajo" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="2"><input type="checkbox" name="multiple" value="1" <? if($_POST[multiple] == "1"){echo "checked";} ?>/>
                      Recolecci&oacute;n M&uacute;ltiple</td>
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
                                  <input name="folior" type="text" class="Tablas" id="folior" style="width:150px" onkeypress="if(event.keyCode==13){insertarRecoleccion(folior, folior.value, document.all.recolecciones);}" value="<?=$_POST[folior] ?>" maxlength="5" />
                                </span></td>
                                <td width="95"><div class="ebtn_agregar" onclick="insertarRecoleccion(folior, folior.value, document.all.recolecciones)"></div></td>
                              </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td colspan="12"><select name="recolecciones" size="7" id="recolecciones" style="width:265px" ondblclick="borrarRecoleccion(this)">
                          </select></td>
                        </tr>
                      </table>
                        <input name="recolecciones_hidden" type="hidden" id="recolecciones_hidden" value="<?=$_POST[recolecciones_hidden] ?>" /></td>
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
                                  <input name="guias" type="text" class="Tablas" id="guias" style="width:150px" onkeypress="if(event.keyCode==13){insertarEmpresarial(guias, guias.value, document.all.empresarial);}" value="<?=$_POST[guias] ?>" maxlength="15"/>
                                </span></td>
                                <td><div class="ebtn_agregar" onclick="insertarEmpresarial(guias, guias.value, document.all.empresarial)"></div></td>
                              </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td colspan="12"><select name="empresarial" size="7" id="empresarial" style="width:265px" ondblclick="borrarEmpresarial(this)">
                            </select>
                              <input name="empresariales_hidden" type="hidden" value="<?=$_POST[empresariales_hidden] ?>" /></td>
                        </tr>
                    </table></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="4"><table width="447" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="85"><div id="d_guardar" class="ebtn_guardar" onclick="validar();"></div></td>
                    <td width="85"><div id="d_transmitir" class="ebtn_transmitir" onclick="mens.show('C','&iquest;Esta seguro de Transmitir la Recolecci&oacute;n?', '', '', 'transmitir();');"></div></td>
                    <td width="85"><div id="d_realizado" class="ebtn_realizado" onclick="mens.show('C','&iquest;Esta seguro que desea Realizar la Recolecci&oacute;n?', '', '', 'realizar();');"></div></td>
                    <td width="85"><div id="d_cancelado" class="ebtn_cancelado" onclick="mens.show('C','&iquest;Esta seguro de Cancelar la Recolecci&oacute;n?', '', '', 'confirmarCancelar();');"></div></td>
                    <td width="96"><div id="d_reprogramado" class="ebtn_reprogramado" onclick="mens.show('C','&iquest;Esta seguro de Reprogramar la Recolecci&oacute;n?', '', '', 'confirmarReprogramacion();');"></div></td>
                    <td width="96"><div id="d_nuevo" class="ebtn_nuevo" onclick="mens.show('C','Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar();')"></div></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td height="11" colspan="4"><label>
                <input name="mensaje" type="hidden" id="mensaje" value="<?=$mensaje ?>" />
                <input name="idsucursal2" type="hidden" id="idsucursal2" value="<?=$idsucursal2 ?>" />
              </label></td>
            </tr>
        </table></td>
    </tr>
  </table>
</form>
</body>
</html>
