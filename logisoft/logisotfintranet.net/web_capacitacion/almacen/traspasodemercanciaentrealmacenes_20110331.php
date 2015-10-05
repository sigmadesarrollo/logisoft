<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}

	include('../Conectar.php');	
	$link=Conectarse('webpmm');
	
	$accion=$_POST['accion'];
	$almacenorigen=$_POST['almacenorigen'];
	$almacendestino=$_POST['almacendestino'];
	$numeroguia=$_POST['numeroguia'];
	$registros=$_POST['registros'];
$fecha = $_POST['fecha'];
if($fecha == ""){
	$fecha = date("d/m/Y");
}


if($_POST[accion]=="grabar"){
	$fecha = cambiaf_a_mysql($fecha);
	#FOLIOS DE FACTURAS
	$foliosfacturas = "";
	$clientesfacturas = "";
	
	//INSERTAR TABLA DETALLE
	for($i=0;$i<$registros;$i++){
		if($_POST['tabladetalle_Almacen_Destino'][$i]=='ALMACEN OCURRE'){$valor='1';}else{$valor='0';}
		
		if(substr($_POST["tabladetalle_No_Guia"][$i],0,3)=="999"){
			$s = "SELECT cs.servicio, cs.idservicio, css.nombre, css.clave AS idsucursal, cs.tipo, css.tipo
			FROM generacionconvenio gc
			INNER JOIN cconvenio_servicios cs ON gc.folio = cs.idconvenio
			INNER JOIN cconvenio_servicios_sucursales css ON gc.folio = css.idconvenio
			WHERE gc.folio = (SELECT convenioaplicado FROM guiasventanilla WHERE id = '".$_POST["tabladetalle_No_Guia"][$i]."') 
			AND cs.tipo = 'CONSIGNACION' AND css.tipo = 'SUCONSIGNACION2' AND (css.clave = 0 || css.clave = '$_SESSION[IDSUCURSAL]')
			AND cs.idservicio  = 7";
			//echo $s."<br><br>";
			$r = mysql_query($s,$link) or die($s);
			if(mysql_num_rows($r)>0){
				$cobrar = 'no';
			}else{
				$cobrar = 'si';
				$tipoguia = 'E';
			}
		}else{
			$s = "SELECT cs.servicio, cs.idservicio, css.nombre, css.clave AS idsucursal, cs.tipo, css.tipo
			FROM generacionconvenio gc
			INNER JOIN cconvenio_servicios cs ON gc.folio = cs.idconvenio
			INNER JOIN cconvenio_servicios_sucursales css ON gc.folio = css.idconvenio
			WHERE gc.folio = (SELECT convenioaplicado FROM guiasventanilla WHERE id = '".$_POST["tabladetalle_No_Guia"][$i]."') 
			AND cs.tipo = 'CONVENIO' AND css.tipo = 'SUCONVENIO' AND (css.clave = 0 || css.clave = '$_SESSION[IDSUCURSAL]')
			AND cs.idservicio  = 7";
			//echo $s."<br><br>";
			$r = mysql_query($s,$link) or die($s);
			if(mysql_num_rows($r)>0){
				$cobrar = 'no';
			}else{
				$cobrar = 'si';
				$tipoguia = 'V';
			}
		}
		
		if(substr($_POST["tabladetalle_No_Guia"][$i],0,3)=="777"){
			$cobrar='no';
		}
		//echo "$valor - $cobrar<br><br>";
		
		if($valor==0 && $cobrar=='si'){		
			
			$s = "SELECT SUM(total) AS ead FROM (
				(SELECT cd.restringiread AS total
				FROM catalogodestino AS cd
				INNER JOIN guiasventanilla gv ON cd.id = gv.iddestino				
				WHERE gv.id = '".$_POST["tabladetalle_No_Guia"][$i]."')
				UNION
				(SELECT cd.restringiread AS total
				FROM catalogodestino AS cd
				INNER JOIN guiasempresariales ge ON cd.id = ge.iddestino
				WHERE ge.id = '".$_POST["tabladetalle_No_Guia"][$i]."')
			) AS t1";
			//echo $s."<br><br>";
			$r = mysql_query($s,$link) or die($s);
			$f = mysql_fetch_object($r);
			if($f->ead==0){
				
				$s = "SELECT cc.id, CONCAT_WS(' ',cc.nombre, cc.paterno, cc.materno) AS clientefacturacion, 
				cc.rfc, di.calle, di.numero, di.codigo, di.colonia, di.municipio, di.estado, 
				di.pais, IF(0.10*gv.tflete<cd.costoead,cd.costoead,0.10*gv.tflete) AS importe, cs.iva, 
				(cs.iva/100)*IF(0.10*gv.tflete<cd.costoead,cd.costoead,0.10*gv.tflete) as civa,
				if(cc.personamoral='SI',(cg.ivaretenido/100)*IF(0.10*gv.tflete<cd.costoead,cd.costoead,0.10*gv.tflete),0) as civar
				FROM catalogocliente AS cc
				INNER JOIN direccion AS di ON cc.id = di.codigo
				INNER JOIN configuradorgeneral AS cg
				INNER JOIN guiasventanilla AS gv ON '".$_POST["tabladetalle_No_Guia"][$i]."' = gv.id 
					AND cc.id = IF(gv.tipoflete = 0, gv.idremitente, gv.iddestinatario)
					AND di.id = IF(gv.tipoflete = 0, gv.iddireccionremitente, gv.iddirecciondestinatario)
				INNER JOIN catalogosucursal AS cs ON gv.idsucursaldestino = cs.id
				INNER JOIN catalogodestino cd ON gv.iddestino = cd.id";
				$rdfe = mysql_query($s,$link) or die($s);
				$fdfw = mysql_fetch_object($rdfe);
				
				
				$importeead = $fdfw->importe;
				$ivaead		= $fdfw->civa;
				$ivar		= $fdfw->civar;
				$importetotal = $importeead+$ivaead-$ivar;
				
				$s = "SELECT IF(IFNULL(sc.montoautorizado,0) <= (IFNULL(SUM(pg.total),0)+$importetotal) OR cc.activado='NO','NO','SI') AS cambiable
				FROM solicitudcredito sc 
				INNER JOIN catalogocliente cc ON sc.cliente = cc.id 
				LEFT JOIN pagoguias pg ON sc.cliente = pg.cliente
				WHERE sc.cliente = $fdfw->id AND pg.pagado = 'N'";
				$rpc = mysql_query($s,$link) or die($s);
				if(mysql_num_rows($rpc)>0){
					$fpc = mysql_fetch_object($rpc);
					$credito = $fpc->cambiable;
				}else{
					$credito = "NO";
				}
				
				//echo "$s<br><br>$credito<br><br>";
				
				
				$datospagina = "data[informacion][rfc]=$fdfw->rfc
				&data[informacion][name]=$fdfw->clientefacturacion
				&data[informacion][street]=$fdfw->calle
				&data[informacion][outside_number]=$fdfw->numero
				&data[informacion][col]=$fdfw->colonia
				&data[informacion][cp]=$fdfw->codigo
				&data[informacion][municipio]=$fdfw->municipio
				&data[informacion][state]=$fdfw->estado
				&data[informacion][country]=$fdfw->pais
				&data[producto][1][preciounitario]=$fdfw->importe
				&data[producto][1][descripcion]=TRASPASO OCURRE A EAD DE GUIA ".$_POST["tabladetalle_No_Guia"][$i]."
				&data[producto][1][cantidad]=1
				&data[producto][1][importe]=$fdfw->importe
				&data[Impuestos][totalImpuestosTrasladados]=0
				&data[Impuestos][tasa]=$fdfw->iva
				&data[Impuestos][iva]=$fdfw->civa
				&data[Impuestos][subtotal]=$fdfw->importe
				&data[Impuestos][total]=".($fdfw->importe+$fdfw->civa);
				
				
				$ch = curl_init("http://pmm.comprobantesdigitales.com.mx/invoices/remote/136f43d234b5c17c34cbd7c7367cd93a");
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $datospagina);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);       
				curl_close($ch);
				
				$arre = split("~",$output);
				
				if($tipoguia=='V'){
					$s = "insert into facturacion (idsucursal, facturaestado, credito, cliente, nombrecliente, apellidopaternocliente,
					apellidomaternocliente, rfc, calle, numero, codigopostal, colonia, crucecalles, poblacion, municipio, estado,
					pais, telefono, fax, otroscantidad, otrosdescripcion, otrosimporte, 
					otrossubtotal, otrosiva, otrosivaretenido, otrosmontofacturar, 
					usuario, idusuario, fecha, ivacobrado, ivarcobrado, personamoral,
					estadocobranza,xml,cadenaoriginal)
					select '$_SESSION[IDSUCURSAL]','GUARDADO', '$credito', cc.id,cc.nombre, cc.paterno,
					cc.materno, cc.rfc, di.calle, di.numero, di.codigo, di.colonia, di.crucecalles, di.poblacion, di.municipio, di.estado,
					di.pais, di.telefono, di.fax, 1, 'TRASPASO OCURRE A EAD DE GUIA ".$_POST["tabladetalle_No_Guia"][$i]."', $importeead, 
					$importeead, (cs.iva/100)*$importeead, if(cc.personamoral='SI',(cg.ivaretenido/100)*$importeead,0), 
					$importeead+((cs.iva/100)*$importeead)-if(cc.personamoral='SI',(cg.ivaretenido/100)*$importeead,0),
					'$_SESSION[NOMBREUSUARIO]','$_SESSION[IDUSUARIO]',current_date, cs.iva, if(cc.personamoral='SI',cg.ivaretenido,0), 
					if(cc.personamoral='SI','SI','NO'),
					'".(($credito=='SI')?'N':'C')."','".html_entity_decode($arre[0])."','".html_entity_decode($arre[1])."'
					from catalogocliente as cc
					inner join direccion as di on cc.id = di.codigo
					inner join configuradorgeneral as cg
					inner join guiasventanilla as gv on '".$_POST["tabladetalle_No_Guia"][$i]."' = gv.id 
						and cc.id = gv.iddestinatario
						AND di.id = gv.iddirecciondestinatario
					inner join catalogosucursal as cs on gv.idsucursaldestino = cs.id";
				}else{
					$s = "INSERT INTO facturacion (idsucursal, facturaestado, credito, cliente, nombrecliente, apellidopaternocliente,
					apellidomaternocliente, rfc, calle, numero, codigopostal, colonia, crucecalles, poblacion, municipio, estado,
					pais, telefono, fax, otroscantidad, otrosdescripcion, otrosimporte, 
					otrossubtotal, otrosiva, otrosivaretenido, otrosmontofacturar, 
					usuario, idusuario, fecha, ivacobrado, ivarcobrado, personamoral,
					estadocobranza,xml,cadenaoriginal)
					SELECT '$_SESSION[IDSUCURSAL]','GUARDADO', '$credito', cc.id,cc.nombre, cc.paterno,
					cc.materno, cc.rfc, di.calle, di.numero, di.codigo, di.colonia, di.crucecalles, di.poblacion, di.municipio, di.estado,
					di.pais, di.telefono, di.fax, 1, 'TRASPASO OCURRE A EAD DE GUIA ".$_POST["tabladetalle_No_Guia"][$i]."', $importeead, 
					$importeead, (cs.iva/100)*$importeead, if(cc.personamoral='SI',(cg.ivaretenido/100)*$importeead,0), 
					$importeead+((cs.iva/100)*$importeead)-if(cc.personamoral='SI',(cg.ivaretenido/100)*$importeead,0),
					'$_SESSION[NOMBREUSUARIO]','$_SESSION[IDUSUARIO]',CURRENT_DATE, cs.iva, IF(cc.personamoral='SI',cg.ivaretenido,0), 
					IF(cc.personamoral='SI','SI','NO'),
					'".(($credito=='SI')?'N':'C')."','".html_entity_decode($arre[0])."', '".html_entity_decode($arre[1])."'
					FROM catalogocliente AS cc
					INNER JOIN direccion AS di ON cc.id = di.codigo
					INNER JOIN configuradorgeneral AS cg
					INNER JOIN guiasempresariales AS gv ON '".$_POST["tabladetalle_No_Guia"][$i]."' = gv.id 
					AND cc.id = gv.idremitente 
					AND di.id = gv.iddirecciondestinatario
					INNER JOIN catalogosucursal AS cs ON gv.idsucursaldestino = cs.id";
				}
				//echo $s."<br><br>";
				mysql_query($s,$link) or die($s);
				$nfact = mysql_insert_id($link);
				
				if($credito!='SI'){
					$s = "INSERT INTO facturacion_fechapago (factura,fechapago)
					SELECT '$nfact', CURRENT_DATE;";
					mysql_query($s,$link) or die($s);
				}
				#Guardar las facturas para mostrar lista de los generados
				$s = "SELECT CONCAT_WS(' ',nombrecliente, apellidopaternocliente, apellidomaternocliente) AS ncliente
				FROM facturacion WHERE folio = $nfact";
				$r = mysql_query($s,$link) or die($s);
				$f = mysql_fetch_object($r);
				$foliosfacturas .= (($foliosfacturas!='')?",":"").$nfact;
				$clientesfacturas .= (($clientesfacturas!='')?",":"").$f->ncliente;
				
				#insertat venta facturacion otros
				$s = "call proc_RegistroVentas('VENTA_OTROS','$nfact',0)";
				$r = @mysql_query($s,$link) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
					<datos>
					<guardado>0</guardado>
					<consulta>".str_replace("''","null",$s)."</consulta>
					</datos>
					</xml>");
				
				#hacer consulta para traer el cliente				
				$s = "select cliente, credito from facturacion where folio = $nfact";
				$rm = mysql_query($s,$link) or die($s);
				$fm = mysql_fetch_object($rm);
				
				$s = "CALL proc_RegistroClientes('facturacion',".$fm->cliente.",0,".$nfact.",0)";
				$r = @mysql_query($s,$link) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
					<datos>
					<guardado>0</guardado>
					<consulta>".str_replace("''","null",$s)."</consulta>
					</datos>
					</xml>");
				#si es credito registrar en cobranza
				if($fm->credito=="SI"){
					$s = "CALL proc_RegistroCobranza('FACTURA', $nfact, '', '', 0, 0);";
					$r = @mysql_query($s,$link) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
						<datos>
						<guardado>0</guardado>
						<consulta>".str_replace("''","null",$s)."</consulta>
						</datos>
						</xml>");
				}
				
				
				if($tipoguia=='V'){
					$s = "select (cs.porcead/100)*gv.tflete as flete, if(gv.tipoflete = 0, gv.idsucursalorigen, gv.idsucursaldestino) as sucursalacobrar,
					cc.activado, if(gv.tipoflete = 0, gv.idremitente, gv.iddestinatario) as cliente
					from catalogocliente as cc
					inner join direccion as di on cc.id = di.codigo
					inner join configuradorgeneral as cg
					inner join guiasventanilla as gv on '".$_POST["tabladetalle_No_Guia"][$i]."' = gv.id 
						and cc.id = if(gv.tipoflete = 0, gv.idremitente, gv.iddestinatario)
					inner join catalogosucursal as cs on gv.idsucursaldestino = cs.id";
				}else{
					$s = "SELECT (cs.porcead/100)*gv.tflete AS flete, IF(gv.tipoflete = 0, gv.idsucursalorigen, gv.idsucursaldestino) AS sucursalacobrar,
					cc.activado, IF(gv.tipoflete = 'POR COBRAR', gv.iddestinatario, gv.idremitente) AS cliente
					FROM catalogocliente AS cc
					INNER JOIN direccion AS di ON cc.id = di.codigo
					INNER JOIN configuradorgeneral AS cg
					INNER JOIN guiasempresariales AS gv ON '".$_POST["tabladetalle_No_Guia"][$i]."' = gv.id 
						AND cc.id = IF(gv.tipoflete = 'POR COBRAR', gv.iddestinatario, gv.idremitente)
					INNER JOIN catalogosucursal AS cs ON gv.idsucursaldestino = cs.id";
				}
				//echo $s."<br><br>";
				$r = mysql_query($s,$link) or die($s);
				$f = mysql_fetch_object($r);
				
				if($credito=="SI"){
					$s = "INSERT INTO pagoguias SET guia = '$nfact', tipo='FACT', total='$importetotal', 
					fechacreo = CURRENT_DATE, usuariocreo = $_SESSION[IDUSUARIO], sucursalcreo = $_SESSION[IDSUCURSAL], 
					cliente = '$f->cliente', credito='SI',
					sucursalacobrar = '$f->sucursalacobrar', pagado='N'";
					$r = @mysql_query(str_replace("''","null",$s),$link) or die($s);
				}else{
					$s = "INSERT INTO formapago SET guia='$nfact',procedencia='F',tipo='O',
					total='$importetotal',efectivo='$importetotal',
					tarjeta='0',transferencia='0',cheque='0',
					ncheque='0',banco='0',notacredito='0', cliente = '$f->cliente',
					nnotacredito='',sucursal='$_SESSION[IDSUCURSAL]',usuario='$_SESSION[IDUSUARIO]',fecha=current_date";
					mysql_query(str_replace("''","null",$s),$link) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
							<datos>
							<guardado>0</guardado>
							<consulta>$s</consulta>
							</datos>
							</xml>");
					
					$s = "INSERT INTO pagoguias SET guia = '$nfact', tipo='FACT', total='$importetotal', 
					fechacreo = CURRENT_DATE, usuariocreo = $_SESSION[IDUSUARIO], sucursalcreo = $_SESSION[IDSUCURSAL], 
					cliente = '$f->cliente', credito='NO', sucursalacobrar = '$_SESSION[IDSUCURSAL]', pagado='S',
					fechapago = CURRENT_DATE, usuariocobro = $_SESSION[IDUSUARIO], sucursalcobro = $_SESSION[IDSUCURSAL]";
					$r = @mysql_query(str_replace("''","null",$s),$link) or die($s);
				}
				
			}
		}
		$sqlins=mysql_query("UPDATE guiasventanilla  SET ocurre = '".$valor."'
							WHERE id = '".$_POST["tabladetalle_No_Guia"][$i]."'",$link)or die("error en linea ".__LINE__);
		$sqlins=mysql_query("UPDATE guiasempresariales  SET ocurre = '".$valor."'
							WHERE id = '".$_POST["tabladetalle_No_Guia"][$i]."'",$link)or die("error en linea ".__LINE__);
		$detalle .= "{
				guia:'".$_POST["tabladetalle_No_Guia"][$i]."',
				origen:'".$_POST["tabladetalle_Almacen_Origen"][$i]."',
				destino:'".$_POST["tabladetalle_Almacen_Destino"][$i]."'},";
	}
	$detalle = substr($detalle,0,strlen($detalle)-1);
	$fecha = cambiaf_a_normal($fecha);
	$mensaje	="Los datos han sido guardados correctamente";
	$accion		="modificar";
}else if($_POST[accion]=="limpiar"){
	$fecha	 		="";
	$almacenorigen	="";
	$almacendestino	="";
	$numeroguia		="";
	$accion  		="";

	$fecha = $_POST['fecha'];
	if($fecha == ""){
		$fecha = date("d/m/Y");
	}
}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
	
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>



