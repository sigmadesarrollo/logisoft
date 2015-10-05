<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">

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

	

	</style>

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

.style5 {

	color: #FFFFFF;

	font-size:8px;

	font-weight: bold;

}

.Balance {background-color: #FFFFFF; border: 0px none}

.Balance2 {background-color: #DEECFA; border: 0px none;}

-->

<!--

.Estilo1 {

	color: #FFFFFF;

	font-weight: bold;

	font-size: 13px;

	font-family: tahoma;

}

-->



</style>



<style>

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



</head>



<BODY onLoad="document.all.cantidad.focus();" >

<br>

<form id="form1" name="form1" method="post" action="">

  <table width="320" border="0" align="center" cellpadding="0" cellspacing="0">

    <tr>

      <td width="3" height="3" background="img/Ccaf1.jpg"></td>

      <td bgcolor="dee3d5"></td>

      <td width="3"  background="img/Ccaf2.jpg"></td>

    </tr>

    <tr bgcolor="dee3d5">

      <td height="26"></td>

      <td ><table width="300" border="0" align="center" cellpadding="0" cellspacing="0">

          

          <tr>

            <td class="Tablas"><label>Servicios</label></td>

            <td class="Tablas"><select name="select" style="width:150px">

            </select></td>

            </tr>

          <tr>

            <td width="275" colspan="4"><table width="100" border="0" align="right" cellpadding="0" cellspacing="0">

                <tr>

                  <td><img src="../img/Boton_Agregari.gif" alt="Guardar" width="70" height="20" style="cursor:pointer" onClick="Validar()" /></td>

                  <td><img src="../img/Boton_Cerrar_.gif" alt="Cerrar" width="70" height="20" style="cursor:pointer" onClick="parent.VentanaModal.cerrar()" /></td>

                </tr>

            </table></td>

          </tr>

      </table></td>

      <td></td>

    </tr>

    <tr>

      <td width="3" height="3"  background="img/Ccaf3.jpg"></td>

      <td bgcolor="dee3d5"></td>

      <td width="3"  background="img/Ccaf4.jpg"></td>

    </tr>

  </table>

  <p>&nbsp;</p>

  <p>&nbsp;</p>

</form>

</body>

</html>

