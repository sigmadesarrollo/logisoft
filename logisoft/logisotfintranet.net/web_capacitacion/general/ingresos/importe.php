<? 	session_start();


	require_once('../../Conectar.php');


	$l = Conectarse('webpmm');


	


	$sql="SELECT DATE_FORMAT(fechaguia, '%d/%m/%Y') AS fechaguia,guia,destino,cliente,nombrecliente,valorfleteneto, estado FROM 


(


	SELECT gv.fecha AS fechaguia,gv.id AS guia, 


	cs.descripcion AS destino,cc.id AS cliente,


	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,


	IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto, gv.estado


	FROM guiasventanilla gv


	INNER JOIN catalogocliente cc ON cc.id  = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)


	INNER JOIN catalogosucursal cs ON cs.id	= gv.idsucursaldestino


	WHERE gv.idvendedorconvenio='" .$_GET[idvendedor]."' AND gv.estado<>'CANCELADO' AND gv.fecha BETWEEN 


	'" .cambiaf_a_mysql($_GET[fecha])."' and 


	'".cambiaf_a_mysql($_GET[fecha2])."'


UNION


	SELECT gv.fecha AS fechaguia,gv.id AS guia, 


	cs.descripcion AS destino,cc.id AS cliente,


	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,


	IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto,gv.estado 				    FROM guiasempresariales gv


	INNER JOIN catalogocliente cc ON cc.id  = IF       (gv.tipoflete=0,gv.idremitente,gv.iddestino)


	INNER JOIN catalogosucursal cs ON cs.id	= gv.idsucursaldestino


	WHERE gv.idvendedorconvenio='" .$_GET[idvendedor]."' AND gv.estado<>'CANCELADO' AND gv.fecha BETWEEN 


	'" .cambiaf_a_mysql($_GET[fecha])."' and 


	'".cambiaf_a_mysql($_GET[fecha2])."' 


)guiasventanillayempresariales";


	$r=mysql_query($sql,$l)or die($sql); 


	$registros= array();	


		


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


	var mens = new ClaseMensajes();


	mens.iniciar('../../javascript',true);


	tabla1.setAttributes({


		nombre:"detalle",


		campos:[


			{nombre:"", medida:20, alineacion:"center", datos:""},


			{nombre:"NÚMERO DE CHEQUE", medida:150, alineacion:"center",  datos:"cheque"},


			{nombre:"IMPORTE", medida:100, alineacion:"center",  datos:"importe"},


			{nombre:"BANCO", medida:50, alineacion:"center",  datos:"banco"},


			{nombre:"NOMBRE DEL CLIENTE", medida:100, alineacion:"left",  datos:"cliente"},


			{nombre:"FACTURAS DE GUIA", medida:100, alineacion:"center",  datos:"facturas"}


		],


		filasInicial:30,


		alto:150,


		seleccion:true,


		ordenable:false,


		//eventoDblClickFila:"verRecoleccion()",


		nombrevar:"tabla1"


	});


	


	window.onload = function(){


		tabla1.create();


		mostrardetalle('<?=$datos ?>');


	}


	


	function mostrardetalle(datos){	


		if (datos!=0) {


			$total=0;


				tabla1.clear();


				var objeto = eval(convertirValoresJson(datos));


				for(var i=0;i<objeto.length;i++){


					var obj		 	   		= new Object();


					obj.cheque 				= objeto[i].cheque;


					obj.importe	 			= objeto[i].importe;


					obj.banco	 	   		= objeto[i].banco;


					obj.cliente   			= objeto[i].cliente;


					obj.facturas			= objeto[i].facturas;


					$total += parseFloat(objeto[i].importe);


					tabla1.add(obj);


				}	


		


				u.total.value=convertirMoneda($total);


			}else{


				tabla1.clear();


				mens.show("A","No existieron datos con los filtros seleccionados","¡Atención!","");


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





</head>


<body>


<form id="form1" name="form1" method="post" action="">


  <table width="530" border="0" align="center" cellpadding="0" cellspacing="0">


    <tr>


      <td width="520"><table width="530" id="detalle" border="0" cellpadding="0" cellspacing="0">


      </table></td>


    </tr>


    <tr>


      <td>&nbsp;</td>


    </tr>


    <tr>


      <td><table width="288" height="16" border="0" cellpadding="0" cellspacing="0">


          <tr>


            <td width="3">&nbsp;</td>


            <td width="18" align="center">&nbsp;</td>


            <td width="144" align="center"><div align="right">Total Gral: </div></td>


            <td width="100" align="center"><input name="total" type="text" class="Tablas" id="total" style="text-align:right;width:100px;background:#FFFF99" value="<?=$total ?>


                " readonly=""  /></td>


          </tr>


      </table></td>


    </tr>


    <tr>


      <td>&nbsp;</td>


    </tr>


  </table>


</form>


</body>


<script>


	parent.frames[1].document.getElementById('titulo').innerHTML = '';


</script>


</html>