<?

 	session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if ( isset ( $_SESSION['gvalidar'] )!=100 ){

	// Muestra el index si no se esta autentificado

	 echo "<script language='javascript' type='text/javascript'>

						document.location.href='../../index.php';

					</script>";

	}else{*/



?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Untitled Document</title>

<link href="../../css/Tablas.css" rel="stylesheet" type="text/css" />

<link href="../../css/FondoTabla.css" rel="stylesheet" type="text/css" />

</head>

<script src="select.js"></script>

<script language="javascript">

function BuscarColonia(e,colonia){

		tecla=(document.all) ? e.keyCode : e.which;		

           if(tecla!=13){

			 return;	

			}else{			

			ConsultaColoniaSucursal(colonia,'3');

	  }

}

</script>



<body onLoad="document.all.buscar.focus();">



  <table width="530" border="1" bordercolor="#006194"  align="center" cellpadding="0" cellspacing="0">

    <tr  class="FondoTabla">

      <td  width="40%" class="FondoTabla">        

        Colonia

        </td>

      <td  width="7%"class="FondoTabla">CP</td>

      <td width="17%" class="FondoTabla">Poblaci&oacute;n</td>

      <td width="16%" class="FondoTabla">Municipio</td>

      <td  width="20%" class="FondoTabla">Estado</td>

    </tr>

    <tr class="FondoTabla">

      <td width="275" class="FondoTabla">        

          <input name="buscar" type="text" class="Tablas" id="buscar" onKeyDown="BuscarColonia(event,this.value);" size="50"  style="text-transform:uppercase;"/>

        </td>

      <td width="91">&nbsp;</td>

      <td width="98">&nbsp;</td>

      <td width="99">&nbsp;</td>

      <td width="125" bgcolor="#006194">&nbsp;</td>

    </tr>

    <tr>

      <td colspan="5">

      <div id="divColonia" style="width:100%; height:170px; overflow: scroll;"></div>      </td>

</tr>

</table>

</body>

</html>

<? //} ?>