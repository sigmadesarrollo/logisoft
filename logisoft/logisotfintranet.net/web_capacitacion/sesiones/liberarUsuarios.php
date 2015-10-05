<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script src="../javascript/ClaseTabla.js"></script>

<script src="../javascript/ClaseMensajes.js"></script>

<script src="../javascript/ajax.js"></script>

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />

<title>Untitled Document</title>

</head>

<script>

	var mens = new ClaseMensajes();

	var tabla1 = new ClaseTabla();

	

	tabla1.setAttributes({

		nombre:"usuarios",

		campos:[

			{nombre:"ID", medida:4, alineacion:"left", tipo:"oculto", datos:"idusuario"},

			{nombre:"EMPLEADO", medida:180, alineacion:"left", datos:"empleado"},

			{nombre:"USUARIO", medida:100, alineacion:"left", datos:"usuario"},

			{nombre:"FECHA", medida:100, alineacion:"center", datos:"fecha"},

			{nombre:"IP", medida:90, alineacion:"center", datos:"ip"}

		],

		filasInicial:10,

		alto:150,

		seleccion:true,

		ordenable:false,

		nombrevar:"tabla1"				 

	});

	

	window.onload = function (){

		mens.iniciar("../javascript",false);

		tabla1.create();

		consultaTexto("mostrarUsuarios","liberarUsuarios_con.php?accion=1");

	}

	

	function mostrarUsuarios(datos){

		var registros = eval(datos);

		

		tabla1.setJsonData(registros);

	}

	

	function preguntar(){

		if(tabla1.getSelectedRow()!=undefined){

			mens.show("C","¿Desea Desbloquear a "+tabla1.getSelectedRow().usuario+"?","Desbloquear Usuario","","desbloquearUsuario()","");

		}else{

			mens.show("A","Seleccione un usuario","¡Atencion!");

		}

	}

	

	function desbloquearUsuario(){

		consultaTexto("resDesbloquearUsuario","liberarUsuarios_con.php?accion=2&idusuario="+tabla1.getSelectedRow().idusuario);

	}

	

	function resDesbloquearUsuario(datos){

		if(datos.indexOf('desbloqueado')>-1){

			tabla1.deleteById(tabla1.getSelectedIdRow());

			mens.show("I","Usuario Desbloqueado", "Atencion");

		}else{

			mens.show("A","Error al desbloquear","¡Atencion!");

		}

	}

</script>

<body>

<table width="452" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

<tr>

<td width="448" class="FondoTabla">Desbloquear Usuarios</td>

  </tr>

      <tr>

      	<td height="171" valign="top">

        

        <table width="448" border="0" cellpadding="0" cellspacing="0">

<tr>

            	<td width="8">&nbsp;</td>

                <td></td>

                <td width="113"></td>

<td width="10"></td>

          </tr>

<tr>

  <td height="100" colspan="4">

  	<table cellpadding="0" cellspacing="0" border="0" id="usuarios">

  	  <tr>

  	    <td></td>

  	    <td></td>

  	    <td></td>

  	    </tr>

	  </table>  </td>

</tr>

<tr>

  <td>&nbsp;</td>

  <td colspan="2"></td>

<td></td>

</tr>

<tr>

  <td>&nbsp;</td>

  <td width="317"></td>

  <td colspan="2"><div class="ebtn_desbloquear" onclick="preguntar()"></div></td>

</tr>

<tr>

  <td>&nbsp;</td>

  <td colspan="2"></td>

<td></td>

</tr>

        </table>        </td>

      </tr>

</table>

</body>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'LIBERAR USUARIOS';

</script>

</html>

