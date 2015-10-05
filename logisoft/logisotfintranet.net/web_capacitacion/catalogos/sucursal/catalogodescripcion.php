<? session_start();



	if(!$_SESSION[IDUSUARIO]!=""){



		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");



	}



	/*if ( isset ( $_SESSION['gvalidar'] )!=100 ){



	 echo "<script language='javascript' type='text/javascript'>



						document.location.href='../index.php';



					</script>";



	}else{*/



	require_once('../../Conectar.php');



	$link=Conectarse('webpmm');	



	$usuario=$_SESSION[NOMBREUSUARIO]; $accion=$_POST['accion']; $codigo=$_POST['codigo']; $descripcion=$_POST['descripcion'];	







	if($accion==""){



	$row=folio('catalogodescripcion','webpmm');



	$codigo=$row[0];



	



	}else if($accion=="grabar"){			



	$sqlins=mysql_query("INSERT INTO catalogodescripcion (id, descripcion, usuario, fecha)VALUES('null', UCASE('$descripcion'), '$usuario', current_timestamp())",$link);



		$codigo=mysql_insert_id();		



		$mensaje='Los datos han sido guardados correctamente';



		$accion="modificar";



					



	}else if($accion=="modificar"){



		$sqlupd=mysql_query("UPDATE catalogodescripcion SET descripcion=UCASE('$descripcion'), usuario='$usuario', fecha=current_date() WHERE id='$codigo'",$link);		



			$mensaje='Los cambios han sido guardados correctamente';			



	}elseif($accion=="limpiar"){



$codigo=""; $descripcion=""; $usuario=$_SESSION[NOMBREUSUARIO]; $accion="";



	$row=folio('catalogodescripcion','webpmm');



	$codigo=$row[0];



	}







?>



<html xmlns="http://www.w3.org/1999/xhtml">



<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />



<script src="../../javascript/shortcut.js"></script>



<script src="select.js"></script>



<script language="javascript" type="text/javascript">



function validar(){



	if(document.getElementById('codigo').value==""){



		document.getElementById('codigo').focus();



		alert('Debe capturar Código');


	}else{



		if(document.getElementById('accion').value==""){



			document.getElementById('accion').value = "grabar";



			document.form1.submit();



		}else if(document.getElementById('accion').value=="modificar"){



			document.form1.submit();



		}



	}



}



function obtener(codigo,descripcion){



	document.getElementById('codigo').value=codigo;



	document.getElementById('descripcion').value=descripcion;



	document.getElementById('accion').value="modificar"



}



function obtenerDes(codigo){



	if(codigo!=""){



	document.getElementById('codigo').value=codigo;



	ConsultarDescripcion(codigo);



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



function limpiar(){	



	document.getElementById('codigo').value="";



	document.getElementById('descripcion').value="";



	document.getElementById('accion').value = "limpiar";



	document.form1.submit();



}



function tabular(e,obj) { 



  tecla=(document.all) ? e.keyCode : e.which; 



  if(tecla!=13) return; 



  frm=obj.form; 



  for(i=0;i<frm.elements.length;i++) 



    if(frm.elements[i]==obj) { 



      if (i==frm.elements.length-1) i=-1; 



      break } 



  frm.elements[i+1].focus(); 



  return false;  



}



function mostrar(capa){



  var obj = document.getElementById(capa)



  if(obj.style.visibility== "hidden")  obj.style.visibility= "visible";



  else obj.style.visibility= "hidden";



}



function foco(nombrecaja){



	if(nombrecaja=="codigo"){



		document.getElementById('oculto').value="1";



	}



}



shortcut.add("Ctrl+b",function() {



	if(document.form1.oculto.value=="1"){



abrirVentanaFija('buscarcatdescripcion.php', 550, 450, 'ventana', 'Busqueda')	}



});



</script>



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



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Catálogo Descripción</title>



</head>







<body onload = "document.form1.descripcion.focus();">



<form id="form1" name="form1" method="post" action="">



  <table width="100%" border="0">   



    <tr>



      <td><table width="310" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">



        <tr>



          <td class="FondoTabla">CATÁLOGO DESCRIPCIÓN</td>



        </tr>



        <tr>



          <td><br><table width="300" border="0" align="center" cellpadding="0">



            <tr>



              <td class="Tablas">C&oacute;digo:</td>



              <td><span class="Tablas">



                <input readonly="" name="codigo" class="Tablas" type="text" id="codigo" style="background-color: #FFFF99; font-size:10px; font:tahoma" value="<?=$codigo; ?>" size="10" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" />



              </span><img src="../../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscarcatdescripcion.php', 600, 500, 'ventana', 'Busqueda')"></td>



            </tr>



            <tr>



              <td width="26%" class="Tablas">Descripci&oacute;n:</td>



              <td width="74%"><input name="descripcion" class="Tablas" type="text" style="font-size:10px; font:tahoma; text-transform:uppercase" onBlur="trim(document.getElementById('descripcion').value,'descripcion');" onKeyPress="return tabular(event,this);" value="<?=$descripcion ?>" size="40" /></td>



            </tr>



            



            



            <tr>



              <td class="Tablas"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">



                <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>"></td>



              <td><table width="60" border="0" align="right">



                <tr>



                  <td><img src="../../img/Boton_Guardar.gif" width="70" height="20" onClick="validar();" style="cursor:pointer"></td>



                  <td><img src="../../img/Boton_Nuevo.gif" width="70" height="20" style="cursor:pointer" onClick="confirmar('Perdera la información capturada ¿Desea continuar?', '', 'limpiar();', '')"></td>



                </tr>



              </table></td>



            </tr>



          </table>



            </td>



        </tr>



      </table>



      <p>



      </p></td>



    </tr>



  </table>



</form>



</body>



</html>



<script>



	//parent.frames[1].document.getElementById('titulo').innerHTML = 'CATÁLOGO DESCRIPCIÓN';



</script>



<?



if ($mensaje!=""){



	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";



	}



//}



?>