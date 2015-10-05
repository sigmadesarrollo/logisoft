<? 	session_start();

	require_once('../../Conectar.php');

	$l = Conectarse('webpmm');	

	

	$sql="SELECT cc.id AS cliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS 	nombrecliente,sc.montoautorizado,cc.diascredito,cc.diarevision AS fecharevision,

	cc.diapago AS fechapago,

	IFNULL(d.dias,0) AS rotacioncartera FROM pagoguias pg

	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente

	INNER JOIN catalogocliente cc ON pg.cliente=cc.id

	LEFT JOIN

	(

	SELECT cliente,IFNULL(SUM(rotacioncartera) / COUNT(guia),0)AS dias FROM 

	(

	SELECT pg.cliente,pg.guia,IFNULL(DATEDIFF(pg.fechapago,pg.fechacreo),0) AS rotacioncartera FROM 

	pagoguias pg

	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente

	INNER JOIN catalogocliente cc ON pg.cliente=cc.id

	WHERE sc.estado='ACTIVADO' AND sc.fechaactivacion<='" .cambiaf_a_mysql($_GET[fechafin])."' 

	AND pg.sucursalacobrar='" .$_GET[sucursal]."' AND IFNULL(DATEDIFF(pg.fechapago,pg.fechacreo),0)<>0

	)tabla GROUP BY cliente

	)d ON cc.id=d.cliente

	WHERE sc.estado='ACTIVADO' AND sc.fechaactivacion<='" .cambiaf_a_mysql($_GET[fechafin])."' 

	AND pg.sucursalacobrar='" .$_GET[sucursal]."'	GROUP BY cc.id ORDER BY cc.id LIMIT 0,30";

	/*pg.sucursalcreo='" .$_GET[sucursal]."'*/

	$r=mysql_query($sql,$l)or die($sql); 

	$tdes = mysql_num_rows($r);

	$registros= array();	

	$inicio=$_GET[inicio];

	$fechaini=$_GET[fechaini];

	$fechafin=$_GET[fechafin];

	$mes=$_GET[mes];

	$sucursal=$_GET[sucursal];

		if (mysql_num_rows($r)>0)

				{

				while ($f=mysql_fetch_object($r))

				{

					$f->nombrecliente=cambio_texto($f->nombrecliente);

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

			{nombre:"#CLIENTE", medida:40, alineacion:"left", datos:"cliente"},

			{nombre:"NOMBRE DEL CLIENTE", medida:200, onDblClick:"agregaestadodecuenta",alineacion:"left",  datos:"nombrecliente"},

			{nombre:"MONTO AUTORIZADO", medida:100, onDblClick:"agregamontoautorizado",tipo:"moneda",alineacion:"right",  datos:"montoautorizado"},	

			{nombre:"CREDITO DÍAS", medida:70, alineacion:"center",  datos:"diascredito"},

			{nombre:"FECHA REVISION", medida:80, alineacion:"left",  datos:"fecharevision"},

			{nombre:"FECHA PAGO ", medida:70, alineacion:"left",  datos:"fechapago"},

			{nombre:"ROTACION DE CARTERA", medida:100, alineacion:"center",  datos:"rotacioncartera"}	

		],

		filasInicial:30,

		alto:300,

		seleccion:true,

		ordenable:false,

		//eventoDblClickFila:"verRecoleccion()",

		nombrevar:"tabla1"

	});

	

	window.onload = function(){

		tabla1.create();
		alert(datos);
		mostrardetalle('<?=$datos ?>');

		u.fecha.value='<?=$fechaini ?>' ;

		u.fecha2.value='<?=$fechafin ?>' ;

		u.mes.value='<?=$mes ?>' ;

		u.sucursal.value='<?=$sucursal ?>' ;

		u.d_atrasdes.style.visibility = "hidden";

		if(parseInt(u.mostrardes2.value) <= 30){

			u.d_sigdes.style.visibility  = "hidden";

		}

	}

	

	function mostrardetalle(datos){	

		if (datos!=0) {

				tabla1.clear();

				var objeto = eval(convertirValoresJson(datos));

				for(var i=0;i<objeto.length;i++){

					var obj		 	   		= new Object();

					obj.cliente 			= objeto[i].cliente;

					obj.nombrecliente 		= objeto[i].nombrecliente;

					obj.montoautorizado	 	= objeto[i].montoautorizado;

					obj.diascredito	 	   	= objeto[i].diascredito;

					obj.fecharevision   	= objeto[i].fecharevision;

					obj.fechapago			= objeto[i].fechapago;

					obj.rotacioncartera		= objeto[i].rotacioncartera;

					tabla1.add(obj);

				}	

			}else{

				if (u.inicio.value!="1"){

					tabla1.clear();

					mens.show("A","No existieron datos con los filtros seleccionados","¡Atención!","");	

				}

				

			}

		}

		

	function agregamontoautorizado(){

		setTimeout("agregamontoautorizado2()",300);

	}

	

	function agregamontoautorizado2(){

		var arr = tabla1.getSelectedRow();

		parent.document.all.barratabs_contenedor_id3.disabled=false;	

		parent.document.all.iframe_id3.src="montoautorizado.php?cliente="+arr.cliente;

		parent.tabs.seleccionar(3);

		parent.cn.agregarDireccion(2);

	}

		

	function agregaestadodecuenta(){

		setTimeout("agregaestadodecuenta2()",300);

	}

	

	function agregaestadodecuenta2(){

		var arr = tabla1.getSelectedRow();

		parent.document.all.barratabs_contenedor_id4.disabled=false;	

		parent.document.all.iframe_id4.src="nombredelcliente.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&cliente="+arr.cliente+"&mes="+u.mes.value+"&nombrecliente="+arr.nombrecliente+"&sucursal=<?=$_GET[sucursal]?>";

		parent.tabs.seleccionar(4);

		parent.cn.agregarDireccion(3);

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

				

				consultaTexto("mostrarDetalle2","principal_con.php?accion=6&sucursal="+u.sucursal.value +"&fechafin="+u.fecha2.value+"&inicio=0");				

			}else{

				if(sepasods!=0){

					u.mostrardes.value = sepasods;

					sepasods = 0;

				}

				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;

				if(parseFloat(u.mostrardes.value) < inicio){

					u.mostrardes.value = inicio;

				}

				consultaTexto("mostrarDetalle2","principal_con.php?accion=6&sucursal="+u.sucursal.value +"&fechafin="+u.fecha2.value+"&inicio="+u.totaldes.value);



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

				consultaTexto("mostrarDetalle2","principal_con.php?accion=6&sucursal="+u.sucursal.value +"&fechafin="+u.fecha2.value+"&inicio="+u.totaldes.value);

			}			

		}	

	}

	

	function mostrarDetalle2(datos){

		if(datos.indexOf("nada")<0){

			var obj = eval(datos);

			tabla1.setJsonData(obj);

		}		

	}

	

	function tipoImpresion(valor){

		if(valor=="Archivo"){

			window.open("http://www.pmmentuempresa.com/web/general/cobranza/generarExcelCobranza.php?accion=2&titulo=CLIENTES CON CREDITO&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>&mes=<?=$_GET[mes] ?>&sucursal=<?=$_GET[sucursal] ?>");			

		}

	}

