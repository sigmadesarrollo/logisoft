<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}/*

if(isset($_SESSION['gvalidar'])!=100){echo"<script language='javascript' type='text/javascript'>			document.location.href='../index.php';</script>";}else{*/

		include('../../Conectar.php');	

		$link=Conectarse('webpmm');

		$usuario=$_SESSION[NOMBREUSUARIO];

$accion=$_POST['accion']; $codigo=$_POST['codigo']; $slServicio=$_POST['slServicio'];	$condicion=$_POST['condicion']; $costo=$_POST['costo']; $costoextra=$_POST['costoextra']; $limite=$_POST['limite']; $porcada=$_POST['porcada'];

$registros=$_POST['registros'];	

	

if($accion==""){

		$sql=mysql_query("SELECT * FROM  configuradorservicios",$link);

		while($row=mysql_fetch_array($sql)){

				$detalle .= "{

						servicio:'".$row['servicio']."',

						condicion:'".$row['condicion']."',

						costo:'".$row['costo']."',

						limite:'".$row['limite']."',

						cada:'".$row['porcada']."',

						extra:'".$row['costoextra']."'},";

		}

}

	

if($accion=="grabar"){

		$sql_eliminar=mysql_query("DELETE FROM configuradorservicios",$link);

		//INSERTAR TABLA DETALLE

		for($i=0;$i<$registros;$i++){

			$sqlins=mysql_query("INSERT INTO configuradorservicios 

			(id,servicio,condicion,costo,costoextra,limite,porcada,usuario,fecha)

			VALUES(NULL,'".$_POST["tabladetalle_SERVICIO"][$i]."',

			'".$_POST["tabladetalle_CONDICION"][$i]."',

			'".$_POST["tabladetalle_COSTO"][$i]."',

			'".$_POST["tabladetalle_COSTO_EXTRA"][$i]."',

			'".$_POST["tabladetalle_LIMITE"][$i]."',

			'".$_POST["tabladetalle_POR_CADA"][$i]."',

			'$usuario',	CURRENT_TIMESTAMP())",$link)or die("error en linea ".__LINE__);

			//Cadena Detalle

			$detalle .= "{

				servicio:'".$_POST["tabladetalle_SERVICIO"][$i]."',

				condicion:'".$_POST["tabladetalle_CONDICION"][$i]."',

				costo:'".$_POST["tabladetalle_COSTO"][$i]."',

				limite:'".$_POST["tabladetalle_LIMITE"][$i]."',

				cada:'".$_POST["tabladetalle_POR_CADA"][$i]."',

				extra:'".$_POST["tabladetalle_COSTO_EXTRA"][$i]."'},";

		}$detalle = substr($detalle,0,strlen($detalle)-1);

		

		$mensaje="Los datos han sido guardados correctamente";

		$codigo		=""; 

		$slServicio	=""; 

		//$condicion	=""; 

		$costo		=""; 

		$costoextra	=""; 

		$limite		=""; 

		$porcada	=""; 

		$accion		=""; 

}



?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script type="text/javascript" src="../../javascript/ClaseTabla.js"></script>

<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../javascript/ajax.js"></script>

<script>

//**************************

var tabla1 = new ClaseTabla();

	

