<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if ( isset ( $_SESSION['gvalidar'] )!=100 ) {

	 echo "<script language='javascript' type='text/javascript'>

						document.location.href='../../index.php';

					</script>";

	}else{ */

	include('../../Conectar.php');	

	$link=Conectarse('webpmm');

$accion=$_POST['accion']; $codigo=$_POST['codigo']; $descripcion=$_POST['descripcion']; $tcarga=$_POST['tcarga']; $tdescarga=$_POST['tdescarga']; $usuario=$_POST['usuario'];	

	

	if($accion=="grabar"){

		$sqlins=mysql_query("INSERT INTO catalogocargadescarga (unidad, tcarga, tdescarga, usuario, fecha)VALUES('$codigo', '$tcarga', '$tdescarga', '$usuario', current_date())",$link);

		$mensaje="Los datos han sido guardados correctamente";

		$accion="modificar";

	}else if($accion=="modificar"){

	$sqlupd=mysql_query("UPDATE catalogocargadescarga SET tcarga='$tcarga', tdescarga='$tdescarga', usuario='$usuario', fecha=current_date() where unidad='$codigo'",$link);

		$mensaje="Los cambios han sido guardados correctamente";

	}else if($accion=="limpiar"){

		$accion=""; $unidad=""; $descripcion=""; $tcarga=""; $tdescarga=""; $usuario=$_POST['usuario'];

	}

	

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="../../javascript/ajax.js"></script>

<script src="select.js"></script>

<script type="text/javascript" src="js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="js/abrir-ventana-alertas.js"></script>

<link href="css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="css/style1.css" rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript">

// Funcion validar caja numero

var nav4 = window.Event ? true : false;

function Numeros(evt){ 

// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 

var key = nav4 ? evt.which : evt.keyCode; 

return (key <= 13 || (key >= 48 && key <= 57));

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



//Funcion par validar

function validar(){

	if(document.getElementById('codigo').value==""){

		alerta('Debe capturar Unidad','¡Atención!','codigo');	

	}else if (document.getElementById('tcarga').value==""){	

		alerta('Debe capturar Tiempo de carga','¡Atención!','tcarga');	

	}else if (document.getElementById('tdescarga').value==""){

		alerta('Debe capturar Tiempo de Descarga','¡Atención!','tdescarga');	

	}else{	

			if(document.getElementById('accion').value==""){

				document.getElementById('accion').value = "grabar";

				document.form1.submit();

			}else if(document.getElementById('accion').value="modificar"){

				document.form1.submit();

			}

	}

	

}

function limpiar(){

	document.getElementById('codigo').value="";

	document.getElementById('descripcion').value="";

	document.getElementById('tcarga').value="";

	document.getElementById('tdescarga').value="";

	document.getElementById('accion').value = "limpiar";

	document.form1.submit();

}

function obtener(id,descripcion){

	if(id!=""){

		//document.getElementById('codigo').value=id;

		ConsultarCargaDescarga(id,descripcion);

		

	}



}



//*********************************************//

function ConsultarCargaDescarga(valor,valor2,tipo){

	consulta("mostrarCargaDescarga","catalogotiempocargadescargaresult.php?unidad="+valor+"&descripcion="+valor2+"&sid="+Math.random());

}



function mostrarCargaDescarga(datos){

		var u	= document.all;

		var con  = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		if(con>0){

			u.codigo.value = datos.getElementsByTagName('codigo').item(0).firstChild.data;

			u.descripcion.value=datos.getElementsByTagName('descripcion').item(0).firstChild.data;

			u.tcarga.value = datos.getElementsByTagName('tcarga').item(0).firstChild.data;

			u.tdescarga.value = datos.getElementsByTagName('tdescarga').item(0).firstChild.data;

			u.accion.value = datos.getElementsByTagName('accion').item(0).firstChild.data;

		}else{

			u.codigo.value = datos.getElementsByTagName('codigo').item(0).firstChild.data;

			u.descripcion.value=datos.getElementsByTagName('descripcion').item(0).firstChild.data;

			u.tcarga.value ="";

			u.tdescarga.value="";

			u.accion.value ="";

			

		}		



		

}



</script>

<meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">

<link href="Tablas.css" rel="stylesheet" type="text/css">

<link href="FondoTabla.css" rel="stylesheet" type="text/css">

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

.Estilo3 {

	color: #FFFFFF;

	font-size: 14px;

	font-weight: bold;

}

-->

</style>

<link href="FondoTabla.css" rel="stylesheet" type="text/css">

<link href="Tablas.css" rel="stylesheet" type="text/css">

</head>

<body >

<form name="form1" method="post" action="">



<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">

 

 <tr>

      <td height="50"><br>

        

        <table width="350" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

          <tr>

            <td width="563" class="FondoTabla">CATÁLOGO TIEMPO DE CARGA Y DESCARGA</td>

          </tr>

          <tr>

            <td><br><table width="301" border="0" align="center" cellpadding="0" cellspacing="0">

                <tr>

                  <td width="67" class="Tablas">Unidad:</td>

                  <td width="234" class="Tablas"><input name="codigo" type="text" id="codigo" size="10" value="<?= $codigo ?>" style=" font:tahoma; font-size:9px; background:#FFFF99" readonly="">

                    &nbsp;&nbsp; <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscar.php?tipo=tipounidad', 600, 550, 'ventana', 'Busqueda')"> &nbsp;&nbsp;</td>

                </tr>

                

                <tr>

                  <td colspan="2" class="Tablas"><table width="300" border="0" cellspacing="0" cellpadding="0">

                    <tr>

                      <td class="Tablas">Descripci&oacute;n:</td>

                      <td class="Tablas"><input name="descripcion" type="text" id="descripcion" size="50" value="<?= $descripcion ?>" style="text-transform:uppercase;font:tahoma; font-size:9px" onKeyPress="return tabular(event,this)"></td>

                    </tr>

                    <tr>

                      <td width="67" class="Tablas">T. Carga: </td>

                      <td width="233" class="Tablas"><input name="tcarga" type="text" id="tcarga" style="text-transform:uppercase;font:tahoma; font-size:9px"  onKeyPress="return tabular(event,this)" value="<?= $tcarga ?>" size="8" maxlength="8"> 

                        T. descarga: 

                        <input name="tdescarga" type="text" id="tdescarga" style="text-transform:uppercase;font:tahoma; font-size:9px" onKeyDown="" value="<?= $tdescarga ?>" size="8" maxlength="8"></td>

                    </tr>

                    

                  </table></td>

                </tr>

                <tr>

                  <td height="32"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>"></td>

                  <td><table width="141" border="0" align="right">

                      <tr>

                        <td width="67"><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" onClick="validar();" style="cursor:pointer" ></td>

                        <td width="64"><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" title="Nuevo" onClick="limpiar();" style="cursor:pointer" ></td>

                      </tr>

                  </table></td>

                </tr>

            </table>

</td>

          </tr>

        </table>        

        </td>

    </tr>

  </table>   

</form>

</body>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'CATÁLOGO CARGA Y DESCARGA';

</script>

</html>

<?

if ($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";

	}

//	}

?>

