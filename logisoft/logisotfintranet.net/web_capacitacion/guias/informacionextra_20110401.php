<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$sql = "SELECT DATE_FORMAT(sg.fecha, '%d/%m/%Y') AS fecha, sg.hora,
					cs.descripcion AS ubicacion, sg.estado as evento, sg.unidad,
					CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado,
					sg.bitacora, sg.embarque, 
					ifnull(sg.motivodevolucion, '') motivodevolucion, ifnull(sg.estatus, '') estatus
					FROM seguimiento_guias AS sg
					INNER JOIN catalogosucursal AS cs ON sg.ubicacion = cs.id
					INNER JOIN catalogoempleado as ce on sg.usuario = ce.id
					WHERE sg.guia = '$_GET[folio]'";
		$r=mysql_query($sql,$l)or die($sql);
		$registros= array();	
		if (mysql_num_rows($r)>0)
				{
				while ($f=mysql_fetch_object($r))
				{
					$f->empleado=cambio_texto($f->empleado);
					$f->ubicacion=cambio_texto($f->ubicacion);
					$f->evento=cambio_texto($f->evento);
					$registros[]=$f;	
				}
			$datos= str_replace('null','""',json_encode($registros));
		}else{
			$datos= str_replace('null','""',json_encode(0));
		}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<script language="javascript" src="../javascript/ajax.js"></script>
<script language="javascript" src="../javascript/ClaseMensajes.js"></script>
<style type="text/css">
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
body {
	margin-left: 1px;
	margin-top: 5px;
	margin-right: 1px;
	margin-bottom: 1px;
}
-->
</style>
</head>
<?
	$_GET[folio]=($_GET[folio]=="" || $_GET[folio]=="&nbsp;")?"0":$_GET[folio];

	if($_GET[tipo]==1){
		$s = "SELECT gv.id, cs1.descripcion AS origen, cs2.descripcion AS destino,
		cd.descripcion as subdestino,
		CONCAT_WS(' ',cc1.nombre,cc1.paterno,cc1.materno) AS remitente,
		CONCAT_WS(' ',cc2.nombre,cc2.paterno,cc2.materno) AS destinatario,
		DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS emision,
		DATE_FORMAT(sg.fecha,'%d/%m/%Y') AS recepcion,
		gv.estado, gv.tflete AS flete, gv.totros AS otros, gv.ttotaldescuento AS descuento,
		gv.subtotal, gv.tiva AS iva, gv.ivaretenido, gv.total,
		IF(gv.condicionpago=1,'CREDITO','CONTADO') AS condicion,
		CONCAT_WS(' ',ce.nombre, ce.apellidopaterno, ce.apellidomaterno) AS capturista,
		gv.idsucursalorigen, gv.idsucursaldestino, gv.recibio, gv.numeroidentificacion,
		gv.tipoidentificacion
		FROM guiasventanilla AS gv
		LEFT JOIN catalogoempleado AS ce ON gv.usuario = ce.user
		INNER JOIN catalogosucursal AS cs1 ON gv.idsucursalorigen = cs1.id
		INNER JOIN catalogosucursal AS cs2 ON gv.idsucursaldestino = cs2.id
		INNER JOIN catalogodestino AS cd ON gv.iddestino = cd.id
		INNER JOIN catalogocliente AS cc1 ON gv.idremitente = cc1.id
		INNER JOIN catalogocliente AS cc2 ON gv.iddestinatario = cc2.id
		LEFT JOIN seguimiento_guias AS sg ON gv.id = sg.guia AND sg.ubicacion = gv.idsucursaldestino
		WHERE gv.id = '$_GET[folio]'";
	}else{
		$s = "SELECT gv.id, cs1.descripcion AS origen, cs2.descripcion AS destino,
		cd.descripcion as subdestino,
		CONCAT_WS(' ',cc1.nombre,cc1.paterno,cc1.materno) AS remitente,
		CONCAT_WS(' ',cc2.nombre,cc2.paterno,cc2.materno) AS destinatario,
		DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS emision,
		DATE_FORMAT(sg.fecha,'%d/%m/%Y') AS recepcion,
		gv.estado, gv.tflete AS flete, gv.totros AS otros, gv.ttotaldescuento AS descuento,
		gv.subtotal, gv.tiva AS iva, gv.ivaretenido, gv.total,
		gv.tipopago AS condicion,
		CONCAT_WS(' ',ce.nombre, ce.apellidopaterno, ce.apellidomaterno) AS capturista,
		gv.idsucursalorigen, gv.idsucursaldestino, gv.recibio, gv.numeroidentificacion,
		gv.tipoidentificacion
		FROM guiasempresariales AS gv
		LEFT JOIN catalogoempleado AS ce ON gv.usuario = ce.user
		INNER JOIN catalogosucursal AS cs1 ON gv.idsucursalorigen = cs1.id
		INNER JOIN catalogosucursal AS cs2 ON gv.idsucursaldestino = cs2.id
		INNER JOIN catalogodestino AS cd ON gv.iddestino = cd.id
		INNER JOIN catalogocliente AS cc1 ON gv.idremitente = cc1.id
		INNER JOIN catalogocliente AS cc2 ON gv.iddestinatario = cc2.id
		LEFT JOIN seguimiento_guias AS sg ON gv.id = sg.guia AND sg.ubicacion = gv.idsucursaldestino
		WHERE gv.id = '$_GET[folio]'";
	}
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r); 
	
	if($f->estado=='EN REPARTO EAD'){
		$s = "SELECT * FROM devyliqautomatica WHERE guia='$_GET[folio]' AND estado='E'";
		$rre = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($rre)>0){
			$fre = mysql_fetch_object($rre);
			$f->estado = 'ENTREGADA';
			$f->recibio = $fre->recibe;
			$f->numeroidentificacion = $fre->noidentificacion;
			$f->tipoidentificacion = $fre->tipoidentificacion;
		}
	}
	
	if($_SESSION[IDSUCURSAL]==$f->idsucursalorigen || $_SESSION[IDSUCURSAL]==$f->idsucursaldestino)
		$comentariovisible = "''";
	else
		$comentariovisible = "none";
	?>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="591" border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<td width="178" id="tab0" height="21" class="tab_seleccionado" onclick="seleccionarTabs(0)" align="center">DATOS GENERALES</td>
      <td width="178" id="tab1" class="tab_deseleccionado" onclick="seleccionarTabs(1)" align="center">COMENTARIOS</td>
      <td width="178" id="tab2" class="tab_deseleccionado" onclick="seleccionarTabs(2)" align="center">DATOS DE LA ENTREGA</td>
      <td width="57"></td>
    </tr>
    <tr>
    	<td height="257" colspan="3">&nbsp;</td>
    </tr>
