<?


	require_once("conexion2.php");


	$link=conexion();


?>








<html>


<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />


<title></title>


<link href="../webpmm/web/estilos_estandar.css">


<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>


<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>


<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>


<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>


<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>


<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">


<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">


<link href="style.css" rel="stylesheet" type="text/css">











<script type="text/javascript" src="ajax.js"></script>


<script language="javascript" src="../../javascript/funciones.js"></script>


<script>


var c_seleccionada = "0_0";


var var_load = '<img src="../../javascript/loading.gif">';


var var_boton = '<img src="../../img/Boton_Agregari.gif" width="70" height="20" style="cursor:hand" onClick="guardarDatos()">';


var guardando = 0;





	function deseleccionar(){			


	   a=document.all.txtzonaf.value/document.all.txtintervalozona.value;	   


	   //return false;	  


       for(i=0;i<=8;i++){	


	      for(c=1;c<=a;c++){		        


				f=eval("celda" + i + "_" + c);


				f.className="estilo_celda";


		}


	}


}





	function escoger(renglon,columna,idcol,valor){	


	    document.all.precio.value=valor;    


		document.all.origen.value=renglon;


		idcol.className="estilo_celda_sel";


		document.all.destino.value=columna;


		document.all.precio.focus();


	}


	


	function escoger2(){		    


		a=document.all.origen.value;


 	    b=document.all.destino.value;


		


		if (a=='' || a=='-1' || b=='' || b=='-1'){return false;}


		


		document.all["celda"+ a +"_"+ b ].className="estilo_celda_sel";


		document.all.precio.value=document.all["costo"+ a +"_"+ b ].value;		


	}








	


	


	











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


  


  //cnf=confirm('Se perderá la información registrada ¿Desea continuar?'); 


  //return cnf;


  /*if (cnf)


  


  {return true;}


  else


  {document.all.txtintervalozona.focus();return false;}*/


  


  confirmar('Perdera la información capturada ¿Desea continuar?', '', 'enviarForm();', '')


  


} 


