<? session_start();



	if(!$_SESSION[IDUSUARIO]!=""){



		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");



	}



		include('../../Conectar.php');	



		$link=Conectarse('webpmm'); 



		$accion=$_POST['accion']; 



		$codigo=$_POST['codigo']; 



		$descripcion=$_POST['descripcion']; 	



	 if($accion==""){



		$row = folio('catalogotipoproveedor','webpmm');



		$codigo=$row[0];



	 }



	if($accion=="grabar"){		



		$sqlins="INSERT INTO catalogotipoproveedor 



		(descripcion, usuario, fecha)VALUES( '$descripcion', '".$_SESSION[NOMBREUSUARIO]."', current_timestamp())";



		$res=mysql_query($sqlins,$link);



		$codigo=mysql_insert_id();



		$mensaje = 'Los datos han sido guardados correctamente';



		$accion="modificar";



	



	}else if($accion=="modificar"){



		$sqlupd="UPDATE catalogotipoproveedor 



		SET descripcion='$descripcion', usuario='".$_SESSION[NOMBREUSUARIO]."', 



		fecha=current_timestamp() WHERE id='$codigo'";



		$res=mysql_query($sqlupd,$link);



		$mensaje = 'Los cambios han sido guardados correctamente';	



		



	}else if($accion=="limpiar"){



		$descripcion="";



		$codigo="";



		$msg="";



		$accion="";



		$usuario=$_SESSION[NOMBREUSUARIO];



		$row = folio('catalogotipoproveedor','webpmm');



		$codigo=$row[0];



	}



?>



<script src="../../javascript/shortcut.js"></script>



<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>



<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>



<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>



<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>



<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>



<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">



<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">



<link href="../../recoleccion/Tablas.css" rel="stylesheet" type="text/css">



<link href="../../FondoTabla.css" rel="stylesheet" type="text/css">



<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />



<script language="JavaScript" type="text/javascript">



function limpiar(){



	document.getElementById('descripcion').value="";



	document.getElementById('accion').value = "limpiar";



	document.form1.submit();



}



function validar(){



	if(document.getElementById('descripcion').value==""){



			alerta('Debe capturar Descripción', '¡Atención!','descripcion');



			document.getElementById('descripcion').focus();



	}else{



			if(document.getElementById('accion').value==""){



				document.getElementById('accion').value = "grabar";



				document.form1.submit();



			}else if(document.getElementById('accion').value="modificar"){



				document.form1.submit();



			}



	}



}



function obtener(id,descripcion){



	document.getElementById('codigo').value=id;



	document.getElementById('descripcion').value=descripcion;



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



abrirVentanaFija('buscartipoproveedor.php', 550, 450, 'ventana', 'Busqueda')



	}



});



</script>



<html xmlns="http://www.w3.org/1999/xhtml">



<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />



<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



<title>Documento sin t&iacute;tulo</title>



<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />



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



<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />



<style type="text/css">



<!--



.Balance {background-color: #FFFFFF; border: 0px none}



.Balance2 {background-color: #DEECFA; border: 0px none;}



-->



</style>



<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />



<style type="text/css">



<!--



.Estilo5 {font-size: 14px}



.Estilo6 {font-size: 12px}



-->



</style>



</head>



<body>



<form id="form1" name="form1" method="post" action="">



  <br>



  <table width="300" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">



    <tr>



      <td width="203" class="FondoTabla">CAT&Aacute;LOGO TIPO DE PROVEEDOR </td>



    </tr>



    <tr>



      <td>



	    <table width="280" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">



        <tr>



          <td colspan="3"><table width="203" border="0" cellspacing="0" cellpadding="0">



            <tr>



              <td style="width:62px" class="Tablas">Codigo</td>



              <td width="101"><input name="codigo" type="text" id="codigo" class="Tablas"  value="<?= $codigo ?>" style=" width:100px; font:tahoma; font-size:9px; background:#FFFF99" readonly="" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''"></td>



              <td width="32"><div class="ebtn_buscar" onClick="abrirVentanaFija('buscartipoproveedor.php', 550, 450, 'ventana', 'Busqueda')"></div></td>



            </tr>



          </table></td>



        </tr>



        <tr>



          <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">



            <tr>



              <td style="width:60px" class="Tablas">Descripci&oacute;n</td>



              <td width="180"><span class="Tablas">



                <input name="descripcion" type="text" class="Tablas" id="descripcion" style="width:200px" value="<?=$descripcion ?>" />



              </span></td>



            </tr>



          </table></td>



        </tr>



        <tr>



          <td width="93"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">



            <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" /></td>



          <td width="70"><div class="ebtn_guardar" onClick="validar();"></div></td>



          <td width="87"><div class="ebtn_nuevo" onClick="confirmar('Perdera la información capturada ¿Desea continuar?', '', 'limpiar();', '')"></div></td>



        </tr>



        <tr>



          <td colspan="3"></td>



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



//	}



?>