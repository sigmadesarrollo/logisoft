<?
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script src="../javascript/jquery-1.4.2.min.js" language="javascript"></script>
<script src="../javascript/ajax.js" language="javascript"></script>

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
</head>
<style type="text/css">
	.td{
		font-family:Verdana, Geneva, sans-serif;
		font-size:12px;
		font-weight:bold;
	}
	.td input {
		font-family:Verdana, Geneva, sans-serif;
		font-size:12px;
	}
</style>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="619" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="619" class="FondoTabla Estilo4">REGISTRO DE USUARIOS PARA LOS CLIENTES</td>
  </tr>
  <tr>
    <td width="619" height="127" valign="top">
    	<table width="617" height="106" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="88" class="td">Cliente</td>
                <td width="109" class="td"><input type="text" id="idcliente" name="idcliente" value="" style="width:80px" onkeypress="if(event.keyCode==13){ cargarCliente(this.value); }" /></td>
                <td width="35"><img src="../img/Buscar_24.gif" style="cursor:pointer" onclick="mostrarBuscador();" /></td>
                <td width="385" class="td" id="nombrecliente"></td>
            </tr>
        	<tr>
        	  <td colspan="4" valign="top">
              	<table width="614" height="109" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="88" class="td">Usuario</td>
                        <td width="220" class="td"><input type="text" name="usuario" id="usuario" style="width:150px;" /></td>
                        <td width="88" class="td">Password</td>                                
                        <td width="218" class="td"><input type="password" name="password" id="password" style="width:150px;" /></td>
                	</tr>
                	<tr>
                    	<td height="19" colspan="4" class="td">Enviar información al siguiente correo</td>
                    </tr>
                	<tr>
                    	<td width="88">&nbsp;</td>
                        <td colspan="3" class="td"><input type="text" id="email" name="email" style="width:350px;" /></td>
                    </tr>
                	<tr>
                	  <td class="td">Estado</td>
                	  <td colspan="3" class="td">
                      <label><input type="radio" id="activar" name="activar" value="S" checked="checked" />Activado</label>
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <label><input type="radio" id="desactivar" name="activar" value="N" />Desactivado</label>
                      </td>                
              	  </tr>
                	<tr>
                	  <td height="32" colspan="4" valign="middle" align="center">
                      	<img src="../img/Boton_Guardar.gif" style="cursor:pointer" onclick="validar()" />
                        &nbsp;&nbsp;&nbsp;
                        <img src="../img/Boton_Nuevo.gif" style="cursor:pointer" onclick="limpiar()" />
                        &nbsp;&nbsp;&nbsp;
                        <img src="../img/Boton_Email.gif" style="cursor:pointer" onclick="enviarCorreo()" />
                      </td>
               	  </tr>
                </table>              
              </td>
       	  </tr>
       	</table>
    </td>
  </tr>
  </table>
</form>
<script>
	function limpiar(){
		$("input[type='text']").val("");
		$("input[type='password']").val("");
		$("input[id='desactivar']").removeAttr("checked");
		$("input[id='activar']").attr("checked","true");
		$("#nombrecliente").html("");
	}
	
	function cargarCliente(valor){
		$.ajax({
		   type: "POST",
		   url: "registrarusuariosclientes_con.php",
		   data: "accion=1&cliente="+valor,
		   success: function(obj){
			   var dat = eval(obj);
			   if(dat.id!=null){
					$("#idcliente").val(dat.id);
					$("#nombrecliente").html(dat.cliente);
					$("#usuario").val(dat.usuario);
					$("#password").val(dat.password);
					$("#email").val(dat.email);
					
					$("input[id='desactivar']").removeAttr("checked");
					$("input[id='activar']").removeAttr("checked");
					if(dat.activado=='S'){
					$("input[id='activar']").attr("checked","true");
					}else{
					$("input[id='desactivar']").attr("checked","true");
					}
					ocultarBuscador();
			   }else{
					alerta3("No se encontro el cliente buscado","!Atencion¡");
			   }
		   }
		 });
	}
	
	function validar(){
		if($("#id").val()=="" && $("#nombrecliente").val()==""){
			alerta3("Proporcione el cliente","¡Atencion!");
			return false;
		}
		
		if($("#usuario").val()==""){
			alerta3("Proporcione el usuario","¡Atencion!");
			return false;
		}
		
		if($("#password").val()==""){
			alerta3("Proporcione el password","¡Atencion!");
			return false;
		}
		
		if($("#email").val()==""){
			confirmar("Si no proporciona un correo, no se le enviara notificación al cliente, ¿Desea continuar?","Atencion","guardar()");
		}else{
			guardar();
		}
	}
	
	function guardar(){		
		$.ajax({
		   type: "POST",
		   url: "registrarusuariosclientes_con.php",
		   data: "accion=2&"+$("form").serialize(),
		   success: function(resultado){
			   if(resultado.indexOf("guardado")>-1){
					info("Datos guardados","¡Atencion!")
			   }else{
					alerta3("Error al guardar "+resultado,"!Atencion¡");
			   }
		   }
		 });
	}
	
	function enviarCorreo(){
		if($("#id").val()=="" && $("#nombrecliente").val()==""){
			alerta3("Proporcione el cliente","¡Atencion!");
			return false;
		}
		
		if($("#usuario").val()==""){
			alerta3("Proporcione el usuario","¡Atencion!");
			return false;
		}
		
		if($("#password").val()==""){
			alerta3("Proporcione el password","¡Atencion!");
			return false;
		}
		
		if($("#email").val()==""){
			alerta3("Proporcione el correo","Atencion");
			return false;
		}
		
		$.ajax({
		   type: "POST",
		   url: "registrarusuariosclientes_con.php",
		   data: "accion=3&"+$("form").serialize(),
		   success: function(resultado){
			   if(resultado.indexOf("enviado")>-1){
					info("Correo enviado","¡Atencion!")
			   }else{
					alerta3("Error al guardar "+resultado,"!Atencion¡");
			   }
		   }
		 });
	}
</script>
<?
	$raiz = "../";
	$funcion = "cargarCliente";
	$nombreBuscador = "buscadorClientes";
	$funcionMostrar = "mostrarBuscador";
	$funcionOcultar = "ocultarBuscador";
	include("../buscadores_generales/buscadorIncrustado.php");
?>
</body>
</html>