<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>


<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js" language="javascript"></script>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script src="../javascript/shortcut.js"></script>

<script>
var tabla1 = new ClaseTabla();
var tabla2 = new ClaseTabla();
var u = document.all;
	
	tabla1.setAttributes({
		nombre:"tabladetalle",
		campos:[
			{nombre:"No_Guia", medida:80, alineacion:"left", datos:"guia"},
			{nombre:"Almacen_Origen", medida:120, alineacion:"left", datos:"origen"},
			{nombre:"Almacen_Destino", medida:120, alineacion:"left", datos:"destino"}
			
		],
		filasInicial:10,
		alto:100,
		seleccion:true,
		ordenable:true,
		eventoClickFila:"document.all.eliminar.value=tabla1.getSelectedIdRow();",
		eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});
	
	tabla2.setAttributes({
		nombre:"tablafacturas",
		campos:[
			{nombre:"Factura", medida:80, alineacion:"left", datos:"factura"},
			{nombre:"Cliente", medida:120, alineacion:"left", datos:"cliente"}
			
		],
		filasInicial:10,
		alto:100,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla2"
	});
	
	window.onload = function(){
		tabla1.create();	
		  <?
		  	if($foliosfacturas!=""){
		  ?>	
			tabla2.create();
			var facturas = "<?=$foliosfacturas?>";
			var clientes = "<?=$clientesfacturas?>";
			if(facturas.indexOf(",")>-1){
				var arfacturas = facturas.split(",");
				var arclientes = clientes.split(",");
				
				var obj;
				
				for(var i=0; i<arfacturas.length; i++){
					obj = new Object();
					obj.factura = arfacturas[i];
					obj.cliente = arclientes[i];
					tabla2.add(obj);
				}
			}else{
				var obj = new Object();
				obj.factura = facturas;
				obj.cliente = clientes;
				tabla2.add(obj);
			}
		  <?
			}
		  ?>
		obtenerDetalles();
		
	}
	
	function obtenerDetalles(){
	var datosTabla = <? if($detalle!=""){echo "[".$detalle."]";}else{echo "0";} ?>;
		if(datosTabla!=0){			
			for(var i=0; i<datosTabla.length;i++){
				tabla1.add(datosTabla[i]);
			}
		}
	}
	
