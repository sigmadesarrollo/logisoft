<? session_start();







	if(!$_SESSION[IDUSUARIO]!=""){







		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");





	}















	include('../../Conectar.php');







	$link=Conectarse('webpmm');







	$usuario=$_SESSION[NOMBREUSUARIO];







	$accion=$_POST['accion'];







	$codigo=$_POST['codigo'];







	$descripcion=$_POST['descripcion'];







	$slclasificacion=$_POST['slclasificacion'];







	$slcolor=$_POST['slcolor'];







	$autorizacion = (($_POST[autorizacion]!="")?1:0);







	







	if($accion==""){		







		$r=folio('catalogomotivos','webpmm');







		$codigo=$r[0];







	}else if($accion=="grabar"){







		$result=@mysql_query("INSERT INTO catalogomotivos (id, descripcion, clasificacion, color, autorizacion, usuario, fecha)VALUES(null,UCASE('$descripcion'),UCASE('$slclasificacion'),UCASE('$slcolor'),'$autorizacion',UCASE('$usuario'),current_timestamp())",$link);







		$codigo=mysql_insert_id();







		$msg ='Los datos han sido guardados correctamente.';







		$accion="modificar";







	}else if($accion=="modificar"){







		$sqlm=mysql_query("UPDATE catalogomotivos SET descripcion=UCASE('$descripcion'), clasificacion=UCASE('$slclasificacion')







, color=UCASE('$slcolor'), autorizacion='$autorizacion', usuario='$usuario', fecha=current_timestamp() WHERE id=$codigo",$link);







		$msg ='Los cambios han sido guardados correctamente.';







	}else if($accion=="limpiar"){







	$accion=''; $codigo=''; $descripcion=''; $slclasificacion=''; $slcolor=''; $autorizacion='';







	$r=folio('catalogomotivos','webpmm');







	$codigo=$r[0];







	}















?>







<html>







<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />







<title></title>







<script src="../../javascript/shortcut.js"></script>







<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>







<script type="text/javascript" src="../../javascript/ventana-modal-1.1.1.js"></script>







<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>







<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>







<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>







<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">







<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">























<link href="FondoTabla.css" rel="stylesheet" type="text/css">







<link href="Tablas.css" rel="stylesheet" type="text/css">







<script type="text/javascript" src="../../javascript/ajax.js"></script>







<script type="text/javascript" src="select.js"></script>















<style type="text/css">







<!--







.style1 {







	font-size: 14px;







	font-weight: bold;







	color: #FFFFFF;







}







.style2 {







	color: #464442;







	font-size:9px;







	border: 0px none;







	background:none







}







.style3 {







	font-size: 9px;







	color: #464442;







}







.style4 {color: #025680;font-size:9px }







.style5 {color: #FFFFFF ; font-size:9px}







-->







<!--







.Estilo1 {







	color: #FFFFFF;







	font-weight: bold;







	font-size: 13px;







	font-family: tahoma;







}







-->







.Button {







margin: 0;







padding: 0;







border: 0;







background-color: transparent;







width:70px;







height:20px;







}







</style>















<script language="javascript">







function validar(){







	if(document.getElementById('descripcion').value==""){







	alerta('Debe Capturar descripción', '¡Atención!','descripcion');







	}else if(document.getElementById('slclasificacion').value==""){







	alerta('Debe Capturar clasificación', '¡Atención!','slclasificacion');







	}else if(document.getElementById('slcolor').value==""){







		alerta('Debe Capturar Color', '¡Atención!','slcolor');







	}else{







			if(document.getElementById('accion').value==""){







				document.getElementById('accion').value="grabar";







				document.form1.submit();	







			}else{







				document.getElementById('accion').value="modificar";







				document.form1.submit();







			}







	}	







}















function limpiar(){







	document.getElementById('codigo').value="";







	document.getElementById('descripcion').value=""; 







	document.getElementById('slclasificacion').value="0";







	document.getElementById('slcolor').value="0"; 







	document.getElementById('accion').value="limpiar"; 







	document.all.autorizacion.checked = false;





	document.form1.submit();



}







//*********************************************//







function obtener(valor,tipo){







		document.all.codigo.value = valor;







consulta("mostrarCatalogoMotivo","CatalogoMotivosResult.php?accion=1"+"&id="+valor+"&sid="+Math.random());







}















function mostrarCatalogoMotivo(datos){







	var u= document.all;







	u.descripcion.value = datos.getElementsByTagName('descripcion').item(0).firstChild.data;







	u.slclasificacion.value = datos.getElementsByTagName('slclasificacion').item(0).firstChild.data;







	u.slcolor.value = datos.getElementsByTagName('slcolor').item(0).firstChild.data;







	u.accion.value = datos.getElementsByTagName('accion').item(0).firstChild.data;







	u.autorizacion.checked = ((datos.getElementsByTagName('autorizacion').item(0).firstChild.data==1)?true:false);







}







function foco(nombrecaja){







	if(nombrecaja=="codigo"){







		document.getElementById('oculto').value="1";







	}







}







shortcut.add("Ctrl+b",function() {







	if(document.form1.oculto.value=="1"){







abrirVentanaFija('buscarMotivos.php', 550, 430, 'ventana', 'Busqueda')







	}







});















</script>























<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css">







</head>







<body onLoad="document.all.descripcion.focus();">







<form id="form1" name="form1" method="post" >







<br>







  <table width="350px" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193" class="Tablas">







    <tr>







      <td class="FondoTabla">CAT&Aacute;LOGO MOTIVOS</td>







    </tr>







    <tr>







      <td><table width="300" border="0" align="center" cellspacing="0" class="Tablas">







          <tr>







            <td width="54" class="Tablas" onClick="document.form1.submit()">Codigo:</td>







            <td width="242"><label>







              <input name="codigo" type="text" class="Tablas" id="codigo" value="<?=$codigo ?>" readonly="" size="10" style="background:#FFFF99; font:tahoma" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''">







              <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscarMotivos.php', 600, 500, 'ventana', 'Busqueda')"></label></td>







          </tr>







          <tr>







            <td colspan="2"><table width="284" border="0" cellspacing="0" cellpadding="0">







              <tr>







                <td width="20"><label>







                  <input name="autorizacion" type="checkbox" id="autorizacion" value="1">







                </label></td>







                <td width="264">Previa Autorización</td>







              </tr>







            </table></td>







          </tr>







          <tr>







            <td>Descripci&oacute;n:</td>







            <td><input name="descripcion" type="text" class="Tablas" id="descripcion" value="<?=$descripcion ?>" size="44" style="text-transform:uppercase"></td>







          </tr>







          <tr>







            <td>Clasificaci&oacute;n:</td>







            <td><select name="slclasificacion" class="Tablas" id="slclasificacion" style="width:195px">







                <option value="0">SELECCIONAR</option>







                <option value="CANCELACION GUIAS" <? if($slclasificacion=="CANCELACION GUIAS"){echo "selected";}?> >CANCELACION GUIAS</option>







                <option value="REPROGRAMACION RECOLECCION" <? if($slclasificacion=="REPROGRAMACION RECOLECCION"){echo "selected";}?> >REPROGRAMACION RECOLECCION</option>







                <option value="CANCELACION RECOLECCION" <? if($slclasificacion=="CANCELACION RECOLECCION"){echo "selected";}?> >CANCELACION RECOLECCION</option>







                <option value="DEVOLUCION MERCANCIA EAD" <? if($slclasificacion=="DEVOLUCION MERCANCIA EAD"){echo "selected";}?> >DEVOLUCION MERCANCIA EAD</option>







				<option value="TRASPASO MERCANCIA CORM" <? if($slclasificacion=="TRASPASO MERCANCIA CORM"){echo "selected";}?> >TRASPASO MERCANCIA CORM</option>







				<option value="NO REVISION FACTURAS" <? if($slclasificacion=="NO REVISION FACTURAS"){echo "selected";}?> >NO REVISION FACTURAS</option>

                

            </select></td>







          </tr>







          <tr>







            <td>Color:</td>







            <td><select name="slcolor" class="Tablas" id="slcolor" style="width:195px">







                <option value="0">SELECCIONAR</option>







                <option value="ROJO"  <? if($slcolor=="ROJO"){echo "selected";}?>  >ROJO</option>







                <option value="AZUL" <? if($slcolor=="AZUL"){echo "selected";}?> >AZUL</option>







                <option value="AMARILLO"  <? if($slcolor=="AMARILLO"){echo "selected";}?> >AMARILLO</option>







                <option value="MORADO"  <? if($slcolor=="MORADO"){echo "selected";}?> >MORADO</option>







                <option value="ROSA" <? if($slcolor=="ROSA"){echo "selected";}?> >ROSA</option>







                <option value="VERDE"  <? if($slcolor=="VERDE"){echo "selected";}?> >VERDE</option>







                <option value="GRIS"  <? if($slcolor=="GRIS"){echo "selected";}?> >GRIS</option>







                <option value="CAFE"  <? if($slcolor=="CAFE"){echo "selected";}?> >CAFE</option>







                <option value="NARANJA"  <? if($slcolor=="NARANJA"){echo "selected";}?> >NARANJA</option>







            </select></td>







          </tr>







          <tr>







            <td colspan="2" align="right"><div align="left">







                <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" />







                <input name="accion" type="hidden" id="accion" value="<?=$accion ?>">







                <table width="20" border="0" align="right" cellpadding="1">







                  <tr>







                    <td><img src="../../img/Boton_Guardar.gif" alt="enviar" width="70" height="20" onClick="validar();"  /></td>







                    <td><img src="../../img/Boton_Nuevo.gif" alt="enviar" width="70" height="20" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')" /></td>







                  </tr>







                </table>







            </div></td>







          </tr>







          <tr>







            <td colspan="2" align="right"></td>







          </tr>







      </table></td>







    </tr>







  </table>







</form>







</body>







<script>







	parent.frames[1].document.getElementById('titulo').innerHTML = 'CATÁLOGO MOTIVOS';







</script>







<? //} ?>















<? 







if ($msg!=""){







	echo "<script language='javascript' type='text/javascript'>info('".$msg."', 'Operación realizada correctamente');</script>";







	}







	







	//} 







?>