<?php	session_start();

 	require_once('../../Conectar.php'); 

	$link=Conectarse('webpmm'); 

 	$usuario=$_SESSION[NOMBREUSUARIO]; $accion=$_POST['accion']; $codigo=$_POST['codigo'];$descripcion=$_POST['descripcion'];
   
	if($accion==""){

		$row=folio('catalogopuesto','webpmm');

		$codigo=$row[0];

	}else if($accion=="grabar"){

		$sqlins=mysql_query("INSERT INTO catalogopuesto (id,descripcion,departamento,sbase,comisiones,sminimo,usuario,fecha)

		VALUES

		('null',UCASE('$descripcion'),UCASE('$sldepartamento'),'$sueldob','$comisiones','$slsminimo','$usuario',

		current_timestamp())",$link);

		$mensaje="Los datos han sido guardados correctamente";

		$accion="modificar";	

	}else if($accion=="modificar"){

		$sqlupd=mysql_query("UPDATE catalogopuesto SET

		descripcion=UCASE('$descripcion'),departamento=UCASE('$sldepartamento'),sbase='$sueldob',comisiones='$comisiones',

		sminimo='$slsminimo',usuario='$usuario',fecha=current_timestamp() where id='$codigo'",$link);	

		$mensaje="Los cambios han sido guardados correctamente"; 

	}else if($accion=="limpiar"){

		$accion=""; $descripcion=""; $sldepartamento=""; $sueldob="";

		$slsminimo="";	

		$row=folio('catalogopuesto','webpmm'); $codigo=$row[0];

	}	

?>



<html>

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="../../javascript/shortcut.js"></script>

<script src="../../javascript/ajax.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<title>CATALOGO PUESTO </title>

<link href="FondoTabla.css" rel="stylesheet" type="text/css">

<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css">

<script language="javascript">

function validar(){	

	if(document.getElementById('descripcion').value==""){

		alerta('Debe Capturar Descripción','¡Atención!','descripcion');				

	}else if(document.all.sldepartamento.value==0){

		alerta('Debe Capturar Departamento','¡Atención!','sldepartamento');		

	}else if(document.all.slsminimo.value==0){

		alerta('Debe Capturar Zona de salario minimo','¡Atención!','slsminimo');		

	}else{

		if(document.getElementById('accion').value=="modificar"){

			document.getElementById('accion').value = "grabar";

			document.form1.submit();

		}else{

			document.getElementById('accion').value = "modificar";

			document.form1.submit();	

		}

	}

}

function Limpiar(){

document.getElementById('codigo').value=""; document.getElementById('descripcion').value=""; document.form1.sueldob.checked=false; document.form1.comisiones.checked=false; document.form1.sldepartamento.value='Seleccionar'; document.form1.slsminimo.value='Seleccionar'; document.getElementById('accion').value = "limpiar"; 

document.form1.submit();

}

function limpiartodo(){

document.getElementById('descripcion').value=""; document.form1.sueldob.checked=false; document.form1.comisiones.checked=false; document.form1.sldepartamento.value='Seleccionar'; document.form1.slsminimo.value='Seleccionar';

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

            else if (frm.elements[i+1].readOnly ==true )    

                tabular(e,frm.elements[i+1]);

            else frm.elements[i+1].focus();

            return false;

}

function obtener(puesto){

	document.all.codigo.value=puesto;

	document.all.accion.value="modificar";

	consulta("mostrarPuesto","Consulta.php?accion=1&puesto="+puesto);	

}

function mostrarPuesto(datos){

		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		var u = document.all;

		limpiartodo();

		

		if(con>0){

		u.descripcion.value = datos.getElementsByTagName('descripcion').item(0).firstChild.data;		

		u.sldepartamento.value = datos.getElementsByTagName('departamento').item(0).firstChild.data;	

	if(datos.getElementsByTagName('sbase').item(0).firstChild.data==0){

		u.sueldob.checked = false; 

	}else{

		u.sueldob.checked = true;

	}		

	if(datos.getElementsByTagName('comisiones').item(0).firstChild.data==0){

		u.comisiones.checked = false;

	}else{

		u.comisiones.checked = true;

	}		

		u.slsminimo.value = datos.getElementsByTagName('sminimo').item(0).firstChild.data;

		}	

}

function foco(nombrecaja){

	if(nombrecaja=="codigo"){

		document.getElementById('oculto').value="1";

	}

}

shortcut.add("Ctrl+b",function() {

	if(document.form1.oculto.value=="1"){

	abrirVentanaFija('buscarPuesto.php', 550, 450, 'ventana', 'Busqueda')

	}

});

</script>

<link href="../../css/FondoTabla.css" rel="stylesheet" type="text/css">

<link href="../../css/Tablas.css" rel="stylesheet" type="text/css">

<link href="Tablas.css" rel="stylesheet" type="text/css">

<style type="text/css">

<!--

.Estilo1 {font-size: 12px}

-->

</style>

</head>

<body onLoad="document.form1.descripcion.focus();">

<form id="form1" name="form1" method="post" >

<table width="400" height="145" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

        <tr>

          <td height="13" class="FondoTabla Estilo1">CAT&Aacute;LOGO PUESTO</td>

        </tr>

        <tr>

          <td height="130"><table width="300" border="0" align="center" cellpadding="0" cellspacing="0">

            <tr>

              <td width="109" ><strong class="Tablas">Codigo:</strong></td>

              <td width="47" ><input name="codigo" type="text" id="codigo" value="<?=$codigo ?>" size="10" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" readonly class="Tablas" style="background:#FFFF99" >              </td>

              <td width="144" ><div class="ebtn_buscar" onClick="abrirVentanaFija('buscarPuesto.php', 550, 450, 'ventana', 'Busqueda')"> </div></td>

            </tr>

            <tr>

              <td ><strong class="Tablas">Descripcion:</strong></td>

              <td colspan="2" >

                <input name="descripcion" type="text" id="descripcion" class="Tablas" style="text-transform:uppercase" onKeyPress="return tabular(event,this)" value="<?=$descripcion ?>" size="30" maxlength="50" >             </td>

            </tr>

            <tr>

              <td ><strong class="Tablas">Departamento:</strong></td>

              <td colspan="2" >                

                <select onKeyPress="return tabular(event,this)" class="Tablas" name="sldepartamento" id="sldepartamento" style="width:120px; text-transform:uppercase" >

                  <option selected="selected" value="0">SELECCIONAR</option>

                  <option value="ADMINISTRATIVO" <? if($sldepartamento=="ADMINISTRATIVO"){echo 'selected';} ?>>ADMINISTRATIVO</option>

                  <option value="OPERATIVO" <? if($sldepartamento=="OPERATIVO"){echo 'selected';} ?>>OPERATIVO</option>

                </select>              </td>

            </tr>

            <tr>

              <td colspan="3" >

                  <input type="checkbox" onKeyPress="return tabular(event,this)"  name="sueldob" value="1" <? if($sueldob==1){echo 'checked'; } ?>>

                  <span class="Tablas">Sueldo Base</span>                &nbsp;&nbsp;<input onKeyPress="return tabular(event,this)" type="checkbox" name="comisiones" value="1" <? if($comisiones==1){echo 'checked'; } ?> >

                  <span class="Tablas">Comisiones</span> </td>

            </tr>

            <tr>

              <td colspan="3" ><strong class="Tablas">Zona de salario minimo: </strong><strong>

                <select onKeyPress="return tabular(event,this)" class="Tablas" name="slsminimo" id="slsminimo" style=" text-transform:uppercase" >

                  <option selected="selected" value="0">SELECCIONAR</option>

                  <option value="A" <? if($slsminimo=="A"){ echo 'selected';} ?>>A</option>

                  <option value="B" <? if($slsminimo=="B"){ echo 'selected';} ?>>B</option>

                  <option value="C" <? if($slsminimo=="C"){ echo 'selected';} ?>>C</option>

                </select>

              </strong></td>

            </tr>

            <tr>

              <td >&nbsp;</td>

              <td colspan="2" >&nbsp;</td>

            </tr>

            <tr>

              <td ><input name="accion" type="hidden" value="<?=$accion ?>"></td>

              <td colspan="2" ><table width="154" border="0" align="right" cellpadding="0" cellspacing="0">

                <tr>

                  <td width="78"><div class="ebtn_guardar" onClick="validar();"></div>				 </td>

                  <td width="76">

				  <div class="ebtn_nuevo" onClick="confirmar('Perdera la información capturada ¿Desea continuar?', '', 'Limpiar();', '')"></div></td>

                </tr>

              </table></td>

            </tr>

            <tr>

              <td colspan="3" >&nbsp;</td>

            </tr>

            <tr>

              <td colspan="3" ><table width="34" border="0" align="center" cellpadding="0" cellspacing="0">

                <tr>

                  <td></td>

                </tr>

              </table>

                <span class="Tablas">

                <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" />

              </span></td>

            </tr>

          </table>

          </td>

        </tr>

  </table>

</form>

</body>

</html>

<script>

	//parent.frames[1].document.getElementById('titulo').innerHTML = 'CATÁLOGO PUESTO';

</script>



<?

if ($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";

	}

//	}

?>