<html>


	<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />


   	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<script src="../catalogos/cliente/select.js"></script>


<script type="text/javascript" src="../javascript/ajax.js"></script>


<script type="text/javascript" src="../javascript/funciones_tablas.js"></script>


<script>


var nav4 = window.Event ? true : false;


var valt = agregar_una_tabla("tablaclientes", "fil_", 1, "Tablas└Tablas", "");


var latabla= "";





function Numeros(evt){ 


// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 


	var key = nav4 ? evt.which : evt.keyCode; 


	return (key <= 13 || (key >= 48 && key <= 57));


}





function ObtenerConsulta(e,nombrecaja,valor){


	tecla=(document.all) ? e.keyCode : e.which;


	var u = document.all;


	if(tecla==13){ /* 


		if(document.all.tipo.value!=""){


			if(document.all.tipo.value=="moral"){


				switch(nombrecaja){


			case "buscarnick":


				consulta("resultado","../convenio/consultasCredito.php?accion=3&campo=nick<?=($_GET[personamoral])?"&personamoral=$_GET[personamoral]":""?>&valor="+valor+"&suert="+Math.random()+"&tipo=SI");


			break;


			case "buscarrfc":


				consulta("resultado","../convenio/consultasCredito.php?accion=3&campo=rfc<?=($_GET[personamoral])?"&personamoral=$_GET[personamoral]":""?>&valor="+valor+"&suert="+Math.random()+"&tipo=SI");


			break;		


			case "buscarid":			


				consulta("resultado","../convenio/consultasCredito.php?accion=3&campo=id<?=($_GET[personamoral])?"&personamoral=$_GET[personamoral]":""?>&valor="+valor+"&suert="+Math.random()+"&tipo=SI");


				


			break;		


			case "buscarnombre":


				consulta("resultado","../convenio/consultasCredito.php?accion=3&campo=nombre<?=($_GET[personamoral])?"&personamoral=$_GET[personamoral]":""?>&valor="+valor+"&suert="+Math.random()+"&tipo=SI");


			break;		


			case "buscarpaterno":


				consulta("resultado","../convenio/consultasCredito.php?accion=3&campo=paterno<?=($_GET[personamoral])?"&personamoral=$_GET[personamoral]":""?>&valor="+valor+"&suert="+Math.random()+"&tipo=SI");


			break;


			case "buscarmaterno":


				consulta("resultado","../convenio/consultasCredito.php?accion=3&campo=materno<?=($_GET[personamoral])?"&personamoral=$_GET[personamoral]":""?>&valor="+valor+"&suert="+Math.random()+"&tipo=SI");


			break;


		}


			}else{


					switch(nombrecaja){


			case "buscarnick":


				consulta("resultado","../convenio/consultasCredito.php?accion=3&campo=nick&valor="+valor+"&suert="+Math.random()+"&tipo=NO");


			break;


			case "buscarrfc":


				consulta("resultado","../convenio/consultasCredito.php?accion=3&campo=rfc&valor="+valor+"&suert="+Math.random()+"&tipo=NO");


			break;		


			case "buscarid":


				consulta("resultado","../convenio/consultasCredito.php?accion=3&campo=id&valor="+valor+"&suert="+Math.random()+"&tipo=NO");	


			break;		


			case "buscarnombre":


				consulta("resultado","../convenio/consultasCredito.php?accion=3&campo=nombre&valor="+valor+"&suert="+Math.random()+"&tipo=NO");


			break;		


			case "buscarpaterno":


				consulta("resultado","../convenio/consultasCredito.php?accion=3&campo=paterno&valor="+valor+"&suert="+Math.random()+"&tipo=NO");


			break;


			case "buscarmaterno":


				consulta("resultado","../convenio/buscarClienteGen_con.php?accion=3&campo=materno&valor="+valor+"&suert="+Math.random()+"&tipo=NO");


			break;


		}


			}





	}else{


		


		switch(nombrecaja){


			case "buscarnick":


				consulta("resultado","buscarClienteGen_con.php?accion=1<?=($_GET[tiposol])?"&tiposol=$_GET[tiposol]":""?>&campo=nick&valor="+valor+"&suert="+Math.random());


			break;


			case "buscarrfc":


				consulta("resultado","buscarClienteGen_con.php?accion=1<?=($_GET[tiposol])?"&tiposol=$_GET[tiposol]":""?>&campo=rfc&valor="+valor+"&suert="+Math.random());


			break;		


			case "buscarid":


				consulta("resultado","buscarClienteGen_con.php?accion=1<?=($_GET[tiposol])?"&tiposol=$_GET[tiposol]":""?>&campo=id&valor="+valor+"&suert="+Math.random());	


			break;		


			case "buscarnombre":


				consulta("resultado","buscarClienteGen_con.php?accion=1<?=($_GET[tiposol])?"&tiposol=$_GET[tiposol]":""?>&campo=nombre&valor="+valor+"&suert="+Math.random());


			break;		


			case "buscarpaterno":


				consulta("resultado","buscarClienteGen_con.php?accion=1<?=($_GET[tiposol])?"&tiposol=$_GET[tiposol]":""?>&campo=paterno&valor="+valor+"&suert="+Math.random());


			break;


			case "buscarmaterno":


				consulta("resultado","buscarClienteGen_con.php?accion=1<?=($_GET[tiposol])?"&tiposol=$_GET[tiposol]":""?>&campo=materno&valor="+valor+"&suert="+Math.random());


			break;


		}


		}


	 */


		


		var	buscarnick		=	(u.buscarnick.value!="")?"&nick="+u.buscarnick.value:"";


		var buscarrfc		=	(u.buscarrfc.value!="")?"&rfc="+u.buscarrfc.value:"";


		var buscarid		=	(u.buscarid.value!="")?"&id="+u.buscarid.value:"";


		var	buscarnombre	=	(u.buscarnombre.value!="")?"&nombre="+u.buscarnombre.value:"";


		var	buscarpaterno	=	(u.buscarpaterno.value!="")?"&paterno="+u.buscarpaterno.value:"";


		var	buscarmaterno	=	(u.buscarmaterno.value!="")?"&materno="+u.buscarmaterno.value:"";


		var	buscarciudad	=	(u.buscarciudad.value!="")?"&ciudad="+u.buscarciudad.value:"";


		var personamoral    = "";


		<? 


			if($_GET[personamoral]!=""){


		?> 


				personamoral = "&personamoral=<?=$_GET[personamoral]?>";


		<?


			}else if($_GET[tipo]!=""){


		?> 


				personamoral = "&personamoral=<?=($_GET[tipo]=='moral')?'SI':'NO'?>";


		<?


			}


		?>


		


		var valor = buscarnick + buscarrfc + buscarid + buscarnombre + buscarpaterno + buscarmaterno + buscarciudad + personamoral;


		consulta("resultado","buscarClienteGen_con.php?accion=2"+valor+"<?=($_GET[tiposol])?"&tiposol=$_GET[tiposol]":""?>&suert="+Math.random());


	}


}





