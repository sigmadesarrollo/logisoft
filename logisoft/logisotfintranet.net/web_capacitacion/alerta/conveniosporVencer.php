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
<script src="../javascript/ClaseTabla.js"></script>
<link href="../estilos_estandar.css" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script>
	var tabla1 		= new ClaseTabla();
	var	u			= document.all;
	var mens 		= new ClaseMensajes();	
	var pag1_cantidadporpagina = 30;
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"# CONVENIO", medida:60, alineacion:"left",  datos:"convenio"},
			{nombre:"IDCLIENTE", medida:4,tipo:"oculto", alineacion:"left",  datos:"idcliente"},
			{nombre:"CLIENTE", medida:100, alineacion:"left",  datos:"cliente"},
			{nombre:"DIRECCION", medida:100, alineacion:"left", datos:"direccion"},
			{nombre:"FECHA DE VENCIMIENTO", medida:130, alineacion:"center", datos:"fechavencimiento"},
			{nombre:"TIPO CONVENIO", medida:70, alineacion:"left", datos:"tipoconvenio"},
			{nombre:"PRECIOS", medida:100, tipo:"moneda",alineacion:"center", onDblClick:"obtenerPrecio",  datos:"precios"},
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
		obtenerDetalle();
	}
	
	function obtenerDetalle(){		
		consultaTexto("resTabla1","consultasAlertas.php?accion=6&contador="+u.pag1_contador.value
		+"&s="+Math.random());
	}
	
	function resTabla1(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;
		
		tabla1.setJsonData(obj.registros);
		
		if(obj.paginado==1){
			document.getElementById('paginado').style.visibility = 'visible';
		}else{
			document.getElementById('paginado').style.visibility = 'hidden';
		}
	}
	
	function paginacion(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla1","consultasAlertas.php?accion=6&contador=0&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","consultasAlertas.php?accion=6&contador="+(parseFloat(u.pag1_contador.value)+1)					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","consultasAlertas.php?accion=6&contador="+(parseFloat(u.pag1_contador.value)-1)					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla1","consultasAlertas.php?accion=6&contador="+contador
				+"&s="+Math.random());
				break;
		}
	}
	function obtenerPrecio(){
		abrirVentanaFija("../general/clientes/informacionextra.php?cliente="+tabla1.getSelectedRow().idcliente,600, 600, 'ventana', 'DESGLOZE CONVENIO');
	}	
	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="650" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">CONVENIOS POR VENCER</td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><div id="txtDir" style=" height:280px; width:650; overflow:auto" align="left">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">
                </table>
            </div></td>
          </tr>
          <tr>
            <td><div id="paginado" align="center" style="visibility:hidden"> <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion('primero')" /> <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion('atras')" /> <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion('adelante')" /> <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion('ultimo')" />
                    <input type="hidden" name="pag1_total" />
                    <input type="hidden" name="pag1_contador" value="0" />
                    <input type="hidden" name="pag1_adelante" value="" />
                    <input type="hidden" name="pag1_atras" value="" />
            </div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