tabla1.setAttributes({

	nombre:"tabladetalle",

	campos:[

		{nombre:"SERVICIO", medida:85, alineacion:"center", datos:"servicio"},

		{nombre:"CONDICION", medida:40, alineacion:"center", datos:"condicion"},

		{nombre:"COSTO", medida:70, alineacion:"center", datos:"costo"},

		{nombre:"LIMITE", medida:70, alineacion:"center", datos:"limite"},

		{nombre:"POR_CADA", medida:70, alineacion:"center", datos:"cada"},

		{nombre:"COSTO_EXTRA", medida:70, alineacion:"center", datos:"extra"}

	],

	filasInicial:8,

	alto:100,

	seleccion:true,

	ordenable:true,

	eventoClickFila:"document.all.eliminar.value=tabla1.getSelectedIdRow();",

	eventoDblClickFila:"ModificarFila();",

	nombrevar:"tabla1"

});



	window.onload = function(){

		tabla1.create();	

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

	

	

function agregarVar(){

	var u= document.all;

if(u.modificarfila.value!=""){

	tabla1.deleteById(document.all.modificarfila.value);

	u.modificarfila.value="";

}

	var valser =tabla1.getValuesFromField("servicio",":");

	if(document.getElementById('slServicio').value==0){

		alerta('Debe Capturar Servicio','¡Atención!','slServicio');

		return false;

	}else if(valser.indexOf(document.getElementById('slServicio').options[document.getElementById('slServicio').selectedIndex].text)!= -1){

		alerta('Ya existe el servicio','¡Atención!','slServicio');

		return false;

	}else if(document.getElementById('costo').value==""){

		alerta('Debe Capturar Costo','¡Atención!','costo');

		return false;	

	}else if(parseInt(document.getElementById('costo').value)<0){

		alerta('Costo debe ser Mayor a Cero','¡Atención!','costo');	

		return false;

	}else if(document.form1.condicion.checked){

		if(document.getElementById('limite').value==""){

			alerta('Debe Capturar Limite','¡Atención!','limite');

			return false;

		}else if(parseInt(document.getElementById('limite').value)<0){

			alerta('Limite debe ser Mayor a Cero','¡Atención!','limite');

			return false;

		}else if(document.getElementById('porcada').value==""){

			alerta('Debe Capturar Por Cada','¡Atención!','porcada');

			return false;

		}else if(parseInt(document.getElementById('porcada').value)<0){

			alerta('Por Cada debe ser Mayor a Cero','¡Atención!','porcada');

			return false;

		}else if(document.getElementById('costoextra').value==""){

			alerta('Debe Capturar Costo Extra','¡Atención!','costoextra');

			return false;

		}else if(parseInt(document.getElementById('costoextra').value)<0){

			alerta('Costo Extra debe ser Mayor a Cero','¡Atención!','costoextra');

			return false;

		}else{



				var registro = new Object();

				registro.servicio 	= document.getElementById('slServicio').options[document.getElementById('slServicio').selectedIndex].text;

				registro.condicion	= document.getElementById('condicion').value;

				registro.costo	 	= document.getElementById('costo').value;

				registro.limite 	= document.getElementById('limite').value;

				registro.cada 		= document.getElementById('porcada').value;

				registro.extra 		= document.getElementById('costoextra').value;

				tabla1.add(registro);

			}

	}else{



				var registro = new Object();

				registro.servicio 	= document.getElementById('slServicio').options[document.getElementById('slServicio').selectedIndex].text;

				registro.condicion	= document.getElementById('condicion').value;

				registro.costo	 	= document.getElementById('costo').value;

				registro.limite 	= document.getElementById('limite').value;

				registro.cada 		= document.getElementById('porcada').value;

				registro.extra 		= document.getElementById('costoextra').value;

				tabla1.add(registro);

	

	}

	

	u.condicion.checked	= false;

	u.slServicio.value 	= "";

	u.costo.value 		= "";

	u.limite.value		= "";

	u.porcada.value 	= "";

	u.costoextra.value 	= "";

	Habilitar();

}



function EliminarFila(){

	if(document.all.eliminar.value!=""){

		if(tabla1.getValSelFromField("servicio","SERVICIO")!=""){

			tabla1.deleteById(document.all.eliminar.value);

		}

	}else{

		alerta('Seleccione una fila a eliminar','¡Atención!','tabladetalle');

	}

}



function ModificarFila(){

	var obj = tabla1.getSelectedRow();

	if(tabla1.getValSelFromField("servicio","SERVICIO")!=""){

		for(i=1;i<document.getElementById('slServicio').options.length;i++){

				if(document.all.slServicio.options[i].text == obj.servicio){

					document.all.slServicio.options[i].selected= true;

				}

		}

		if(obj.condicion==1){document.getElementById('condicion').checked=true;}else{document.getElementById('condicion').checked=false;}

		document.getElementById('costo').value 		= obj.costo;

		document.getElementById('limite').value 	= obj.limite;

		document.getElementById('porcada').value 	= obj.cada;

		document.getElementById('costoextra').value = obj.extra;

		document.all.modificarfila.value	=tabla1.getSelectedIdRow();

	}

}



var nav4 = window.Event ? true : false;

function Numeros(evt){

	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 

	var key = nav4 ? evt.which : evt.keyCode; 

	return (key <= 13 || (key >= 48 && key <= 57));

}

function validar(){

document.all.registros.value = tabla1.getRecordCount();

 if(tabla1.getRecordCount()<=0 || tabla1.getRecordCount()==""){

			alerta('Debe agregar por lo menos un servicio','¡Atención!','costo');

			return false;			

}



	document.getElementById('accion').value = "grabar";

	document.form1.submit();



}



function trim(cadena,caja)

{

for(i=0;i<cadena.length;) { if(cadena.charAt(i)==" ") cadena=cadena.substring(i+1, cadena.length); else break; }

for(i=cadena.length-1; i>=0; i=cadena.length-1) { if(cadena.charAt(i)==" ") 			cadena=cadena.substring(0,i); else break; }	document.getElementById(caja).value=cadena;

}



function tabular(e,obj) 

        {

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

            /*ACA ESTA EL CAMBIO*/

            if (frm.elements[i+1].disabled ==true )    

                tabular(e,frm.elements[i+1]);

            else frm.elements[i+1].focus();

            return false;

} 

function Habilitar(){

	if(document.form1.condicion.checked==false){			

		document.getElementById('limite').disabled=true

		document.getElementById('limite').value="";

		document.getElementById('porcada').disabled=true

		document.getElementById('porcada').value="";

		document.getElementById('costoextra').disabled=true

		document.getElementById('costoextra').value="";

		document.getElementById('limite').style.backgroundColor='#FFFF99';

		document.getElementById('porcada').style.backgroundColor='#FFFF99';

		document.getElementById('costoextra').style.backgroundColor='#FFFF99';

	}else{		

		document.getElementById('limite').style.backgroundColor='';

		document.getElementById('porcada').style.backgroundColor='';

		document.getElementById('costoextra').style.backgroundColor='';

		document.getElementById('limite').disabled=false

		document.getElementById('porcada').disabled=false

		document.getElementById('costoextra').disabled=false

		document.getElementById('limite').value=0;

		document.getElementById('porcada').value=1;

		document.getElementById('costoextra').value=0;

	}

}



/*function Verificar(){

	if(document.form1.condicion.checked==true){

		document.getElementById('limite').style.backgroundColor='';

		document.getElementById('porcada').style.backgroundColor='';

		document.getElementById('costoextra').style.backgroundColor='';

		document.getElementById('limite').disabled=false

		document.getElementById('porcada').disabled=false

		document.getElementById('costoextra').disabled=false		

	}

}*/

function ObtenerId(id){

	document.getElementById('codigo').value=id;

}

function verificarServicio(ser){

	consulta("obtenerServicio","configuradorservicioresult.php?accion=1&servicio="+ser);

}

function obtenerServicio(datos){

var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		var u = document.all;		

		if(con>0){		

			u.servicio.value=datos.getElementsByTagName('servicio').item(0).firstChild.data;		

		}else{

			u.servicio.value="";

		}

		

}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Configurador Servicios</title>

<script src="select.js"></script> 

<script type="text/javascript" src="js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="js/abrir-ventana-alertas.js"></script>

<link href="css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="css/style1.css" rel="stylesheet" type="text/css">

<link href="puntovta.css" rel="stylesheet" type="text/css">

<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="Tablas.css" rel="stylesheet" type="text/css" />

<style type="text/css">

<!--

.style1 {

	color: #FFFFFF;

	font-weight: bold;

}

.style2 {	color: #464442;

	font-size:9px;

	border: 0px none;

	background:none

}

.style3 {	font-size: 9px;

	color: #464442;

}

.style5 {color: #FFFFFF ; font-size:9px}

.Estilo2 {font-size: 8px;

	font-weight: bold;

}

.style31 {font-size: 9px;

	color: #464442;

}

.style31 {font-size: 9px;

	color: #464442;

}

.style51 {	color: #FFFFFF;

	font-size:8px;

	font-weight: bold;

}

.Balance {background-color: #FFFFFF; border: 0px none}

.Balance2 {background-color: #DEECFA; border: 0px none;}

-->

</style>

</head>



<body >

<form id="form1" name="form1" method="post" action="">



  <table width="100%" border="0">

    <tr>

      <td><table width="500" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

          <tr>

            <td width="563" class="FondoTabla">Datos Generales </td>

          </tr>

          

          <tr>

            <td><table width="430" border="0" align="center" cellpadding="0" cellspacing="0">

                <tr> 

                  <td colspan="4" class="Tablas"><div id="txtHint"> 

                      <table width="445" border="0" cellspacing="0" cellpadding="0">

                        <tr> 

                          <td width="65" class="Tablas">Servicio:</td>

                          <td width="175"><label> 

                            <select name="slServicio" class="Tablas" onChange="verificarServicio(this.value);">

                              <option selected="selected" value="0">SELECCIONAR 

                              SERVICIO</option>

                              <? $sql=@mysql_query("SELECT * FROM catalogoservicio",$link);

				  while($row=mysql_fetch_array($sql)){

				   ?>

                              <option value="<?=$row[0];?>" <? if($row[0]==$slServicio){ echo 'selected'; } ?>>

                              <?=$row[1];?>

                              </option>

                              <? } ?>

                            </select>

                            </label></td>

                          <td width="88" class="Tablas"><input name="condicion" type="checkbox" id="condicion" onKeyPress="return tabular(event,this)" onClick="if(document.form1.condicion.checked==true){document.form1.condicion.value=1;}else{document.form1.condicion.value='';}Habilitar();" value="" <? if($condicion==1){ echo 'checked'; } ?>   >

                            Condici&oacute;n </td>

                          <td width="120">&nbsp;</td>

                        </tr>

                        <tr> 

                          <td class="Tablas">Costo:</td>

                          <td class="Tablas"><input name="costo" type="text" class="Tablas" id="costo" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('costoextra').value,'costoextra');" onKeyPress="return tabular(event,this)" value="<?=$costo ?>" size="20" /></td>

                          <td class="Tablas">Limite: </td>

                          <td><span class="Tablas"> 

                            <input name="limite" type="text" disabled="disabled" class="Tablas" id="limite" style="font-size:9px; font:tahoma; background:#FFFF99" onBlur="trim(document.getElementById('limite').value,'limite');" onKeyPress="return tabular(event,this)" value="<?=$limite ?>" size="20" />

                            </span></td>

                        </tr>

                        <tr> 

                          <td class="Tablas">Por Cada: </td>

                          <td><input name="porcada" type="text" disabled="disabled" class="Tablas" id="porcada" style="font-size:9px; font:tahoma;background:#FFFF99" onBlur="trim(document.getElementById('porcada').value,'porcada');" onKeyPress="return tabular(event,this)" value="<?=$porcada ?>" size="20" /></td>

                          <td class="Tablas">Costo Extra: </td>

                          <td><input name="costoextra" type="text" disabled="disabled" class="Tablas" id="costoextra" style="font-size:9px; font:tahoma;background:#FFFF99" onBlur="trim(document.getElementById('costoextra').value,'costoextra');" onKeyPress="return tabular(event,this)" value="<?=$costoextra ?>" size="20" /></td>

                        </tr>

                      </table>

                    </div></td>

                </tr>

                <tr> 

                  <td width="65"><input name="accion" type="hidden" id="accion" value="<?=$accion; ?>">

                    <input name="codigo" type="hidden" id="codigo" value="<?=$codigo; ?>"> 

                    <input name="servicio" type="hidden" id="servicio" value="<?=$servicio; ?>"> 

                    <input name="eliminar" type="hidden" id="eliminar">

                    <input name="modificarfila" type="hidden" id="modificarfila">

                    <input name="registros" type="hidden" id="registros"></td>

                  <td width="175">&nbsp;</td>

                  <td width="88">&nbsp;</td>

                  <td width="120"><table width="20" border="0" align="right" cellpadding="0" cellspacing="0">

                      <tr> 

                        <td><img src="../../img/Boton_Agregari.gif" alt="Guardar" width="70" height="20" style="cursor:pointer" title="Guardar" onClick="agregarVar();"></td>

                      </tr>

                    </table></td>

                </tr>

                <tr> 

                  <td colspan="4"> <table  border="0" cellspacing="0" cellpadding="0" id="tabladetalle">

                    </table></td>

                </tr>

                <tr>

                  <td colspan="4" align="right">

                    &nbsp;

                    <table width="34%" border="0">

                      <tr>

                        <td width="32%"><div class="ebtn_guardar" onClick="validar();"></div></td>

                        <td width="68%"><div class="ebtn_eliminar" onClick="EliminarFila()" ></div></td>

                      </tr>

                    </table></td>

                </tr>

              </table>

            </td>

          </tr>

      </table></td>

    </tr>

  </table>

</form>

</body>

<script>

//tabla1.create();

	//parent.frames[1].document.getElementById('titulo').innerHTML = 'CONFIGURADOR SERVICIOS';

</script>

</html>

<?

if ($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";	

	}

//}

?>