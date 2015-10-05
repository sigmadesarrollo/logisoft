<? session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/

	

	require_once('../../Conectar.php');

	$link=Conectarse('webpmm');

	$get = mysql_query('select count(*) from catalogocolonia');

	$total = mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }

	$pp = 20;

?>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<script src="select.js"></script>

<script>



	function consultaColonia(e,obj){

		tecla=(document.all) ? e.keyCode : e.which;

		if(tecla==13 && obj != ""){				

			if(obj.length >= 4){

				if(obj!=""){

					ColoniaConsulta('colonia',obj);

				}else{

					document.form1.submit();

				}

			}else{

				alerta("El criterio de busqueda debe ser mayor a cuatro caracteres","¡Atención!","colonia");

			}

		}else if(tecla==13 && obj == ""){

			document.form1.submit();

		}

	}



	function foco(){

		document.all.colonia.focus();

	}

</script>

<link href="Tablas.css" rel="stylesheet" type="text/css" />

<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<form id="form1" name="form1" method="post" action="" onsubmit="return false">

  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td class="FondoTabla">ID</td>

      <td width="60%" class="FondoTabla">Descripci&oacute;n</td>

      <td width="31%" class="FondoTabla">Población</td>

    </tr>

    <tr>

      <td width="9%" class="FondoTabla">&nbsp;</td>

      <td colspan="2" class="FondoTabla"><p class="Tablas">

          <input name="colonia" type="text" class="Tablas" id="colonia"  onkeypress="consultaColonia(event,this.value)" style="text-transform:uppercase" value="<?=$colonia ?>" size="50" />

        <label></label>

      </p></td>

    </tr>

    <tr>

      <td colspan="3"><div id="txtColonia" style="width:100%; height:auto; overflow: scroll;">

        <table width="100%" border="0" align="center">

            <?	

				$get = mysql_query('SELECT cc.id, cc.descripcion AS colonia, 

				cp.descripcion AS poblacion FROM catalogocolonia cc

				INNER JOIN catalogopoblacion cp ON cc.poblacion = cp.id limit '.$st.','.$pp,$link);		

				while($row=@mysql_fetch_array($get)){

			?>

            <tr >

              <td width="39" class="Tablas" ><span onclick="window.parent.obtener('<?=$row['id'];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">

                <?=$row[0];?>

              </span></td>

              <td width="296" class="Tablas"><input name="descripcion" type="text" class="Tablas" value="<?=$row[1]; ?>" readonly="true" style="border:none; cursor:pointer; width:220px" /></td>

              <td width="143" class="Tablas"><input name="poblacion" type="text" class="Tablas" value="<?=$row[2]; ?>" readonly="true" style="border:none; cursor:pointer; width:120px" /></td>

              <td width="0"></td>

            </tr>

            <? } ?>

          </table>

      </div></td>

    </tr>

    <tr>

      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarColonia.php?st='); ?></font></td>

    </tr>

  </table>

</form>

<script>foco();</script>