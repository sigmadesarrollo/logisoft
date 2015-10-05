<? session_start();



	if(!$_SESSION[IDUSUARIO]!=""){



		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");



	}







		require_once('../../Conectar.php');	



		$link=Conectarse('webpmm');



$accion=$_POST['accion']; $codigo=$_POST['codigo']; $descripcion=$_POST['descripcion']; $cp=$_POST['cp']; $municipio=$_POST['municipio']; $estado=$_POST['estado']; $pais=$_POST['pais']; $poblacion=$_POST['poblacion']; $usuario=$_SESSION[NOMBREUSUARIO]; $despoblacion=$_POST['despoblacion']; $existe=$_POST['existe'];



	



 if($accion==""){



 	$row=folio('catalogocolonia','webpmm');



	$codigo=$row[0];



 }else if($accion=="grabar"){		



		$sqlins="INSERT INTO catalogocolonia (id, descripcion, cp, poblacion, usuario, fecha)VALUES('null', UCASE('$descripcion'), '$cp', '$poblacion', '$usuario', current_timestamp())";



		$res=mysql_query($sqlins,$link);



		$codigo=mysql_insert_id();



		$mensaje = 'Los datos han sido guardados correctamente';



		$accion="modificar";



	}else if($accion=="modificar"){



		$sqlupd="UPDATE catalogocolonia SET descripcion=UCASE('$descripcion'), cp='$cp', poblacion='$poblacion', usuario='$usuario', fecha=current_timestamp() WHERE id='$codigo'";



		$res=mysql_query($sqlupd,$link);



		$mensaje = 'Los cambios han sido guardados correctamente';	



	}else if($accion=="limpiar"){



$cp=""; $municipio=""; $estado=""; $pais=""; $poblacion=""; $despoblacion=""; 		$tipocliente=""; $descripcion=""; $codigo=""; $mensaje=""; $accion=""; $existe=""; $usuario=$_SESSION[NOMBREUSUARIO]; $row=folio('catalogocolonia','webpmm'); $codigo=$row[0];



	}



?>



<html xmlns="http://www.w3.org/1999/xhtml">



<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />



<script src="../../javascript/shortcut.js"></script>



<script language="JavaScript" type="text/javascript">



function Limpiar(){



document.getElementById('cp').value=0; document.getElementById('descripcion').value=""; document.getElementById('poblacion').value=""; document.getElementById('estado').value=""; document.getElementById('municipio').value=""; document.getElementById('despoblacion').value=""; document.getElementById('pais').value=""; document.all.existe.value="";



document.getElementById('accion').value = "limpiar";



document.form1.submit();



}



function validar(){	
	<?=$cpermiso->verificarPermiso(285,$_SESSION[IDUSUARIO]);?>


	if(document.getElementById('descripcion').value==""){



			alerta('Debe capturar Descripción', '¡Atención!','descripcion');



	}else if(document.getElementById('cp').value==""){



			alerta('Debe capturar Código Postal', '¡Atención!','cp');



	}else if(document.getElementById('existe').value=="NO"){



		alerta('El Código Postal no existe', '¡Atención!','cp');	



	}else if(document.all.cp.value.length < 5){



		alerta('El Código Postal debe contener 5 numeros', '¡Atención!','cp');



	}else if(document.getElementById('poblacion').value==""){



			alerta('Debe capturar Población', '¡Atención!','poblacion');



	}else{



			if(document.getElementById('accion').value==""){



			document.getElementById('accion').value = "grabar";



				document.form1.submit();



			}else if(document.getElementById('accion').value=="modificar"){



				document.form1.submit();



			}



	}



}



function limpiartodo(){



document.getElementById('cp').value=0; document.getElementById('descripcion').value=""; document.getElementById('poblacion').value=""; document.getElementById('estado').value=""; document.getElementById('municipio').value=""; document.getElementById('despoblacion').value=""; document.getElementById('pais').value=""; document.all.existe.value="";



}



function obtener(id){



	if(id!=""){



		document.getElementById('codigo').value=id;



		document.getElementById('accion').value="modificar";



		consulta("mostrarColonia","consultas.php?accion=1&colonia="+id);	



	}







}