</table>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="canvas0">
<table width="622" border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td height="109"><table width="617" border="0" cellpadding="0" cellspacing="0">
     
      <tr>
        <td colspan="2"><table width="602" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="24" height="23">Folio</td>
            <td width="80"><input name="Amaterno" type="text"  id="Amaterno" style="width:80px;background:#FFFF99" value="<?=$f->id?>"  readonly=""/></td>
            <td width="18">&nbsp;</td>
            <td width="64">&nbsp;</td>
            <td width="34">Origen</td>
            <td width="80"><input name="Amaterno3" type="text"  id="Amaterno3" style="width:80px;background:#FFFF99" value="<?=$f->origen?>"  readonly=""/></td>
            <td width="20">&nbsp;</td>
            <td width="54">Suc. Destino</td>
            <td width="91"><input name="Amaterno4" type="text"  id="Amaterno4" style="width:80px;background:#FFFF99" value="<?=$f->destino?>"  readonly=""/></td>
            <td width="42">Destino</td>
<td width="95"><input name="dest" type="text"  id="dest" style="width:80px;background:#FFFF99" value="<?=$f->subdestino?>"  readonly=""/></td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td><table width="402" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="63">Remitente</td>
            <td width="339"><input type="text" name="destino" id="destino" style="background:#FFFF99;width:254px; font-size:9px"
        onkeyup="ajax_showOptions(this,'getCountriesByLetters',event,'buscarSucursales.php')"
        onchange="devolverDestino()" onkeypress="if(event.keyCode==13){devolverDestino(); document.all.Cliente.focus();}"
        onblur="devolverDestino()" value="<?=$f->remitente?>" /></td>
          </tr>
        </table></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><table width="568" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="63">Destinatario</td>
            <td width="273"><input type="text" name="destino2" id="destino2" style="background:#FFFF99;width:254px; font-size:9px"
        onkeyup="ajax_showOptions(this,'getCountriesByLetters',event,'buscarSucursales.php')"
        onchange="devolverDestino()" onkeypress="if(event.keyCode==13){devolverDestino(); document.all.Cliente.focus();}"
        onblur="devolverDestino()" value="<?=$f->destinatario?>" /></td>
            <td width="68">Condici&oacute;n</td>
            <td width="164"><input name="Amaterno32" type="text"  id="Amaterno32" style="width:100px;background:#FFFF99" value="<?=$f->condicion?>"  readonly=""/></td>
          </tr>
        </table></td>
