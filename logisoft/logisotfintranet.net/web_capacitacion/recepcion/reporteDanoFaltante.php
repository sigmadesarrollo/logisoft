<?  session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');	
	$link=Conectarse('webpmm');
	
	$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS empleado FROM catalogoempleado WHERE id = ".$_SESSION[IDUSUARIO]."";
	$r = mysql_query($s,$link) or die($s); $f = mysql_fetch_object($r); $empleadob1 = utf8_decode($f->empleado);
	
	if($_GET[vieneentrega]==""){	
		$tipo 	= $_GET[tipo]; $noguia	= $_GET[guia]; $unidad = $_GET[unidad];
		$s = "SELECT guia, fecha, origen, destino, remitente, destinatario, importe FROM
		(SELECT gv.id AS guia, gv.fecha, cs.descripcion AS origen,
		cd.descripcion AS destino, gv.total AS importe,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS remitente,
		CONCAT(cde.nombre,' ',cde.paterno,' ',cde.materno) AS destinatario
		FROM guiasventanilla gv
		INNER JOIN guiaventanilla_unidades gvu ON gv.id = gvu.idguia
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogodestino cd ON gv.iddestino = cd.id
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		INNER JOIN catalogocliente cde ON gv.iddestinatario = cde.id
		WHERE gv.id = '".$_GET[guia]."'
		UNION
		SELECT ge.id AS guia, ge.fecha, cs.descripcion AS origen, 
		cd.descripcion AS destino, ge.total AS importe,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS remitente,
		CONCAT(cde.nombre,' ',cde.paterno,' ',cde.materno) AS destinatario
		FROM guiasempresariales ge
		INNER JOIN guiasempresariales_unidades gvu ON ge.id = gvu.idguia
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogodestino cd ON ge.iddestino = cd.id
		INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
		INNER JOIN catalogocliente cde ON ge.iddestinatario = cde.id
		WHERE ge.id = '".$_GET[guia]."') tabla1";
		$r = mysql_query($s, $link) or die (mysql_error($link).$s);
		$f = mysql_fetch_object($r);
		
		$s = "SELECT idtipounidad, descripcion AS ruta FROM catalogoruta WHERE id=".$_GET[ruta]."";
		$sq = mysql_query($s, $link) or die($s); $fq = mysql_fetch_object($sq);
		
		$s = "SELECT CONCAT(c1.nombre,' ', c1.apellidopaterno,' ', c1.apellidomaterno) AS conductor1,
		CONCAT(c2.nombre,' ', c2.apellidopaterno,' ', c2.apellidomaterno) AS conductor2
		FROM bitacorasalida bs
		LEFT JOIN catalogoempleado c1 ON bs.conductor1 = c1.id
		LEFT JOIN catalogoempleado c2 ON bs.conductor2 = c2.id
		WHERE bs.status = 0 AND bs.unidad='".$_GET[unidad]."'";
		$em = mysql_query($s, $link) or die(mysql_error($link).$s);
		$empl = mysql_fetch_object($em);
		
		$s = "SELECT descripcion AS sucursal FROM catalogosucursal WHERE id=".$_GET[sucursal]."";
		$su = mysql_query($s, $link) or die(mysql_error($link).$s);
		$suc= mysql_fetch_object($su);
	}else{		
		$s = "SELECT guia, fecha, origen, destino, remitente, destinatario, importe FROM
		(SELECT gv.id AS guia, gv.fecha, cs.descripcion AS origen,
		cd.descripcion AS destino, gv.total AS importe,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS remitente,
		CONCAT(cde.nombre,' ',cde.paterno,' ',cde.materno) AS destinatario
		FROM guiasventanilla gv
		INNER JOIN guiaventanilla_unidades gvu ON gv.id = gvu.idguia
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogodestino cd ON gv.iddestino = cd.id
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		INNER JOIN catalogocliente cde ON gv.iddestinatario = cde.id
		WHERE gv.id = '".$_GET[guia]."'
		UNION
		SELECT ge.id AS guia, ge.fecha, cs.descripcion AS origen, 
		cd.descripcion AS destino, ge.total AS importe,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS remitente,
		CONCAT(cde.nombre,' ',cde.paterno,' ',cde.materno) AS destinatario
		FROM guiasempresariales ge
		INNER JOIN guiasempresariales_unidades gvu ON ge.id = gvu.idguia
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogodestino cd ON ge.iddestino = cd.id
		INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
		INNER JOIN catalogocliente cde ON ge.iddestinatario = cde.id
		WHERE ge.id = '".$_GET[guia]."') tabla1";
		$r = mysql_query($s, $link) or die (mysql_error($link).$s);
		$f = mysql_fetch_object($r);
		
		$s = "SELECT cr.descripcion AS ruta, rm.unidad FROM reportedanosfaltante rep
		INNER JOIN recepcionmercancia rm ON rep.recepcion = rm.folio
		INNER JOIN catalogoruta cr ON rm.ruta = cr.id
		WHERE rep.guia = '".$_GET[guia]."'";
		$sq = mysql_query($s, $link) or die($s); $fq = mysql_fetch_object($sq);
		$_GET[unidad] = $fq->unidad;
		
		$tipo 	= $_GET[tipo]; $noguia	= $_GET[guia]; $unidad = $_GET[unidad];
		
		$s = "SELECT CONCAT(c1.nombre,' ', c1.apellidopaterno,' ', c1.apellidomaterno) AS conductor1,
		CONCAT(c2.nombre,' ', c2.apellidopaterno,' ', c2.apellidomaterno) AS conductor2
		FROM bitacorasalida bs
		LEFT JOIN catalogoempleado c1 ON bs.conductor1 = c1.id
		LEFT JOIN catalogoempleado c2 ON bs.conductor2 = c2.id
		WHERE bs.status = 0 AND bs.unidad='".$_GET[unidad]."'";
		$em = mysql_query($s, $link) or die(mysql_error($link).$s);
		$empl = mysql_fetch_object($em);
		
		$s = "SELECT descripcion AS sucursal FROM catalogosucursal WHERE id=".$_SESSION[IDSUCURSAL]."";
		$su = mysql_query($s, $link) or die(mysql_error($link).$s);
		$suc= mysql_fetch_object($su);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script src="../recepciones/select.js"></script>
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
<script>
	var u = document.all;
	function obtenerRecibeBusqueda(id,caja){
		if(id!=""){
			switch(caja){
				case "1":
					u.empleado1.value = id;
				break;				
			}
			consulta("mostrarRecibe","consultasRecepcion.php?accion=5&id="+id+"&caja="+caja);
		}
	}
	function obtenerRecibe(e,id,caja){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 && id!=""){
consulta("mostrarRecibe","consultasRecepcion.php?accion=5&id="+id+"&caja="+caja);
		}
	}
	function mostrarRecibe(datos){
	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
	var caja = datos.getElementsByTagName('caja').item(0).firstChild.data;
		if(con>0){
			switch(caja){
			case "1":
				u.empleadob1.value = datos.getElementsByTagName('nombre').item(0).firstChild.data;
			break;			
		}		
		}else{
			alerta3('La persona no existe','¡Atención!','recibe'+caja);
			switch(caja){
				case "1":
					u.empleado1.select();
					u.empleadob1.value = "";
				break;				
			}
		}
	}
	var nav4 = window.Event ? true : false;
	function Numeros(evt){
		var key = nav4 ? evt.which : evt.keyCode; 
		return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
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
		if(nombrecaja=="empleado1"){
			u.oculto.value="1";
		}
	}
	function borrarDescripciones(nombrecaja){
		if(nombrecaja =="empleado1" && u.empleado1.value ==""){
			u.empleadob1.value = "";
		}
	}
	function guardar(){
		if(u.empleado1.value == ""){
			alerta('Debe capturar nombre de la persona que recibió la mercancía','¡Atención!','empleado1');		
		}else{
			u.d_guardar.style.visibility = "hidden";
			var objeto = new Array();
			objeto[0] = u.noguia.value;
			if(document.getElementById('dano')){
				objeto[1] = ((u.dano.checked == true) ? 1 : 0);
				objeto[2] = 0;
			}
			if(document.getElementById('faltante')){
				objeto[1] = 0;
				objeto[2] = ((u.faltante.checked == true) ? 1 : 0);
			}
			objeto[3] = u.empleado1.value;			
			objeto[4] = u.comentarios.value;
			
			if('<?=$_GET[vieneentrega] ?>'==''){
				consultaTexto("confirmarRegistro","reporteDanoFaltante_con.php?accion=1&arreglo="+objeto
				+"&m="+Math.random());
			}else{
				consultaTexto("confirmarRegistro","reporteDanoFaltante_con.php?accion=3&arreglo="+objeto
				+"&m="+Math.random());
			}
		}
	}
	function confirmarRegistro(datos){
		if(datos.indexOf("ok")>-1){
			u.d_guardar.style.visibility = "visible";
			var dat = datos.split(",");
			alert(document.location.href.replace("reporteDanoFaltante.php","reporteDanoFaltante_impre.php"));
			window.open(document.location.href.replace("reporteDanoFaltante.php","reporteDanoFaltante_impre.php")+
								"&observaciones="+u.comentarios.value.toUpperCase()+"&folioreporte="+dat[1],null,"top=1500 left=5000 width=5 height=5");
			if(u.tipo.value == "dano"){
				info('Los datos han sido guardados correctamente', '');
				
			}else if('<?=$_GET[dblClick] ?>'=='SI' && u.tipo.value == "faltante"){
				parent.dobleClickFaltante(u.noguia.value);
				
			}else if(u.tipo.value == "faltante"){
				info('Los datos han sido guardados correctamente', '');
				parent.mostrarGuiaArreglo();
			}else if(u.tipo.value == "faltantedbl"){
				info('Los datos han sido guardados correctamente', '');
			}
		}else{
			u.d_guardar.style.visibility = "visible";
			alerta3('Hubo un error al guardar los datos '+datos,'¡Atención!');
		}
	}
	
	function limpiar(){		
		u.empleado1.value = "";
		u.empleadob1.value= "";		
		u.comentarios.value="";
		u.accion.value = "";
		u.oculto.value = "";
	}
	window.onload = function(){
		checarTipo();
		if('<?=$_GET[indice] ?>' > 0){
			info('Se han capturado los datos de la guia faltante<br>Se continua con la siguiente', '');
		}
	}
	
	function checarTipo(){
		if(u.dano.checked==true){
			u.td_faltante.innerHTML = "";
		}else if(u.faltante.checked==true){
			u.td_dano.innerHTML = "";
		}
	}