function obtenerPoblacionx(id){



	if(id!=""){



		document.getElementById('poblacion').value=id;		



		consulta("mostrarPoblacion","consultas.php?accion=2&poblacion="+id);



	}



}



function obtenerColoniax(id){



	if(id!=""){



		document.getElementById('codigo').value=id;		



		consulta("mostrarColonia","consultas.php?accion=1&colonia="+id);



	}



}



function mostrarColonia(datos){



	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;



		var u = document.all;



		limpiartodo();



		



		if(con>0){			



			u.descripcion.value = datos.getElementsByTagName('descripcion').item(0).firstChild.data;



			u.cp.value = datos.getElementsByTagName('cp').item(0).firstChild.data;



			u.poblacion.value = datos.getElementsByTagName('poblacion').item(0).firstChild.data;



			u.despoblacion.value = datos.getElementsByTagName('despoblacion').item(0).firstChild.data;



			u.estado.value = datos.getElementsByTagName('estado').item(0).firstChild.data;



			u.municipio.value = datos.getElementsByTagName('municipio').item(0).firstChild.data;



			u.pais.value = datos.getElementsByTagName('pais').item(0).firstChild.data;



		}



}



function mostrarPoblacion(datos){



	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;



		var u = document.all;



u.estado.value=""; u.municipio.value=""; u.despoblacion.value=""; u.pais.value="";



		



		if(con>0){



			u.despoblacion.value = datos.getElementsByTagName('despoblacion').item(0).firstChild.data;



			u.estado.value = datos.getElementsByTagName('estado').item(0).firstChild.data;



			u.municipio.value = datos.getElementsByTagName('municipio').item(0).firstChild.data;



			u.pais.value = datos.getElementsByTagName('pais').item(0).firstChild.data;



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



function ObtenerCodigoPostal(cp){



	 if(cp!=""){



consulta("ExisteCP", "consultas.php?accion=4&codigopostal="+document.all.cp.value+"&valrandom="+Math.random());		



	}



}



function ExisteCP(datos){



	var u = document.all;



	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;



	



		if(con>0){



u.existe.value = datos.getElementsByTagName('existe').item(0).firstChild.data;



		}else{



		u.existe.value = "NO";



		}



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



	}else if(nombrecaja=="poblacion"){



		document.getElementById('oculto').value="2";



	}



}



shortcut.add("Ctrl+b",function() {



	if(document.form1.oculto.value=="1"){



	abrirVentanaFija('buscar.php?tipo=colonia', 550, 450, 'ventana', 'Busqueda')



	}else if(document.form1.oculto.value=="2"){



	abrirVentanaFija('buscarPoblacion.php', 550, 430, 'ventana', 'Busqueda')



	}



});



var nav4 = window.Event ? true : false;



function Numeros(evt){ 



// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46, ',' = 44 



var key = nav4 ? evt.which : evt.keyCode; 



return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);







}		



</script>



<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



<title>Cat&aacute;logo Colonias</title>



<style type="text/css">	



	



	/* Big box with list of options */



	#ajax_listOfOptions{



		position:absolute;	/* Never change this one */



		width:80px;	/* Width of box */



		height:250px;	/* Height of box */



		overflow:auto;	/* Scrolling features */



		border:1px solid #317082;	/* Dark green border */



		background-color:#FFF;	/* White background color */



		text-align:left;



		font-size:0.9em;



		z-index:100;



	}



	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */



		margin:1px;		



		padding:1px;



		cursor:pointer;



		font-size:0.9em;



	}



	#ajax_listOfOptions .optionDiv{	/* Div for each item in list */



		



	}



	#ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */



		background-color:#317082;



		color:#FFF;



	}



	#ajax_listOfOptions_iframe{



		background-color:#F00;



		position:absolute;



		z-index:5;



	}



	



	form{



		display:inline;



	}



	



	</style>



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



<script type="text/javascript" src="../../javascript/ajaxlist/ajax.js"></script> 



<script type="text/javascript" src="../../javascript/ajaxlist/ajax-dynamic-list.js"></script>



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







<body onLoad="document.form1.descripcion.focus()">