</tr>
      <tr>
        <td width="453" valign="top"><table width="439" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="439">&nbsp;</td>
          </tr>
          <tr>
            <td><table width="404" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="64">Emisi&oacute;n</td>
                  <td width="80"><input type="text" name="destino23" id="destino23" style="background:#FFFF99;background:#FFFF99;width:80px; font-size:9px"
        onkeyup="ajax_showOptions(this,'getCountriesByLetters',event,'buscarSucursales.php')"
        onchange="devolverDestino()" onkeypress="if(event.keyCode==13){devolverDestino(); document.all.Cliente.focus();}"
        onblur="devolverDestino()" value="<?=$f->emision?>" /></td>
                  <td width="31">&nbsp;</td>
                  <td width="90">Estado de la Guia </td>
                  <td width="139"><input type="text" name="destino24" id="destino24" style="background:#FFFF99; width:120px; font-size:9px"
        onkeyup="ajax_showOptions(this,'getCountriesByLetters',event,'buscarSucursales.php')"
        onchange="devolverDestino()" onkeypress="if(event.keyCode==13){devolverDestino(); document.all.Cliente.focus();}"
        onblur="devolverDestino()" value="<?=$f->estado?>" /></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="175" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="65">Recepci&oacute;n</td>
                  <td width="80"><input type="text" name="destino232" id="destino232" style="background:#FFFF99;width:80px; font-size:9px"
        onkeyup="ajax_showOptions(this,'getCountriesByLetters',event,'buscarSucursales.php')"
        onchange="devolverDestino()" onkeypress="if(event.keyCode==13){devolverDestino(); document.all.Cliente.focus();}"
        onblur="devolverDestino()" value="<?=$f->recepcion?>" /></td>
                  <td width="30">&nbsp;</td>
</tr>
            </table></td>
          </tr>
          <tr>
            <td>
            <?
				$s = "SELECT DATE_FORMAT(fechapago, '%d/%m/%Y') lafecha
				FROM pagoguias WHERE guia = '$_GET[folio]' AND pagado = 'S' AND ISNULL(fechacancelacion)";
				$x = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($x)>0){
					$fx = mysql_fetch_object($x);
					$fechapago = $fx->lafecha;
				}else{
					$fechapago = "";
				}
				
				if($fechapago==""){
					$s = "SELECT DATE_FORMAT(pg.fechapago, '%d/%m/%Y') lafecha FROM 
					facturacion f
					INNER JOIN pagoguias pg ON f.folio = pg.guia AND pg.tipo = 'FACT'
					INNER JOIN guiasempresariales gv ON f.folio = gv.factura
					where gv.id = '$_GET[folio]' and pg.pagado = 'S'";
					$x = mysql_query($s,$l) or die($s);
					if(mysql_num_rows($x)>0){
						$fx = mysql_fetch_object($x);
						$fechapago = $fx->lafecha;
					}else{
						$fechapago = "";
					}
				}
			?>
            <table width="197" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="64">Pago</td>
                <td width="100"><input type="text" name="destino2322" id="destino2322" style="background:#FFFF99;width:100px; font-size:9px"
        onkeyup="ajax_showOptions(this,'getCountriesByLetters',event,'buscarSucursales.php')"
        onchange="devolverDestino()" onkeypress="if(event.keyCode==13){devolverDestino(); document.all.Cliente.focus();}"
        onblur="devolverDestino()" value="<?=$fechapago?>" /></td>
                <td width="33">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="439">
            	<div style="width:439px; height:120px; overflow-x:scroll; overflow-y:hidden">
                    <table width="305" id="detalle" border="0" cellpadding="0" cellspacing="0">
                    </table>	  
                </div>
                </td>
          </tr>
          <tr>
            <td></td>
          </tr>
         