function EliminarFila(){
	if(document.all.eliminar.value!=""){
		if(tabla1.getValSelFromField("guia","No_Guia")!=""){
			tabla1.deleteById(document.all.eliminar.value);
		}
	}else{
		alerta('Seleccione la fila a eliminar','¡Atención!','tabladetalle');
	}
}


function ModificarFila(){
	var obj = tabla1.getSelectedRow();
	if(tabla1.getValSelFromField("guia","No_Guia")!=""){
		document.all.numeroguia.value		=obj.guia;
		document.all.almacenorigen.value	=obj.origen;
		document.all.almacendestino.value	=obj.destino;
		document.all.modificarfila.value	=tabla1.getSelectedIdRow();
	}
}

function agregarVar(){
	var u = document.all;
	BuscarIdGuia(u.numeroguia.value);
}

function validar(){
	<?=$cpermiso->verificarPermiso(317,$_SESSION[IDUSUARIO]);?>
	var u = document.all;
	u.registros.value = tabla1.getRecordCount();
	if(tabla1.getRecordCount()<=0 || tabla1.getRecordCount()==""){
			alerta('Debe agregar por lo menos una guia','¡Atención!','tabladetalle');
			return false;			
	}
		if(document.getElementById('accion').value==""){
			document.getElementById('accion').value = "grabar";
			document.form1.submit();
		}else if(document.getElementById('accion').value=="modificar"){
			document.form1.submit();
		}
}