<form name="form1" method="post" action="">



  <table width="100%" border="0">



    <tr>



      <td><table width="450" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">



          <tr>



            <td width="563" class="FondoTabla">CAT&Aacute;LOGO COLONIAS</td>



          </tr>



          <tr>



            <td><table width="400" border="0" align="center" cellpadding="0" cellspacing="0">



              <tr>



                <td width="78" class="Tablas"><strong>C&oacute;digo:</strong></td>



                <td width="262"><label>



                  <input name="codigo" type="text" id="codigo" size="10" value="<?=$codigo ?>" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" style="font:tahoma; font-size:9px; background:#FFFF99" readonly="">



                  &nbsp;&nbsp; <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscarColonia.php', 600, 550, 'ventana', 'Busqueda')">



                  &nbsp;&nbsp;</label></td>



              </tr>



              <tr>



                <td class="Tablas">Descripci&oacute;n:</td>



                <td><input name="descripcion" type="text" id="descripcion" style="text-transform:uppercase;font:tahoma; font-size:9px" onBlur="trim(document.getElementById('descripcion').value,'descripcion');" onKeyPress="return tabular(event,this)" value="<?=$descripcion ?>" size="59" maxlength="100"></td>



              </tr>



              <tr>



                <td class="Tablas">C&oacute;digo Postal: </td>



                <td><input name="cp" type="text" class="Tablas" id="cp" style="font-size:9px; text-transform:uppercase" onKeyPress="if(event.keyCode==13){document.all.poblacion.focus(); document.all.oculto.value=2;}else{return Numeros(event)}" onBlur="ObtenerCodigoPostal(this.value)" onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event,'ajax-list-cp.php')"  value="<?=$cp ?>" size="5" maxlength="5" ></td>



              </tr>



              



              <tr>



                <td class="Tablas">Poblaci&oacute;n:</td>



                <td><input name="poblacion" type="text" id="poblacion" size="10" value="<?=$poblacion ?>" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" style="font:tahoma; font-size:9px; background:#FFFF99" readonly="">



&nbsp;&nbsp; <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscarPoblacion.php', 600, 500, 'ventana', 'Busqueda')">&nbsp;



<input name="despoblacion" type="text" id="despoblacion" size="30" value="<?=$despoblacion ?>" style=" font:tahoma; font-size:9px; background:#FFFF99" readonly=""></td>



              </tr>



              <tr>



                <td class="Tablas">Mun/Del.:</td>



                <td><input name="municipio" type="text" id="municipio"  onKeyPress="return tabular(event,this)" readonly="" size="59" value="<?=$municipio ?>" style="text-transform:uppercase;font:tahoma; font-size:9px;background:#FFFF99"></td>



              </tr>



              <tr>



                <td class="Tablas">Estado:</td>



                <td><input name="estado" type="text" id="estado" size="25" value="<?=$estado ?>" style=" font:tahoma; font-size:9px; background:#FFFF99" readonly="">



                  <span class="Tablas">&nbsp;&nbsp;Pais:&nbsp;&nbsp;



                  <input name="pais" type="text" id="pais" size="21" value="<?=$pais ?>" style=" font:tahoma; font-size:9px; background:#FFFF99" readonly="">



                  </span></td>



              </tr>



              <tr>



                <td class="Tablas">&nbsp;</td>



                <td><label></label></td>



              </tr>



              



              <tr>



                <td height="32"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">



                  <span class="Tablas">



                  <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" />



                  <input name="idcp" type="hidden" id="cp_hidden" value="<?=$idcp ?>" />



                  <input name="existe" type="hidden" id="existe" value="<?=$existe ?>">



                  </span></td>



                <td><table width="141" border="0" align="right">



                  <tr>



                    <td width="67"><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" onClick="validar();" style="cursor:pointer" ></td>



                    <td width="64"><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" title="Nuevo" onClick="confirmar('Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'Limpiar();', '')" style="cursor:pointer" ></td>



                  </tr>



                </table></td>



              </tr>



              <tr>



                <td height="32">&nbsp;</td>



                <td><table width="50" border="0" align="center" cellpadding="0" cellspacing="0">



                  <tr>



                    <td></td>



                  </tr>



                </table></td>



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