</script>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="552" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="569" class="FondoTabla Estilo4">REPORTE DE DA&Ntilde;OS Y FALTANTES</td>
  </tr>
  <tr>
    <td><table width="551" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="564"><table width="550" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="54">No. Gu&iacute;a: </td>
            <td width="138"><span class="Tablas">
              <input name="noguia" type="text" class="Tablas" id="noguia" style="width:100px;background:#FFFF99" value="<?=$noguia ?>" readonly=""/>
            </span></td>
            <td width="38">Fecha:</td>
            <td width="126"><span class="Tablas">
              <input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px;background:#FFFF99" value="<?=$f->fecha ?>" readonly=""/>
            </span></td>
            <td width="56">Sucursal:</td>
            <td width="138"><span class="Tablas">
              <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:130px;background:#FFFF99" value="<?=$suc->sucursal ?>" readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      
      <tr>
        <td><table width="550" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="37">Origen:<br /></td>
            <td width="143"><span class="Tablas">
              <input name="origen" type="text" class="Tablas" id="origen" style="width:140px;background:#FFFF99" value="<?=$f->origen ?>" readonly=""/>
            </span></td>
            <td width="40">Destino:</td>
            <td width="141"><span class="Tablas">
              <input name="destino" type="text" class="Tablas" id="destino" style="width:140px;background:#FFFF99" value="<?=$f->destino ?>" readonly=""/>
            </span></td>
            <td width="51">Importe:</td>
            <td width="138"><span class="Tablas">
              <input name="importe" type="text" class="Tablas" id="importe" style="width:130px;background:#FFFF99" value="<?=$f->importe ?>" readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="550" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td id="td_dano"><label>
              <input name="dano" type="checkbox" id="dano" value="1" <? if($tipo=="dano"){echo "checked";} ?> />
            </label>              Da&ntilde;o</td>
            <td id="td_faltante"><label>
              <input name="faltante" type="checkbox" id="faltante" value="1" <? if($tipo=="faltante" || $tipo=="faltantedbl"){echo "checked";} ?> />
            </label>Faltante</td>
            <td width="44">Unidad:</td>
            <td width="169"><span class="Tablas">
              <input name="unidad" type="text" class="Tablas" id="unidad" style="width:150px;background:#FFFF99" value="<?=$unidad ?>" readonly=""/>
            </span></td>
            <td width="32">Ruta:</td>
            <td width="167"><span class="Tablas">
              <input name="ruta" type="text" class="Tablas" id="ruta" style="width:160px;background:#FFFF99" value="<?=$fq->ruta ?>" readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="550" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="60">Remitente:</td>
            <td width="213"><span class="Tablas">
              <input name="remitente" type="text" class="Tablas" id="remitente" style="width:200px;background:#FFFF99" value="<?=$f->remitente ?>" readonly=""/>
            </span></td>
            <td width="70">Destinatario:</td>
            <td width="207"><span class="Tablas">
              <input name="destinatario" type="text" class="Tablas" id="destinatario" style="width:200px;background:#FFFF99" value="<?=$f->destinatario ?>" readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="550" border="0" cellpadding="0" cellspacing="0" id="choferes" <?=((!empty($_GET[vieneentrega]))? 'style="display:none"' : '' ) ?>>
          <tr>
            <td width="194">Nombre de los operadores:<br /></td>
            <td width="356"><label></label>              <span class="Tablas">
              <input name="empleadob3" type="text" class="Tablas" id="empleadob3" style="width:350px;background:#FFFF99" value="<?=$empl->conductor1 ?>" readonly=""/>
                          </span></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td><label></label>              <span class="Tablas">
              <input name="empleadob4" type="text" class="Tablas" id="empleadob4" style="width:350px;background:#FFFF99" value="<?=$empl->conductor2 ?>" readonly=""/>
                          </span></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="550" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="253"><br /></td>
            <td width="58"><label></label></td>
            <td width="31">&nbsp;</td>
            <td width="220">&nbsp;</td>
          </tr>
          <tr>
            <td>Nombre de la persona que recibi&oacute; la mercanc&iacute;a:</td>
            <td><label><span class="Tablas">
              <input name="empleado1" type="text" class="Tablas" id="empleado1" style="width:50px;background:#FFFF99" value="<?=$_SESSION[IDUSUARIO] ?>"  />
            </span></label></td>
            <td><div style="visibility:hidden" class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarEmpleado.php?funcion=obtenerRecibeBusqueda&caja=1', 550, 450, 'ventana', 'Busqueda')"></div></td>
            <td><span class="Tablas">
              <input name="empleadob1" type="text" class="Tablas" id="empleadob1" style="width:213px;background:#FFFF99" value="<?=$empleadob1 ?>" readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="550" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="65" valign="top">Comentarios:<br /></td>
            <td width="537"><label>
<textarea name="comentarios" id="comentarios" class="Tablas" style="width:480px; text-transform:uppercase"><?=$comentarios ?></textarea>
            <input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
              <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>">
              <input name="tipo" type="hidden" id="tipo" value="<?=$tipo ?>">
            </label></td>
          </tr>
        </table></td>
      </tr>
      
      <tr>
        <td><table border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td width="84"><div class="ebtn_guardar" id="d_guardar" onClick="guardar()"></div></td>
            <td width="84"><div class="ebtn_nuevo" onClick="confirmar('&iquest;Desea limpiar los datos?','&iexcl;Atencion!','limpiar()','')"></div></td>
            </tr>
        </table></td>
      </tr>
    </table>
    </tr>
</table> 
</form>
</body>
</html>