function enviarForm(){


	document.all.destino.value=-1; document.all.origen.value=-1; document.all.txtstatus.value=1; document.form1.submit();


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


if ($_POST[origen]!=-1 && $_POST[destino]!=-1 && $_POST[origen]!='' && $_POST[destino]!=''){








                if($_POST[txtzonai]!='' && $_POST[txtzonaf]!='' && $_POST[txtintervalozona]!=''){      


				    


                	$sql="delete from configuracion";


			 		mysql_query($sql,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql");		 		 


								 		 


                	$sql="insert into configuracion set zonai=".$_POST[txtzonai].",zonaf=".$_POST[txtzonaf].",intervalozona=".$_POST[txtintervalozona].",tarifai=0,tarifaf=70,intervalotarifa=5";


				 	mysql_query($sql,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql");		 		 


				}


				


				if ($_POST[precio]=='') {$_POST[precio]=0;}


				


		 		$sql="update configuraciondetalles set costo=".$_POST[precio].",vazio=1 where renglon=".$_POST[origen]." and columna=".$_POST[destino]."";  								


			 	mysql_query($sql,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql");		 		 





    }





 ?>


 


 


<?


if ($_POST[txtstatus]==1){


     $x=1;


	 $zi=$_POST[txtzonai];


	 


     $sql="Select * from configuracion";


	 mysql_query($sql,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql");


     $res=mysql_query($sql,$link);


     $row=mysql_fetch_array($res);


	 if ($row>0){ 


	     $sw=0;


 


	     if ($_POST[txtzonaf]>=$row['zonaf'] && $_POST[txtintervalozona]==$row['intervalozona']){		 	


			 $sql2="Select max(columna) as mcol,max(zof) as zoi from configuraciondetalles where renglon=0";


			 mysql_query($sql2,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql2");


		     $res2=mysql_query($sql2,$link);


		     $row2=mysql_fetch_array($res2);


	 		 if ($row2>0){


			      $x=$row2['mcol']+1;				  


				  $sw=1; 


				  $_POST[txtzonai]=$row2['zoi'];                  


		     }


		  }


		  		  


	  }


	  


	


     $query = "delete from configuracion";


     mysql_query($query,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $query");		 		 





     $sql="insert into configuracion set zonai=$zi,zonaf=".$_POST[txtzonaf].",intervalozona=".$_POST[txtintervalozona].",tarifai=0,tarifaf=70,intervalotarifa=5";


	 mysql_query($sql,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql");		 		 


	 


	 if ($sw==0){			


     	$query = "delete from configuraciondetalles";


     	mysql_query($query,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $query");		 		 


	 }	


     


 


      for ($r=0;$r<=9;$r++){ 


       


	   if ($r==0) {$vtarifai=-1;$vtarifai2=-1;} 


       if ($r==1) {$vtarifai=0;$vtarifai2=5;}


       if ($r==2) {$vtarifai=6;$vtarifai2=10;}


       if ($r==3) {$vtarifai=11;$vtarifai2=20;} 


       if ($r==4) {$vtarifai=21;$vtarifai2=30;} 


       if ($r==5) {$vtarifai=31;$vtarifai2=40;} 


       if ($r==6) {$vtarifai=41;$vtarifai2=50;} 


       if ($r==7) {$vtarifai=51;$vtarifai2=60;} 


       if ($r==8) {$vtarifai=61;$vtarifai2=70;} 


       if ($r==9) {$vtarifai=71;$vtarifai2=999999;} 


	   


	   


	   $vzonai=$_POST[txtzonai];


	   if ($sw==1){


	       $vzonai=$_POST[txtzonai]+1;


	   }


		   


       $vzonai2=$_POST[txtzonai];


		


	   $columnas=$_POST[txtzonaf]/$_POST[txtintervalozona];


	   


       for ($i=$x;$i<=$columnas;$i++){  





         $vzonai2=$vzonai2+$_POST[txtintervalozona];     





      	 $query = "insert into configuraciondetalles (columna,renglon,zoi,zof,kgi,kgf) values($i,$r,$vzonai,$vzonai2,$vtarifai,$vtarifai2)";


	     mysql_query($query,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $query");		 		 





         $vzonai=$vzonai2+1;





       }


       


      }


$_POST[txtzonai]=$zi;


}?>





<!-- termina es para eliminar el catalogo -->





</head>


<?


     $sql="Select * from configuracion";


	 mysql_query($sql,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql");


     $res=mysql_query($sql,$link);


     $row=mysql_fetch_array($res);


	 if ($row>0)


	    {


			$_POST[txtzonai]=$row['zonai'];


			$_POST[txtzonaf]=$row['zonaf'];


			$_POST[txtintervalozona]=$row['intervalozona'];	


			$_POST[txtstatus]='';		


          }


		 


?>						





<body onLoad="document.all.origen.focus();">


<br>  


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


				<form id="form1" name="form1" method="post" > 


				<td></td>


								


		  	<tr>


				<td width="3" height="20">&nbsp;</td>


				<td width="574">


					<table width="562" border="0" cellpadding="0" cellspacing="0">


						<tr>


							<td width="73" class="Tablas">Zona Inicio : </td>


							<td width="108">


							<input type="text" maxlength="12" size="12" style="font:tahoma;font-size:9px; text-transform:uppercase; " name="txtzonai" value="<?=$_POST[txtzonai]?>" onKeyPress="if(event.keyCode==13){document.all.txtzonaf.focus();return false;}else{return solonumeros(event);}"  >					


							</td>


							


							<td width="69" class="Tablas">Zona Final: </td>


							<td width="134">


                            <input type="text" maxlength="12" size="12" style="font:tahoma;font-size:9px; text-transform:uppercase; " name="txtzonaf" value="<?=$_POST[txtzonaf]?>" onKeyPress="if(event.keyCode==13){document.all.txtintervalozona.focus();return false;}else{return solonumeros(event);}" >					


							</td>


							


							<td width="53" class="Tablas">Intervalo: </td>


							<td width="125">


                            <input type="text" maxlength="12" size="12" style="font:tahoma;font-size:9px; text-transform:uppercase;" name="txtintervalozona" value="<?=$_POST[txtintervalozona]?>" onKeyPress="if(event.keyCode==13){document.all.txtintervalozona.focus();return false;}else{return solonumeros(event);}" >					


							</td>


						</tr>


						


						<tr><td colspan="6">&nbsp; </td> </tr>


						


						<tr>


						





						


                    <td width="42" height="26" class="Tablas">Tarifa</td>


                    <td width="139"><select name="origen" onBlur="deseleccionar();escoger2();" onChange="deseleccionar();escoger2();" validar="1"  style="width:130px; font-size:9px; text-transform:uppercase" onkeypress="if(event.keyCode==13)document.all.destino.focus();" value="<?=$_POST[origen]?>">


                        <option value="-1"></option>


                        <? 


										$s = "select * from tarifa order by id_tarifa";


										$r = mysql_query($s,$link) or die($s);


										while($f = mysql_fetch_object($r)){


									?>


									<? if ($_POST[origen]== $f->id_tarifa){?>


	                                    <option value="<?=$f->id_tarifa?>" selected>


									<?}else{?>


									    <option value="<?=$f->id_tarifa?>">


									<?}?>	


                          <?=strtoupper($f->descripcion)?>


                          </option>


                        <?


										}


									?>


                      </select>                    </td>


                    <td width="40" class="Tablas">Zona</td>


                    <td width="130"><select name="destino" onChange="deseleccionar();escoger2();" validar="1" 


					style="width:120px; font-size:9px; text-transform:uppercase" 


					 onkeypress="if(event.keyCode==13){document.all.precio.focus();}" >


                        <option value="-1"></option>


                        <? 


										$s = "select columna from configuraciondetalles where renglon=0 order by columna";


										$r = mysql_query($s,$link) or die($s);


										while($f = mysql_fetch_object($r)){


									?>


						<? if ($_POST[destino]== $f->columna){?>


                        	<option value="<?=$f->columna?>" selected>


						<?}else{?>


							<option value="<?=$f->columna?>">


						<?}?>


                          <?=strtoupper('zona'.(($f->columna)-1))?>


                          </option>


                        <?


										}


									?>


                      </select></td>


                    <td width="50" class="Tablas">Precio </td>


                    <td width="66">


					<input type="hidden" name="txtstatus" value="$_POST[txtstatus]">	


					<input name="precio" type="text"


					style="width:60px; font:tahoma;font-size:9px; text-transform:uppercase;"


					onKeyPress="if(event.keyCode==13){}else{return solonumeros(event);}" maxlength="5" > 


									


					</td>


                  </tr>


						


						


						


					</table>				


				</td>


				<td width="84"  align="center" id="celdaboton">





				<!--<input name="btncrear2" type="image" src="Boton_Agregari.gif" width="70" height="20" style="cursor:hand" onClick="if(validar()){pedirDatos(document.all.txtzonai.value,document.all.txtzonaf.value,document.all.txtintervalozona.value);return false;}else{return false;}">-->


                <img name="btncrear2" src="../../img/Boton_Agregari.gif" alt="Agregar" width="70" height="20" style="cursor:pointer" onClick="if(validar()){document.all.destino.value=-1;document.all.origen.value=-1;document.all.txtstatus.value=1;document.all.submit();return false;}else{return false;}" />


				<p>


				<img name="btncrear3" src="../../img/Boton_Agregari.gif" alt="Agregar" width="70" height="20" style="cursor:pointer" onClick="if(document.form1.submit());return false;" /></td>


				<td width="10">&nbsp;</td>


			</tr>


		  	<tr>


		  	  <td>&nbsp;</td>


		  	  <td>


			  	</td>


		  	  <td>&nbsp;</td>


		  	  </tr>


			  


		  	<tr>


		  	  <td>&nbsp;</td>


		  	  <td colspan="2" align="center">


			  


			 


			  	<div id=detalle name=detalle style="  width:630px; height:250px; overflow:auto" align=center>


				<?php include('consulta.php');?>


				</div>	  


			  


	          </td>


		  	  <td>&nbsp;</td>


		  	  </tr>


			  <tr><td>&nbsp;</td></tr>


			  <tr><td>&nbsp;</td></tr>


		  </table>


		  </td>


        </tr>


      </table></td>


    </tr>


</table>


</form>


</body>


<script>


	//parent.frames[1].document.getElementById('titulo').innerHTML = 'CONFIGURADOR FLETES';


</script>