function Limpiar(){
	document.getElementById('fecha').value="";
	document.getElementById('almacenorigen').value="";
	document.getElementById('almacendestino').value="";
	document.getElementById('numeroguia').value="";
	document.getElementById('accion').value = "limpiar";
	
	document.form1.submit();
	tabla1.clear();
}

function obtenerIdGuia(idguia){
	var u = document.all;
	u.numeroguia.value=idguia;
	u.hiddenguia.value=1;
}

	function tabular(e,obj) {
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


/********************/
	function BuscarIdGuia(folio){
		consultaTexto("mostrarBuscarIdGuia","traspasodemercanciaentrealmacenes_result.php?accion=1&guia="+folio
		+"&tipo="+((u.almacenorigen.value=="ALMACEN EAD")?0:1)+"&sid="+Math.random());				
	}
	function mostrarBuscarIdGuia(datos){		
		if(datos.indexOf("no encontro")<0){
			if(u.almacenorigen.value!="" && u.almacendestino.value!=""){
				var obj = new Object();
				obj.guia = u.numeroguia.value;
				obj.origen = u.almacenorigen.value;
				obj.destino = u.almacendestino.value;
				tabla1.add(obj);
			}
			var lguias = "";
			for(var i=0; i<tabla1.getRecordCount(); i++){
				lguias += ((lguias!="")?",":"")+u.numeroguia.value;
			}
			u.guiasseleccionadas.value = lguias;
		}else{
			alerta("La guia capturada no existe o no a llegado a su destino",'¡Atención!','numeroguia');
			u.numeroguia.value = "";
		}
	}
function foco(nombrecaja){
	if(nombrecaja=="numeroguia"){
		document.getElementById('oculto').value="1";
	}
}
shortcut.add("Ctrl+b",function() {
	if(document.form1.oculto.value=="1"){
		if(document.getElementById('almacenorigen').value=='ALMACEN EAD'){abrirVentanaFija('traspasodemercanciaentrealmacenes_buscar.php?tipo=1', 550, 450, 'ventana', 'Busqueda');} 
		else {abrirVentanaFija('traspasodemercanciaentrealmacenes_buscar.php?tipo=2', 550, 450, 'ventana', 'Busqueda');}
	}
});
</script>
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
-->
</style>

<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="3" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="357" class="FondoTabla Estilo4">TRASPASO DE MERCANC&Iacute;A ENTRE ALMACENES</td>
  </tr>
  <tr>
    <td><table width="400" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td width="102">&nbsp;</td>
            <td width="36"><span class="Tablas">Fecha</span></td>
            <td width="100" colspan="3"><span class="Tablas"> 
              <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
              </span></td>
          </tr>
          <tr> 
            <td colspan="7"><table width="360" border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td style="width:96px">Almac&eacute;n Origen </td>
                  <td ><span class="Tablas"> 
                    <select name="almacenorigen" class="Tablas" style="width:120px; font-size:9px" id="almacenorigen" onchange="document.all.hiddenguia.value='';document.all.numeroguia.value=''" >
                      <option></option>
                      <option value="ALMACEN EAD">ALMACEN EAD</option>
                      <option value="ALMACEN OCURRE">ALMACEN OCURRE</option>
                    </select>
                    </span></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td colspan="7"><table width="360" border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td width="96">Almac&eacute;n Destino </td>
                  <td width="194"><span class="Tablas"> 
                    <select name="almacendestino" class="Tablas" style="width:120px; font-size:9px" id="almacendestino" >
                      <option></option>
                      <option value="ALMACEN EAD">ALMACEN EAD</option>
                      <option value="ALMACEN OCURRE">ALMACEN OCURRE</option>
                    </select>
                    </span></td>
                  <td width="70"><div class="ebtn_agregar" onclick="agregarVar();"></div></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td width="62">No. Gu&iacute;a</td>
            <td  ><span class="Tablas"> 
              <input name="numeroguia" type="text" class="Tablas" id="numeroguia" style="width:100px"  onFocus="foco(this.name);" onBlur="document.getElementById('oculto').value=''" onchange="document.all.hiddenguia.value=''" onkeypress="if(event.keyCode==13){BuscarIdGuia(this.value)}" value="<?=$numeroguia ?>" maxlength="13"/>
              </span></td>
            <td colspan="3"><div class="ebtn_buscar" onclick="if(u.almacenorigen.value==u.almacendestino.value){alerta3('Seleccione Almacenes Diferentes'); return false;}abrirVentanaFija('traspasodemercanciaentrealmacenes_buscar.php?tipo='+((document.all.almacenorigen.value=='ALMACEN EAD')?0:1)+'&guias='+u.guiasseleccionadas.value, 550, 450, 'ventana', 'Busqueda');"></div><input type="hidden" name="guiasseleccionadas" value="" /></td>
          </tr>
          <tr> 
            <td colspan="7">
              <input name="hiddenguia" type="hidden" id="hiddenguia" />
            <br> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td width="85%"><table border="0" align="center" cellpadding="0" cellspacing="0" id="tabladetalle">
                    </table></td>
                  <td width="15%"><div class="ebtn_eliminar" onclick="EliminarFila();"></div></td>
                </tr>
              </table></td>
          </tr>
          <?
		  	if($foliosfacturas!=""){
		  ?>
          <tr>
          	<td colspan="7">FACTURAS GENERADAS</td>
          </tr>
          <tr>
          	<td colspan="7">
            	<table border="0" align="center" cellpadding="0" cellspacing="0" id="tablafacturas"></table>
            </td>
          </tr>
          <?
			}
		  ?>
          <tr> 
            <td colspan="7" align="right"><input name="registros" type="hidden" id="registros" />
              <input name="accion" type="hidden" id="accion" /> 
              <input name="oculto" type="hidden" id="oculto" /> <input name="eliminar" type="hidden" id="eliminar" /> 
              <input name="modificarfila" type="hidden" id="modificarfila" /> 
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="54%"></td>
                  <td width="46%"><table width="33%" border="0" align="right">
                    <tr>
                      <td width="24%"><div class="ebtn_guardar" onclick="validar();" <? if($accion!=""){echo "style='visibility:hidden'";} ?> ></div></td>
                      <td width="76%"><div class="ebtn_nuevo" onclick="Limpiar();"></div></td>
                    </tr>
                  </table></td>
                </tr>
              </table>
            </td>
          </table>
    </tr>
</table>
</form>
</body>
</html>
<?
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
	}
//	}
?>