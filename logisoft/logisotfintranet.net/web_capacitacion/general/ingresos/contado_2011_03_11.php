<? 	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');

$sql="SELECT sucursal,nombresucursal,fecha,guia,cliente,importe,caja FROM (
	(SELECT cs.id AS sucursal,cs.prefijo as nombresucursal,DATE_FORMAT(gv.fecha,'%d/%m/%Y')AS fecha,gv.id AS guia,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,gv.total AS importe,gv.idusuario AS caja FROM formapago fp
	INNER JOIN guiasventanilla gv ON fp.guia=gv.id
	INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal
	INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND fp.procedencia IN('G')
	AND isnull(fp.fechacancelacion)
	AND cs.id='" .$_GET[sucursal]."')
UNION ALL
	(SELECT cs.id AS sucursal,cs.prefijo as nombresucursal,gv.fecha,gv.id AS guia,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,gv.total AS importe,gv.idusuario AS caja FROM formapago fp
	INNER JOIN guiasempresariales gv ON fp.guia=gv.id
	INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal
	INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)		
	WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' AND 
	fp.procedencia IN('E')
	AND isnull(fp.fechacancelacion)
	AND cs.id='" .$_GET[sucursal]."')
UNION ALL
	(SELECT cs.id AS sucursal,cs.prefijo as nombresucursal,DATE_FORMAT(f.fecha,'%d/%m/%Y')AS fecha,concat('FA-',f.folio) AS guia,
CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
fp.total AS importe,f.idusuario AS caja
	FROM facturacion f
	INNER join formapago fp on f.folio = fp.guia and fp.procedencia = 'F'	
	INNER JOIN catalogosucursal cs ON cs.id=f.idsucursal
	INNER JOIN catalogocliente cc ON cc.id = f.cliente
	WHERE f.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND f.facturaestado='GUARDADO' and f.credito='NO'
	AND cs.id='" .$_GET[sucursal]."')
)Tabla ORDER BY fecha LIMIT 0,30";
	$r=mysql_query($sql,$l)or die($sql); 
	$tdes = mysql_num_rows($r);
	$registros= array();	
	
	$fechaini=$_GET[fecha];
	$fechafin=$_GET[fecha2];
	$idsucursal=$_GET[sucursal];
	$inicio=$_GET[inicio];
		
		if (mysql_num_rows($r)>0)
				{
				while ($f=mysql_fetch_object($r))
				{
					$f->cliente=cambio_texto($f->cliente);
					$f->nombresucursal=cambio_texto($f->nombresucursal);
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
<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Reporte Ventas</title>
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../Tablas.css" rel="stylesheet" type="text/css">
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
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
#form1 table tr td table tr td div {
	text-align: right;
}
#form1 table tr td #txtDir table tr td {
	text-align: center;
}
#form1 table tr td #txtDir table {
	text-align: center;
}
-->
</style>
<SCRIPT type="text/javascript" src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/funciones.js"></script>
<script language="javascript1.1" src="../../javascript/funcionesDrag.js"></script>
<script language="javascript1.1" src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../../javascript/ajaxlist/ajax.js"></script>
<script src="../../javascript/ClaseTabs.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script>
	var tabla1 		= new ClaseTabla();
	var	u		= document.all;
	var inicio		= 30;
	var sepasods	= 0;
	var mens = new ClaseMensajes();
	mens.iniciar('../../javascript',true);
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"SUCURSAL", medida:80, alineacion:"left", datos:"nombresucursal"},
			{nombre:"FECHA", medida:80, alineacion:"left",  datos:"fecha"},
			{nombre:"GUIA", medida:90, alineacion:"left",  datos:"guia"},
			{nombre:"CLIENTE", medida:250, alineacion:"left",  datos:"cliente"},			
			{nombre:"IMPORTE", medida:100, tipo:"moneda",alineacion:"right", datos:"importe"},
			{nombre:"CAJA", medida:50, alineacion:"center",  datos:"caja"}
		],
		filasInicial:30,
		alto:260,
		seleccion:true,
		ordenable:false,
		//eventoDblClickFila:"verRecoleccion()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		mostrardetalle('<?=$datos ?>');
		u.fecha.value='<?=$fechaini ?>' ;
		u.fecha2.value='<?=$fechafin ?>' ;
		u.sucursal.value='<?=$idsucursal ?>' ;
		u.d_atrasdes.style.visibility = "hidden";
		if(parseInt(u.mostrardes2.value) <= 30){
			u.d_sigdes.style.visibility  = "hidden";
		}
	}
	
	function mostrardetalle(datos){	
		if (datos!=0) {
			$total=0;
				tabla1.clear();
				var objeto = eval(convertirValoresJson(datos));
				for(var i=0;i<objeto.length;i++){
					var obj		 	   		= new Object();
					obj.nombresucursal 		= objeto[i].nombresucursal;
					obj.fecha	 			= objeto[i].fecha;
					obj.guia	 	   		= objeto[i].guia;
					obj.cliente   			= objeto[i].cliente;
					obj.importe				= objeto[i].importe;
					obj.caja				= objeto[i].caja;
					$total += parseFloat(objeto[i].importe);
					tabla1.add(obj);
				}	
		
				u.total.value=convertirMoneda($total);
			}else{
				if (u.inicio.value!="1"){
				tabla1.clear();
				mens.show("A","No existieron datos con los filtros seleccionados","�Atenci�n!","");
				}
			}
		}
		
	function convertirMoneda(valor){
		valorx = (valor=="")?"0.00":valor;
		valor1 = Math.round(parseFloat(valorx)*100)/100;
		valor2 = "$ "+numcredvar(valor1.toLocaleString());
		return valor2;
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
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
				
			consultaTexto("mostrardetalle2","total_con.php?accion=3&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&sucursal="+u.sucursal.value+"&inicio=0");
		
			}else{
				if(sepasods!=0){
					u.mostrardes.value = sepasods;
					sepasods = 0;
				}
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
			consultaTexto("mostrardetalle2","total_con.php?accion=3&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&sucursal="+u.sucursal.value+"&inicio="+u.totaldes.value);
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
				consultaTexto("mostrardetalle2","total_con.php?accion=3&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&sucursal="+u.sucursal.value+"&inicio="+u.totaldes.value);
			}			
		}	
	}
	
	function mostrardetalle2(datos){
		if(datos.indexOf("nada")<0){
			var obj = eval(datos);
			tabla1.setJsonData(obj);
		}		
	}
	
	function imprimirReporte(){
		if(document.URL.indexOf("web/")>-1){		
			var v_dir = "http://www.pmmintranet.net/web/general/ingresos/";
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_capacitacion/general/ingresos/";
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_pruebas/general/ingresos/";
		}
			
		window.open(v_dir+"generarExcelIngresos.php?accion=3&titulo=INGRESOS POR GUIAS DE CONTADO&fecha="+u.fecha.value
		+"&fecha2="+u.fecha2.value+"&sucursal="+u.sucursal.value+"&val="+Math.random());
	}