</table></td>
        <td width="164"><table width="164" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2" class="FondoTabla Estilo4" valign="top">Totales</td>
          </tr>
          <tr>
            <td width="73">Flete</td>
            <td width="91"><input type="text" name="destino243" id="destino243" style="background:#FFFF99;width:80px; font-size:9px; text-align:right" value="$ <?=number_format($f->flete,2,'.',',')?>" /></td>
          </tr>
          <tr>
            <td>Otro</td>
            <td><input type="text" name="destino244"  style="background:#FFFF99;width:80px; font-size:9px; text-align:right" value="$ <?=number_format($f->otros,2,'.',',')?>" /></td>
          </tr>
          <tr>
            <td>Descto.</td>
            <td><input type="text" name="destino245" style="background:#FFFF99;width:80px; font-size:9px; text-align:right" value="$ <?=number_format($f->descuento,2,'.',',')?>" /></td>
          </tr>
          <tr>
            <td>Sub Total </td>
            <td><input type="text" name="destino246" style="background:#FFFF99;width:80px; font-size:9px; text-align:right" value="$ <?=number_format($f->subtotal,2,'.',',')?>"/></td>
          </tr>
          <tr>
            <td>Iva</td>
            <td><input type="text" name="destino247" style="background:#FFFF99;width:80px; font-size:9px; text-align:right" value="$ <?=number_format($f->iva,2,'.',',')?>"/></td>
          </tr>
          <tr>
            <td>Iva Retenido </td>
            <td><input type="text" name="destino248" style="background:#FFFF99;width:80px; font-size:9px; text-align:right" value="$ <?=number_format($f->ivaretenido,2,'.',',')?>" /></td>
          </tr>
          <tr>
            <td>Total</td>
            <td><input type="text" name="destino249" id="destino249" style="background:#FFFF99;width:80px; font-size:9px; text-align:right"
        onkeyup="ajax_showOptions(this,'getCountriesByLetters',event,'buscarSucursales.php')"
        onchange="devolverDestino()" onkeypress="if(event.keyCode==13){devolverDestino(); document.all.Cliente.focus();}"
        onblur="devolverDestino()" value="$ <?=number_format($f->total,2,'.',',')?>" /></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="403" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="63">Capturista</td>
            <td width="340">
            	<input type="text" name="capturista" style="background:#FFFF99;width:324px; font-size:9px;"
        					value="<?=$f->capturista?>" />            </td>
          </tr>
        </table></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 617px; visibility:hidden;" id="canvas1">
<table width="617" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
	<tr>
    	<td height="13">
        <table width="567" border="0" cellpadding="0" cellspacing="0">
  <tr>
            	<td width="66" height="18">Comentarios:</td>
                <td width="184">&nbsp;</td>
                <td width="265">&nbsp;</td>
                <td width="52">&nbsp;</td>
            </tr>
  <tr>
    <td height="119" colspan="4" align="center">
    <table id="listacomentarios" border="0" cellpadding="0" cellspacing="0"></table>    </td>
</tr>
  <tr>
    <td height="18" colspan="4" id="datos" align="center" style="text-transform:uppercase">&nbsp;</td>
</tr>
  <tr>
    <td height="56">&nbsp;</td>
    <td colspan="2" align="center">
    	<table width="378" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="36" align="center">&nbsp;</td>
                <td width="307" align="center">
                <textarea name="comentario" rows="3"  style="width:300px; background:#FFFF99; font-size:12px; border:1; text-transform:uppercase" readonly="readonly"></textarea>                </td>
                <td width="35" align="center">&nbsp;</td>
            </tr>
        </table>    </td>
