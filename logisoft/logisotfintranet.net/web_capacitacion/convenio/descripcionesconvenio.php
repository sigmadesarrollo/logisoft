<?

session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

	require_once("../Conectar.php");

	$l	= Conectarse("webpmm");

?>



<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<script type="text/javascript" src="../javascript/ajax.js"></script>

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />

<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<style type="text/css">

<!--

.style2 {	color: #464442;

	font-size:9px;

	border: 0px none;

	background:none

}

.style5 {	color: #FFFFFF;

	font-size:8px;

	font-weight: bold;

}

.Balance {background-color: #FFFFFF; border: 0px none}

.Balance2 {background-color: #DEECFA; border: 0px none;}

-->

</style>

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />

<style type="text/css">

<!--

.Estilo4 {font-size: 12px}

.Estilo5 {

	font-size: 9px;

	font-family: tahoma;

	font-style: italic;

}

-->

</style>

</head>

<script>

	function agregar(){

		if(document.all.descripciones.value==""){

			alerta("Seleccione una descripcion para agregarla a la mercancia seleccionada", "&iexcl;Atencion!","descripciones");

		}else{

			parent.cambiarValor(document.all.descripciones.value);

			parent.VentanaModal.cerrar();

		}

	}

</script>

<body>

<form id="form1" name="form1" method="post" action="" onSubmit="return false;">

  <br>

<table width="267" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr>

    <td width="207" class="FondoTabla Estilo4">Datos Generales  </td>

  </tr>

  <tr>

    <td height="80"><table width="265" border="0" align="center" cellpadding="0" cellspacing="0">

      <tr>

        <td width="20">&nbsp;</td>

        <td width="30">&nbsp;</td>

        <td width="50"><div align="right"></div></td>

        <td width="107">&nbsp;</td>

      </tr>

      <tr>

        <td colspan="2" class="Tablas"><label>Descripcion</label></td>

        <td colspan="2" class="Tablas"><select name="descripciones" style="width:150px" >

        <option value=""></option>

        <?
			
			if($_GET[tipo]=='E'){
				$and = " AND tipo = 'CONSIGNACION' ";
			}else{
				$and = " AND tipo = 'CONVENIO' ";
			}
			
			$s = "SELECT descripcion FROM cconvenio_configurador_caja 

			WHERE idconvenio = $_GET[idconvenio] 
			
			$and
			
			GROUP BY descripcion";

			$r = mysql_query($s,$l) or die($s);

			while($f = mysql_fetch_object($r)){

			?>

			<option value="<?=$f->descripcion?>"><?=$f->descripcion?></option>

			<?	

			}

		?>

        </select></td>

      </tr>

      <tr>

        <td width="20">&nbsp;</td>

        <td width="30">&nbsp;</td>

        <td width="50"><div align="right"></div></td>

        <td width="107">&nbsp;</td>

      </tr>

<tr>

        <td height="28" colspan="4" align="center"><table>

        	<tr>

       <td> <div class="ebtn_agregar" onClick="agregar()"></div></td>

        <td><div class="ebtn_cerrarventana" onClick="parent.VentanaModal.cerrar()"></div></td>

            </tr>

        </table>        </td>

      </tr>

    </table></td>

  </tr>

</table>

<p>&nbsp;</p>

</form>

</body>

</html>





