


<?


	require_once("../../Conectar.php");


	$l = Conectarse("webpmm");


	


	


?>


<html>


<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />


<title>Configurador Distancia</title>


<link href="FondoTabla.css" rel="stylesheet" type="text/css">


<link href="Tablas.css" rel="stylesheet" type="text/css">


<link href="puntovta.css" rel="stylesheet" type="text/css">


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


.estilo_celda {


	border-right-width: thin;


	border-bottom-width: thin;


	border-right-style: solid;


	border-bottom-style: solid;


	border-right-color: #006192;


	border-bottom-color: #006192;


}


.style3 {


	font-size: 9px;


	color: #464442;


}


.style4 {color: #025680;font-size:9px }


.style5 {color: #FFFFFF ; font-size:9px}


.Balance {background-color: #FFFFFF; border: 0px none}


.Balance2 {background-color: #DEECFA; border: 0px none;}


.estilo_relleno{


	background-color:#006192;


	color:#FFFFFF;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_div {


	background: white;  width:200px; height:100px; overflow: scroll;


	border: 1px solid #006699;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.Tablas{


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


-->


<!--


.Estilo1 {


	color: #FFFFFF;


	font-weight: bold;


	font-size: 13px;


	font-family: tahoma;


}


.estilo_celda_sel {


	border: thin solid #FF9900;


}


-->


</style>


<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>


<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>


<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>


<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>


<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>


<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">


<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">





<script language="javascript" src="../../javascript/ajax.js"></script>


<script language="javascript" src="../../javascript/funciones.js"></script>


<script>


var c_seleccionada = "0_0";


var var_load = '<img src="../../javascript/loading.gif">';


var var_boton = '<img src="../../img/Boton_Agregari.gif" width="70" height="20" style="cursor:hand" onClick="if(guardando==0){guardarDatos();}">';


var guardando = 0;


	





	function checarsipedir(){


		u = document.all;


		ori = u.origen.value;


		des = u.destino.value;


		if(ori > 0 && des> 0 && ori!=des){


			if(!document.getElementById("c_"+ori+"_"+des+"_a")){


			//alert("existe");


			}else


				pedirDatos(ori,des,2);


		}


	}


	function pedirDatos(ori,des,desde){


		u = document.all;


		u.celdaboton.innerHTML = var_load;


		guardando = 1;


			


		consulta("llegadaDatos","configurador_distancias_con.php?accion=1&idorigen="+ori+"&iddestino="+des+"&desde="+desde);


	}


	function llegadaDatos(datos){


		var con   	= datos.getElementsByTagName('encontro').item(0).firstChild.data;


		var u 		= document.all;		


		var ori		= datos.getElementsByTagName('idorigen').item(0).firstChild.data;


		var des		= datos.getElementsByTagName('iddestino').item(0).firstChild.data;	


		var desde	= datos.getElementsByTagName('desde').item(0).firstChild.data;	


		


		if(c_seleccionada!="0_0"){


			document.all["c_"+c_seleccionada+"_a"].className="estilo_celda";


		}


		


		c_seleccionada = ori+"_"+des;


		document.all["c_"+ori+"_"+des+"_a"].className="estilo_celda_sel";


		


		//document.getElementById("c_"+ori+"_"+des+"_a").scrollIntoView(true);


		


		if(con>0){


			u.origen.value 		= datos.getElementsByTagName('idorigen').item(0).firstChild.data;


			u.destino.value 	= datos.getElementsByTagName('iddestino').item(0).firstChild.data;


			u.distancia.value 	= datos.getElementsByTagName('distancia').item(0).firstChild.data;


		}else{


			u.origen.value 		= datos.getElementsByTagName('idorigen').item(0).firstChild.data;


			u.destino.value 	= datos.getElementsByTagName('iddestino').item(0).firstChild.data;


			u.distancia.value 	= "";


		}	


		guardando = 0;


		u.celdaboton.innerHTML = var_boton;


		


		if(desde==1)


			document.all.distancia.focus();


	}


	


	function guardarDatos(){


		u = document.all;





		if(validarFormularios(document.form1)){


			if(!document.getElementById("c_"+u.origen.value+"_"+u.destino.value+"_a")){


				alerta('El origen y destino seleccionado no son validos', 'Atencin!',"origen");


				document.all.distancia.value="";


				if(c_seleccionada!="0_0"){


			document.all["c_"+c_seleccionada+"_a"].className="estilo_celda";


				}


			}else{


				u.celdaboton.innerHTML = var_load;


				guardando = 1;


				


				consulta("regguarConsulta","configurador_distancias_con.php?accion=2&idorigen="+u.origen.value+"&iddestino="+u.destino.value+


				"&distancia="+u.distancia.value);


			}


		}


	}


	function regguarConsulta(datos){


		var ori		= datos.getElementsByTagName('idorigen').item(0).firstChild.data;


		var des		= datos.getElementsByTagName('iddestino').item(0).firstChild.data;	


		var dis		= datos.getElementsByTagName('distancia').item(0).firstChild.data;


		


		guardando = 0;


		u.celdaboton.innerHTML = var_boton;


		document.all["c_"+ori+"_"+des+"_a"].innerHTML=dis;


	}


	


</script>


</head>


<body>


<?


if($impri==1){


		


					$s = "select id, prefijo from catalogosucursal where id <> 1 order by id";


					$r = mysql_query($s,$l) or die($s);


					$con = mysql_num_rows($r);


					


				?>


			  	<table border="0" cellpadding="0" cellspacing="0">


					<tr>


						<td class="estilo_relleno">&nbsp;</td>


					<?


						$s = "select id, prefijo from catalogosucursal where id <> 1 order by id";


						$r = mysql_query($s,$l) or die($s);


						while($f = mysql_fetch_object($r)){


					?>


						<td align="center">&nbsp;</td>


					<?


						}


					?>


					</tr>


					


					<?


						$s = "select id, prefijo from catalogosucursal where id <> 1 order by id";


						$r = mysql_query($s,$l) or die($s);


						while($f = mysql_fetch_object($r)){


						$idfila = $f->id;


					?>


					<tr>


						<td align="center" class="estilo_relleno"><?=$f->prefijo?></td>


					<?


						$s = "select id, prefijo from catalogosucursal where id <> 1 order by id";


						$rx = mysql_query($s,$l) or die($s);


						$interno = 0;


						while($fx = mysql_fetch_object($rx)){


							if($idfila==$fx->id)


								$interno = 1;


							$where = "idorigen = $idfila and iddestino = $fx->id";


							$s = "select distancia as e from catalogodistancias where $where";


							$ry = mysql_query($s,$l) or die($s);


							$fy = mysql_fetch_object($ry);


					?>


						<td align="center" 


						class="<?=(($idfila!=$fx->id)?(($interno == 0)?"estilo_celda":""):"estilo_relleno");?>" 


						id="<? if($interno==0){ ?>c_<?=$idfila?>_<?=$fx->id?>_a<? } ?>" >


							<?


							if($idfila!=$fx->id){


								echo (($fy->e!="")?$fy->e:"&nbsp;");


							}else{


								echo $fx->prefijo;


							}


							?> 


						</td>


					    <?


						}


					?>


					</tr>


					<?


						}


					?>


				</table>


<object id=factory viewastext style="display:none"


classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"


codebase="smsx.cab#Version=6,4,438,06">


</object>


<script>


function printpr()


{


var OLECMDID = 7;


var PROMPT = 1; // 2 DONTPROMPTUSER 


var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 PAGEFOOTER=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';


document.body.insertAdjacentHTML('beforeEnd', WebBrowser); 


WebBrowser1.ExecWB(OLECMDID, PROMPT);WebBrowser1.outerHTML = "";


}


printpr();


</script>


                <?


	}else{


		?>


<form id="form1" name="form1" method="post" onSubmit="return false" >


<table width="619" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


        <tr>


          <td width="615" class="FondoTabla">Datos Generales </td>


        </tr>


        <tr>


          <td>


		  <table width="615" border="0" cellpadding="0" class="Tablas" cellspacing="0">


		  <tr>


				<td width="10" height="20">&nbsp;</td>


				<td colspan="2"></td>


				<td></td>


		  	<tr>


		  	<tr>


				<td width="10" height="28">&nbsp;</td>


				<td width="492"><table width="467" border="0" cellpadding="0" cellspacing="0">


                  <tr>


                    <td width="42" class="Tablas">Origen</td>


                    <td width="139"><select name="origen" onChange="checarsipedir()" validar="1" devolver="el origen"


					style="width:130px; font-size:9px; text-transform:uppercase" onkeypress="if(event.keyCode==13)document.all.destino.focus();">


                        <option value="0"></option>


                        <? 


										$s = "select id, descripcion from catalogosucursal where id <> 1 order by descripcion";


										$r = mysql_query($s,$l) or die($s);


										while($f = mysql_fetch_object($r)){


									?>


                        <option value="<?=$f->id?>">


                          <?=strtoupper($f->descripcion)?>


                          </option>


                        <?


										}


									?>


                      </select>                    </td>


                    <td width="40" class="Tablas">Destino</td>


                    <td width="130"><select name="destino" validar="1" devolver="el destino"


					style="width:120px; font-size:9px; text-transform:uppercase" 


					onChange="checarsipedir()" onkeypress="if(event.keyCode==13)document.all.distancia.focus();">


                        <option value="0"></option>


                        <? 


										$s = "select id, descripcion from catalogosucursal where id <> 1 order by descripcion";


										$r = mysql_query($s,$l) or die($s);


										while($f = mysql_fetch_object($r)){


									?>


                        <option value="<?=$f->id?>">


                          <?=strtoupper($f->descripcion)?>


                          </option>


                        <?


										}


									?>


                      </select></td>


                    <td width="50" class="Tablas">Distancia</td>


                    <td width="66"><input name="distancia" type="text"


					style="width:60px; font:tahoma;font-size:9px; text-transform:uppercase;"


					onKeyPress="if(event.keyCode==13){guardarDatos();}else{return solonumeros(event);}" maxlength="5" validar="1" devolver="la distancia"></td>


                  </tr>


                </table></td>


				<td width="109" align="center" id="celdaboton"><img src="../../img/Boton_Agregari.gif" width="70" height="20" style="cursor:hand" onClick="if(guardando==0){guardarDatos();}"></td>


				<td width="10">&nbsp;</td>


			</tr>


            <tr>


				<td width="10" height="20">&nbsp;</td>


				<td width="492">&nbsp;</td>


				<td width="109" align="center" id="celdaboton"><img src="../../img/Boton_Imprimir.gif" width="70" height="20" style="cursor:hand" onClick="if(guardando==0){guardarDatos();}"></td>


				<td width="10">&nbsp;</td>


			</tr>


		  	<tr>


		  	  <td>&nbsp;</td>


		  	  <td colspan="2">


			  	<div id=detalle name=detalle class="barras_div" style=" height:500px; width:600px; overflow:scroll;" align=left>


				<?


					$s = "select id, prefijo from catalogosucursal where id <> 1 order by id";


					$r = mysql_query($s,$l) or die($s);


					$con = mysql_num_rows($r);


					


				?>


			  	<table border="0" cellpadding="0" cellspacing="0" width="<?=($con*100)+100?>px">


					<tr>


						<td width="100" class="estilo_relleno">&nbsp;</td>


					<?


						$s = "select id, prefijo from catalogosucursal where id <> 1 order by id";


						$r = mysql_query($s,$l) or die($s);


						while($f = mysql_fetch_object($r)){


					?>


						<td width="100" align="center">&nbsp;</td>


					<?


						}


					?>


					</tr>


					


					<?


						$s = "select id, prefijo from catalogosucursal where id <> 1 order by id";


						$r = mysql_query($s,$l) or die($s);


						while($f = mysql_fetch_object($r)){


						$idfila = $f->id;


					?>


					<tr>


						<td width="100" align="center" class="estilo_relleno"><?=$f->prefijo?></td>


					<?


						$s = "select id, prefijo from catalogosucursal where id <> 1 order by id";


						$rx = mysql_query($s,$l) or die($s);


						$interno = 0;


						while($fx = mysql_fetch_object($rx)){


							if($idfila==$fx->id)


								$interno = 1;


							$where = "idorigen = $idfila and iddestino = $fx->id";


							$s = "select distancia as e from catalogodistancias where $where";


							$ry = mysql_query($s,$l) or die($s);


							$fy = mysql_fetch_object($ry);


					?>


						<td align="center" 


						class="<?=(($idfila!=$fx->id)?(($interno == 0)?"estilo_celda":""):"estilo_relleno");?>" 


						id="<? if($interno==0){ ?>c_<?=$idfila?>_<?=$fx->id?>_a<? } ?>" 


						<? if($idfila!=$fx->id && $interno==0) {?>onDblClick="pedirDatos(<?=$idfila?>,<?=$fx->id?>,1)"<? } ?>>


							<?


							if($idfila!=$fx->id){


								echo (($fy->e!="")?$fy->e:"&nbsp;");


							}else{


								echo $fx->prefijo;


							}


							?> 


						</td>


					    <?


						}


					?>


					</tr>


					<?


						}


					?>


				</table>


					</div>			  </td>


		  	  <td>&nbsp;</td>


		  	  </tr>


		  </table>


		  </td>


        </tr>


      </table>      


  <script>


	//parent.frames[1].document.getElementById('titulo').innerHTML = 'CONFIGURADOR DISTANCIAS';


</script>


<?


	}


?>


</form>


</body>


</HTML>


