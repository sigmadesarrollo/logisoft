<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

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



<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">

</head>



<BODY onLoad="document.all.cantidad.focus();" >

<br>

<form id="form1" name="form1" method="post" action="">

  <p>&nbsp;</p>

</form>

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

        <td class="Tablas">Descripción:</td>

        <td class="Tablas"><input name="descripcion" type="text" class="Tablas" id="descripcion" onBlur="CalcularUnitarioFoco()" onKeyPress="return Numeros(event)" onKeyDown="CalcularUnitario(event); return tabular(event,this)" value="<?=$descripcion2 ?>" size="12" maxlength="15" /></td>

        <td class="Tablas"><div class="ebtn_buscar"></div></td>

        <td class="Tablas"><input name="descripcion2" type="text" class="Tablas" id="descripcion2" onBlur="CalcularUnitarioFoco()" onKeyPress="return Numeros(event)" onKeyDown="CalcularUnitario(event); return tabular(event,this)" value="<?=$descripcion2 ?>" size="12" maxlength="15" /></td>

      </tr>

      <tr>

        <td class="Tablas">Peso:</td>

        <td colspan="3" class="Tablas"><input name="peso" type="text" class="Tablas" id="peso" onBlur="CalcularUnitarioFoco()" onKeyPress="return Numeros(event)" onKeyDown="CalcularUnitario(event); return tabular(event,this)" value="<?=$peso ?>" size="15" maxlength="15" /></td>

      </tr>

      <tr>

        <td class="Tablas">Precio:</td>

        <td colspan="3" class="Tablas"><input name="precio" type="text" class="Tablas" id="precio" onBlur="CalcularUnitarioFoco()" onKeyPress="return Numeros(event)" onKeyDown="CalcularUnitario(event); return tabular(event,this)" value="<?=$precio ?>" size="15" maxlength="15" /></td>

      </tr>

      <tr>

        <td>Precio KM Exc: </td>

        <td colspan="3"><span class="Tablas">

          <input name="preciokm" type="text" class="Tablas" id="preciokm" onBlur="CalcularUnitarioFoco()" onKeyPress="return Numeros(event)" onKeyDown="CalcularUnitario(event); return tabular(event,this)" value="<?=$preciokm ?>" size="15" maxlength="15" />

        </span></td>

      </tr>

      <tr>

        <td>Precio KG Exc: </td>

        <td colspan="3"><span class="Tablas">

          <input name="preciokg" type="text" class="Tablas" id="preciokg" onBlur="CalcularUnitarioFoco()" onKeyPress="return Numeros(event)" onKeyDown="CalcularUnitario(event); return tabular(event,this)" value="<?=$preciokg ?>" size="15" maxlength="15" />

        </span></td>

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

</body>

</html>

