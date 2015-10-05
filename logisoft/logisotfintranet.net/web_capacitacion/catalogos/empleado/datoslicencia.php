<?

/*if ( isset ( $_SESSION['gvalidar'] )!=100 ){

	// Muestra el index si no se esta autentificado

	 echo "<script language='javascript' type='text/javascript'>

						document.location.href='../../index.php';

					</script>";

	}else{*/

require_once('../../Conectar.php');	$link=Conectarse('webpmm'); $usuario=$_SESSION[NOMBREUSUARIO]; $nlicencia=$_GET['nlicencia']; $sltlicencia=$_GET['sltlicencia']; 		$vigencia=$_GET['vigencia']; $lentes=$_GET['lentes'];

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Datos Licencia </title>



<link type="text/css" rel="stylesheet" href="calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>

<SCRIPT type="text/javascript" src="calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script language="JavaScript" type="text/javascript">

function validar(){

	var u = document.all;

	if(u.nlicencia.value!=""){

		if(u.sltlicencia.value==0){

			alerta('Debe Capturar Tipo Licencia','¡Atención!','sltlicencia');

		}else if(u.vigencia.value==""){

			alerta('Debe Capturar Vigencia','¡Atención!','vigencia');		

		}else{

			CrearArray();

		}

	}else{

		CrearArray();

	}

}

function CrearArray(){

window.parent.obtener(document.getElementById('nlicencia').value,document.getElementById('sltlicencia').value,document.getElementById('vigencia').value,document.all.lentes.value,document.getElementById('accionli').value);

parent.VentanaModal.cerrar();

}

function tabular(e,obj) 

        {

            tecla=(document.all) ? e.keyCode : e.which;

            if(tecla!=13) return;

            frm=obj.form;

            for(i=0;i<frm.elements.length;i++) 

                if(frm.elements[i]==obj) 

                { 

                    if (i==frm.elements.length-1) 

                        i=-1;

                    break 

                }

            if (frm.elements[i+1].disabled ==true )    

                tabular(e,frm.elements[i+1]);

            else if (frm.elements[i+1].readOnly ==true )    

                tabular(e,frm.elements[i+1]);

            else frm.elements[i+1].focus();

            return false;

} 

function Cerrar(){

	window.parent.ValidarLicenciax();

}

</script>



<script language="JavaScript" type="text/javascript" src="calendar/calendar.js"></script>

<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css">

<link href="puntovta.css" rel="stylesheet" type="text/css">



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

.style5 {color: #FFFFFF ; font-size:9px}

.Balance {background-color: #FFFFFF; border: 0px none}

.Balance2 {background-color: #DEECFA; border: 0px none;}

-->

<!--

-->

.Estilo6 {

	font-size: 9px;

	font-weight: bold;

}

-->

.txtbox{

	font-size:9px;

	text-transform: uppercase;

}

</style>

<link href="calendar/calendar_style.css" rel="stylesheet" type="text/css" />

<style type="text/css">

<!--

.Button {margin: 0;

padding: 0;

border: 0;

background-color: transparent;

width:70px;

height:20px;

}

.txtbox {font-size:9px}

-->

</style>

<link href="../../css/FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="../../css/Tablas.css" rel="stylesheet" type="text/css" />

<link href="Tablas.css" rel="stylesheet" type="text/css">

</head>



<body onLoad="document.all.nlicencia.focus();" >

 <form id="form1" name="form1" >		 

			   

			    <p>&nbsp;</p>

<table width="349" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

                  <tr>

                    <td width="345"  bordercolor="#016193" class="FondoTabla">Datos Generales</td>

                  </tr>

                  <tr>

                    <td ><!-- aki mero -->

                        <table width="345" height="108" border="0" align="center" cellpadding="0" cellspacing="0">

                          <tr>

                            <td width="98"  class="Estilo6">No.Licencia:</td>

                            <td width="172" class="Tablas">

                                <input name="nlicencia" type="text" class="Tablas" id="nlicencia" onKeyPress="return tabular(event,this)" value="<?=$nlicencia ?>" size="20"/>

                            </td>

                            <td width="75">&nbsp;</td>

                          </tr>

                          <tr>

                            <td  class="Estilo6">Tipo de Licencia: </td>

                            <td class="Tablas">

                                <select onKeyPress="return tabular(event,this)" name="sltlicencia" class="Tablas" id="sltlicencia" >

                                  <option value="0">Seleccione</option>

                                  <option value="ESTATAL" <? if($sltlicencia=="ESTATAL"){ echo'selected'; } ?> >Licencia Chofer Estatal</option>

                                  <option value="FEDERAL B" <? if($sltlicencia=="FEDERAL B"){ echo'selected'; } ?> >Licencia Federal B</option>

                                  <option value="FEDERAL C" <? if($sltlicencia=="FEDERAL C"){ echo'selected'; } ?> >Licencia Federal C</option>

                                  <option value="FEDERAL D" <? if($sltlicencia=="FEDERAL D"){ echo'selected'; } ?> >Licencia Federal D</option>

                                </select>                            </td>

                            <td class="Tablas"><strong>

                            <input name="lentes" onKeyPress="return tabular(event,this)" type="checkbox" id="lentes"  value="1" onClick="if(this.checked){document.getElementById('lentes').value='1';} else {document.getElementById('lentes').value='0';} "  <? if($lentes=='1'){echo "checked";} ?> />

Lentes </strong></td>

                          </tr>

                          <tr>

                            <td  class="Estilo6">Vigencia:</td>

                            

                  <td class="Tablas">

                  <input name="vigencia" type="text" class="Tablas" id="vigencia" readonly="" value="<?=$vigencia ?>" />

                  <img src="../../img/calendario.gif" alt="Alta" width="25" height="25" align="absbottom" style="cursor:pointer" title="Calendario" onClick="displayCalendar(vigencia,'dd/mm/yyyy',this)" /></td>

                            <td class="Tablas">&nbsp;</td>

                          </tr>

                          <tr>

                            <td class="Tablas">

                            <input name="accionli" type="hidden" id="accionli" value="<?=$accionli ?>" />

                            </td>

                            <td colspan="2"><table width="0" border="0" align="right" cellpadding="0" cellspacing="0">

                              <tr>

                                <td class="Tablas">

                                 <img src="../../img/Boton_Agregari.gif" onClick="validar();" alt="enviar" width="70" height="20" />

                                </td>

                                <td></td>

</tr>

                            </table></td>

                          </tr>

                    </table></td>

                  </tr>

                </table>

			   

                </form>

</body>

</html>

<? //} ?>