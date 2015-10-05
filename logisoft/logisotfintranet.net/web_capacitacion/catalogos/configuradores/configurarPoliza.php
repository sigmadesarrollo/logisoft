<?
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "SELECT * FROM configuradorpoliza";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script src="../../javascript/jquery-1.4.2.min.js"></script>
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
</head>
<script>
	$(document).ready(function(){
		$('#botonGuardar').click(function(){
			var datos = $("#form1").serialize();
			$.ajax({
				type: "POST",
				url: "configurarPoliza_con.php",
				data: datos,
				success: respuestaGuardar
			});
		})
	});
	
	function respuestaGuardar(datos){
		if(datos.indexOf("guardado")>-1){
			info("Datos guardados correctamente","Atencion");
		}else{
			alerta3("Error al guardar","Atencion");
		}
	}
</script>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
    <table width="703" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
            <tr>
              <td width="699" class="FondoTabla">CONFIGURADOR DE POLIZA</td>
            </tr>
            <tr>
          <td height="147">
          <form name="form1" id="form1" action="">
          	<table width="699" cellspacing="0px" cellpadding="0px" border="0px">
            	<tr>
            	  <td colspan="4" align="center" class="sobreFila">Segmentos Contables Generales</td>
           	    </tr>
                <tr>
            	  <td>Cuentas de Mayor</td>
            	  <td></td>
            	  <td>Seg. Cont. Pos-Sucursal</td>
            	  <td></td>
          	  </tr>
            	<tr>
                	<td width="162"> Inicial de Flete</td>
                    <td width="182"><input name="m_flete" type="text" value="<?=$f->m_flete?>" /></td>
                    <td width="234">Seg. Cont. de Flete</td>
                    <td width="121"><input name="p_flete" type="text" value="<?=$f->p_flete?>" /></td>
                </tr>
            	<tr>
            	  <td>Inicial EAD</td>
            	  <td><input name="m_ead" type="text" value="<?=$f->m_ead?>" /></td>
            	  <td>Seg. Cont. de EAD</td>
            	  <td><input name="p_ead" type="text" value="<?=$f->p_ead?>" /></td>
          	  </tr>
            	<tr>
            	  <td>Inicial Recoleccion</td>
            	  <td><input name="m_rec" type="text" value="<?=$f->m_rec?>" /></td>
            	  <td>Seg. Cont. de Recoleccion</td>
            	  <td><input name="p_rec" type="text" value="<?=$f->p_rec?>" /></td>
          	  </tr>
            	<tr>
            	  <td>Inicial Seguro</td>
            	  <td><input name="m_seguro" type="text" value="<?=$f->m_seguro?>" /></td>
            	  <td>Seg. Cont. de Seguro</td>
            	  <td><input name="p_seguro" type="text" value="<?=$f->p_seguro?>" /></td>
          	  </tr>
            	<tr>
            	  <td>Inicial Descuento</td>
            	  <td><input name="m_descuento" type="text" value="<?=$f->m_descuento?>" /></td>
            	  <td>Seg. Cont. de Descuento</td>
            	  <td><input name="p_descuento" type="text" value="<?=$f->p_descuento?>" /></td>
          	  </tr>
              <tr>
            	  <td>Inicial Cargo Adicional</td>
            	  <td><input name="m_cargoadicional" type="text" value="<?=$f->m_cargoadicional?>" /></td>
            	  <td>Seg. Cont. de CargoAdicional</td>
            	  <td><input name="p_cargoadicional" type="text" value="<?=$f->p_cargoadicional?>" /></td>
          	  </tr>
              <tr>
            	  <td>Inicial Otros</td>
            	  <td><input name="m_otros" type="text" value="<?=$f->m_otros?>" /></td>
            	  <td>Seg. Cont. de Otros</td>
            	  <td><input name="p_otros" type="text" value="<?=$f->p_otros?>" /></td>
          	  </tr>
              <tr>
            	  <td> Inicial de Iva</td>
            	  <td><input name="m_iva" type="text" value="<?=$f->m_iva?>" /></td>
            	  <td>Seg. Cont. de Iva</td>
            	  <td><input name="p_iva" type="text" value="<?=$f->p_iva?>" /></td>
          	  </tr>
              <tr>
            	  <td> Incial de Iva Retenido</td>
            	  <td><input name="m_ivaretenido" type="text" value="<?=$f->m_ivaretenido?>" /></td>
            	  <td>Seg. Cont. de Iva Retenido</td>
            	  <td><input name="p_ivaretenido" type="text" value="<?=$f->p_ivaretenido?>" /></td>
          	  </tr>
            <tr>
            	  <td>Inicial de Total</td>
            	  <td><input name="m_total" type="text" value="<?=$f->m_total?>" /></td>
            	  <td>Seg. Cont. Total</td>
            	  <td><input name="p_total" type="text" value="<?=$f->p_total?>" /></td>
          	  </tr>
              <tr>
            	  <td> Inicial de Devolucion S/V</td>
            	  <td><input name="m_devolucion" type="text" value="<?=$f->m_devolucion?>" /></td>
            	  <td>Seg. Cont. de Devolucion</td>
            	  <td><input name="p_devolucion" type="text" value="<?=$f->p_devolucion?>" /></td>
          	  </tr>
              <tr>
            	  <td colspan="4" style="text-align:center">
                  	<div class="ebtn_guardar" id="botonGuardar"></div>
                  </td>
           	  </tr>
              <tr>
            	  <td>&nbsp;</td>
            	  <td></td>
            	  <td></td>
            	  <td></td>
          	  </tr>
              </table>
              </form>
              <form name="form2" action="">
              <table width="699" cellspacing="0px" cellpadding="0px" border="0px">
            	<tr>
            	  <td colspan="4" align="center" class="sobreFila">Segmentos Contables por sucursal (GER)</td>
           	    </tr>
            	<tr>
            	  <td width="162">Seleccione Sucursal</td>
            	  <td width="182"></td>
            	  <td width="234">Seg Cont. del Gerente</td>
            	  <td width="121"></td>
          	  </tr>
            	<tr>
            	  <td>&nbsp;</td>
            	  <td></td>
            	  <td></td>
            	  <td></td>
          	  </tr>
            </table>
            </form>
          </td>
            </tr>
      </table></td>
    </tr>
  </table>
</body>
</html>