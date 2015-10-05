<?
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script>
	var u = document.all;
	var mens = new ClaseMensajes();
	mens.iniciar('../../javascript');
	
	window.onload = function(){
		obtenerGeneral();
	}
	
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","tipoajuste_con.php?accion=1");
	}
	
	function mostrarGeneral(datos){
		u.codigo.value = datos;
		u.descripcion.focus();
	}
	
	function validar(){
		if(u.descripcion.value == ""){
			mens.show("A","Debe capturar Descripción","¡Atención!","descripcion");
			return false;
		}
		u.btnGuardar.style.visibility = "hidden";
		consultaTexto("registro","tipoajuste_con.php?accion=2&tipo="+((u.accion.value=="")?"grabar":"modificar")
		+"&descripcion="+u.descripcion.value
		+"&positivo="+((u.positivo.checked==true)?1:0)
		+"&ajuste="+u.codigo.value
		+"&val="+Math.random());		
	}
	
	function registro(datos){
		if(datos.indexOf("ok")>-1){
			var r = datos.split(",");
			if(r[1]=="grabar"){
				u.codigo.value = r[2];
				u.accion.value = r[1];
				u.btnGuardar.style.visibility = "visible";
				mens.show("I","Los datos se guardarón satisfactoriamente","");
			}else if(r[1]=="modificar"){
				u.btnGuardar.style.visibility = "visible";
				mens.show("I","Los cambios se guardarón satisfactoriamente","");
				u.accion.value = r[1];
			}
		}else{
			mens.show("A","Hubo un error al guardar "+datos,"¡Atención!");
			u.btnGuardar.style.visibility = "visible";
		}
	}
	
	function obtenerAjuste(ajuste){
		u.codigo.value = ajuste;
		consultaTexto("mostrarAjuste","tipoajuste_con.php?accion=3&ajuste="+ajuste);
	}
	
	function mostrarAjuste(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.descripcion.value = obj.descripcion;
			u.positivo.checked 	= ((obj.positivo==1)?true:false);
			u.accion.value 		= "modificar";
		}else{
			mens.show("A","El numero de ajuste no existe","¡Atención!");
			u.descripcion.value = "";
			u.positivo.checked = false;
			u.accion.value = "";
		}
	}
	
	function limpiar(){
		u.descripcion.value = "";
		u.positivo.checked = false;
		u.accion.value = "";
		obtenerGeneral();
	}
	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="312" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="308" class="FondoTabla">CAT&Aacute;LOGO TIPO AJUSTE </td>
    </tr>
    <tr>
      <td><table width="301" border="0" align="center">
          <tr>
            <td width="85" class="Tablas"><strong>C&oacute;digo:</strong></td>
            <td width="206"><label>
              <input name="codigo" type="text" id="codigo" class="Tablas" onkeypress="if(event.keyCode==13){obtenerAjuste(this.value);}" style="width:70px" />
              &nbsp;&nbsp; <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../../buscadores_generales/buscarTipoAjuste.php', 550, 480, 'ventana', 'Busqueda')" /></label></td>
          </tr>
          <tr>
            <td class="Tablas">Descripci&oacute;n:</td>
            <td><input name="descripcion" type="text" id="descripcion" class="Tablas" style="text-transform:uppercase;width:150px" onkeypress="if(event.keyCode==13){document.all.positivo.focus();}" /></td>
          </tr>
          <tr>
            <td valign="middle" class="Tablas">Positivo:</td>
            <td><label>
            <input name="positivo" type="checkbox" id="positivo" value="1" />
            </label></td>
          </tr>
          <tr>
            <td height="32"><input name="accion" type="hidden" id="accion" /></td>
            <td><table width="141" border="0" align="right">
                <tr>
                  <td width="67"><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" onclick="validar();" style="cursor:pointer" id="btnGuardar" /></td>
                  <td width="64"><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" title="Nuevo" onclick="mens.show('C','Perderá la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')" style="cursor:pointer" /></td>
                </tr>
            </table></td>
          </tr>
        </table> </td>
    </tr>
  </table>
</form>
</body>
</html>
