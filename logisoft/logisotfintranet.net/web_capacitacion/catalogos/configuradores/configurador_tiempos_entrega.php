<?


	require_once("../../Conectar.php");


	$l = Conectarse("webpmm");


?>


<html>


<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />


<title></title>


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


.estilo_borsup{


	border-top-width: thin;


	border-top-style: solid;


	border-top-color: #006699;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_borsupeizq{


	border-top-width: thin;


	border-left-width: thin;


	border-top-style: solid;


	border-left-style: solid;


	border-top-color: #006699;


	border-left-color: #006699;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_borizq{


	border-left-width: thin;


	border-left-style: solid;


	border-left-color: #006699;


	border-top-width: 1px;


	border-top-style: dotted;


	border-top-color: #006699;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_borsupdelg{


	border-top-width: 1px;


	border-top-style: dotted;


	border-top-color: #006699;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}





.estilo_borsup_am{


	border-top-width: thin;


	border-top-style: solid;


	border-top-color: #006699;


	background-color: #FFCC00;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_borsupeizq_am{


	border-top-width: thin;


	border-left-width: thin;


	border-top-style: solid;


	border-left-style: solid;


	border-top-color: #006699;


	border-left-color: #006699;


	background-color: #FFCC00;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_borizq_am{


	border-left-width: thin;


	border-left-style: solid;


	border-left-color: #006699;


	border-top-width: 1px;


	border-top-style: dotted;


	border-top-color: #006699;


	background-color: #FFCC00;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_borsupdelg_am{


	border-top-width: 1px;


	border-top-style: dotted;


	border-top-color: #006699;


	background-color: #FFCC00;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}





.estilo_borsup_ve{


	border-top-width: thin;


	border-top-style: solid;


	border-top-color: #006699;


	background-color: #00CC00;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_borsupeizq_ve{


	border-top-width: thin;


	border-left-width: thin;


	border-top-style: solid;


	border-left-style: solid;


	border-top-color: #006699;


	border-left-color: #006699;


	background-color: #00CC00;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_borizq_ve{


	border-left-width: thin;


	border-left-style: solid;


	border-left-color: #006699;


	border-top-width: 1px;


	border-top-style: dotted;


	border-top-color: #006699;


	background-color: #00CC00;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_borsupdelg_ve{


	border-top-width: 1px;


	border-top-style: dotted;


	border-top-color: #006699;


	background-color: #00CC00;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}





.estilo_borsup_az{


	border-top-width: thin;


	border-top-style: solid;


	border-top-color: #006699;


	background-color: #3399CC;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_borsupeizq_az{


	border-top-width: thin;


	border-left-width: thin;


	border-top-style: solid;


	border-left-style: solid;


	border-top-color: #006699;


	border-left-color: #006699;


	background-color: #3399CC;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_borizq_az{


	border-left-width: thin;


	border-left-style: solid;


	border-left-color: #006699;


	border-top-width: 1px;


	border-top-style: dotted;


	border-top-color: #006699;


	background-color: #3399CC;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_borsupdelg_az{


	border-top-width: 1px;


	border-top-style: dotted;


	border-top-color: #006699;


	background-color: #3399CC;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}





.estilo_selb{


	border-top-width: 2px;


	border-top-style: solid;


	border-top-color: #FF9900;


	border-right-width: 2px;


	border-right-style: solid;


	border-right-color: #FF9900;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_sela{


	border-top-width: 2px;


	border-left-width: 2px;


	border-top-style: solid;


	border-left-style: solid;


	border-top-color: #FF9900;


	border-left-color: #FF9900;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_selc{


	border-left-width: 2px;


	border-left-style: solid;


	border-left-color: #FF9900;


	border-bottom-width: 2px;


	border-bottom-style: solid;


	border-bottom-color: #FF9900;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_seld{


	border-right-width: 2px;


	border-bottom-width: 2px;


	border-right-style: solid;


	border-bottom-style: solid;


	border-right-color: #FF9900;


	border-bottom-color: #FF9900;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}





.estilo_celvac{


	background-color:#CCCCCC;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_celvacsup{


	background-color:#CCCCCC;


	border-top-width: thin;


	border-top-style: solid;


	border-top-color: #006699;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_celvacsupizq{


	background-color:#CCCCCC;


	border-top-width: thin;


	border-top-style: solid;


	border-top-color: #006699;


	border-left-width: thin;


	border-left-style: solid;


	border-left-color: #006699;


	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


.estilo_celvacizq{


	background-color:#CCCCCC;


	border-left-width: thin;


	border-left-style: solid;


	border-left-color: #006699;


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


.Blanco{ 


background:#FFFFFF;


	


}


-->


<!--


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


var var_boton = '<img src="../../img/Boton_Agregari.gif" width="70" height="20" style="cursor:hand" onClick="guardarDatos()">';


var guardando = 0;





	function validar(){


		u = document.all;


		if(u.origen.value == u.destino.value){


			alerta('El origen y el destino deben ser diferentes', 'Atencin!','origen');


			return false;


		}else if(u.origen.value=="0"){


			alerta('Debe seleccionar el origen', 'Atencin!','origen');


			return false;


		}else if(u.destino.value=="0"){


			alerta('Debe seleccionar el destino', 'Atencin!','destino');


			return false;


		}else if(u.teo.value==""){


			alerta('Debe capturar el tiempo de entrega', 'Atencin!','teo');


			return false;


		}else if(u.ead.value==""){


			alerta('Debe capturar el tiempo de entrega a domicilio', 'Atencin!','ead');


			return false;


		}else if(u.inc_tiempo.checked==true){


			if(u.horas.value=="0"){


				alerta('Debe capturar la hora en la que se incrementara el tiempo', 'Atencin!','horas');


				return false;


			}else if(u.cantidad.value==""){


				alerta('Debe capturar el tiempo que se incrementara', 'Atencin!','cantidad');


				return false;


			}


		}


		return true;


	}





	function checarsipedir(){


		u = document.all;


		ori = u.origen.value;


		des = u.destino.value;


		if(ori > 0 && des> 0 && ori!=des){


			pedirDatos(ori,des,2);


		}


	}


	function pedirDatos(ori,des,desde){


		u = document.all;


		u.celdaboton.innerHTML = var_load;


		guardando = 1;


		consulta("llegadaDatos","configurador_tiempos_entrega_con.php?accion=1&idorigen="+ori+"&iddestino="+des+"&desde="+desde);


	}


	function llegadaDatos(datos){


		var con   	= datos.getElementsByTagName('encontro').item(0).firstChild.data;


		var u 		= document.all;		


		var ori		= datos.getElementsByTagName('idorigen').item(0).firstChild.data;


		var des		= datos.getElementsByTagName('iddestino').item(0).firstChild.data;	


		var desde	= datos.getElementsByTagName('desde').item(0).firstChild.data;	


		


		if(c_seleccionada!="0_0"){


			document.all["c_"+c_seleccionada+"_a"].className="estilo_borsupeizq";


			document.all["c_"+c_seleccionada+"_b"].className="estilo_borsup";


			document.all["c_"+c_seleccionada+"_c"].className="estilo_borizq";


			document.all["c_"+c_seleccionada+"_d"].className="estilo_borsupdelg";


		}


		


		c_seleccionada = ori+"_"+des;


		document.all["c_"+ori+"_"+des+"_a"].className="estilo_sela";


		document.all["c_"+ori+"_"+des+"_b"].className="estilo_selb";


		document.all["c_"+ori+"_"+des+"_c"].className="estilo_selc";


		document.all["c_"+ori+"_"+des+"_d"].className="estilo_seld";


		


		//document.getElementById("c_"+ori+"_"+des+"_a").scrollIntoView(true);


		


		if(con>0){


			u.origen.value 		= datos.getElementsByTagName('idorigen').item(0).firstChild.data;


			u.destino.value 	= datos.getElementsByTagName('iddestino').item(0).firstChild.data;


			u.teo.value 		= datos.getElementsByTagName('tentrega').item(0).firstChild.data;


			u.ead.value 		= datos.getElementsByTagName('tadquisicion').item(0).firstChild.data;


			if(datos.getElementsByTagName('incrementartiempo').item(0).firstChild.data==1){


				u.inc_tiempo.checked= true;


				u.horas.value 		= datos.getElementsByTagName('siocurre').item(0).firstChild.data;


				u.cantidad.value 	= datos.getElementsByTagName('aincrementar').item(0).firstChild.data;


				Habilitar();


			}else{


				u.inc_tiempo.checked= false;				


				Habilitar();


				u.horas.value 		= "0";


				u.cantidad.value 	= "";


				u.teo.focus();


				


				


			}


		}else{


			u.origen.value 		= datos.getElementsByTagName('idorigen').item(0).firstChild.data;


			u.destino.value 	= datos.getElementsByTagName('iddestino').item(0).firstChild.data;


			u.teo.value 		= "";


			u.ead.value 		= "";


			u.inc_tiempo.checked= false;


			u.horas.value 		= "0";


			u.cantidad.value 	= "";


			Habilitar();


		}	


		guardando = 0;


		u.celdaboton.innerHTML = var_boton;


		if(desde==1)


			document.all.teo.focus();


	}


	


	function guardarDatos(){


		u = document.all;


		if(validar()){


			u.celdaboton.innerHTML = var_load;


			guardando = 1;


			consulta("regguarConsulta","configurador_tiempos_entrega_con.php?accion=2&idorigen="+u.origen.value+"&iddestino="+u.destino.value+


			"&tentrega="+u.teo.value+"&tentregaad="+u.ead.value+"&incrementartiempo="+((u.inc_tiempo.checked)?"1":"0")+


			"&siocurre="+((u.inc_tiempo.checked)?u.horas.value:"0")+"&aincrementar="+((u.inc_tiempo.checked)?u.cantidad.value:"0"));


		}


	}


	function regguarConsulta(datos){


		var ori		= datos.getElementsByTagName('idorigen').item(0).firstChild.data;


		var des		= datos.getElementsByTagName('iddestino').item(0).firstChild.data;	


		var inc		= datos.getElementsByTagName('siocurre').item(0).firstChild.data;


		var ocu		= datos.getElementsByTagName('tentrega').item(0).firstChild.data;	


		var ead		= datos.getElementsByTagName('tentregaad').item(0).firstChild.data; 


			


		if(inc==1){


			document.getElementById("c_"+ori+"_"+des+"_a").bgColor="#FFCC00";


			document.getElementById("c_"+ori+"_"+des+"_b").bgColor="#FFCC00";


			document.getElementById("c_"+ori+"_"+des+"_c").bgColor="#FFCC00";


			document.getElementById("c_"+ori+"_"+des+"_d").bgColor="#FFCC00";


		}else if(inc==3){


			document.getElementById("c_"+ori+"_"+des+"_a").bgColor="#3399CC";


			document.getElementById("c_"+ori+"_"+des+"_b").bgColor="#3399CC";


			document.getElementById("c_"+ori+"_"+des+"_c").bgColor="#3399CC";


			document.getElementById("c_"+ori+"_"+des+"_d").bgColor="#3399CC";


		}else if(inc==2){


			document.getElementById("c_"+ori+"_"+des+"_a").bgColor="#00CC00";


			document.getElementById("c_"+ori+"_"+des+"_b").bgColor="#00CC00";


			document.getElementById("c_"+ori+"_"+des+"_c").bgColor="#00CC00";


			document.getElementById("c_"+ori+"_"+des+"_d").bgColor="#00CC00";


		}else{


			document.getElementById("c_"+ori+"_"+des+"_a").bgColor="#FFFFFF";


			document.getElementById("c_"+ori+"_"+des+"_b").bgColor="#FFFFFF";


			document.getElementById("c_"+ori+"_"+des+"_c").bgColor="#FFFFFF";


			document.getElementById("c_"+ori+"_"+des+"_d").bgColor="#FFFFFF";


			/*document.getElementById("c_"+ori+"_"+des+"_a").className='Blanco';


			document.getElementById("c_"+ori+"_"+des+"_b").className='Blanco';


			document.getElementById("c_"+ori+"_"+des+"_c").className='Blanco';


			document.getElementById("c_"+ori+"_"+des+"_d").className='Blanco';*/


		}


		


		//document.getElementById("c_"+ori+"_"+des+"_a").bgColor = "#FF0000";


		


		document.all["c_"+ori+"_"+des+"_a"].innerHTML=ocu;


		document.all["c_"+ori+"_"+des+"_d"].innerHTML=ead;


		guardando = 0;


		u.celdaboton.innerHTML = var_boton;


	}


function Habilitar(){


	if(document.all.inc_tiempo.checked==true){


		document.all.horas.disabled=false;


		document.all.horas.style.backgroundColor='';


		document.all.cantidad.disabled=false;


		document.all.cantidad.style.backgroundColor='';


		document.all.horas.focus();		


	}else{


		document.all.cantidad.style.backgroundColor='#FFFF99';


		document.all.cantidad.disabled=true;


		document.all.horas.disabled=true;


		document.all.horas.style.backgroundColor='#FFFF99';		


	}


}	


</script>


<style type="text/css">


<!--


.Estilo2 {


	color: #FFFFFF;


	font-weight: bold;


}


-->


</style>


</head>


<body>


<form id="form1" name="form1" method="post" >





  <table width="100%" border="0">


    <tr>


      <td><br></td>


    </tr>


    <tr>


      <td><table width="612" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


        <tr>


          <td width="608" class="FondoTabla">Datos Generales </td>


        </tr>


        <tr>


          <td>


		  <table width="608" border="0" cellpadding="0" class="Tablas" cellspacing="0">


		  	<tr>


				<td width="3" height="38">&nbsp;</td>


				<td colspan="2"></td>


				<td></td>


		  	<tr>


				<td width="3" height="20">&nbsp;</td>


				<td width="489">


					<table width="484" border="0" cellpadding="0" cellspacing="0">


						<tr>


							<td width="79" class="Tablas">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Origen:</td>


							<td width="172">


								<select name="origen" onChange="checarsipedir()" style="width:150px; font-size:9px; text-transform:uppercase" onkeypress="if(event.keyCode==13)document.all.destino.focus();">


									<option value="0"></option>


									<? 


										$s = "select id, descripcion from catalogosucursal order by descripcion";


										$r = mysql_query($s,$l) or die($s);


										while($f = mysql_fetch_object($r)){


									?>


										<option value="<?=$f->id?>"><?=strtoupper($f->descripcion)?></option>


									<?


										}


									?>


						  </select>				</td>


							<td width="61" class="Tablas">Destino:</td>


							<td width="172">


								<select name="destino" style="width:150px; font-size:9px; text-transform:uppercase" onChange="checarsipedir()" onkeypress="if(event.keyCode==13)document.all.teo.focus();">


									<option value="0"></option>


									<? 


										$s = "select id, descripcion from catalogosucursal order by descripcion";


										$r = mysql_query($s,$l) or die($s);


										while($f = mysql_fetch_object($r)){


									?>


										<option value="<?=$f->id?>"><?=strtoupper($f->descripcion)?></option>


									<?


										}


									?>


						  </select>						  </td>


						</tr>


				  </table>				</td>


				<td width="112" rowspan="2" align="center" id="celdaboton"><img src="../../img/Boton_Agregari.gif" width="70" height="20" style="cursor:hand" onClick="if(guardando==0){guardarDatos();}"></td>


				<td width="10">&nbsp;</td>


			</tr>


		  	<tr>


		  	  <td>&nbsp;</td>


		  	  <td>


			  	<table border="0" cellpadding="0" cellspacing="0">


					<tr>


						<td width="149" class="Tablas">Tiempo entrega ocurre: </td>


						<td width="103"><input name="teo" type="text" style="font:tahoma;font-size:9px; text-transform:uppercase; width:80px" onKeyPress="if(event.keyCode==13){document.all.ead.focus();}else{return solonumeros(event);}" maxlength="5"></td>


						<td width="76" class="Tablas">Tiempo EAD: </td>


						<td width="156"><input name="ead" type="text" style="font:tahoma;font-size:9px; text-transform:uppercase; width:133px" onKeyPress="if(event.keyCode==13){document.all.inc_tiempo.focus();}else{return solonumeros(event);}" maxlength="5"></td>


					  </tr>


				</table>			  </td>


		  	  <td>&nbsp;</td>


		  	  </tr>


			  <tr>


			  	<td>&nbsp;</td>


				<td colspan="2">


					<table width="584" border="0" cellpadding="0" cellspacing="0">


						<tr>


						  <td width="21"><input type="checkbox" name="inc_tiempo" onKeyPress="if(event.keyCode==13)document.all.horas.focus();" onClick="Habilitar()"></td>


							<td width="113" class="Tablas">Incrementar tiempo </td>


							<td width="140" class="Tablas">Si lo documenta despues de:</td>


							<td width="94">


								<select name="horas" onkeypress="if(event.keyCode==13)document.all.cantidad.focus();" disabled="disabled" style="font-size:9px; text-transform:uppercase; background:#FFFF99">


									<option value="0"></option>


									<option value="4">08:00 PM</option>


									<option value="5">09:00 PM</option>


									<option value="6">10:00 PM</option>


									<option value="7">11:00 PM</option>


									<option value="1">12:00 PM</option>


									<option value="8">13:00 PM</option>


									<option value="2">14:00 PM</option>


									<option value="9">15:00 PM</option>


									<option value="3">16:00 PM</option>


									<option value="10">17:00 PM</option>


				  		  </select>							</td>


							<td width="101" class="Tablas">Incrementar:</td>


						    <td width="115"><input type="text" name="cantidad" style="font:tahoma;font-size:9px; text-transform:uppercase; width:70px; background:#FFFF99" disabled="disabled" onKeyPress="if(event.keyCode==13){guardarDatos();}else{return solonumeros(event);}" ></td>


						</tr>


					</table>				</td>


				<td>&nbsp;</td>


			  </tr>


		  	<tr>


		  	  <td>&nbsp;</td>


		  	  <td colspan="2">


			  	<div id=detalle name=detalle class="barras_div" style=" height:462px; width:600px; overflow:scroll;" align=left>


				<?


					$s = "select id, prefijo from catalogosucursal order by id";


					$r = mysql_query($s,$l) or die($s);


					$con = mysql_num_rows($r);


					


				?>


			  	<table border="0" cellpadding="0" cellspacing="0" width="<?=($con*100)+100?>px">


					<tr>


						<td width="100px" class="estilo_relleno" align="left">&nbsp;&nbsp;&nbsp;&nbsp;OCURRE</td>


					    <?


						$s = "select id, prefijo from catalogosucursal order by id";


						$r = mysql_query($s,$l) or die($s);


						while($f = mysql_fetch_object($r)){


					?>


						<td width="100px" colspan="2" rowspan="2" align="center" class="estilo_relleno"><?=$f->prefijo?></td>


					<?


						}


					?>


					</tr>


					<tr>


					  <td class="estilo_relleno" align="right">EAD&nbsp;&nbsp;&nbsp;&nbsp;</td>


					  </tr>


					<?


						$s = "select id, prefijo from catalogosucursal order by id";


						$r = mysql_query($s,$l) or die($s);


						while($f = mysql_fetch_object($r)){


						$idfila = $f->id;


					?>


					<tr>


						<td width="100px" rowspan="2" align="center" class="estilo_relleno"><?=$f->prefijo?></td>


					<?


						$s = "select id, prefijo from catalogosucursal order by id";


						$rx = mysql_query($s,$l) or die($s);


						while($fx = mysql_fetch_object($rx)){


							$where = "idorigen = $idfila and iddestino = $fx->id";


							$s = "select tentrega as e, siocurre as si from catalogotiempodeentregas where $where";


							$ry = mysql_query($s,$l) or die($s);


							$fy = mysql_fetch_object($ry);


					?>


					


						<td width="50" id="c_<?=$idfila?>_<?=$fx->id?>_a" 


						<? if($idfila!=$fx->id) {?>onDblClick="pedirDatos(<?=$idfila?>,<?=$fx->id?>,1)"<? } ?>


						style="background-color:<?=(($fy->si==1)?"#FFCC00":(($fy->si==3)?"#3399CC":(($fy->si==2)?"#00CC00":"")))?>" 


						class="<?=(($idfila!=$fx->id)?"estilo_borsupeizq":"estilo_celvacsupizq");?>" align="center"><?=(($fy->e!="")?$fy->e:"&nbsp;");?> </td>


					    <td width="50" id="c_<?=$idfila?>_<?=$fx->id?>_b" 


						<? if($idfila!=$fx->id) {?>onDblClick="pedirDatos(<?=$idfila?>,<?=$fx->id?>,1)"<? } ?>


						style="background-color:<?=(($fy->si==1)?"#FFCC00":(($fy->si==3)?"#3399CC":(($fy->si==2)?"#00CC00":"")))?>"


						align="center" class="<?=(($idfila!=$fx->id)?"estilo_borsup":"estilo_celvacsup");?>">&nbsp;</td>


					    <?


						}


					?>


					</tr>


					<tr>


					  <?


						$s = "select id, prefijo from catalogosucursal order by id";


						$rx = mysql_query($s,$l) or die($s);


						while($fx = mysql_fetch_object($rx)){


							$where = "idorigen = $idfila and iddestino = $fx->id";


							$s = "select tentregaad as a, siocurre as si from catalogotiempodeentregas where $where";


							$ry = mysql_query($s,$l) or die($s);


							$fy = mysql_fetch_object($ry);


					?>


						<td width="50"  id="c_<?=$idfila?>_<?=$fx->id?>_c" 


						<? if($idfila!=$fx->id) {?>onDblClick="pedirDatos(<?=$idfila?>,<?=$fx->id?>,1)"<? } ?>


						align="center" style="background-color:<?=(($fy->si==1)?"#FFCC00":(($fy->si==3)?"#3399CC":(($fy->si==2)?"#00CC00":"")))?>"


						class="<?=(($idfila!=$fx->id)?"estilo_borizq":"estilo_celvacizq");?>">&nbsp;</td>


					    <td width="50"  id="c_<?=$idfila?>_<?=$fx->id?>_d" 


						<? if($idfila!=$fx->id) {?>onDblClick="pedirDatos(<?=$idfila?>,<?=$fx->id?>,1)"<? } ?>


						style="background-color:<?=(($fy->si==1)?"#FFCC00":(($fy->si==3)?"#3399CC":(($fy->si==2)?"#00CC00":"")))?>"


						class="<?=(($idfila!=$fx->id)?"estilo_borsupdelg":"estilo_celvac");?>" align="center"><?=(($fy->a!="")?$fy->a:"&nbsp;");?> </td>


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


    </tr>


  </table>


</form>


</body>


</html>


<script>


//	parent.frames[1].document.getElementById('titulo').innerHTML = 'CONFIGURADOR TIEMPOS DE ENTREGAS';


</script>