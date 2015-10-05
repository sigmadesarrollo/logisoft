<?

	require_once("conexion2.php");

	$link=conexion();

?>



<style type="text/css">





body {

	margin-left: 0px;

	margin-top: 0px;

	margin-right: 0px;

	margin-bottom: 0px;

	background-color: #0099CC;

	background-image: url(images/Fondo.jpg);

}

</style>





<link href="css/generales.css" rel="stylesheet" type="text/css">



<?



$query = "Select id_folio as Clave,zonai,zonaf from configuracion order by id_folio";

$dataset = @mysql_query($query, $link);

?>



<html>

<title>Buscar Configuracion</title>

<link href="../../recoleccion/Tablas.css" rel="stylesheet" type="text/css">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

    <!-- include AW stylesheet and script -->

    <link href="runtime/styles/xp/aw.css" rel="stylesheet" type="text/css" ></link>

	<link href="Styles.css" rel="stylesheet" type="text/css"></link>

    <script src="runtime/lib/aw.js"></script>

<script>

<!-- ESTO ES PARA EL F5 PARA QUE NO FUNCIONE -->

   document.onkeydown = function(){  

    if(window.event && window.event.keyCode == 116){ 

     window.event.keyCode = 505;  

    } 

    if(window.event && window.event.keyCode == 505){  

     return false;     

    }  

   }     

   </script>

  <!-- TERMINA -->

  <!-- ESTO ES PARA QUE NO FUNCIONE EL BOTON DERECHO DEL MOUSE -->



  <script language=JavaScript>

<!--



var message="";

///////////////////////////////////

function clickIE() {if (document.all) {(message);return false;}}

function clickNS(e) {if

(document.layers||(document.getElementById&&!document.all)) {

if (e.which==2||e.which==3) {(message);return false;}}}

if (document.layers)

{document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;}

else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;}



document.oncontextmenu=new Function("return false")

// -->

</script>

  <!-- TERMINA -->

  <!-- ES PARA MANDAR EL DATO LA FORMA QUE LLAMO ESTE POPUP -->



<SCRIPT LANGUAGE="JavaScript">

<!-- Begin

function sendValue(valor){

	window.opener.document.form2.txtclave.value =valor;

	window.opener.document.form2.submit();

	window.close();

   }

//  End -->

</script>

  <!-- TERMINA -->

  

</head>

<body onLoad="document.selectform.txtbuscar.focus();" >



<table border="2" align="center" bgcolor="#DEECFA" class="Cabecera10">

	<tr>

	<td height="89">

	<table>

	<tr>

	<td width="887" >

<!--************** Tabla en la que se encuentra el motor de busqueda **********--> 	

        <form name="selectform"  method="post" action="">	

        <span class="Cabecera10">Buscar:</span>  

        <input name="txtbuscar" type="text" class="CaptionL" style="text-transform:uppercase;font:Arial, Helvetica, sans-serif; font-size:9px " onKeyDown="if (window.event.keyCode==113){document.selectform.submit();}" size="40" maxlength="100">

        <INPUT  TYPE="submit"  Value="Buscar" NAME="Boton2" align="absbottom" class=boxlookLila>



</form>



    <table width="602" border="0" align="center" cellpadding="0" cellspacing="0" id="DCliente">

    <tr>

   	<td width="51"  align="center" class="Cabecera10"><font color="#000099">Codigo</font></td>

    <td width="551"  align="center" class="Cabecera10"><font color="#000099">Zona Inicial</font></td>

    </tr>

    <tr>

    <td height="18" colspan="11" align="center" bgcolor="#FFFFFF" Id="IDetalle">

  		 <div style="position:relative; width:600px; height:250px; z-index:2; overflow: Auto;"> 

         <table border="0" cellpadding="0" cellspacing="1" id="DetallePedido"  name="DetallePedido" align="left" class="EncabAzul">

         <tbody id='DetallePedido1' name='DetallePedido1'>

         <?

		 $cSw=0;

  		 $sql="Select * from configuracion where id_folio like '" . $_POST[txtbuscar] . "%' order by id_folio";

		 $rec=mysql_query($sql,$link) or die ("Error en la linea" .__LINE__. "Llamar al Web Master <br> $sql");

		 while ($row=mysql_fetch_array($rec)){		 

			  if($cSw==0){ $cSw=1; $Class="Cuadro"; } else { $cSw=0; $Class="Cuadro_Azul"; }

			  ?>

       		  <tr onClick="sendValue('<?=$row['id_folio']?>');" style="cursor:pointer; cursor:hand" class="<?=$Class?>" Estilo='<?=$Class?>'> 

       		  

       		  <td width="50"  ><?=$row['id_folio']?></td>

       		  <td width="550"  ><?=$row['zonai']?></td>

   			  </tr><?

		 } ?>

   	     </tbody>

 		 </table>

	 	 </div>

	 </td>

	 </tr>

	</table>







	</td>

	</tr>

	</table>   

	 </td>

    </tr>

</table>



</body>

</html>