</script>



</head>

<body>

<form id="form1" name="form1" method="post" action="">

  <table width="690" border="0" align="center" cellpadding="0" cellspacing="0">

    <tr>

      <td width="426"><table width="578" border="0" cellpadding="0" cellspacing="0">

      </table>

          <input name="fecha" type="hidden" class="Tablas" id="fecha" style="width:100px" value="<?=$fecha ?>"/>

          <input name="fecha2" type="hidden" class="Tablas" id="fecha2" style="width:100px" value="<?=$fecha2 ?>"/>

          <input name="mes" type="hidden" class="Tablas" id="mes" style="width:100px" value="<?=$mes ?>"/>

          <span class="Estilo4">

          <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />

          </span><span class="Estilo4">

          <input name="totaldes" type="hidden" id="totaldes" value="01" />

          <input name="sucursal" type="hidden" class="Tablas" id="sucursal" style="width:100px" value="<?=$sucursal ?>"/>

          <input name="inicio" type="hidden" class="Tablas" id="inicio" style="width:100px" value="<?=$inicio ?>"/>

        </span></td>

    </tr>

    <tr>

      <td colspan="2">

          <table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">

          </table>

     </td>

    </tr>

    <tr>

      <td><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">

          <tr>

            <td width="107" align="right"><div id="d_atrasdes" class="ebtn_atraz" onclick="mostrarDescuento('atras');"></div></td>

            <td width="302" align="center"><strong>Total:&nbsp;

                  <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" style="width:70px; text-align:center; border:none" value="30" />

                  <strong><span style="color:#FF0000"></span>

                  <input name="mostrardes2" class="Tablas" type="text" id="mostrardes2" style="width:100px; text-align:center; background-color:#FFFF99;" value="<?=$tdes; ?>" />

                </strong></strong></td>

            <td width="91" align="left"><div id="d_sigdes" <? if($tdes=="0" || $tdes==""){ echo 'style="visibility:hidden"';} ?> class="ebtn_adelante" onclick="mostrarDescuento('adelante');"></div></td>

          </tr>

      </table></td>

    </tr>

<tr>

      <td align="right"><table width="74" align="center">

        <tr>

          <td width="66" ><div class="ebtn_imprimir" onclick="abrirVentanaFija('../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>

        </tr>

      </table></td>

    </tr>

  </table>

</form>

</body>

<script>

	//parent.frames[1].document.getElementById('titulo').innerHTML = '';

</script>

</html>