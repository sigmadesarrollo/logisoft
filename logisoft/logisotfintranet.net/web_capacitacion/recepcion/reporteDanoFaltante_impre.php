<?  session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');	
	$link=Conectarse('webpmm');
	
	$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS empleado FROM catalogoempleado WHERE id = ".$_SESSION[IDUSUARIO]."";
	$r = mysql_query($s,$link) or die($s); $f = mysql_fetch_object($r); $empleadob1 = utf8_decode($f->empleado);
	
	$s = "SELECT REPLACE(GROUP_CONCAT(CONCAT(descripcion,' CON ',contenido)),'                            ','') contenidos
			FROM guiaventanilla_detalle WHERE idguia = '".$_GET[guia]."'";
			$rx = mysql_query($s,$link) or die($s);
			$fx = mysql_fetch_object($rx);
			$contenidos = utf8_encode($fx->contenidos);
	
	if($_GET[vieneentrega]==""){	
		$tipo 	= $_GET[tipo]; $noguia	= $_GET[guia]; $unidad = $_GET[unidad];
		$s = "SELECT guia, date_format(fecha, '%d/%m/%Y') fecha, origen, destino, remitente, destinatario, importe FROM
		(SELECT gv.id AS guia, gv.fecha, cs.prefijo AS origen,
		csd.prefijo AS destino, gv.total AS importe,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS remitente,
		CONCAT(cde.nombre,' ',cde.paterno,' ',cde.materno) AS destinatario
		FROM guiasventanilla gv
		INNER JOIN guiaventanilla_unidades gvu ON gv.id = gvu.idguia
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal csd ON gv.idsucursaldestino = csd.id
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		INNER JOIN catalogocliente cde ON gv.iddestinatario = cde.id
		WHERE gv.id = '".$_GET[guia]."'
		UNION
		SELECT ge.id AS guia, ge.fecha, cs.prefijo AS origen, 
		csd.prefijo AS destino, ge.total AS importe,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS remitente,
		CONCAT(cde.nombre,' ',cde.paterno,' ',cde.materno) AS destinatario
		FROM guiasempresariales ge
		INNER JOIN guiasempresariales_unidades gvu ON ge.id = gvu.idguia
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal csd ON ge.idsucursaldestino = csd.id
		INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
		INNER JOIN catalogocliente cde ON ge.iddestinatario = cde.id
		WHERE ge.id = '".$_GET[guia]."'
		) tabla1";
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
			if(u.tipo.value == "dano"){
				info('Los datos han sido guardados correctamente', '');
				
			}else if('<?=$_GET[dblClick] ?>'=='SI' && u.tipo.value == "faltante"){
				parent.dobleClickFaltante(u.noguia.value);
				
			}else if(u.tipo.value == "faltante"){
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
</script>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<style type="text/css" media="all">
	.titulo1{
		font-size:20px;
	}
	.titulo2{
		font-size:12px;
	}
	.textos{
		text-decoration:underline;
	}
</style>
<body>
<form id="form1" name="form1" method="post" action="">
</form>
<table width="668" height="239" align="center" style="border:1px solid #88D2FF" cellpadding="0" cellspacing="0">
	<tr style="">
   	  <td width="153" height="54" rowspan="2" class="titulo2" style="text-align:center; color:#FFF;background-color:#039">
        	REPORTE DE DAÑOS <br />
       	O FALTANTES <br>
       	RECEPCI&Oacute;N</td>
        <td width="423" height="41" style="vertical-align:top; text-align:center" class="titulo1">PAQUETERIA Y MENSAJERIA</td>
        <td width="76" rowspan="2" style="vertical-align:top; text-align:center"><img src="../img/logoPMM.png" width="50" height="52" /></td>
    </tr>
	<tr>
	  <td height="15" style="text-align:right">FOLIO <?=str_pad($_GET[folioreporte],7,"0",STR_PAD_LEFT)?></td>
  </tr>
	<tr>
	  <td height="30" colspan="3" class="titulo2"  style="border:1px solid #88D2FF">
      	<table width="665" height="59" border="0" cellpadding="0" cellspacing="1px">
        	<tr>
            	<td colspan="2">FECHA Y LUGAR DE EXPED.</td>
                <td colspan="8" class="textos" id="in_fechaylugar"><?=$suc->sucursal.", ".date("d/m/Y"); ?></td>
            </tr>
        	<tr>
        	  <td width="69">No. DE GUIA:</td>
        	  <td width="95" class="textos" id="in_noguia"><?=$noguia ?></td>
        	  <td width="54">FECHA:</td>
        	  <td width="69" class="textos" id="in_fecha"><?=$f->fecha ?></td>
        	  <td width="53">ORIGEN:</td>
        	  <td width="60" class="textos" id="in_origen"><?=$f->origen ?></td>
        	  <td width="56">DESTINO:</td>
        	  <td width="65" class="textos" id="in_destino"><?=$f->destino ?></td>
        	  <td width="65">IMPORTE:</td>
        	  <td width="74" class="textos" id="in_importe">$ <?=$f->importe ?></td>
      	  </tr>
        	<tr>
        	  <td>CONTENIDO:</td>
        	  <td colspan="9" class="textos" id="in_contenido"><?=$contenidos?></td>
       	  </tr>
        </table>
      </td>
  </tr>
	<tr>
	  <td height="30" colspan="3" class="titulo2" style="border:1px solid #88D2FF">
      	<table width="666" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="146" height="33"></td>
                <td width="73" class="titulo2">DA&Ntilde;O</td>
                <td width="97" style="text-align:center"><input type="checkbox" name="dano" style="width:25px; height:25px;" <? if($tipo=="dano"){echo "checked";} ?> /></td>
                <td width="93" class="titulo2">FALTANTE</td>
                <td width="94" style="text-align:center"><input type="checkbox" name="faltante" <? if($tipo=="faltante" || $tipo=="faltantedbl"){echo "checked";} ?> style="width:25px; height:25px;" /></td>
                <td width="129"></td>
            </tr>
        </table>
      </td>
  </tr>
	<tr>
	  <td height="30" colspan="3"  style="border:1px solid #88D2FF">
      	<table width="666" height="46" border="0" cellpadding="0" cellspacing="1px">
        	<tr>
            	<td width="109">CLIENTE ORIGEN:</td>
                <td width="539" class="textos" id="in_remitente"><?=$f->remitente ?></td>
            </tr>
        	<tr>
        	  <td>CLIENTE DESTINO:</td>
        	  <td class="textos" id="in_destinatario"><?=$f->destinatario?></td>
      	  </tr>
        </table>
      </td>
  </tr>
	<tr>
	  <td height="30" colspan="3" class="titulo2">
      	<table width="665" height="100" border="0" cellpadding="0" cellspacing="1">
        	<tr>
            	<td width="120">PERSONA QUE RECIBE:</td>
                <td width="526" class="textos"><?=ucwords(utf8_decode($empleadob1))?></td>
            </tr>
        	<tr>
        	  <td>OPERADORES:</td>
        	  <td class="textos"></td>
      	  </tr>
        	<tr>
        	  <td colspan="2">
              	<table border="0" cellpadding="0" cellspacing="0">
                	<tr class="textos">
                    	<td width="332" id="in_operador1"><?=$empl->conductor1 ?></td>
                        <td width="324" id="in_operador2"><?=$empl->conductor2 ?></td>
                    </tr>
                </table>
              </td>
       	  </tr>
        	<tr>
        	  <td colspan="2">
              	<table width="655" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="51">UNIDAD:</td>
                        <td width="124" class="textos" id="in_unidad"><?=$unidad ?></td>
                        <td width="40">RUTA</td>
                        <td width="440" class="textos" id="in_ruta"><?=$fq->ruta?></td>
                    </tr>
                </table>
              </td>
      	  </tr>
        	<tr>
        	  <td colspan="2"><table width="655" border="0" cellpadding="0" cellspacing="0">
        	    <tr>
        	      <td width="92">COMENTARIOS:</td>
        	      <td width="563" class="textos"><?=$_GET[observaciones]?></td>
       	        </tr>
      	    </table></td>
      	  </tr>
	    </table>
      </td>
  </tr>
</table>
</body>
</html>
<object id=factory viewastext style="display:none"
classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"
  codebase="https://www.pmmintranet.net/software/smsx.cab#Version=6,5,439,30">
</object>
<script>
	function enviarImpresion(){
		factory.printing.header = "";
		factory.printing.footer = "";
		factory.printing.portrait = false;
		factory.printing.leftMargin = 2.0;
		factory.printing.topMargin = 5.0;
		factory.printing.rightMargin = 1.0;
		factory.printing.bottomMargin = 1.0;
	  	factory.printing.Print(false);
		window.close();
	}
	window.onload = function(){
		enviarImpresion();
	}
</script>