<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script type="text/javascript" src="js/ajax.js"></script> 
<SCRIPT language="JavaScript" src="moautocomplete.js"></SCRIPT>
<script src="../javascript/ajax.js"></script>
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Datos Evaluación</title>
<script type="text/javascript" src="js/ajax-dynamic-list.js"></script>
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<style type="text/css">	
	
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:175px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:0.9em;
		z-index:100;
	}
	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */
		margin:1px;		
		padding:1px;
		cursor:pointer;
		font-size:0.9em;
	}
	#ajax_listOfOptions .optionDiv{	/* Div for each item in list */
		
	}
	#ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */
		background-color:#317082;
		color:#FFF;
	}
	#ajax_listOfOptions_iframe{
		background-color:#F00;
		position:absolute;
		z-index:5;
	}
	
	form{
		display:inline;
	}

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
.style5 {
	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
	font-size: 13px;
	font-family: tahoma;
}
.Txtamarillo{
font:tahoma; font-size:9px; background-color:#FFFF99;text-transform:uppercase;
}
.Txt{
font:tahoma; font-size:9px;text-transform:uppercase;
}

.Button {
margin: 0;
padding: 0;
border: 0;
background-color: transparent;
width:70px;
height:20px;
}
.Estilo2 {
	font-size: 8px;
	font-weight: bold;
}
.Estilo3 {font-size: 9px}
.style31 {font-size: 9px;
	color: #464442;
}
.style31 {font-size: 9px;
	color: #464442;
}
</style>

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
</head>
<script>
	function guardarInformacion(){
		parent.document.all.motivonoautorizar.value = document.all.motivonoautorizar.value;
		if('<?=$_GET[estado]?>'=='abierto'){
			parent.darEstadoPropuesta('NO AUTORIZADA');
		}
		parent.VentanaModal.cerrar()
	}
</script>
<BODY>
<br>
<form id="form1" name="form1" method="post" action="">  
  <table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="3" height="3" background="../img/Ccaf1.jpg"></td>
      <td bgcolor="dee3d5"></td>
      <td width="3"  background="../img/Ccaf2.jpg"></td>
    </tr>
    <tr bgcolor="dee3d5">
      <td height="114" rowspan="2"></td>
      <td >Motivo de la no Autorización</td>
      <td rowspan="2"></td>
    </tr>
    <tr bgcolor="dee3d5">
      <td height="90" >
      	<textarea name="motivonoautorizar" rows="5" style="width:350px"></textarea>
      </td>
    </tr>
    <tr>
      <td width="3" height="21"></td>
      <td align="center"> <img src="../img/Boton_Aceptar.gif" style="cursor:pointer" onclick="guardarInformacion()" /></td>
      <td width="3" ></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
</html>
