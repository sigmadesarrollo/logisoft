<?
	session_start();
	if($_GET[datos]!=""){
		$arre = split(";",$_GET[datos]);
		$identificacion = $arre[0];
		$noidentificacion = $arre[1];
		$nombre = $arre[2];
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cat&aacute;logo Bancos</title>
<script language="javascript" src="../javascript/ClaseMensajes.js"></script>
<link href="../catalogos/sucursal/puntovta.css" rel="stylesheet" type="text/css">
<link href="../catalogos/sucursal/FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../catalogos/sucursal/Tablas.css" rel="stylesheet" type="text/css">
</head>
<script>
	var mens = new ClaseMensajes();
	mens.iniciar("../javascript/");
</script>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0">
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td><table width="373" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
        <tr>
          <td class="FondoTabla">DATOS PARA FRONTERA</td>
        </tr>
        <tr>
          <td><table width="369" border="0" align="center">
            <tr>
              <td width="111" class="Tablas">Tipo Identificaci&oacute;n:</td>
              <td width="248">
                <select name="identificacion" class="Tablas" style="width:160px;" >
                  <option value="" selected="selected">.:: IDENTIFICACION ::.</option>
                  <option value="CREDENCIAL DE ELECTOR" <?=($identificacion=='CREDENCIAL DE ELECTOR')?"SELECTED":"";?>>CREDENCIAL DE ELECTOR</option>
                  <option value="LICENCIA DE MANEJO" <?=($identificacion=='LICENCIA DE MANEJO')?"SELECTED":"";?>>LICENCIA DE MANEJO</option>
                  <option value="PASAPORTE" <?=($identificacion=='PASAPORTE')?"SELECTED":"";?>>PASAPORTE</option>
                </select>
                &nbsp;</td>
              </tr>
            <tr>
              <td width="111" class="Tablas">No. de Identificaci&oacute;n</td>
              <td>
                <input name="noidentificacion" type="text" id="noidentificacion" class="Tablas" value="<?= $noidentificacion ?>" style=" font:tahoma; font-size:9px; text-transform:uppercase; width:200px" />
                &nbsp;</td>
              </tr>
            <tr>
              <td class="Tablas">Nombre:</td>
              <td><input name="nombre" type="text" id="nombre" class="Tablas" value="<?=$nombre ?>" style=" font:tahoma; font-size:9px; text-transform:uppercase; width:200px" /></td>
            </tr>
            <tr>
              <td height="32">&nbsp;</td>
              <td><table width="141" border="0" align="right">
                  <tr>
                    <td><img src="../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" onClick="enviarDatos()" style="cursor:pointer" ></td>
                    <td><img src="../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" title="Nuevo" onClick="mens.show('C','Perdera la información capturada ¿Desea continuar?', '¡ATENCION!','','Limpiar();', '')" style="cursor:pointer" ></td>
                  </tr>
              </table></td>
            </tr>            
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
	function enviarDatos(){
		var ub = parent.document.all;
		var u  = document.all;
		if(u.identificacion.value==""){
			mens.show("A","Seleccione la identificación","ATENCION","identificacion");
			return false;
		}
		if(u.noidentificacion.value==""){
			mens.show("A","Proporcione el número de identificación","ATENCION","noidentificacion");
			return false;
		}
		if(u.nombre.value==""){
			mens.show("A","Proporcione el nombre","ATENCION","nombre");
			return false;
		}
		
		ub.txtobservaciones.value=u.identificacion.value+";\n"+u.noidentificacion.value+";\n"+u.nombre.value;
		parent.VentanaModal.cerrar();
	}
	
	function Limpiar(){
		var ub = parent.document.all;
		ub.txtobservaciones.value="";
		parent.VentanaModal.cerrar();
	}
</script>