function ponerOnClick(valor,valordevuelto){


	return	"<a style='cursor:hand;' onclick='parent.<?=$_GET[funcion]?>("+valordevuelto+"); parent.VentanaModal.cerrar();'>"+valor+"</a>";


}





function resultado(datos){


	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;


	if(latabla=="")


		latabla=document.all.txtHint.innerHTML;


	document.all.txtHint.innerHTML=latabla;


	reiniciar_indice(valt);


	if(con>0){


		for(m=0;m<con;m++){	


			nick		= datos.getElementsByTagName('nick').item(m).firstChild.data;


			rfc			= datos.getElementsByTagName('rfc').item(m).firstChild.data;


			idcliente	= datos.getElementsByTagName('idcliente').item(m).firstChild.data;


			nombre		= datos.getElementsByTagName('nombre').item(m).firstChild.data;


			paterno		= datos.getElementsByTagName('paterno').item(m).firstChild.data;


			materno		= datos.getElementsByTagName('materno').item(m).firstChild.data;


			insertar_en_tabla(valt,ponerOnClick(nick,idcliente)+"└"+ponerOnClick(rfc,idcliente)+"└"+


			ponerOnClick(idcliente,idcliente)+"└"+ponerOnClick(nombre,idcliente)+"└"+


			ponerOnClick(paterno,idcliente)+"└"+ponerOnClick(materno,idcliente));


		}	


	}


}


