<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

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


	if(tecla==13){


		<?


			if($_GET[personamoral]){


				echo "var personamoral = '&personamoral=$_GET[personamoral]';";


			}else{


				echo "var personamoral = '';";


			}


		?>


		


		switch(nombrecaja){


			case "buscarnick":


				consulta("resultado","buscarProspectoGen_con.php?accion=1&campo=nick&valor="+valor+personamoral+"&suert="+Math.random());


			break;


			case "buscarrfc":


				consulta("resultado","buscarProspectoGen_con.php?accion=1&campo=rfc&valor="+valor+personamoral+"&suert="+Math.random());


			break;		


			case "buscarid":


				consulta("resultado","buscarProspectoGen_con.php?accion=1&campo=id&valor="+valor+personamoral+"&suert="+Math.random());	


			break;		


			case "buscarnombre":


				consulta("resultado","buscarProspectoGen_con.php?accion=1&campo=nombre&valor="+valor+personamoral+"&suert="+Math.random());


			break;		


			case "buscarpaterno":


				consulta("resultado","buscarProspectoGen_con.php?accion=1&campo=paterno&valor="+valor+personamoral+"&suert="+Math.random());


			break;


			case "buscarmaterno":


				consulta("resultado","buscarProspectoGen_con.php?accion=1&campo=materno&valor="+valor+personamoral+"&suert="+Math.random());


			break;


		}


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


<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />


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


      <td class="FondoTabla"># Prospecto</td>


      <td class="FondoTabla">Nombre</td>


      <td class="FondoTabla">Paterno</td>


      <td class="FondoTabla">Materno</td>


    </tr>


    <tr>


      <td width="15%" class="FondoTabla"><input class="Tablas" name="buscarnick" type="text" id="buscarnick" onKeyPress="ObtenerConsulta(event,this.name,this.value)" value="" size="10" style="border:none; text-transform:uppercase" /></td>


      <td width="12%" class="FondoTabla"><span class="Tablas">


      <input name="buscarrfc" type="text" id="buscarrfc" value="" class="Tablas" size="12" style="border:none;text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />


      </span></td>


      <td width="15%" class="FondoTabla"><div align="center"><span class="Tablas">


        <input name="buscarid" type="text" id="buscarid" value="" class="Tablas" size="4" style="border:none;text-transform:uppercase" onKeyPress="return Numeros(event)" onKeyUp="ObtenerConsulta(event,this.name,this.value)" />


      </span></div></td>


      <td width="16%" class="FondoTabla"><span class="Tablas">


      <input name="buscarnombre" type="text" id="buscarnombre" value="" class="Tablas" size="16" style="border:none; text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />


      </span></td>


      <td width="17%" class="FondoTabla"><span class="Tablas">


        <input name="buscarpaterno" type="text" id="buscarpaterno" value="" class="Tablas" size="15" style="border:none ;text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />


      </span></td>


      <td width="25%" class="FondoTabla"><span class="Tablas">


        <input name="buscarmaterno" type="text" id="buscarmaterno" value="" class="Tablas" size="15" style="border:none;text-transform:uppercase" onKeyPress="ObtenerConsulta(event,this.name,this.value)" />


      </span></td>


    </tr>


    <tr>


      <td colspan="6">


      <div id="txtHint" style="width:100%; height:300px; overflow: scroll;">


      <table width="572" id="tablaclientes" border="0" align="left" alagregar="" alborrar="" cellpadding="0" cellspacing="0">


      	  <tr>


       		<td width="79" class="Tablas" ></td>


            <td width="90" class="Tablas"></td>


            <td width="64" class="Tablas"></td>


            <td width="103" class="Tablas"></td>


            <td width="111" class="Tablas"></td>


            <td width="125" class="Tablas"></td>


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


      </div>


      </td>


    </tr>


  </table> 


<? //} ?>


</body>


</html>