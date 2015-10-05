<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$sucursal=$_SESSION[IDSUCURSAL];
	
	$s = "SELECT diasvencimientoconvenio FROM configuradorgeneral";
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
	
	$s = "SELECT folio AS convenio,idcliente,CONCAT(nombre,' ',apaterno,' ',amaterno)AS cliente,
	CONCAT(calle,' ',numero,' ',colonia,' ',poblacion) AS direccion,
	DATE_FORMAT(vigencia,'%d/%m/%Y') AS fechavencimiento,
	0 AS tipoconvenio,0 AS precios,ifnull(nvendedor,'') AS vendedorasignado FROM generacionconvenio 
	WHERE sucursal='" .$_SESSION[IDSUCURSAL]."' AND
	DATEDIFF(vigencia,CURDATE()) <= '".$f->diasvencimientoconvenio."'
	ORDER BY nombre,apaterno,amaterno";
		
		$p = mysql_query($s,$l) or die(mysql_error($l).$s);
		$tdes = mysql_num_rows($p);
		
		$r = mysql_query($s." LIMIT 0,30",$l) or die(mysql_error($l).$s);		
		
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
			
				$f->cliente = cambio_texto($f->cliente);
				$f->direccion = cambio_texto($f->direccion);
				$f->vendedorasignado = cambio_texto($f->vendedorasignado);
				
				$sql="SELECT precioporkg,precioporcaja,descuentosobreflete,
							prepagadas,consignacionkg,consignacioncaja,
							consignaciondescuento FROM generacionconvenio WHERE folio='$f->convenio'";
						$d = mysql_query($sql,$l) or die($sql);
						if(mysql_num_rows($d)>0){
							$t = mysql_fetch_object($d);
							$conbinacion=0;
							if ($t->precioporkg!=0){
								$conbinacion1='KILOGRAMO'; 
							}
							
							if ($t->precioporcaja!=0){
								$conbinacion2='PAQUETE'; 
							}
							
							if ($t->descuentosobreflete!=0){
								$conbinacion3='DESCUENTO'; 
							}
							
							if ($t->prepagadas!=0){
								$conbinacion4='PREPAGADAS'; 
							}
							
							if ($t->consignacionkg!=0){
								$conbinacion5='KILOGRAMO'; 
							}
							
							if ($t->consignacioncaja!=0){
								$conbinacion6='PAQUETE'; 
							}
							
							if ($t->consignaciondescuento!=0){
								$conbinacion7='DESCUENTO'; 
							}
							if ($conbinacion1!="" or $conbinacion2!="" or $conbinacion3!=""){
								$conbinacion8='GUIA NORMAL-'.$conbinacion1.$conbinacion2.$conbinacion3;	
							}
							
							if ($conbinacion4!=""  or $conbinacion5!="" or $conbinacion6!="" or $conbinacion7!=""){
								$conbinacion9='GUIA EMPRESARIAL-'.$conbinacion4.$conbinacion5.$conbinacion6.$conbinacion7;		
							}
							
							$f->tipoconvenio=$conbinacion8.$conbinacion9;
						}
				
				$registros[] = $f;
				}
				$datos= str_replace('null','""',json_encode($registros));
		}else{
				$datos= "no encontro";
			}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar2.css?random=20051112"></LINK>
