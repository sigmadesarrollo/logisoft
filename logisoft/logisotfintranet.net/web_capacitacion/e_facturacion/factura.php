<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script>
	
	var u = document.all;
	var tabla1 = new ClaseTabla();
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"CANTIDAD", medida:90, alineacion:"left", datos:"cantidad"},			
			{nombre:"CONTENIDO", medida:390, alineacion:"left", datos:"contenido"},			
			{nombre:"IMPORTE", medida:100, alineacion:"right", tipo:"moneda", datos:"importe"}
		],
		filasInicial:15,
		alto:170,
		seleccion:true,
		ordenable:false,
		//eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		//obtenerDatos();
	}
	
	function obtenerDatos(){
		consultaTexto("mostrarDatos","factura_con.php?accion=1&cliente=1");
	}
	
	function mostrarDatos(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.nombre.value	= obj.principal.nombre;
			u.nombre.value	= obj.principal.domicilio;
			u.ciudad.value	= obj.principal.ciudad;
			u.rfc.value		= obj.principal.rfc;
			tabla1.setJsonData(obj.detalle);
		}else{
			
		}
	}
	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="estilo.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {color: #FFFFFF}
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <p>&nbsp;</p>
  <table width="601" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5"><table width="599" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="28">&nbsp;</td>
              <td width="28">&nbsp;</td>
              <td width="28">&nbsp;</td>
              <td width="333">&nbsp;</td>
              <td width="80" align="right">Fecha:</td>
              <td width="102"><input name="fecha" type="text" id="fecha" style="width:80px;text-align:center" readonly="" value="<?=date('d/m/Y'); ?>"/></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="5"><table width="100%" border="1" bordercolor="#006192"  cellspacing="0" cellpadding="0">
            <tr>
              <td class="estilo_relleno">Emisor</td>
            </tr>
            <tr>
              <td bordercolor="#006192"><table width="599" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="93">Nombre:</td>
                  <td colspan="2" id="celda_des_calle"><span >
                    <input name="nombre" type="text"  id="nombre" style="width:300px" value="PAQUETERIA Y MENSAJERIA EN MOVIMIENTO S.A. DE C.V" readonly=""/>
                  </span></td>
                  <td width="168" id="celda_des_calle">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">Domicilio::</td>
                  <td colspan="3" id="celda_des_calle"><span >
                    <input name="dir1" type="text"  id="dir1" style="width:468px" value="OFNA. MATRIZ FCO. SERRANO No 2316-306 TEL:(669) 985 48 11 C.P. 82000 COL. CENTRO" readonly=""/>
                  </span></td>
                </tr>
                <tr>
                  <td valign="top">Ciudad,Estado:</td>
                  <td width="280"><span >
                    <input name="dir2" type="text"  id="dir2" style="width:250px" value="MAZATLAN, SINALOA" readonly=""/>
                  </span></td>
                  <td width="45">R.F.C.:</td>
                  <td><input name="rfc" type="text"  id="rfc" style="width:127px" value="PMM-900725-698" readonly=""/></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="5">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="5"><table width="100%" border="1" bordercolor="#006192"  cellspacing="0" cellpadding="0">
            <tr>
              <td class="estilo_relleno">Receptor</td>
            </tr>
            <tr>
              <td bordercolor="#006192"><table width="599" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="93">Nombre:</td>
                  <td colspan="2" id="celda_des_calle"><span >
                    <input name="nombre" type="text"  id="nombre" style="width:300px" readonly=""/>
                  </span></td>
                  <td width="168" id="celda_des_calle">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">Domicilio::</td>
                  <td colspan="3" id="celda_des_calle"><span >
                    <input name="dir1" type="text"  id="dir1" style="width:468px" readonly=""/>
                  </span></td>
                </tr>
                <tr>
                  <td valign="top">Ciudad,Estado:</td>
                  <td width="280"><span >
                    <input name="dir2" type="text"  id="dir2" style="width:250px" readonly=""/>
                  </span></td>
                  <td width="45">R.F.C.:</td>
                  <td><input name="rfc" type="text"  id="rfc" style="width:127px" readonly=""/></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td width="54">&nbsp;</td>
          <td width="136">&nbsp;</td>
          <td width="136">&nbsp;</td>
          <td width="151">&nbsp;</td>
          <td width="123">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5"><table width="599" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table id="detalle" width="599" border="0" cellspacing="0" cellpadding="0">  
					</table>
				</td>
              </tr>
              <tr>
                <td><table width="599" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="312">Importe con letra:<br />
                          <? echo "Novecientos Cincuenta"; ?></td>
                      <td width="287"><table width="236" border="1" align="right" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="97">SubTotal:</td>
                            <td width="179">1000</td>
                          </tr>
                          <tr>
                            <td>Iva:</td>
                            <td>160</td>
                          </tr>
                          <tr>
                            <td>TOTAL:</td>
                            <td>1600</td>
                          </tr>
                      </table></td>
                    </tr>
                </table></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="5">Cadena Original: </td>
          </tr>
        <tr>
          <td colspan="5"><table width="100%" border="1" cellspacing="0" cellpadding="0">
            <tr>
              <td bordercolor="#CCCCCC" style="font-size:10px">||A|1|2005-09-02T16:30:00|1|PMM900725698|Paqueteria y Mensajeria en Movimiento, S.A. de   C.V.|Ofna. Matriz Fco. Serrano|2316|306|Col. Centro|Mazatl&aacute;n|Sinaloa|82000|Pino Suarez|23|Centro|Monterrey|Monterrey|Nuevo   L&eacute;on|M&eacute;xico|95460|CAUR390312S87|Rosa Mar&iacute;a Calder&oacute;n   Uriegas|Topochico|52|Jardines del Valle|Monterrey|Monterrey|Nuevo   Le&oacute;n|M&eacute;xico|95465|10|Caja|Vasos decorados|20|200|1|pieza|Charola   met&aacute;lica|150|150|IVA|52.5||</td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td colspan="5">Sello Digital:</td>
        </tr>
        <tr>
          <td colspan="5"><table width="100%" border="1" cellspacing="0" cellpadding="0">
            <tr>
              <td bordercolor="#CCCCCC">&nbsp;</td>
            </tr>
          </table></td>
          </tr>
      </table></td>
    </tr>
  </table>
  <p>&nbsp;	</p>
  </form>
</body>
</html>
