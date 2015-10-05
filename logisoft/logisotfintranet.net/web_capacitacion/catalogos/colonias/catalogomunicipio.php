<? session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/

		include('../../Conectar.php');	

		$link=Conectarse('webpmm');

$accion=$_POST['accion']; $codigo=$_POST['codigo']; $descripcion=$_POST['descripcion'];  $usuario=$_SESSION[NOMBREUSUARIO]; $estado=$_POST['estado']; $descripcionestado=$_POST['descripcionestado']; $pais=$_POST['pais'];

	

 if($accion==""){

	$sql=mysql_query("SELECT ifnull(max(id),0)+1 As id FROM catalogomunicipio",$link);

	$row=mysql_fetch_array($sql);

	$codigo=$row[0];

 }

	if($accion=="grabar"){		

		$sqlins=mysql_query("INSERT INTO catalogomunicipio (id, descripcion, estado, usuario, fecha)VALUES('null', UCASE('$descripcion'), '$estado', '$usuario', current_timestamp())",$link);

		$codigo=mysql_insert_id();

		$mensaje = 'Los datos han sido guardados correctamente';

		$accion="modificar";

	}else if($accion=="modificar"){

		$sqlupd=mysql_query("UPDATE catalogomunicipio SET descripcion=UCASE('$descripcion'), estado='$estado', usuario='$usuario', fecha=current_timestamp() WHERE id='$codigo'",$link);		

		$mensaje = 'Los cambios han sido guardados correctamente';

		$accion="modificar";

	}else if($accion=="limpiar"){

		$estado="";

		$descripcionestado="";

		$pais="";

		$descripcion="";

		$codigo="";

		$accion="";

		$usuario=$_SESSION[NOMBREUSUARIO];

	$sql=mysql_query("SELECT ifnull(max(id),0)+1 As id FROM catalogomunicipio",$link);

	$row=mysql_fetch_array($sql);

	$codigo=$row[0];

	}

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="../../javascript/shortcut.js"></script>

<script language="JavaScript" type="text/javascript">

function Limpiar(){	

	document.getElementById('estado').value="";

	document.getElementById('descripcion').value="";

	document.getElementById('descripcionestado').value="";

	document.getElementById('pais').value="";	

	document.getElementById('accion').value = "limpiar";

	document.form1.submit();

}

function validar(){

	 if(document.getElementById('descripcion').value==""){

			alerta('Debe capturar Descripción', '¡Atención!','descripcion');

	 }else if(document.getElementById('estado').value==""){

			alerta('Debe capturar Estado', '¡Atención!','estado');

	 }else{

			if(document.getElementById('accion').value==""){

			document.getElementById('accion').value = "grabar";

				document.form1.submit();

			}else if(document.getElementById('accion').value=="modificar"){

				document.form1.submit();

			}

	}

}



function obtenerEstado(id,estado,pais){

		document.getElementById('estado').value=id;

		document.getElementById('descripcionestado').value=estado;

		document.getElementById('pais').value=pais;

}function obtenerMunicipio(idmunicipio,descmunicipio,idestado,descestado,pais){

		document.getElementById('codigo').value=idmunicipio;

		document.getElementById('descripcion').value=descmunicipio;

		document.getElementById('estado').value=idestado;

		document.getElementById('descripcionestado').value=descestado;

		document.getElementById('pais').value=pais;

		document.getElementById('accion').value="modificar";

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

            if (frm.elements[i+1].disabled ==true )    

                tabular(e,frm.elements[i+1]);

            else frm.elements[i+1].focus();

            return false;

} 

function foco(nombrecaja){

	if(nombrecaja=="codigo"){

		document.getElementById('oculto').value="1";

	}else if(nombrecaja=="estado"){

	document.getElementById('oculto').value="2";

	}

}

