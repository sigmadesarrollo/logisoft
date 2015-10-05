<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>

<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/ajax.js"></script>
<script language="javascript" src="../javascript/funciones_tablas.js"></script>

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<link href="../javascript/ajaxlist/ajaxlist_estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ClaseTabla.js"></script>
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
<script>
	var tabla1 = new ClaseTabla();
	var tabla2 = new ClaseTabla();
	var u = document.all;
	tabla1.setAttributes({
		nombre:"detalle_guias1",
		campos:[
			{nombre:"GUIA", medida:60, alineacion:"center" , datos:"id"},
			{nombre:"TIPOGUIA", medida:45, alineacion:"center", datos:"tipoguia"},
			{nombre:"FECHA", medida:39, alineacion:"center", datos:"fecha"},
			{nombre:"FLETE", medida:40, tipo:"moneda", alineacion:"right", datos:"tflete"},
			{nombre:"EXCEDENTE", medida:43, tipo:"moneda", alineacion:"right", datos:"texcedente"},
			{nombre:"EAD", medida:37, tipo:"moneda", alineacion:"right", datos:"tcostoead"},
			{nombre:"RECOL.", medida:46, tipo:"moneda", alineacion:"right", datos:"trecoleccion"},
			{nombre:"SEGURO", medida:43, tipo:"moneda", alineacion:"right", datos:"tseguro"},
			{nombre:"COMB", medida:38, tipo:"moneda", alineacion:"right", datos:"tcombustible"},
			{nombre:"OTROS", medida:34, tipo:"moneda", alineacion:"right", datos:"totros"},
			{nombre:"SUBTOTAL", medida:42, tipo:"moneda", alineacion:"right", datos:"subtotal"},
			{nombre:"IVA", medida:34, tipo:"moneda", alineacion:"right", datos:"tiva"},
			{nombre:"IVARET.", medida:37, tipo:"moneda", alineacion:"right", datos:"ivaretenido"},
			{nombre:"TOTAL", medida:37, tipo:"moneda", alineacion:"right", datos:"total"}
		],
		filasInicial:7,
		alto:100,
		seleccion:false,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	tabla2.setAttributes({
		nombre:"detalle_guias2",
		campos:[
			{nombre:"GUIA", medida:70, alineacion:"center" , datos:"id"},
			{nombre:"TIPOGUIA", medida:60, alineacion:"center", datos:"tipoguia"},
			{nombre:"CONCEPTO", medida:85, alineacion:"center", datos:"concepto"},
			{nombre:"SEGURO", medida:65, tipo:"moneda", alineacion:"center", datos:"tseguro"},
			{nombre:"FECHA", medida:53, alineacion:"center", datos:"fecha"},
			{nombre:"IMPORTE", medida:60, tipo:"moneda", alineacion:"right", datos:"subtotal"},
			{nombre:"IVA", medida:50, tipo:"moneda", alineacion:"right", datos:"tiva"},
			{nombre:"IVARET.", medida:50, tipo:"moneda", alineacion:"right", datos:"ivaretenido"},
			{nombre:"TOTAL", medida:60, tipo:"moneda", alineacion:"right", datos:"total"}
		],
		filasInicial:7,
		alto:100,
		seleccion:false,
		ordenable:false,
		nombrevar:"tabla2"
	});
	
	window.onload = function(){
		tabla1.create();
		tabla2.create();
		Consulta();
	}


	function Consulta(){
		consultaTexto("mostrar","Facturacion_imprimir_con.php?accion=1&valram="+Math.random());
	}
	function mostrar(datos){
	var objeto = eval("("+convertirValoresJson(datos)+")");
	u.fecha.value=objeto.general.fecha;
	u.folio.innerHTML=objeto.general.folio;
	u.estadofactura.innerHTML=objeto.general.facturaestado;
	u.idcliente.value =objeto.general.cliente;
	u.nombre.value	=objeto.general.nombrecliente;
	u.paterno.value	=objeto.general.apellidopaternocliente;
	u.materno.value=objeto.general.apellidomaternocliente;
	u.calle.value=objeto.general.calle;
	u.numero.value=objeto.general.numero;
	u.cp.value=objeto.general.codigopostal;
	u.colonia.value=objeto.general.colonia;
	u.ccalles.value=objeto.general.crucecalles;
	u.poblacion.value=objeto.general.poblacion;
	u.municipio.value=objeto.general.municipio;
	u.estado.value=objeto.general.estado;
	u.pais.value=objeto.general.pais;
	u.telefono.value=objeto.general.telefono;
	u.fax.value=objeto.general.fax;
	u.rfc.value=objeto.general.rfc;
	u.guiase.value=objeto.general.guiasempresa;
	u.guiasn.value=objeto.general.guiasnormales;
	u.tflete.value=objeto.general.flete;
	u.texcedente.value=objeto.general.excedente;
	u.tead.value=objeto.general.ead;
	u.trecoleccion.value=objeto.general.recoleccion;
	u.tseguro.value=objeto.general.seguro;
	u.tcombustible.value=objeto.general.combustible;
	u.totros.value=objeto.general.otros;
	u.tsubtotal.value=objeto.general.subtotal;
	u.tiva.value=objeto.general.iva;
	u.tivar.value=objeto.general.ivaretenido;
	u.ttotal.value=objeto.general.total;
	u.sseguro.value=objeto.general.sobseguro;
	/*objeto.sobexcedente*/
	u.ssubtotal.value=objeto.general.sobsubtotal;
	u.siva.value=objeto.general.sobiva;
	u.sivar.value=objeto.general.sobivaretenido;
	u.smonto.value=objeto.general.sobmontoafacturar;
	u.cantidad.value=objeto.general.otroscantidad;
	u.descripcion.value=objeto.general.otrosdescripcion;
	u.importe.value=objeto.general.otrosimporte;
	u.subtotalotros.value=objeto.general.otrossubtotal;
	u.ivaotros.value=objeto.general.otrosiva;
	u.ivarotros.value=objeto.general.otrosivaretenido;
	u.montootros.value=objeto.general.otrosmontofacturar;
	
	tabla1.setJsonData(objeto.detalle1);
	tabla2.setJsonData(objeto.detalle2);
	
	}
</script>

</head>

<body>
<table width="602" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="598"><span class="Estilo4">Datos Generales </span></td>
  </tr>
  <tr>
    <td><table width="598" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2" align="center">
		              <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="42"></td>
                  <td width="122" align="right">                    Folio </td>
                  <td width="89" align="right" id="folio" style="font:tahoma; font-size:15px; font-weight:bold"></td>
                  <td width="30" align="right">&nbsp;</td>
                  <td width="52" align="right">Fecha</td>
                  <td width="72" align="right"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:70px; border:none" value="" readonly=""/></td>
                  <td width="50" align="right">Estado</td>
                  <td width="136" align="right" id="estadofactura" style="font:tahoma; font-size:15px; font-weight:bold"></td>
                  <td width="1" align="right" ></td>
                </tr>
            </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><span class="Estilo4">Datos Facturaci&oacute;n de Cliente </span></td>
      </tr>
      <tr>
        <td colspan="49"><table width="419" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="39" height="24"><label>Cliente</label></td>
            <td width="26"><input name="idcliente" type="text"  class="Tablas" id="nick" style="width:25px; border:none" value="" /></td>
            <td width="57">&nbsp;</td>
            <td width="119">&nbsp;</td>
            <td width="21">&nbsp;</td>
            <td width="64">&nbsp;</td>
            <td width="21">&nbsp;</td>
            <td width="72">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2">Nombre&nbsp;
          &nbsp;
          <input name="nombre" type="text" class="Tablas" id="nombre" style="width:125px;border:none" value="<?=$nombre ?>" readonly=""/>
          Apellido Pat
          <input name="paterno" type="text" class="Tablas" id="paterno" style="width:125px;border:none" value="<?=$paterno ?>" readonly=""/>
          Apellido Mat
          <input name="materno" type="text" class="Tablas" id="materno" style="width:125px;border:none" value="<?=$materno ?>" readonly=""/></td>
      </tr>
      <tr>
        <td colspan="3"><table width="589" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="49"> Calle</td>
              <td width="253" id="celdacalle"><input name="calle" type="text" class="Tablas" id="calle" style="width:250px;border:none" readonly=""/></td>
              <td width="34">&nbsp;</td>
              <td width="47">N&uacute;mero</td>
              <td width="93"><input name="numero" type="text" class="Tablas" id="numero" style="width:80px;border:none" value="<?=$numero ?>" readonly=""/></td>
              <td width="20">CP</td>
              <td width="93"><input name="cp" type="text" class="Tablas" id="cp" style="width:80px;border:none" value="<?=$cp ?>" readonly=""/></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="49"><table width="577" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><label>Colonia</label></td>
            <td colspan="3"><input name="colonia" type="text" class="Tablas" id="colonia" style="width:220px;border:none" value="<?=$colonia ?>" readonly=""/>
                    <label></label></td>
            <td>Cruce de Calles </td>
            <td colspan="3"><input name="ccalles" type="text" class="Tablas" id="ccalles" style="width:220px;border:none" value="<?=$ccalles ?>" readonly=""/></td>
          </tr>
          <tr>
            <td><label>Poblacion</label></td>
            <td><input name="poblacion" type="text" class="Tablas" id="poblacion" style="width:80px;border:none" value="<?=$poblacion ?>" readonly=""/></td>
            <td><label>Municipio/ Delegacion</label></td>
            <td><input name="municipio" type="text" class="Tablas" id="municipio" style="width:80px;border:none" value="<?=$municipio ?>" readonly=""/></td>
            <td width="79"><label>Estado </label></td>
            <td width="93"><input name="estado" type="text" class="Tablas" id="estado" style="width:80px;border:none" value="<?=$estado ?>" readonly=""/></td>
            <td width="28"><label>Pa&iacute;s</label></td>
            <td width="101"><input name="pais" type="text" class="Tablas" id="pais" style="width:80px;border:none" value="<?=$pais ?>" readonly=""/></td>
          </tr>
          <tr>
            <td width="48"><label>T&eacute;lefono</label></td>
            <td width="82"><input name="telefono" type="text" class="Tablas" id="telefono" style="width:80px;border:none" value="<?=$telefono ?>" readonly=""/></td>
            <td width="60"><label>Fax</label></td>
            <td width="86"><input name="fax" type="text" class="Tablas" id="fax" style="width:80px;border:none" value="<?=$fax ?>" readonly=""/></td>
            <td>            Rfc</td>
            <td colspan="3"><input name="rfc" type="text" class="Tablas" id="rfc" style="width:95px;border:none" value="<?=$rfc ?>" readonly=""/></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4">&nbsp;</td>
          </tr>
        </table>
              <label></label>
              <label></label></td>
      </tr>
      <tr>
        <td><span class="Estilo4">Facturac&iacute;on Gu&iacute;as </span></td>
      </tr>
      <tr>
        <td colspan="2"><table width="584" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <th width="584" scope="row">&nbsp;</th>
          </tr>
        </table>
              <table border="0" cellpadding="0" cellspacing="0" id="detalle_guias1">
            </table></td>
      </tr>
      <tr>
        <td colspan="49"><table width="595" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="40">&nbsp;</td>
            <td width="73">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2"><label>Gu&iacute;as Empresariales</label></td>
            <td width="139"><input name="guiase" type="text" class="Tablas" id="guiase" style="width:100px; text-align:right;border:none" value="<?=$guiase ?>" readonly=""/></td>
            <td width="58"><label>Flete</label></td>
            <td width="112"><input name="tflete" type="text" class="Tablas" style="width:100px; text-align:right;border:none" value="<?=$flete ?>" readonly=""/>            </td>
            <td width="73"><label>Combustible</label></td>
            <td width="108"><div align="left">
              <input name="tcombustible" type="text" class="Tablas" style="width:100px; text-align:right;border:none" value="<?=$combustible ?>" readonly=""/>
            </div></td>
          </tr>
          <tr>
            <td colspan="2"><label>Gu&iacute;as Normales </label></td>
            <td><input name="guiasn" type="text" class="Tablas" id="guiasn" style="width:100px; text-align:right;border:none" value="<?=$guiasn ?>" readonly=""/></td>
            <td><label>Excedente</label></td>
            <td><div align="left">
              <input name="texcedente" type="text" class="Tablas" style="width:100px; text-align:right;border:none" value="<?=$excedente ?>" readonly=""/>
            </div></td>
            <td><label>Otros</label></td>
            <td><div align="left">
              <input name="totros" type="text" class="Tablas" style="width:100px; text-align:right;border:none" value="<?=$otros ?>" readonly=""/>
            </div></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
            <td><label>EAD</label></td>
            <td><div align="left">
              <input name="tead" type="text" class="Tablas" style="width:100px; text-align:right;border:none" value="<?=$eao ?>" readonly=""/>
            </div></td>
            <td><label>Subtotal</label></td>
            <td><div align="left">
              <input name="tsubtotal" type="text" class="Tablas" id="subtotal" style="width:100px; text-align:right;border:none" value="<?=$subtotal ?>" readonly=""/>
            </div></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
            <td><label>Recolecci&oacute;n</label></td>
            <td><div align="left">
              <input name="trecoleccion" type="text" class="Tablas" style="width:100px; text-align:right;border:none" value="<?=$recoleccion ?>" readonly=""/>
            </div></td>
            <td><label>IVA</label></td>
            <td><input name="tiva" type="text" class="Tablas" style="width:100px; text-align:right;border:none" value="<?=$iva ?>" readonly=""/>            </td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
            <td><label>Seguro</label></td>
            <td><div align="left">
              <input name="tseguro" type="text" class="Tablas" style="width:100px; text-align:right;border:none" value="<?=$seguro ?>" readonly=""/>
            </div></td>
            <td><label>IVA Retenido</label></td>
            <td><div align="left">
              <input name="tivar" type="text" class="Tablas" style="width:100px; text-align:right;border:none" value="<?=$ivar ?>" readonly=""/>
            </div>
                    <div align="left"></div></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td><label>Total</label></td>
            <td><div align="left">
              <input name="ttotal" type="text" class="Tablas" style="width:100px; text-align:right;border:none" value="<?=$total ?>" readonly=""/>
            </div></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><span class="Estilo4">Facturaci&oacute;n de Sobrepeso y Valores Declarados </span></td>
      </tr>
      <tr>
        <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="detalle_guias2">
        </table></td>
      </tr>
      <tr>
        <td colspan="49"><table width="598" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td width="8">&nbsp;</td>
          </tr>
          <tr>
            <td width="41">&nbsp;</td>
            <td width="56">Seguro</td>
            <td width="118"><input name="sseguro" type="text" class="Tablas" style="width:100px; text-align:right;border:none" value="<?=$seguro ?>" readonly=""/></td>
            <td width="34">&nbsp;</td>
            <td width="59"><label>Subtotal</label></td>
            <td width="108"><input name="ssubtotal" type="text" class="Tablas" style="width:80px; text-align:right;border:none" value="<?=$subtotal ?>" readonly=""/></td>
            <td><label>IVA Retenido</label></td>
            <td colspan="2"><div align="left">
              <input name="sivar" type="text" class="Tablas" style="width:80px; text-align:right;border:none"  readonly=""/>
            </div></td>
          </tr>
          <tr>
            <td colspan="4">&nbsp;</td>
            <td><label>IVA</label></td>
            <td><input name="siva" type="text" class="Tablas" style="width:80px; text-align:right;border:none" value="<?=$iva2 ?>" readonly=""/></td>
            <td width="94"><label>Monto a Facturar</label></td>
            <td width="87"><div align="left">
              <input name="smonto" type="text" class="Tablas" style="width:80px; text-align:right;border:none" value="<?=$monto ?>" readonly=""/>
            </div></td>
          </tr>
          <tr>
            <td colspan="9">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>Facturaci&oacute;n Otros </td>
      </tr>
      <tr>
        <td colspan="49"></td>
      </tr>
      <tr>
        <td colspan="49"></td>
      </tr>
      <tr>
        <td width="616" valign="top"><label>Cantidad
          <input name="cantidad" type="text" style="width:80px; text-align:right; text-align:right;border:none"  />
          Descripcion
            <textarea name="descripcion" cols="30" style="font-family: tahoma; font-size: 9px; font-style: normal; font-weight: bold; text-transform:uppercase;border:none" ></textarea>
            </label></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><label>Importe
          <input name="importe" type="text" style="width:80px; text-align:right; text-align:right;border:none" />
          </label>
              <label>Subtotal
                <input name="subtotalotros" type="text" class="Tablas" style="width:60px; text-align:right;border:none" value="<?=$subtotal3 ?>" readonly=""/>
              </label>
              <label>IVA
                <input name="ivaotros" type="text" class="Tablas" style="width:60px; text-align:right;border:none" value="<?=$iva3 ?>" readonly=""/>
              </label>
              <label>IVA Ret</label>
              <input name="ivarotros" type="text" class="Tablas" style="width:60px; text-align:right;border:none" value="<?=$ivar3 ?>" readonly=""/>
              <label>Monto a Facturar
                <input name="montootros" type="text" class="Tablas" style="width:60px; text-align:right;border:none" value="<?=$monto2 ?>" readonly=""/>
            </label></td>
      </tr>
      <tr>
        <td id="textosustitucion" align="center" style="font-family: tahoma;font-size: 16px;font-style: normal;font-weight: bold;color:#FF0000;"></td>
      </tr>
      <tr>
        <td align="center" id="bonotesAccion">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
