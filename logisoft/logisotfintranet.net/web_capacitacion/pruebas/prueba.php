<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script>
	var u = document.all;
	
	function OnPageLoad(){	
	  try{
		Tablet.Clear();
	  }catch(e){     
	  
	  }
	}
	
    function OnSubmit(){
		if(Tablet.Firmo()!=0){
			var firma = Tablet.SaveImage("pmm_dbpruebas");
			if(firma!=0){
				parent.<?=$_GET[funcion] ?>(firma);
				u.mensaje.innerHTML = "La firma se obtuvo satisfactoriamente";
			}else{
				u.mensaje.innerHTML = "La firma no se pudo obtener";
			}
		}else{
			u.mensaje.innerHTML = "Debe capturar la firma";
		}
    }
	
	function limpiarFirma(){
		u.mensaje.innerHTML = "";
		Tablet.Clear();
	}
	
</script>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Prueba de Firma</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>

<body >
  <table width="410" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="4" align="center"><OBJECT id="Tablet" name="Tablet"
		classid="CLSID:1D44B406-4BFA-470C-BDB1-769C89210A41"
		CODBASE="../activexs/FirmaDigital.CAB#version=1,0,0,10" >
      </OBJECT>
	  </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td width="223">&nbsp;</td>
      <td width="89"><img src="../img/Boton_Guardar.gif" style="cursor:pointer" onclick="OnSubmit()"/></td>
      <td width="85"><img src="../img/Boton_Nuevo.gif" style="cursor:pointer" onclick="limpiarFirma()"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="13">&nbsp;</td>
      <td colspan="3" id="mensaje" style="font:tahoma; font-size:15px; font-weight:bold">&nbsp;</td>
    </tr>
  </table>
  </body>

</html>
