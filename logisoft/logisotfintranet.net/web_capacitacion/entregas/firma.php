<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="../javascript/ClaseMensajes.js"></script>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Prueba de Firma</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>

<script>
	var mens = new ClaseMensajes();
	mens.iniciar("../javascript");
	
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
				mens.show("I","La firma se obtuvo satisfactoriamente");
			}else{
				mens.show("A","La firma no se pudo obtener","¡Atención!");
			}
		}else{
			mens.show("A","Debe capturar la firma","¡Atención!");
		}
    }
	
	function limpiarFirma(){
		Tablet.Clear();
	}
	
</script>

<body >
<table width="410" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="4" align="center"><OBJECT ID="Tablet" name="Tablet" width="400" height="200"
		CLASSID="CLSID:1D44B406-4BFA-470C-BDB1-769C89210A41"
		CODEBASE="../activexs/FirmaDigital.CAB#version=1,0,0,8">
    </OBJECT></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="13">&nbsp;</td>
    <td width="223">&nbsp;</td>
    <td width="89"><img src="../img/Boton_Guardar.gif" style="cursor:pointer" onclick="OnSubmit()"/></td>
    <td width="85"><img src="../img/Boton_Nuevo.gif" style="cursor:pointer" onclick="limpiarFirma()"/></td>
  </tr>
</table>
</body>
</html>
