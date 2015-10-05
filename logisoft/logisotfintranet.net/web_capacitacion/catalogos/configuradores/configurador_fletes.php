<?


	require_once("../../Conectar.php");


	$link = Conectarse("webpmm");


?>





<html>


<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />


<title></title>


<script type="text/javascript" src="js/ventana-modal-1.3.js"></script>


<script type="text/javascript" src="js/ventana-modal-1.1.1.js"></script>


<script type="text/javascript" src="js/abrir-ventana-variable.js"></script>


<script type="text/javascript" src="js/abrir-ventana-fija.js"></script>


<script type="text/javascript" src="js/abrir-ventana-alertas.js"></script>








<link href="css/ventana-modal.css" rel="stylesheet" type="text/css">


<link href="css/style1.css" rel="stylesheet" type="text/css">


<link href="style.css" rel="stylesheet" type="text/css">











<script type="text/javascript" src="ajax.js"></script>


<script language="javascript" src="../../javascript/funciones.js"></script>


<script>


var c_seleccionada = "0_0";


var var_load = '<img src="../../javascript/loading.gif">';


var var_boton = '<img src="../../img/Boton_Agregari.gif" width="70" height="20" style="cursor:hand" onClick="guardarDatos()">';


var guardando = 0;





function limpiar(){


for (a = 0; a < document.form1.elements.length - 1; a++)


 {


    valor = document.form1.elements[a];


 	if(valor.type=="text"  ||  valor.type=="password" || valor.type=="file" ){


 		document.form1.elements[a].value='';


    }


	if(valor.type=="checkbox"){


		document.form1.elements[a].checked=false;


	}


 }


 document.form1.txtclave2.value="";


 document.form1.txtstatus.value="";


 }








function validar()


 { 


 if (document.all.txtzonai.value==""){alerta('Introduce la Zona de Inicio', 'Atencion!','txtzonai'); document.all.txtzonai.focus(); return false;} 


 if (document.all.txtzonaf.value==""){alerta("Introduce la Zona de Final",'Atencion!','txtzonaf'); document.all.txtzonaf.focus(); return false;} 


 if (document.all.txtintervalozona.value==""){alerta("Introduce el Intervalo de Zona",'Atencion!','txtintervalozona'); document.all.txtintervalozona.focus(); return false;} 


 if (document.all.txtintervalozona.value<=0){alerta("El Intervalo de Zona no puede ser  menor ni igual a cero",'Atencion!','txtintervalozona'); document.all.txtintervalozona.focus(); return false;} 


   


 if (parseFloat(document.all.txtzonai.value)>=parseFloat(document.all.txtzonaf.value)){alerta("La Zona de Inicio es Mayor a la Zona Final",'Atencion!','txtzonaf'); document.all.txtzonai.focus(); return false;} 





  //confirmar('Se perderá la información registrada  ¿Desea continuar?', '', 'window.parent.limpiar();', 'parent.VentanaModal.cerrar();')


  cnf=confirm('Se perderá la información registrada ¿Desea continuar?'); 


  return cnf;


  /*if (cnf)


  


  {return true;}


  else


  {document.all.txtintervalozona.focus();return false;}*/


  


  


} 





function ocultar()


{





   //document.form2.elements[0].value!='' && 


   if (document.form2.txtpuede.value!='1') {


   		for (a = 0; a < document.form1.elements.length - 1; a++)


   		{


     		valor = document.form1.elements[a];


     		if(valor.type=="text"  || valor.type=="select-one" || valor.type=="password" || valor.type=="file" || valor.type=="checkbox")


      		valor.disabled=true;


   		}


   	   	document.form2.elements[0].disabled=false;


	   	document.form2.elements[0].focus();


		


   }else{


		for (a = 0; a < document.form1.elements.length - 1; a++)


   		{


     		valor = document.form1.elements[a];


     		if(valor.type=="text"  || valor.type=="select-one" || valor.type=="password" || valor.type=="file" || valor.type=="checkbox")


      		valor.disabled=false;


   		}


   	   	document.form2.elements[0].disabled=true; 


		document.form1.elements[0].focus();


		


   }


} 





	<!--


var nav4 = window.Event ? true : false;


function solonumeros(evt){


// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46


var key = nav4 ? evt.which : evt.keyCode;


return (key <= 13 || (key >= 48 && key <= 57) || key == 46);


}


//-->


</script>





<!-- es para dar de alta el catalogo -->


<? 


$r=0;


