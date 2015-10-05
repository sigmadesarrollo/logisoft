<? session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/		

	include('../../Conectar.php');	

	$link=Conectarse('webpmm');

$accion=$_POST['accion']; $codigo=$_POST['codigo']; $descripcion=$_POST['descripcion']; $usuario=$_SESSION[NOMBREUSUARIO]; $pais=$_POST['pais'];

 if($accion==""){

	$sql=mysql_query("SELECT ifnull(max(id),0)+1 As id FROM catalogoestado",$link);

	$row=mysql_fetch_array($sql);

	$codigo=$row[0];

 }

	if($accion=="grabar"){		

		$sqlins=mysql_query("INSERT INTO catalogoestado (id, descripcion, pais, usuario, fecha)VALUES('null', UCASE('$descripcion'), '$pais', '$usuario', current_timestamp())",$link);		

		$codigo=mysql_insert_id();

		$mensaje = 'Los datos han sido guardados correctamente';

		$accion="modificar";

	}else if($accion=="modificar"){

		$sqlupd=mysql_query("UPDATE catalogoestado SET descripcion=UCASE('$descripcion'), pais='$pais', usuario='$usuario', fecha=current_timestamp() WHERE id='$codigo'",$link);

		$mensaje = 'Los cambios han sido guardados correctamente';	

		$accion="modificar";

	}else if($accion=="limpiar"){

		$pais="";

		$descripcion="";

		$codigo="";

		$accion="";

		$usuario=$_SESSION[NOMBREUSUARIO];

	$sql=mysql_query("SELECT ifnull(max(id),0)+1 As id FROM catalogoestado",$link);

	$row=mysql_fetch_array($sql);

	$codigo=$row[0];

	}

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="../../javascript/shortcut.js"></script>

<script src="select.js"></script>

<script language="JavaScript" type="text/javascript">



	var u = document.all;

	window.onload = function(){

		if(u.accion.value==""){

			obtenerGeneral();

		}

		u.descripcion.focus();

	}



	function obtenerGeneral(){

		consultaTexto("mostrarGeneral","catalogoestadoresult.php?accion=2");

	}



	function mostrarGeneral(datos){

		u.codigo.value = datos;

	}

	

	function limpiar(){

		document.getElementById('pais').value="";

		document.getElementById('descripcion').value="";

		document.getElementById('accion').value = "";

		u.pais.value = "0";

		obtenerGeneral();	

	}

	function validar(){

	 if(document.getElementById('descripcion').value==""){

			alerta('Debe capturar Descripción', '¡Atención!','descripcion');

			document.getElementById('descripcion').focus();

	}else if(document.form1.pais.value==""){

			alerta('Debe capturar País', '¡Atención!','pais');

			document.getElementById('pais').focus();

	}else{

			if(document.getElementById('accion').value==""){

			document.getElementById('accion').value = "grabar";

				document.form1.submit();

			}else if(document.getElementById('accion').value="modificar"){

				document.form1.submit();

			}

	}

}

	function obtener(id){

		document.getElementById('codigo').value=id;

		consultaTexto("mostrarDatos","catalogoestadoresult.php?accion=1&estado="+id);

		document.getElementById('accion').value="modificar";

	}

	function mostrarDatos(datos){

		if(datos.indexOf("no encontro")<0){

			var obj = eval(convertirValoresJson(datos));

			u.descripcion.value = obj[0].descripcion;

			u.pais.value = obj[0].pais;

		}else{

			alerta("El codigo de estado no existe","¡Atención!","descripcion");

			u.descripcion.value = "";

			u.pais.value = 0;

		}

	}

	

	function trim(cadena,caja)

{

	for(i=0;i<cadena.length;)

	{

		if(cadena.charAt(i)==" ")

			cadena=cadena.substring(i+1, cadena.length);

		else

			break;

	}



	for(i=cadena.length-1; i>=0; i=cadena.length-1)

	{

		if(cadena.charAt(i)==" ")

			cadena=cadena.substring(0,i);

		else

			break;

	}

	

	document.getElementById(caja).value=cadena;

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

function foco(nombrecaja){

	if(nombrecaja=="codigo"){

		document.getElementById('oculto').value="1";

	}

}

shortcut.add("Ctrl+b",function() {

	if(document.form1.oculto.value=="1"){

abrirVentanaFija('buscar.php?tipo=estado', 550, 450, 'ventana', 'Busqueda')

	}

});

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Cat&aacute;logo Estados</title>

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

-->

</style>

<script src="../../javascript/ajax.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<link href="FondoTabla.css" rel="stylesheet" type="text/css">

<link href="puntovta.css" rel="stylesheet" type="text/css">

<link href="Tablas.css" rel="stylesheet" type="text/css">

</head>



<body>

<form name="form1" method="post" action="">

  <table width="100%" border="0">

    <tr>

      <td><table width="350" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

          <tr>

            <td width="563" class="FondoTabla">CAT&Aacute;LOGO ESTADO</td>

          </tr>

          <tr>

            <td><table width="301" border="0" align="center" cellpadding="0" cellspacing="0">

              <tr>

                <td class="Tablas"><strong>C&oacute;digo:</strong></td>

                <td><input name="codigo" type="text" class="Tablas" id="codigo" size="10" value="<?= $codigo ?>" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" style=" font:tahoma; font-size:9px; background:#FFFF99" readonly="">

&nbsp;&nbsp; <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscar.php?tipo=estado', 550, 450, 'ventana', 'Busqueda')"> &nbsp;</td>

              </tr>

              <tr>

                <td class="Tablas">Descripci&oacute;n:</td>

                <td><input class="Tablas" name="descripcion" type="text" id="descripcion" onBlur="trim(document.getElementById('descripcion').value,'descripcion');" onKeyPress="return tabular(event,this)" size="35" value="<?= $descripcion ?>" style="text-transform:uppercase;font:tahoma; font-size:9px"></td>

              </tr>

              <tr>

                <td width="73" class="Tablas">Pa&iacute;s:</td>

                <td width="228">

                  <select name="pais" class="Tablas" id="pais" style="text-transform:uppercase;width:200px">

                    <option selected="selected" value="0">Seleccionar País</option>

                    <?

					$sqlp=mysql_query("SELECT * FROM catalogopais",$link);

					while($res=mysql_fetch_array($sqlp)){

					?>

                    <option value="<?=$res[0]; ?>" <? if($res[0]==$pais){echo 'selected';} ?>>

                      <?=$res['descripcion']; ?>

                      </option>

                    <? } ?>

                  </select>

                </td>

              </tr>           

              

              <tr>

                <td height="32"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">

                  <span class="Tablas">

                  <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" />

                  </span></td>

                <td><table width="141" border="0" align="right">

                  <tr>

                    <td width="67"><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" onClick="validar();" style="cursor:pointer" ></td>

                    <td width="64"><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" title="Nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')" style="cursor:pointer" ></td>

                  </tr>

                </table></td>

              </tr>

              <tr>

                <td height="32" colspan="2"></td>

                </tr>

            </table></td>

          </tr>

      </table>

        </td>

    </tr>

  </table>

</form>

</body>

</html>



<?

if ($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";

	}

//}

?>