shortcut.add("Ctrl+b",function() {

	if(document.form1.oculto.value=="1"){

abrirVentanaFija('catalogomunicipio_Buscar.php?tipo=2', 550, 450, 'ventana', 'Busqueda')

	}else if(document.form1.oculto.value=="2"){

abrirVentanaFija('catalogomunicipio_Buscar.php?tipo=1', 550, 450, 'ventana', 'Busqueda')

	}

});

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Cat&aacute;logo Municipios</title>

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

<script src="select.js"></script>

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

<style type="text/css">

<!--

.Estilo1 {font-size: 14px}

-->

</style>

</head>



<body>

<form name="form1" method="post" action="">

    <tr>

      <td><p>&nbsp;</p>

        <table width="400" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

          <tr>

            <td width="563" class="FondoTabla">CAT&Aacute;LOGO MUNICIPIO</td>

          </tr>

          <tr>

            <td><table width="390" border="0" align="center" cellpadding="0" cellspacing="0">

              <tr>

                <td width="65" class="Tablas"><strong>C&oacute;digo:</strong></td>

                <td width="325"><input class="Tablas" name="codigo" type="text" id="codigo" size="10" value="<?= $codigo ?>" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" style=" font:tahoma; font-size:9px; background:#FFFF99" readonly="">

&nbsp; <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('catalogomunicipio_Buscar.php?tipo=2', 550, 450, 'ventana', 'Busqueda')"> </td>

              </tr>

              <tr>

                <td colspan="2" class="Tablas"><div id="txtHint"><table width="389" border="0" cellpadding="0" cellspacing="0">

                  <tr>

                    <td width="64" class="Tablas">Descripci&oacute;n:</td>

                    <td width="325"><input class="Tablas" name="descripcion" type="text" id="descripcion" onBlur="trim(document.getElementById('descripcion').value,'descripcion');" onKeyPress="if(event.keyCode==13){document.all.estado.focus(); document.all.oculto.value=2}else{return tabular(event,this)}" size="35" value="<?= $descripcion ?>" style="text-transform:uppercase;font:tahoma; font-size:9px"></td>

                  </tr>

                  <tr>

                    <td class="Tablas">Estado:</td>

                    <td><input name="estado" class="Tablas" type="text" id="estado" size="10" value="<?=$estado ?>" style=" font:tahoma; font-size:9px; background:#FFFF99" readonly="">

                      &nbsp;&nbsp; <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('catalogomunicipio_Buscar.php?tipo=1', 550, 450, 'ventana', 'Busqueda')">&nbsp;</td>

                  </tr>

                  <tr>

                    <td colspan="2" class="Tablas"><div id="txtEstado">

                      <table width="389" border="0" cellspacing="0" cellpadding="0">

                          <tr>

                            <td width="64">&nbsp;</td>

                            <td width="325"><input class="Tablas" name="descripcionestado" type="text" id="descripcionestado" size="30" value="<?=$descripcionestado ?>" style=" font:tahoma; font-size:9px; background:#FFFF99; text-transform:uppercase" readonly=""></td>

                          </tr>

                          <tr>

                            <td class="Tablas">Pa&iacute;s:</td>

                            <td><input class="Tablas" name="pais" type="text" id="pais" size="54" value="<?=$pais ?>" style=" font:tahoma; font-size:9px; background:#FFFF99; text-transform:uppercase" readonly=""></td>

                          </tr>

                        </table>

                    </div></td>

                  </tr>

                  

                </table></div></td>

              </tr>

              <tr>

                <td colspan="2" class="Tablas">

                  <input name="accion" type="hidden" id="accion" value="<?=$accion ?>">

                  <input name="oculto" type="hidden" id="oculto" value="<?=$accion ?>">

                  <table width="141" border="0" align="right">

                    <tr>

                      <td width="67"><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" onClick="validar();" style="cursor:pointer" ></td>

                      <td width="64"><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" title="Nuevo" onClick="confirmar('Perdera la información capturada ¿Desea continuar?', '', 'Limpiar();', '')" style="cursor:pointer" ></td>

                    </tr>

                  </table></td>

              </tr>

              <tr>

                <td colspan="2" class="Tablas"></td>

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