if ($_POST[txtstatus]==1 && $_POST[txtclave2]>0){





                $sql="delete from configuracion where id_empresa=1 and id_folio=".$_POST[txtclave2];  


			 	mysql_query($sql,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql");		 		 


				


				 		 


                $sql="insert into configuracion set id_empresa=1,id_folio=".$_POST[txtclave2].", zonai=".$_POST[txtzonai].",zonaf=".$_POST[txtzonaf].",intervalozona=".$_POST[txtintervalozona].",tarifai=0,tarifaf=70,intervalotarifa=5";


			 	mysql_query($sql,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql");		 		 


				


         for ($i=0;$i<=8;$i++){    		 


		 	for ($c=1;$c<=$_POST[txtcolumnas];$c++){


			    $r++;


				if ($_POST[costo.$r]=='') {$_POST[costo.$r]=0;}


				


		 		$sql="update configuraciondetalles set costo=".$_POST[costo.$r].",vazio=1 where id_empresa=1 and id_folio=".$_POST[txtclave2]." and renglon=$i and columna=$c";  


			 	mysql_query($sql,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql");		 		 





			}


		} 


    }





 ?>





<!-- es para eliminar el catalogo -->


<? if ($_POST[txtstatus]==3 && $_POST[txtclave2]>0)


{	    	


	     


	     $sql="Delete FROM configuracion where id_folio=".$_POST[txtclave2];  


	     mysql_query($sql,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql");  





 		 $sql="delete from configuraciondetalles where id_folio=".$_POST[txtclave2]; ;


   		 mysql_query($sql,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql");





		 


}?>





<!-- termina es para eliminar el catalogo -->








</head>





<? 


$disabled='disabled';


$disabled2='disabled';


$_POST[txtstatus]='1';


$disabled5='';


if($_POST[txtclave]>0)


   {


   $disabled5='disabled';


    $disabled='';


    $sql="Select * from configuracion where id_folio='". $_POST[txtclave]. "'";


	 mysql_query($sql,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql");


     $res=mysql_query($sql,$link);


     $row=mysql_fetch_array($res);


	 if ($row>0)


	    {


			$_POST[txtzonai]=$row['zonai'];


			$_POST[txtzonaf]=$row['zonaf'];


			$_POST[txtintervalozona]=$row['intervalozona'];			


	 		$_POST[txttarifai]=$row['tarifai'];


			$_POST[txttarifaf]=$row['tarifaf'];


			$_POST[txtintervalotarifa]=$row['intervalotarifa'];			


						


  			


			$disabled='';


			$disabled2='';


			$_POST[txtstatus]='2';


      }	else


	  {


            $sql="delete from configuraciondetalles where id_empresa=1 and id_folio=".$_POST[txtclave];  


			mysql_query($sql,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql");	


			


  	  }	


				   


}


else


{


			    $sql2="Select max(id_folio)+1 as folio from configuracion";


	 			mysql_query($sql2,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql2");


			    $res2=mysql_query($sql2,$link);


			    $row2=mysql_fetch_array($res2);


			    if ($row2>0)			


				{


					$_POST[txtclave]=$row2['folio'];	 		 


				}	


				else


				{


			        $_POST[txtclave]='1';	 		 


				}





					


} ?>





<body onLoad="ocultar();">


<table width="100%" border="0">


    <tr>


      <td><br></td>


    </tr>


    <tr>


      <td><table width="670" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


        <tr>


          <td class="FondoTabla">Datos de Configuraci&oacute;n</td>


        </tr>


        <tr>


          <td>


		  


		  <table width="666" border="0" cellpadding="0" class="Tablas" cellspacing="0">


		  	<tr>


				<td width="3" height="42">&nbsp;</td>


				<td colspan="2">				 </td>


			</tr>


			<tr>


				 <td colspan="4" class="Tablas">


				  <form name="form2" method="post" action="" enctype="multipart/form-data" >


				       <hr class="boxlookLila">


                	  &nbsp;&nbsp;Clave: 


                	  <input name="txtclave" type="text" style="font:tahoma;font-size:9px; text-transform:uppercase;" value ="<?= $_POST[txtclave]?>"class="CaptionL" id="txtclave"   maxlength="10" onKeyup="javascript:this.value=this.value.toUpperCase();" onKeyDown="if (window.event.keyCode==113){window.event.keyCode = 505; window.open('buscarconfiguracion.php', '_blank', 'titlebar=no,scrollbars=no,resizable=no,width=610,height=320');window.event.keyCode=0;}"  onKeyPress="if(window.event.keyCode==13){document.form2.txtpuede.value=1;document.form2.submit();void(0);}" onClick="document.form2.txtclave.value=''" onFocus="limpiar();">


					  <input type="hidden" name="txtpuede" value ="<?= $_POST[txtpuede]?>">


					  <input name="btnbuscar" type="image" onClick="JavaScript:window.open('buscarconfiguracion.php', '_blank', 'titlebar=no,scrollbars=no,resizable=no,width=610,height=320');return false;" src="../../img/Buscar_24.gif" alt="Buscar Configuraciones" width="24" height="23" <?=$disabled5?>>                  	  


				      


				      <? 


						if ($_POST[txtstatus]=='1' && $_POST[txtclave]!='' && $_POST[txtpuede]=='1' ) {echo "*** Altas ***";}


						if ($_POST[txtstatus]=='2' && $_POST[txtpuede]=='1') {echo "*** Cambios ***";}


					 ?>


					 <hr class="boxlookLila">


                   </form>				</td>


			</tr>


				<form id="form1" name="form1" method="post" > 


				<td></td>


								


		  	<tr>


				<td width="3" height="20">&nbsp;</td>


				<td width="574">


					<table width="562" border="0" cellpadding="0" cellspacing="0">


						<tr>


							<td width="73" class="Tablas">Zona Inicio : </td>


							<td width="108">


							<input type="text" maxlength="12" size="12" style="font:tahoma;font-size:9px; text-transform:uppercase; " name="txtzonai" value="<?=$_POST[txtzonai]?>" onKeyPress="if(event.keyCode==13){document.all.txtzonaf.focus();return false;}else{return solonumeros(event);}" onKeyDown="if (window.event.keyCode==27){limpiar();document.form1.submit();return false;}">							</td>


							


							<td width="69" class="Tablas">Zona Final: </td>


							<td width="134">


                            <input type="text" maxlength="12" size="12" style="font:tahoma;font-size:9px; text-transform:uppercase; " name="txtzonaf" value="<?=$_POST[txtzonaf]?>" onKeyPress="if(event.keyCode==13){document.all.txtintervalozona.focus();return false;}else{return solonumeros(event);}" onKeyDown="if (window.event.keyCode==27){limpiar();document.form1.submit();return false;}">							</td>


							


							<td width="53" class="Tablas">Intervalo: </td>


							<td width="125">


                            <input type="text" maxlength="12" size="12" style="font:tahoma;font-size:9px; text-transform:uppercase;" name="txtintervalozona" value="<?=$_POST[txtintervalozona]?>" onKeyPress="if(event.keyCode==13){document.all.costo1.focus();return false;}else{return solonumeros(event);}" onKeyDown="if (window.event.keyCode==27){limpiar();document.form1.submit();return false;}">							</td>


						</tr>


					</table>				</td>


				<td width="84" rowspan="2" align="center" id="celdaboton">





				<input name="btncrear2" type="image" src="../../img/Boton_Agregari.gif" width="70" height="20" style="cursor:hand" onClick="if(validar()){pedirDatos(document.all.txtzonai.value,document.all.txtzonaf.value,document.all.txtintervalozona.value,document.all.txtclave2.value);return false;}else{return false;}" <?=$disabled?>>				</td>


				<td width="10">&nbsp;</td>


			</tr>


		  	<tr>


		  	  <td>&nbsp;</td>


		  	  <td>			  	</td>


		  	  <td>&nbsp;</td>


		  	  </tr>


			  


		  	<tr>


		  	  <td>&nbsp;</td>


		  	  <td colspan="2" align="center">


			  


			  <?if ($_POST[txtclave]>0){?>


			  	<div id=detalle name=detalle style="  width:630px; height:250px; overflow:auto" align=center>


				<?php include('consulta.php');?>


				</div>	  


			  <?}	?>	          </td>


		  	  <td>&nbsp;</td>


		  	  </tr>


			  <tr><td>&nbsp;</td>


			  <td colspan="3" align="right">


			              <hr class="boxlookLila">


			              <input name="btnaceptar" type="image" src="../../img/Boton_Guardar.gif" style="cursor:pointer"  alt="F10"  value="Aceptar " <?=$disabled?> onClick="if(validar()){document.form1.submit();return false;} else {return false;}">


  			  	 		  <input name="txtclave2" type="hidden" class="TextBox"  value="<?=$_POST[txtclave]?>">


   				          <input name="txtstatus" type="hidden" class="TextBox"  value="<?=$_POST[txtstatus]?>">


   				          <input name="btncancelar" type="image" src="../../img/Boton_Cancelar.gif"   value="Cancelar" onClick="limpiar();document.form2.value==0;document.form1.submit();void(0);">


						  <input name="btneliminar" type="image" src="../../img/Boton_Eliminar.gif" style="cursor:pointer"  value="Eliminar"  <?=$disabled2?> onClick="cnf=confirm('¿Esta Seguro de Eliminar la Configuracion?'); if(cnf==true){document.form1.txtstatus.value=3;document.form1.submit();return false;} else {return false;}">


&nbsp;&nbsp;&nbsp;			  </td></tr>


			  <tr><td>&nbsp;</td></tr>


		  </table>


		  </td>


        </tr>


      </table></td>


    </tr>


</table>


</form>


</body>


</html>


<script>


	//parent.frames[1].document.getElementById('titulo').innerHTML = 'CONFIGURADOR FLETES';


</script>