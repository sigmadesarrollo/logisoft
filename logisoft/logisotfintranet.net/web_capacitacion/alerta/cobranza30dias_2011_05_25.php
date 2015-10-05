<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');

	$s = "DELETE FROM cobranza30dias_tmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
	mysql_query($s,$l) or die($s);
	
		$s = "INSERT INTO cobranza60dias_tmp(id, factura, fechaemision, cliente, nombre,
		importe, tipofactura, fechavencimiento, diasatraso, contrarecibo, diapago, idusuario, idsucursal)																																																															  		SELECT 0 AS id, f.folio AS factura,f.fecha AS fechaemision, f.cliente,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombre,f.total AS importe,
		IF(f.total<>0 AND f.sobmontoafacturar<>0,'AMBAS',
		IF(f.total<>0,'NORMAL',IF(f.sobmontoafacturar<>0,'VALOR AGREGADO',''))) AS tipofactura,
		DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento,
		IF(DATEDIFF(CURDATE(),
		DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))<0,0,
		DATEDIFF(CURDATE(),
		DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)))AS diasatrazo,
		IFNULL(ld.contrarecibo,0) AS contrarecibo,
		IF (ld.compromiso<>'0000-00-00',ld.compromiso,rc.fecharegistro) AS proxdiapago,
		".$_SESSION[IDUSUARIO].", ".$_SESSION[IDSUCURSAL]." 
		FROM facturacion f
		INNER JOIN guiasventanilla gv ON f.folio=gv.factura
		INNER JOIN catalogocliente cc ON f.cliente=cc.id
		INNER JOIN solicitudcredito sc ON cc.id=sc.cliente
		LEFT JOIN liquidacioncobranzadetalle ld ON gv.id=ld.guia
		LEFT JOIN registrodecontrarecibos rc ON ld.folioliquidacion=rc.folioliquidacion
		WHERE (DATEDIFF(CURDATE(),DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)))>30
		AND f.estado<>'CANCELADO' AND f.idsucursal=".$_SESSION[IDSUCURSAL]." GROUP BY f.folio 
	UNION
		SELECT 0 AS id, f.folio AS factura,f.fecha AS fechaemision, f.cliente,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,f.total AS importe,
		IF(f.total<>0 AND f.sobmontoafacturar<>0,'AMBAS',
		IF(f.total<>0,'NORMAL',IF(f.sobmontoafacturar<>0,'VALOR AGREGADO',''))) AS tipofactura,
		DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento,
		IF(DATEDIFF(CURDATE(),
		DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))<0,0,
		DATEDIFF(CURDATE(),
		DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)))AS diasatrazo,
		IFNULL(ld.contrarecibo,0) AS contrarecibo,
		IF (ld.compromiso<>'0000-00-00',ld.compromiso,rc.fecharegistro) AS proxdiapago,
		".$_SESSION[IDUSUARIO].", ".$_SESSION[IDSUCURSAL]." 
		FROM facturacion f
		INNER JOIN guiasempresariales gv ON f.folio=gv.factura
		INNER JOIN catalogocliente cc ON f.cliente=cc.id
		INNER JOIN solicitudcredito sc ON cc.id=sc.cliente
		LEFT JOIN liquidacioncobranzadetalle ld ON gv.id=ld.guia
		LEFT JOIN registrodecontrarecibos rc ON ld.folioliquidacion=rc.folioliquidacion
		WHERE (DATEDIFF(CURDATE(),DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)))>30 
		AND f.estado<>'CANCELADO' AND f.idsucursal=".$_SESSION[IDSUCURSAL]." GROUP BY f.folio";
		mysql_query($s,$l) or die($s);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<link href="../estilos_estandar.css" />

<script>
	var tabla1 		= new ClaseTabla();
	var	u			= document.all;
	var mens 		= new ClaseMensajes();	
	var pag1_cantidadporpagina = 30;
	mens.iniciar("../javascript");
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"FACTURA", medida:50, alineacion:"left",  datos:"factura"},
			{nombre:"FECHA EMISION", medida:80, alineacion:"left", datos:"fechaemision"},
			{nombre:"CLIENTE", medida:150, alineacion:"left", datos:"nombre"},
			{nombre:"IMPORTE", medida:80, alineacion:"center", datos:"importe"},
			{nombre:"TIPO FACTURA", medida:70, alineacion:"left", datos:"tipofactura"},
			{nombre:"FECHA VENCIMIENTO", medida:130, alineacion:"left", datos:"fechavencimiento"},
			{nombre:"DIAS ATRAZO", medida:70, alineacion:"left", datos:"diasatraso"},
			{nombre:"CONTRARECIBO", medida:65, alineacion:"left", datos:"contrarecibo"},
			{nombre:"PROX DIA PAGO", medida:70, alineacion:"left", datos:"diapago"}			
		],
		filasInicial:15,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});	
	

	window.onload = function(){
		tabla1.create();
		obtenerDetalle();
	}
	
	function obtenerDetalle(){
		consultaTexto("resTabla1","consultasAlertas.php?accion=3&contador="+u.pag1_contador.value
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
				consultaTexto("resTabla1","consultasAlertas.php?accion=3&contador=0&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","consultasAlertas.php?accion=3&contador="+(parseFloat(u.pag1_contador.value)+1)					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","consultasAlertas.php?accion=3&contador="+(parseFloat(u.pag1_contador.value)-1)					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla1","consultasAlertas.php?accion=3&contador="+contador
				+"&s="+Math.random());
				break;
		}
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="550" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">COBRANZA 30 DIAS </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><div id="txtDir" style=" height:270px; width:620px; overflow:auto" align="left">
                <table width="549" border="0" cellspacing="0" cellpadding="0" id="detalle">
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
  <p>&nbsp;</p>
</form>
</body>
</html>
