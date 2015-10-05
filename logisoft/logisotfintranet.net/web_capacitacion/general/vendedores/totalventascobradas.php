<? 	session_start();

	require_once('../../Conectar.php');

	$l = Conectarse('webpmm');



$sql="SELECT sucursal,clavevendedor,vendedor,IFNULL(SUM(ventascobradas),0)AS ventascobradas,IFNULL(SUM(comision),0) AS comision FROM 

(

	SELECT cs.descripcion AS sucursal,gv.idvendedorconvenio AS clavevendedor,

	gv.nvendedorconvenio AS vendedor,

	IFNULL(vc.ventascobradas,0) AS ventascobradas,(IFNULL(vc.ventascobradas,0)*(IFNULL(c.comision,0)/100))AS comision FROM guiasventanilla gv

	INNER JOIN catalogosucursal cs ON gv.sucursalconvenio=cs.id

	LEFT JOIN

	(SELECT gc.vendedor,SUM(ld.importe)AS ventascobradas FROM liquidacioncobranza l 

				INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 

				INNER JOIN generacionconvenio gc ON ld.cliente=gc.idcliente

				WHERE l.estado='LIQUIDADO' AND l.fechaliquidacion 

				BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  

				GROUP BY gc.vendedor)vc ON gv.idvendedorconvenio=vc.vendedor

	LEFT JOIN 

		(SELECT vendedor,comision FROM 

		(SELECT gc.vendedor, CASE  

		WHEN gc.tipoautorizacion='EN AUTORIZACION (ok)' THEN 

			(IFNULL(IF ((DATEDIFF(CURRENT_DATE,cc.fechainicioconvenio)/365)

			>(SELECT despues FROM configuradorpromociones),

			(SELECT porcentaje FROM configuradorpromociones),

			CASE cc.tipoclientepromociones

			WHEN 'A' THEN (SELECT porcA FROM configuradorpromociones) WHEN 'B' THEN (SELECT porcB FROM configuradorpromociones)END),0))

		WHEN gc.tipoautorizacion='EN AUTORIZACION (x)' THEN 

			(SELECT porcentaje FROM configuradorpromociones)

		END AS comision FROM catalogocliente cc 

		INNER JOIN generacionconvenio gc ON cc.id=gc.idcliente

		)Tabla WHERE vendedor<>0 AND comision<>0 GROUP BY vendedor ORDER BY vendedor)c ON gv.idvendedorconvenio=c.vendedor

			WHERE gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 

	GROUP BY cs.descripcion,gv.idvendedorconvenio

UNION ALL

	SELECT cs.descripcion AS sucursal,gv.idvendedorconvenio AS clavevendedor,

	gv.nvendedorconvenio AS vendedor,

	IFNULL(vc.ventascobradas,0) AS ventascobradas,(IFNULL(vc.ventascobradas,0)*(IFNULL(c.comision,0)/100))AS comision FROM guiasempresariales gv

	INNER JOIN catalogosucursal cs ON gv.sucursalconvenio=cs.id

	LEFT JOIN

	(SELECT gc.vendedor,SUM(ld.importe)AS ventascobradas FROM liquidacioncobranza l 

				INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 

				INNER JOIN generacionconvenio gc ON ld.cliente=gc.idcliente

				WHERE l.estado='LIQUIDADO' AND l.fechaliquidacion 

				BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  

				GROUP BY gc.vendedor)vc ON gv.idvendedorconvenio=vc.vendedor

	LEFT JOIN 

		(SELECT vendedor,comision FROM 

		(SELECT gc.vendedor, CASE  

		WHEN gc.tipoautorizacion='EN AUTORIZACION (ok)' THEN 

			(IFNULL(IF ((DATEDIFF(CURRENT_DATE,cc.fechainicioconvenio)/365)

			>(SELECT despues FROM configuradorpromociones),

			(SELECT porcentaje FROM configuradorpromociones),

			CASE cc.tipoclientepromociones

			WHEN 'A' THEN (SELECT porcA FROM configuradorpromociones) WHEN 'B' THEN (SELECT porcB FROM configuradorpromociones)END),0))

		WHEN gc.tipoautorizacion='EN AUTORIZACION (x)' THEN 

			(SELECT porcentaje FROM configuradorpromociones)

		END AS comision FROM catalogocliente cc 

		INNER JOIN generacionconvenio gc ON cc.id=gc.idcliente

		)Tabla WHERE vendedor<>0 AND comision<>0 GROUP BY vendedor ORDER BY vendedor)c ON gv.idvendedorconvenio=c.vendedor

			WHERE gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 

	GROUP BY cs.descripcion,gv.idvendedorconvenio)ventasporvendedor GROUP BY clavevendedor ORDER BY vendedor";

		$r=mysql_query($sql,$l)or die($sql); 

		$registros= array();

		$inicio=$_GET[inicio];

		if (mysql_num_rows($r)>0){

				while ($f=mysql_fetch_object($r)){

				$f->vendedor=cambio_texto($f->vendedor);

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

<SCRIPT type="text/javascript" src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script src="../../javascript/ClaseTabla.js"></script>

<link href="../../estilos_estandar.css" />

<script src="../../javascript/ajax.js"></script>

<script language="javascript" src="../../javascript/funcionesDrag.js"></script>

<script language="javascript" src="../../javascript/ClaseMensajes.js"></script>

<script>

	var tabla1 		= new ClaseTabla();

	var	u		= document.all;

	var mens = new ClaseMensajes();

	mens.iniciar('../../javascript',true);

	tabla1.setAttributes({

		nombre:"detalle",

		campos:[

			{nombre:"SUCURSAL", medida:100, alineacion:"left", datos:"sucursal"},

			{nombre:"VENDEDOR", medida:250, alineacion:"left",  datos:"vendedor"},

			{nombre:"VENTAS COBRADAS", medida:100, tipo:"moneda" ,alineacion:"right",  datos:"ventascobradas"},			

			{nombre:"COMISION", medida:100, tipo:"moneda", alineacion:"right", datos:"comision"}

		],

		filasInicial:30,

		alto:230,

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

				var total=0;

				var total2=0;

				tabla1.clear();

				var objeto = eval(convertirValoresJson(datos));

				for(var i=0;i<objeto.length;i++){

					var obj		 	   	= new Object();

					obj.sucursal 			= objeto[i].sucursal;

					obj.vendedor		 	= objeto[i].vendedor;

					obj.ventascobradas		= objeto[i].ventascobradas;

					obj.comision			= objeto[i].comision;

					total += parseFloat(objeto[i].ventascobradas);

					total2 += parseFloat(objeto[i].comision);

					tabla1.add(obj);

				}	

				u.total.value=convertirMoneda(total);

				u.total2.value=convertirMoneda(total2);

			}else{

				tabla1.clear();

				u.total.value=0;

				u.total2.value=0;

				if (u.inicio.value!="1"){

				parent.mens.show("A","No existieron datos con los filtros seleccionados","¡Atención!","");

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

	function tipoImpresion(valor){

		if(valor=="Archivo"){			

			window.open("http://www.pmmentuempresa.com/web/general/vendedores/generarExcelVendedor.php?accion=2&titulo=COMISIÓN POR VENDEDOR&fecha=<?=$_GET[fecha] ?>&fecha2=<?=$_GET[fecha2] ?>");			

		}

	}

</script>

<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />

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

<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />

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

  <table width="690" border="0" align="center" cellpadding="0" cellspacing="0">

    <tr>

      <td width="426"><table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">

      </table></td>
    </tr>


    <tr>

      <td><table width="419" height="16" border="0" cellpadding="0" cellspacing="0">

          <tr>

            <td width="3">&nbsp;</td>

            <td><div align="right"><span class="Estilo4">
              <input name="inicio" type="hidden" id="inicio" value="<?=$inicio ?>" />
            </span>Total:</div>

                <div align="right"></div>

                <div align="right"></div></td>

            <td width="108" align="center"><input name="total" type="text" class="Tablas" id="total" style="text-align:right;width:100px;background:#FFFF99" value="<?=$total ?>

                " readonly="" align="right" /></td>

            <td width="108" align="center"><input name="total2" type="text" class="Tablas" id="total2" style="text-align:right;width:100px;background:#FFFF99" value="<?=$total2 ?>

                " readonly="" align="right" /></td>
          </tr>

      </table></td>
    </tr>

    <tr>

      <td align="right"><span class="Estilo4">

        </span>
        <table width="74" align="center">
          <tr>
            <td width="66" ><div class="ebtn_imprimir" onclick="abrirVentanaFija('../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>
          </tr>
        </table>        </td>
    </tr>
  </table>

</form>

</body>

<script>

	//parent.frames[1].document.getElementById('titulo').innerHTML = '';

</script>

</html>