<td>&nbsp;</td>
  </tr>
  <tr>
    <td height="26">&nbsp;</td>
    <td colspan="2" align="center">
    	<table>
        	<tr>
            	<td id="btnnuevo" onclick="nuevo()" style="display:<?=$comentariovisible?>"><div class="ebtn_nuevo"></div></td>
                <td id="btnguardar" style="display:none" onclick="guardarComentario()"><div class="ebtn_guardar"></div></td>
                <td id="btncancelar" style="display:none" onclick="cancelar()"><div class="ebtn_cancelar"></div></td>
            </tr>
        </table>    </td>
<td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>        </td>
    </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 617px; visibility:hidden; height: 260px;" id="canvas2">
<table width="617" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
	<tr>
    	<td height="13">
        <table width="567" border="0" cellpadding="0" cellspacing="0">
  <tr>
            	<td width="6" height="8"></td>
            <td width="142"></td>
                <td width="367"></td>
                <td width="52"></td>
            </tr>
  
  <tr>
    <td height="18" id="datos" align="center" style="text-transform:uppercase"></td>
    <td height="18" id="datos" align="left">Persona que recibe:</td>
    <td height="18" id="datos" align="left" style="text-transform:uppercase"><?=$f->recibio?></td>
    <td height="18" id="datos" align="left" style="text-transform:uppercase">&nbsp;</td>
  </tr>
  <tr>
    <td height="21"></td>
    <td align="left">Tipo de identificaci&oacute;n:</td>
    <td align="left"><?=$f->tipoidentificacion?></td>
  <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="20"></td>
    <td align="left">N&uacute;mero de Identificaci&oacute;n:</td>
    <td align="left"><?=$f->numeroidentificacion?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="17"></td>
    <td colspan="3" align="center">Firma electrónica</td>
    </tr>
  <tr>
    <td height="17"></td>
    <td colspan="3" align="center">
    	<table>
        	<tr>
            	<td><img src="paraimagenes.php?tipo=<?=$_GET[tipo]?>&guia=<?=$_GET[folio]?>" width="190px" height="190px"></td>
            </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td height="5"></td>
    <td colspan="3" align="center"></td>
  </tr>
        </table>        </td>
    </tr>
