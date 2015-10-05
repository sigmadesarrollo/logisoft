<?	session_start();



	if(!$_SESSION[IDUSUARIO]!=""){



		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");



	}



	require_once('../../Conectar.php');



	$l = Conectarse('webpmm');







	if($_POST[accion]=="grabar"){



			$s = "INSERT INTO configuradorpromociones 



			(despues, porcentaje,porcA, porcB, usuario, fecha) 



			VALUES



	(".$_POST[despues].",".$_POST[porcentaje].",".$_POST[a].",".$_POST[b].",".$_SESSION[IDUSUARIO].",current_timestamp())";



				$r = mysql_query($s, $l) or die($s);



	}else if ($_POST[accion]=="modificar"){



	



			$s = "UPDATE configuradorpromociones SET despues=".$_POST[despues].", porcentaje=".$_POST[porcentaje].",porcA=".$_POST[a].", porcB=".$_POST[b]." WHERE id=".$_POST[id]."";



				$r = mysql_query($s, $l) or die($s);



	



	}



	



?>



<html xmlns="http://www.w3.org/1999/xhtml">



<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />



<script language="javascript" src="../../javascript/ClaseTabla.js"></script>



<script src="../../javascript/ajax.js"></script>



<script language="javascript" src="../../javascript/funcionesDrag.js"></script>



<script language="javascript" src="../../javascript/ClaseMensajes.js"></script>







<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">



<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">



<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />



<script>



	var u = document.all;



	var mens = new ClaseMensajes();



	mens.iniciar('../../javascript',true);







	window.onload = function(){



		u.despues.focus();



		obtenerDetalles();



	}



	



	function obtenerDetalles(){



		consultaTexto("mostrarDetalle","configuradorpromociones_con.php?accion=1");	



	}



	



	function mostrarDetalle(datos){



		if(datos!=0){



			var obj				= eval(convertirValoresJson(datos));



			u.despues.value		= obj[0].despues;



			u.porcentaje.value	= obj[0].porcentaje;



			u.a.value 			= obj[0].porcA;



			u.b.value 			= obj[0].porcB;



			u.id.value			= obj[0].id;



			u.accion.value = "modificar";



		}else{



			u.accion.value = "grabar";



		}



	}



	



	function limpiarTodo(){



		u.despues.value 	= "";



		u.porcentaje.value	= "";



		u.a.value			= "";



		u.b.value			= "";



		u.id.value			= "";



		obtenerDetalles();



	}



	



	function validar(){



		if(u.despues.value==""){



			//alerta('Debe agregar el año','¡Atención!','despues');



			mens.show("A","Debe agregar el año","¡Atención!","");



		}else if (u.porcentaje.value==""){



			//alerta('Debe agregar porcentaje','¡Atención!','porcentaje');



			mens.show("A","Debe agregar porcentaje","¡Atención!","");



		}else if (u.a.value==""){



			//alerta('Debe agregar el porcentaje tipo A','¡Atención!','a');



			mens.show("A","Debe agregar el porcentaje tipo A","¡Atención!","");



		}else if(u.b.value==""){



			//alerta('Debe agregar el porcentaje tipo B','¡Atención!','b');



			mens.show("A","Debe agregar el porcentaje tipo B","¡Atención!","");



		}else{



				if (u.accion.value=="grabar"){



					//info("Los datos han sido guardados correctamente","");



					mens.show("I","Los datos han sido guardados correctamente","¡Atención!","");



					u.guardar.style.visibility="hidden";



					document.form1.submit();



				}else if (u.accion.value=="modificar"){



					//info("Los datos han sido modificados correctamente","");



					mens.show("I","Los datos han sido modificados correctamente","¡Atención!","");



					u.guardar.style.visibility="hidden";



					document.form1.submit();



				}



				



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



            else if (frm.elements[i+1].readOnly ==true )    



                tabular(e,frm.elements[i+1]);



            else frm.elements[i+1].focus();



            return false;



}  



</script>



<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



<title>Documento sin t&iacute;tulo</title>



<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css">



<link href="Tablas.css" rel="stylesheet" type="text/css">



</head>







<body>



<form name="form1" method="post" action="">



  <table width="316" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">



    <tr>



      <td width="308" class="FondoTabla">CONFIGURADOR DE PROMOCIONES</td>



    </tr>



    <tr>



      <td><table width="306" border="0" align="center" cellpadding="0" cellspacing="0">



        



        <tr>



          <td>&nbsp;</td>



          <td colspan="3">&nbsp;</td>



        </tr>



        <tr>



          <td width="45">Despues:</td>



          <td width="99"><label>



            <input name="despues" type="text" class="Tablas" id="despues" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="<?=$despues ?>" size="8" maxlength="3">



          



          A&ntilde;os</label></td>



          <td width="58">Porcentaje:</td>



          <td width="104"><input name="porcentaje" type="text" class="Tablas" id="porcentaje" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="<?=$porcentaje ?>" size="8" maxlength="3">



%</td>



        </tr>



        



        <tr>



          <td><div align="left">Tipo A:</div></td>



          <td><input name="a" type="text" class="Tablas" id="a" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="<?=$a ?>" size="8" maxlength="3">



            %</td>



          <td>Tipo B: </td>



          <td><input name="b" type="text" class="Tablas" id="b" onKeyPress="return Numeros(event)" value="<?=$b ?>" size="8" maxlength="3">



%</td>



        </tr>



        



        <tr>



          <td colspan="4" align="center">&nbsp;</td>



        </tr>



        <tr>



          <td colspan="4" align="center"><table width="161" align="center">



            <tr>



              <td width="160"><table width="155" border="0">



                  <tr>



                    <td width="75" ><div id="guardar" style="visibility:visible" class="ebtn_guardar" onClick="validar()" ></div></td>



                    <td width="70"><div class="ebtn_nuevo" id="nuevo" onClick="limpiarTodo()"/></td>



                  </tr>



              </table></td>



            </tr>



          </table>



            <a href="../menu/webministator.php">



            <input name="accion" type="hidden" id="accion" value="<?=$accion ?>">



            <input name="id" type="hidden" id="id" value="<?=$id ?>">



            </a></td>



        </tr>



        <tr>



          <td colspan="4" align="center"></td>



        </tr>



      </table></td>



    </tr>



  </table>







</form>



</body>



<script>



//	parent.frames[1].document.getElementById('titulo').innerHTML = 'CONFIGURADOR DE PROMOCIONES';



</script>



</html>



