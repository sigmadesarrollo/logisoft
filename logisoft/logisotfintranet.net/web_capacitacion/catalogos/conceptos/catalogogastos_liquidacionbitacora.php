<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if(isset($_SESSION['gvalidar'] )!=100){ echo "<script language='javascript' type='text/javascript'>						document.location.href='../../../index.php';</script>";

	}else{*/

		require_once('../../Conectar.php');	

		$link=Conectarse('webpmm');		

		$usuario	=$_SESSION[NOMBREUSUARIO];

		$accion		=$_POST['accion']; 

		$codigo		=$_POST['codigo']; 

		$descripcion=$_POST['descripcion']; 



	if($accion == ""){

		$row	=folio('catalogogastos_liquidacionbitacora','webpmm');

		$codigo	=$row[0];

	}

		

		if($accion == "grabar"){

			$sqlins	=mysql_query("INSERT INTO catalogogastos_liquidacionbitacora (id, descripcion, usuario, fecha)

VALUES(null, UCASE('$descripcion'), '$usuario', current_timestamp())",$link); 		

			$mensaje="Los datos han sido guardados correctamente";

			$codigo	=mysql_insert_id();

			$accion	="modificar";

			

		}else if($accion == "modificar"){

			$sqlupd	=mysql_query("UPDATE catalogogastos_liquidacionbitacora SET descripcion=UCASE('$descripcion'), usuario='$usuario', fecha=current_timestamp() WHERE id='$codigo'",$link);

			$mensaje='Los cambios han sido guardados correctamente';

			$accion	="modificar";

			

		}else if($accion=="limpiar"){

			$codigo		="";

			$accion		="";

			$descripcion="";

	

			$usuario	=$_SESSION[NOMBREUSUARIO];

			$row=folio('catalogogastos_liquidacionbitacora','webpmm');

			$codigo=$row[0];

		}



?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="../../javascript/shortcut.js"></script>

<script language="JavaScript" type="text/javascript">



	function Limpiar(){

		document.getElementById('descripcion').value="";

		document.getElementById('accion').value = "limpiar";

		document.form1.submit();

	}



	function validar(){

		 if(document.getElementById('descripcion').value==""){

				alerta('Debe capturar Descripci�n', '�Atenci�n!','descripcion');

		}else{

				if(document.getElementById('accion').value == ""){

					document.getElementById('accion').value = "grabar";

					document.form1.submit();

					

				}else if(document.getElementById('accion').value=="modificar"){

					document.form1.submit();

				}

		}

	}



	function obtener(id,descripcion){

		document.getElementById('codigo').value		=id;

		document.getElementById('descripcion').value=descripcion;	

		document.getElementById('descripcion').focus();

		document.getElementById('accion').value		="modificar";

	}



	function trim(cadena,caja)

	{

		for(i=0;i<cadena.length;)

		{

			if(cadena.charAt(i)==" ")

				cadena=cadena.substring(i+1, cadena.length);

			else

				break;

		}

	

		for(i=cadena.length-1; i>=0; i=cadena.length-1)

		{

			if(cadena.charAt(i)==" ")

				cadena=cadena.substring(0,i);

			else

				break;

		}

		

		document.getElementById(caja).value=cadena;

	}

	

	function tabular(e,obj) { 

	  tecla=(document.all) ? e.keyCode : e.which; 

	  if(tecla!=13) return; 

	  frm=obj.form; 

	  for(i=0;i<frm.elements.length;i++) 

		if(frm.elements[i]==obj) { 

		  if (i==frm.elements.length-1) i=-1; 

		  break } 

	  frm.elements[i+1].focus(); 

	  return false; 

	  

	}

	

	function foco(nombrecaja){

			if(nombrecaja=="codigo"){

				document.getElementById('oculto').value="1";

			}

		}

	shortcut.add("Ctrl+b",function() {

		if(document.form1.oculto.value=="1"){

	abrirVentanaFija('CatalogoConceptos_Buscar.php', 550, 450, 'ventana', 'Busqueda');

		}

	});

</script>

<script src="../../javascript/ajax.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<link href="../sucursal/puntovta.css" rel="stylesheet" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Cat&aacute;logo Servicio</title>

<link href="../sucursal/FondoTabla.css" rel="stylesheet" type="text/css">

<link href="../sucursal/Tablas.css" rel="stylesheet" type="text/css">

</head>



<body onLoad="document.form1.descripcion.focus()">

<form id="form1" name="form1" method="post" action="">

  <table width="100%" border="0">

    <tr>

      <td><br></td>

    </tr>

    <tr>

      <td><table width="310" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

        <tr>

          <td class="FondoTabla">CAT&Aacute;LOGO DE GASTOS DE LIQ. BIT&Aacute;CORA</td>

        </tr>

        <tr>

          <td><br><table width="309" border="0" align="center">

            

            <tr>

              <td width="73" class="Tablas">C&oacute;digo:</td>

              <td width="159">

                <input name="codigo" class="Tablas" type="text" id="codigo" size="10" value="<?=$codigo ?>" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" style="background:#FFFF99; font-size:9px; font:tahoma" readonly=""  />

                &nbsp;<img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('catalogogastos_liquidacionbitacora_buscar.php', 550, 450, 'ventana', 'Busqueda')"></td>

              <td width="137">&nbsp;</td>

              </tr>

            <tr>

              <td class="Tablas">Descripci&oacute;n:</td>

              <td colspan="2"><input name="descripcion" type="text" id="descripcion" onKeyPress="if(event.keyCode==13){validar();}" class="Tablas" onBlur="trim(document.getElementById('descripcion').value,'descripcion')" size="50" value="<?= $descripcion ?>" style=" font:tahoma; font-size:9px; text-transform:uppercase" /></td>

            </tr>

            <tr>

              <td height="32"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>" />

                <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" /></td>

              <td colspan="2"><table width="141" border="0" align="right">

                  <tr>

                    <td><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" onClick="validar();" style="cursor:pointer" ></td>

                    <td><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" title="Nuevo" onClick="confirmar('Perdera la informaci�n capturada �Desea continuar?', '', 'Limpiar();', '')" style="cursor:pointer" ></td>

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

</html>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'CATALOGO GASTOS LIQUIDACION BITACORA';

</script>

<? 

if ($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operaci�n realizada correctamente');</script>";	

	}

//} ?>