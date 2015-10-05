<? 



	session_start();



	require_once('../../../Conectar.php');



	$l = Conectarse('webpmm');



	$sql="SELECT cs.prefijo as sucursal,cc.id AS cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombrecliente,

		s.foliotipo AS venta, IFNULL(CONCAT_WS(' - ',s.desdefolio,s.hastafolio),0) AS folios, ifnull(f.folio,0)as folio,

		IFNULL(IF(ge.factura IS NOT NULL OR ge.factura<>0,ge.total,0),0) AS importe,

		IFNULL(s.total,0) AS porfacturar

		FROM guiasempresariales ge

		LEFT JOIN facturacion f ON ge.factura=f.folio		

		INNER JOIN catalogocliente cc ON cc.id=ge.idremitente		

		LEFT JOIN solicitudguiasempresariales s ON ge.idsolicitudguia = s.id		

		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id

		WHERE cs.prefijo = '".$_GET[sucursal]."' AND ge.tipoguia = 'PREPAGADA'

		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'

		GROUP BY s.id LIMIT 0,30";



	$r=mysql_query($sql,$l)or die($sql); 



	$tdes = mysql_num_rows($r);



	$registros= array();	



	



	$fechaini=$_GET[fechaini];



	$fechafin=$_GET[fechafin];



	$idsucursal=$_GET[sucursal];



	$inicio=$_GET[inicio];



		



		if (mysql_num_rows($r)>0)



				{



				while ($f=mysql_fetch_object($r))



				{



					$f->nombre=cambio_texto($f->nombre);



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



<script src="../../../javascript/ClaseTabla.js"></script>



<script src="../../../javascript/ajax.js"></script>



<script src="../../../javascript/funciones.js"></script>



<script language="javascript1.1" src="../../../javascript/funcionesDrag.js"></script>



<script language="javascript1.1" src="../../../javascript/ClaseMensajes.js"></script>



<script>



	var tabla1 		= new ClaseTabla();



	var	u			= document.all;



	var inicio		= 30;



	var sepasods	= 0;



	var mens 		= new ClaseMensajes();



	mens.iniciar('../../../javascript',true);



	



	tabla1.setAttributes({



		nombre:"detalle",



		campos:[



			{nombre:"SUCURSAL", medida:60, alineacion:"left", datos:"sucursal"},

			{nombre:"# CLIENTE", medida:60, alineacion:"left", datos:"cliente"},



			{nombre:"CLIENTE", medida:100, alineacion:"left",datos:"nombrecliente"},



			{nombre:"VENTA", medida:60, alineacion:"center",  datos:"venta"},



			{nombre:"FOLIOS", medida:120, alineacion:"left", datos:"folios"},



			{nombre:"FACTURA", medida:60, alineacion:"right", datos:"folio"},



			{nombre:"IMPORTE", medida:80, tipo:"moneda", alineacion:"right", datos:"importe"},



			{nombre:"PEND. DE FACTURA", medida:120, tipo:"moneda", onDblClick:"obtenerPendientes", alineacion:"right", datos:"porfacturar"}



		],



		filasInicial:30,



		alto:270,



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

			var total=0;

			var total2=0;

				tabla1.clear();

				var objeto = eval(convertirValoresJson(datos));

				for(var i=0;i<objeto.length;i++){

					var obj		 			= new Object();

					obj.sucursal			= objeto[i].sucursal;

					obj.cliente				= objeto[i].cliente;

					obj.nombrecliente		= objeto[i].nombrecliente;

					obj.venta				= objeto[i].venta;

					obj.folios				= objeto[i].folios;

					obj.folio				= objeto[i].folio;

					obj.importe				= objeto[i].importe;

					obj.porfacturar			= objeto[i].porfacturar;

					total += parseFloat(objeto[i].importe);

					total2 += parseFloat(objeto[i].porfacturar);

					tabla1.add(obj);

				}

				u.importe.value=convertirMoneda(total);

				u.porfacturar.value=convertirMoneda(total2);

			}else{

				if (u.inicio.value!="1"){

				tabla1.clear();

				mens.show("A","No existieron datos con los filtros seleccionados","¡Atención!","");

				}

			}

		}

		



	function obtenerDetalle(){

		consultaTexto("mostrarDetalle","../ventas/consultasVentas.php?accion=8&sucursal=<?=$_GET[sucursal]; ?>&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>");

	}



	function obtenerPendientes(){

		var arr = tabla1.getSelectedRow();

		parent.document.all.barratabs_contenedor_id7.disabled=false;

		parent.document.all.iframe_id7.src="../ventasprepagado/pendientedefactura.php?sucursal="+arr.sucursal+"&cliente="+arr.cliente+"&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>";

		parent.tabs.seleccionar(7);

		parent.cn.agregarDireccion(6);

		parent.tabs.moverManual(-600);

	}



	function tipoImpresion(valor){

		if(valor=="Archivo"){

			window.open("http://www.pmmentuempresa.com/web/general/venta/generarExcelVenta.php?accion=8&sucursal=<?=$_GET[sucursal]; ?>&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>&m="+Math.random()+"&titulo=VENTAS POR GUIAS PREPAGADAS");

		}

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



				



			consultaTexto("mostrardetalle2","../ventas/consultasVentas.php?accion=8&fechaini="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal.value+"&inicio=0");



		



			}else{



				if(sepasods!=0){



					u.mostrardes.value = sepasods;



					sepasods = 0;



				}



				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;



				if(parseFloat(u.mostrardes.value) < inicio){



					u.mostrardes.value = inicio;



				}



				consultaTexto("mostrardetalle2","../ventas/consultasVentas.php?accion=8&fechaini="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal.value+"&inicio="+u.totaldes.value);



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



				consultaTexto("mostrardetalle2","../ventas/consultasVentas.php?accion=8&fechaini="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal.value+"&inicio="+u.totaldes.value);



			}			



		}	



	}



	



	function mostrardetalle2(datos){



		if(datos.indexOf("nada")<0){



			var obj = eval(datos);



			tabla1.setJsonData(obj);



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



</script>



<script src="../../../javascript/ventanas/js/ventana-modal-1.3.js"></script>



<script src="../../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>



<script src="../../../javascript/ventanas/js/abrir-ventana-fija.js"></script>



<link href="../../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">



<link href="../../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">



<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



<title>Documento sin t&iacute;tulo</title>



<link href="../../../FondoTabla.css" rel="stylesheet" type="text/css" />



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



<link href="../../../estilos_estandar.css" rel="stylesheet" type="text/css" />



<style type="text/css">



<!--



.Estilo4 {font-size: 12px}



.Balance {background-color: #FFFFFF; border: 0px none}



.Balance2 {background-color: #DEECFA; border: 0px none;}



-->



</style>



<link href="../../../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />



</head>



<body>



<form id="form1" name="form1" method="post" action="">

  <table width="690" border="0" align="center" cellpadding="0" cellspacing="0">



    <tr>



      <td width="586">



          <table width="426" id="detalle" border="0" cellpadding="0" cellspacing="0">

          </table>



         </td>

    </tr>



    <tr>

      <td align="right"><table width="573" border="0" cellspacing="0" cellpadding="0">

        <tr>

          <td><div align="right"><strong><strong> Total Gral:

            <input name="mostrardes2" class="Tablas" type="text" id="mostrardes2" style="width:100px; text-align:center; background-color:#FFFF99;" value="<?=$tdes; ?>" />

          </strong></strong></div></td>

          <td width="110" align="center"><span class="style31">

            <input name="importe" type="text" class="Tablas" id="importe" readonly="" style="text-align:right;background-color:#FFFF99; width:100px; text-align:right" />

          </span></td>

          <td width="110" align="center"><span class="style31">

            <input name="porfacturar" type="text" class="Tablas" id="porfacturar" readonly="" style="text-align:right;background-color:#FFFF99; width:100px; text-align:right">

          </span></td>

        </tr>

      </table></td>

    </tr>

    <tr>

      <td align="right"><table width="437" border="0" align="center" cellpadding="0" cellspacing="0">

        <tr>

          <td width="107" align="right"><div id="d_atrasdes" class="ebtn_atraz" onclick="mostrarDescuento('atras');"></div></td>

          <td width="302" align="center"><strong><span class="Tablas"><span class="Estilo4">

            <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />

            </span><span class="Estilo4">

            <input name="totaldes" type="hidden" id="totaldes" value="1" />

            <input name="fecha" type="hidden" class="Tablas" id="fecha" style="width:100px" value="<?=$fecha ?>" onkeyup="mascara(this,'/',patron,true)" />

            <input name="fecha2" type="hidden" class="Tablas" id="fecha2" style="width:100px" value="<?=$fecha2 ?>" onkeyup="mascara(this,'/',patron,true)"/>

            <input name="sucursal" type="hidden" class="Tablas" id="sucursal" style="width:100px" value="<?=$sucursal ?>" onkeyup="mascara(this,'/',patron,true)"/>

            <input name="inicio" type="hidden" class="Tablas" id="inicio" style="width:100px" value="<?=$inicio ?>" onkeyup="mascara(this,'/',patron,true)"/>

            </span></span>&nbsp;

            <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" style="width:70px; text-align:center; border:none" value="30" />

            <strong><span style="color:#FF0000"></span></strong></strong></td>

          <td width="91" align="left"><div id="d_sigdes" <? if($tdes=="0" || $tdes==""){ echo 'style="visibility:hidden"';} ?> class="ebtn_adelante" onclick="mostrarDescuento('adelante');"></div></td>

        </tr>

      </table></td>

    </tr>

    <tr>



      <td align="right"><table width="74" align="center">



        <tr>



          <td width="66" ><div class="ebtn_imprimir" onclick="abrirVentanaFija('../../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>

        </tr>



      </table></td>

    </tr>

  </table>



</form>



</body>



</html>