</table>
</div>
</form>
</body>
<script>
	var tabla2 = new ClaseTabla();
	
	tabla2.setAttributes({
		nombre:"listacomentarios",
		campos:[
			{nombre:"HORA", medida:40, alineacion:"center", datos:"hora"},
			{nombre:"FECHA", medida:90, alineacion:"center", datos:"fecha"},
			{nombre:"COMENTO", medida:200, alineacion:"left", datos:"comentario"},
			{nombre:"EMPLEADO", medida:200, alineacion:"left", datos:"empleado"}
		],
		filasInicial:9,
		alto:115,
		seleccion:true,
		eventoClickFila:"mostrarComentario()",
		ordenable:true,
		nombrevar:"tabla2"
	});
	
	var tabla1 		= new ClaseTabla();
	var mens = new ClaseMensajes();
	var	u		= document.all;
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"FECHA", medida:60, alineacion:"left", datos:"fecha"},
			{nombre:"HORA", medida:40, alineacion:"left",  datos:"hora"},
			{nombre:"UBICACION", medida:60,alineacion:"left",  datos:"ubicacion"},			
			{nombre:"EVENTO REALIZADO", medida:180,alineacion:"left", datos:"evento"},	
			{nombre:"UNIDAD", medida:50,alineacion:"left", datos:"unidad"},
			{nombre:"EMPLEADO", medida:70,alineacion:"right", datos:"empleado"},
			{nombre:"BIT", medida:20,alineacion:"right", datos:"bitacora"},
			{nombre:"EMB", medida:20,alineacion:"right", datos:"embarque"},
			{nombre:"MOTIVO", medida:60,alineacion:"left",  datos:"motivodevolucion"}
		],
		filasInicial:9,
		alto:100,
		seleccion:true,
		eventoClickFila:"mostrarComentario()",
		ordenable:true,
		nombrevar:"tabla1"
	});
	
	function mostrardetalle(datos){	
		if (datos!=0) {
				tabla1.clear();
				var objeto = eval(convertirValoresJson(datos));
				for(var i=0;i<objeto.length;i++){
					var obj		 	   		= new Object();
					obj.fecha 				= objeto[i].fecha;
					obj.hora 				= objeto[i].hora;
					obj.ubicacion	 		= objeto[i].ubicacion;
					obj.evento	 			= objeto[i].evento;
					obj.unidad	 			= objeto[i].unidad;
					obj.empleado	 		= objeto[i].empleado;
					obj.bitacora 			= (objeto[i].bitacora==0)?'':objeto[i].bitacora;
					obj.embarque	 		= (objeto[i].embarque==0)?'':objeto[i].embarque;
					obj.motivodevolucion 	= objeto[i].motivodevolucion;
					obj.estatus	 			= objeto[i].estatus;
					tabla1.add(obj);
				}	
			}
		}

	window.onload = function (){
		mens.iniciar("../javascript");
		seleccionarTabs(0);
		consultaTexto('mostrarComentarios', 'informacionextra_con.php?accion=1&folio=<?=$_GET[folio]?>');
		tabla1.create();
		tabla2.create();
		mostrardetalle('<?=$datos ?>');
	}
	
	function mostrarComentarios(datos){
		var objeto = eval(convertirValoresJson(datos));
		tabla2.setJsonData(objeto);
	}
	
	function seleccionarTabs(seleccion){
		var totaltabs 	= 3;
		var estilosel 	= "tab_seleccionado";
		var estilodesel = "tab_deseleccionado";
		var tabs		= "tab";
		var canvas		= "canvas";
		
		for(var i=0; i<totaltabs; i++){
			if(seleccion==i){
				document.getElementById(tabs+i).className = estilosel;
			}else{
				document.getElementById(tabs+i).className = estilodesel;
			}
		}
		
		for(var i=0; i<totaltabs; i++){
			if(seleccion==i){
				document.getElementById(canvas+i).style.visibility = "visible";
			}else{
				document.getElementById(canvas+i).style.visibility = "hidden";
			}
		}
	}
	
	function nuevo(){
		document.getElementById("comentario").value = "";
		document.getElementById("datos").innerHTML = "";
		document.getElementById("comentario").readOnly=false;
		document.getElementById("comentario").style.backgroundColor="";
		
		document.getElementById("btnnuevo").style.display="none";
		document.getElementById("btnguardar").style.display="";
		document.getElementById("btncancelar").style.display="";
	}
	
	function mostrarComentario(){
		if(document.getElementById("comentario").readOnly==true){
			if(tabla2.getSelectedRow()!=undefined){
				var fila = tabla2.getSelectedRow();
				document.getElementById("comentario").value = fila.comentario;
				document.getElementById("datos").innerHTML = "FECHA: "+fila.fecha + "&nbsp;&nbsp;&nbsp;EMPLEADO: "+fila.empleado;
			}else{
				document.getElementById("comentario").value = "";
				document.getElementById("datos").innerHTML = "";
			}
		}
	}
	
	function guardarComentario(){
		if(document.getElementById("comentario").value != ""){
			consultaTexto("resGuardarComentario", "informacionextra_con.php?accion=2&folio=<?=$_GET[folio]?>&comentario="+document.getElementById("comentario").value);
		}else{
			mens.show("A", "Proporcione algun comentario para guardar","!Atencion¡","comentario");
		}
	}
	
	function resGuardarComentario(datos){
		if(datos.indexOf("guardo")>-1){
			document.getElementById("comentario").value="";
			document.getElementById("comentario").readOnly=true;
			document.getElementById("comentario").style.backgroundColor="#FFFF99";
			
			document.getElementById("btnnuevo").style.display="";
			document.getElementById("btnguardar").style.display="none";
			document.getElementById("btncancelar").style.display="none";
			consultaTexto('mostrarComentarios', 'informacionextra_con.php?accion=1&folio=<?=$_GET[folio]?>');
		}else{
			document.getElementById("comentario").value=datos;
		}
	}
	
	function cancelar(){
		document.getElementById("comentario").readOnly=true;
		document.getElementById("comentario").style.backgroundColor="#FFFF99";
		
		document.getElementById("btnnuevo").style.display="";
		document.getElementById("btnguardar").style.display="none";
		document.getElementById("btncancelar").style.display="none";	
	}
</script>
</html>