</script>


<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />


<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />





<style type="text/css">


<!--


body {


	margin-left: 1px;


	margin-top: 1px;


	margin-right: 1px;


	margin-bottom: 1px;


}


-->


</style>


</head>


<body>


  <table width="600" class="Tablas"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


    <tr>


      <td class="FondoTabla">Nick</td>


      <td class="FondoTabla">R.F.C</td>


      <td class="FondoTabla"># Cliente</td>


      <td class="FondoTabla">Nombre</td>


      <td class="FondoTabla">Paterno</td>


      <td class="FondoTabla">Materno</td>


      <td class="FondoTabla">Ciudad</td>


    </tr>


    <tr>


      <td width="16%" class="FondoTabla"><input class="Tablas" name="buscarnick" type="text" id="buscarnick" onKeyPress="ObtenerConsulta(event,this.name,this.value)" value="" size="10" style="border:none; text-transform:uppercase" /></td>


      <td width="12%" class="FondoTabla"><span class="Tablas">


        <input name="buscarrfc" type="text" id="buscarrfc" value="" class="Tablas" size="12" style="border:none;text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />


      </span></td>


      <td width="10%" class="FondoTabla" align="center">


        <input name="buscarid" type="text" id="buscarid" value="" class="Tablas" style="border:none;text-transform:uppercase; width:20px" onKeyPress="return Numeros(event)" onKeyUp="ObtenerConsulta(event,this.name,this.value)" />


      </td>


      <td width="15%" class="FondoTabla"><span class="Tablas">


        <input name="buscarnombre" type="text" id="buscarnombre" value="" class="Tablas" size="16" style="border:none; text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />


      </span></td>


      <td width="15%" class="FondoTabla"><span class="Tablas">


      <input name="buscarpaterno" type="text" id="buscarpaterno" value="" class="Tablas" size="15" style="border:none ;text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />


      </span></td>


      <td width="15%" class="FondoTabla"><span class="Tablas">


      <input name="buscarmaterno" type="text" id="buscarmaterno" value="" class="Tablas" size="15" style="border:none;text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />


      </span></td>


      <td width="17%" class="FondoTabla">


      <input name="buscarciudad" type="text" id="buscarciudad" value="" class="Tablas" size="15" style="border:none;text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />


      </td>


</tr>


    <tr>


      <td colspan="7">


      <div id="txtHint" style="width:100%; height:300px; overflow: scroll;">


      <table width="495" id="tablaclientes" border="0" align="left" alagregar="" alborrar="" cellpadding="0" cellspacing="0">


      	  <tr>


       		<td width="79" class="Tablas" ></td>


            <td width="90" class="Tablas"></td>


            <td width="58" class="Tablas"></td>


            <td width="85" class="Tablas"></td>


            <td width="86" class="Tablas"></td>


            <td width="97" class="Tablas"></td>


</tr>


      	  <tr id="fil_0">


      	    <td class="Tablas" >&nbsp;</td>


      	    <td class="Tablas">&nbsp;</td>


      	    <td class="Tablas">&nbsp;</td>


      	    <td class="Tablas">&nbsp;</td>


      	    <td class="Tablas">&nbsp;</td>


      	    <td class="Tablas">&nbsp;</td>


</tr>


      </table>


      </div>      </td>


    </tr>


  </table>  


</body>


</html>