</script>

</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="680" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="426"><table width="578" border="0" cellpadding="0" cellspacing="0">
      </table>
          <span class="Tablas"><span class="Estilo4">
          <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />
          </span><span class="Estilo4">
          <input name="totaldes" type="hidden" id="totaldes" value="1" />
          <input name="fecha" type="hidden" class="Tablas" id="fecha" style="width:100px" value="<?=$fechaini ?>" />
          <input name="fecha2" type="hidden" class="Tablas" id="fecha2" style="width:100px" value="<?=$fechafin ?>" />
          <input name="sucursal" type="hidden" class="Tablas" id="sucursal" style="width:100px" value="<?=$sucursal ?>" />
          <input name="inicio" type="hidden" class="Tablas" id="inicio" style="width:100px" value="<?=$inicio ?>" />
        </span></span></td>
    </tr>
    <tr>
      <td><div id="txtDir" style=" height:300px; width:680px; overflow:auto" align="left">
        <table width="426" id="detalle" border="0" cellpadding="0" cellspacing="0">
        </table>
        <table width="647" height="17" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="3">&nbsp;</td>
            <td width="59">&nbsp;</td>
            <td width="22" align="center">&nbsp;</td>
            <td width="69" align="center">&nbsp;</td>
            <td width="54" align="center">&nbsp;</td>
            <td width="168" align="center">&nbsp;</td>
            <td width="163" align="center"><strong><strong> Total Gral:
              <input name="mostrardes2" class="Tablas" type="text" id="mostrardes2" style="width:100px; text-align:center; background-color:#FFFF99;" value="<?=$tdes; ?>" />
            </strong></strong></td>
            <td width="109" align="center"><input name="total" type="text" class="Tablas" id="total" style="text-align:right;width:100px;background:#FFFF99" value="<?=$total ?>
                " readonly="" align="right" /></td>
          </tr>
        </table>
</div></td>
    </tr>
<tr>
      <td><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="107" align="right"><div id="d_atrasdes" class="ebtn_atraz" onclick="mostrarDescuento('atras');"></div></td>
            <td width="302" align="center"><strong>&nbsp;
                  <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" style="width:70px; text-align:center; border:none" value="30" />
                  <strong><span style="color:#FF0000"></span></strong></strong></td>
            <td width="91" align="left"><div id="d_sigdes" <? if($tdes=="0" || $tdes==""){ echo 'style="visibility:hidden"';} ?> class="ebtn_adelante" onclick="mostrarDescuento('adelante');"></div></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td><table width="74" align="center">
        <tr>
          <td width="66" ><div class="ebtn_imprimir" onclick="imprimirReporte()"></div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>