<SCRIPT type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar2.js?random=20060118"></script>
<script src="../javascript/ClaseTabla.js"></script>
<link href="../estilos_estandar.css" />
<script src="../javascript/ajax.js"></script>
<script language="javascript" src="../javascript/funcionesDrag.js"></script>
<script language="javascript" src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/shortcut.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script>
	var tabla1 		= new ClaseTabla();
	var	u			= document.all;
	var mens 		= new ClaseMensajes();	
	var inicio		= 30;
	var sepasods	= 0;
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"# CONVENIO", medida:60, alineacion:"left",  datos:"convenio"},
			{nombre:"IDCLIENTE", medida:4,tipo:"oculto", alineacion:"left",  datos:"idcliente"},
			{nombre:"CLIENTE", medida:100, alineacion:"left",  datos:"cliente"},
			{nombre:"DIRECCION", medida:100, alineacion:"left", datos:"direccion"},
			{nombre:"FECHA DE VENCIMIENTO", medida:130, alineacion:"center", datos:"fechavencimiento"},
			{nombre:"TIPO CONVENIO", medida:70, alineacion:"left", datos:"tipoconvenio"},
			{nombre:"PRECIOS", medida:100, tipo:"moneda",alineacion:"center", onDblClick:"obtenerPrecioPaquete",  datos:"precios"},
			{nombre:"VENDEDOR ASIGNADO", medida:100, alineacion:"left", datos:"vendedorasignado"}			
		],
		filasInicial:20,
		alto:280,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		mostrardetalle('<?=$datos ?>');
		u.d_atrasdes.style.visibility = "hidden";
		if(parseInt(u.mostrardes2.value) <= 30){
			u.d_sigdes.style.visibility  = "hidden";
		}
	}
	
	function mostrardetalle(datos){
		if (datos.indexOf("no encontro")<0) {
			var obj = eval(datos);
			tabla1.setJsonData(obj);
		}		
	}
	
	function obtenerPrecioPaquete(){
		var obj = tabla1.getSelectedRow();
		abrirVentanaFija("../general/clientes/mostrarDesglozeConvenio.php?&cliente="+obj.idcliente,580, 300, 'ventana', 'DESGLOZE CONVENIO');
	}
	
	function mostrarDescuento(tipo){
		if(tipo == "atras"){
			u.d_sigdes.style.visibility = "visible";
			u.totaldes.value = parseFloat(u.totaldes.value) - inicio;
			if(parseFloat(u.totaldes.value) <= "1"){				
				u.totaldes.value = "01";
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
				u.d_atrasdes.style.visibility = "hidden";
				
			consultaTexto("mostrardetalle","consultasAlertas.php?accion=6&sucursal="+u.sucursal.value+"&inicio=0");
		
			}else{
				if(sepasods!=0){
					u.mostrardes.value = sepasods;
					sepasods = 0;
				}
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
			consultaTexto("mostrardetalle","consultasAlertas.php?accion=6&sucursal="+u.sucursal.value+"&inicio="+u.totaldes.value);
			}			
		}else{
			u.d_atrasdes.style.visibility = "visible";
			u.totaldes.value = inicio + parseFloat(u.totaldes.value);		
			if(parseFloat(u.totaldes.value) > parseFloat(u.contadordes.value)){
				
				u.totaldes.value = parseFloat(u.totaldes.value) - inicio;
				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;
				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){
					u.mostrardes.value = u.contadordes.value;
				}
				u.d_sigdes.style.visibility = "hidden";
			}else{
				
				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;
				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){
					sepasods	=	u.mostrardes.value;
					u.mostrardes.value = u.contadordes.value;
				}
				consultaTexto("mostrardetalle","consultasAlertas.php?accion=6&sucursal="+u.sucursal.value+"&inicio="+u.totaldes.value);
			}			
		}	
	}

</script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
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
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="710" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="700" class="FondoTabla Estilo4">Convenios Por Vencer</td>
    </tr>
<tr>
      <td><table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><div id="txtDir" style=" height:300px; width:700px; overflow:auto" align="left">
            <table width="305" id="detalle" border="0" cellpadding="0" cellspacing="0">
            </table>
          </div></td>
        </tr>
<tr>
          <td><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="107" align="right"><div id="d_atrasdes" class="ebtn_atraz" onclick="mostrarDescuento('atras');"></div></td>
              <td width="302" align="center"><strong><span class="Estilo4">
                <input name="sucursal" type="hidden" class="Tablas" id="sucursal" style="width:100px" value="<?=$sucursal ?>"  />
                <span class="Tablas">
                <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />
                <input name="totaldes" type="hidden" id="totaldes" value="1" />
                </span></span>&nbsp;
                    <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" style="width:70px; text-align:center; border:none" value="30" />
                    <strong><strong>
                    <input name="mostrardes2" class="Tablas" type="text" id="mostrardes2" style="width:100px; text-align:center; background-color:#FFFF99;" value="<?=$tdes; ?>" />
                  </strong><span style="color:#FF0000"></span></strong></strong></td>
              <td width="91" align="left"><div id="d_sigdes" <? if($tdes=="0" || $tdes==""){ echo 'style="visibility:hidden"';} ?> class="ebtn_adelante" onclick="mostrarDescuento('adelante');"></div></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
