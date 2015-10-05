<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	include('../Conectar.php');
	$link=Conectarse('webpmm');	
	$folio=$_GET['folio'];	
	$sql=@mysql_query("SELECT fechaevaluacion, estado, guiaempresarial, recoleccion, destino, sucursaldestino, bolsaempaque, cantidadbolsa, totalbolsaempaque, emplaye, totalemplaye FROM evaluacionmercancia WHERE folio='$folio'",$link);
	$row=@mysql_fetch_array($sql);
	$fechaevaluacion=$row[0]; $estado=$row[0]; $guiaempresarial=$row[0]; $recoleccion=$row[0]; $destino=$row[0]; $sucursaldestino=$row[0]; $bolsaempaque=$row[0]; $cantidadbolsa=$row[0]; $totalbolsaempaque=$row[0]; $emplaye=$row[0]; $totalemplaye=$row[0];	
	$sqldetalle=@mysql_query("SELECT cantidad, descripcion, contenido, peso, largo, ancho, alto, volumen FROM evaluacionmercanciadetalle WHERE evaluacion='$folio'",$link);	
?>

  <table width="612" height="247" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <th width="612" height="64" scope="row"><table width="608" height="75" border="0" cellspacing="0" class="Tablas">
          <tr>
            <td height="19" colspan="2" class="Tablas"><label> Folio:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input name="folio" type="text" class="Tablas" id="folio" value="<?=$folio ?>" size="10" style="text-align:right; background:#FFFF99" readonly="" >
                  <img src="../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscarEvaluacion.php?tipo=evaluacion', 550, 450, 'ventana', 'Busqueda')"></label></td>
            <td class="Tablas">Fecha: </td>
            <td colspan="2" class="Tablas"><input name="fechaevaluacion" type="text" class="Tablas" id="fechaevaluacion" style="background:#FFFF99" value="<?=$fechaevaluacion ?>" size="15" readonly=""  >
                <label></label></td>
            <td colspan="2" class="Tablas">Estado:&nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="Estado" type="text" class="Tablas" id="Estado" style="background:#FFFF99" value="<?=$Estado ?>" size="15" readonly="">
              </label></td>
          </tr>
          <tr>
            <td width="78" height="19" class="Tablas"><label>No.Recoleccion:</label></td>
            <td width="79" class="Tablas"><input name="NRecoleccion" type="text" class="Tablas" id="NRecoleccion" value="<?=$NRecoleccion ?>" size="10" onkeydown="return tabular(event,this)" onkeypress="return Numeros(event)" onkeyup="return habilitar(event,this.name)"></td>
            <td width="59" class="Tablas"><label>Guia Emp.:</label></td>
            <td width="52" class="Tablas"><input name="NGuias" type="text" class="Tablas" id="NGuias" value="<?=$NGuias ?>" size="10" onkeydown="return tabular(event,this)" onkeypress="return Numeros(event)" onkeyup="return habilitar(event,this.name)" /></td>
            <td width="69" class="Tablas">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Destino:</td>
            <td colspan="2" class="Tablas"><input name="country" type="text" class="Tablas" id="country" style="font-size:9px; text-transform:uppercase" onchange="DestinoId();" onkeyup="ajax_showOptions(this,'getCountriesByLetters',event)" value="" size="35" /></td>
          </tr>
          <tr>
            <td height="19" colspan="3" class="Tablas"><input type="hidden" id="country_hidden" name="country_ID"></td>
            <td colspan="2" class="Tablas">&nbsp;</td>
            <td width="78" class="Tablas">Suc Destino:</td>
            <td width="179" class="Tablas"><div id="txtDestino">
                <input name="SucDestino" type="text" class="Tablas" id="SucDestino" style="background:#FFFF99" value="<?=$SucDestino ?>" size="20" readonly="">
            </div></td>
          </tr>
      </table></th>
    </tr>
    <tr>
      <th scope="row"><table width="560" border="0" cellpadding="0">
          <tr>
            <th scope="row"><div id="txtHint">
              <table width="579" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="5" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
                    <td width="29"  background="../img/borde1_2.jpg" class="style5" align="center">&nbsp;</td>
                    <td width="31"  background="../img/borde1_2.jpg" class="style5" align="center">CANT</td>
                    <td width="115" background="../img/borde1_2.jpg" class="style5" align="center">DESCRIPCION</td>
                    <td width="124" background="../img/borde1_2.jpg" class="style5" align="center">CONTENIDO</td>
                    <td width="59" background="../img/borde1_2.jpg" class="style5" align="center">PESO KG </td>
                    <td width="48" background="../img/borde1_2.jpg" class="style5" align="center">LARGO</td>
                    <td width="47" background="../img/borde1_2.jpg" class="style5" align="center">ANCHO</td>
                    <td width="30" background="../img/borde1_2.jpg" class="style5" align="center">ALTO</td>
                    <td width="59" align="center" background="../img/borde1_2.jpg" class="style5 Estilo2">P. VOLU </td>
                    <td width="14" background="../img/borde1_2.jpg" class="style5"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
                    <td width="18"  background="../img/borde1_3.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
                  </tr>
                  <tr>
                    <td colspan="12" align="right"><div id="detalle" name="detalle" style=" height:150px; overflow:auto" align="left">
                        <? $line = 0; ?>
                        <table width="570" border="0" cellspacing="0" cellpadding="0">
                          <?
			$line=@mysql_num_rows($sqldetalle);
			while($res=@mysql_fetch_array($sqldetalle)){?>
                          <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >
                             <td height="16" width="17" ><input name="id" type="hidden" id="id" value="<?=$row[id] ?>" /></td>
                    <td width="45" align="center" class="style31"  >&nbsp;</td>
                    <td width="32" align="center" class="style31"  ><input name="cantidad" type="text" class="style2" id="cantidad" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" value="<?=$res[cantidad]?>" size="8" /></td>
                    <td width="95" align="center" class="style31"><input name="descripcion" type="text" class="style2" id="descripcion" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$res[descripcion] ?>" readonly="" size="20" /></td>
                    <td width="128" align="center" class="style31"><input name="contenido" type="text" class="style2" id="contenido" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$res[contenido] ?>" readonly="" size="20" /></td>
                    <td width="119" class="style31" align="center"><input name="peso" type="text" class="style2" id="peso" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$res[peso] ?>" readonly="" size="8" /></td>
                    <td width="43" class="style31" align="center"><input name="largo" type="text" readonly="" class="style2" id="largo" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$res[largo] ?>" size="5" /></td>
                    <td width="29" class="style31" align="center"><input name="ancho" type="text" readonly="" class="style2" id="ancho" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$res[ancho] ?>" size="5" />
                    </td>
                    <td width="22" align="center" class="style31" ><input name="alto" type="text" class="style2" id="alto" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$res[alto] ?>" size="5" /></td>
                    <td width="40" align="center" class="style31"><input name="volumen" type="text" class="style2" id="volumen" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$res[volumen] ?>" size="8" /></td>
                          </tr>
                          <?
		$line ++ ; }			
	?>
                        </table>
                    </div></td>
                  </tr>
                </table>
            </div></th>
          </tr>
          <tr>
            <th scope="row"><table width="20" border="0" align="right" cellpadding="0" cellspacing="0">
                <tr>
                  <td><img src="../img/Boton_Agregari.gif" alt="Agregar" width="70" height="20" style="cursor:pointer" onClick="abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?usuario=<?=$usuario ?>&fechahora=<?=$fechahora ?>', 350, 350, 'ventana', 'Datos Evaluaci&oacute;n')" /></td>
                </tr>
            </table></th>
          </tr>
      </table></th>
    </tr>
    <tr>
      <th height="87" scope="row"><table width="600" height="73" border="0" cellpadding="0" cellspacing="0" class="Tablas">
          <tr>
            <td height="12" colspan="4" class="FondoTabla" scope="row">Servicios </td>
          </tr>
          <tr>
            <td height="20" colspan="3" class="Tablas" scope="row"><label></label>
                <input name="BolsaEmpaque" type="checkbox" class="Txt" id="BolsaEmpaque" value="Bolsa de Empaque" onClick="ObtenerPrecioBolsa()">
                <span class="Estilo3">Bolsa de Empaque</span>
                <input name="CantidadEmpaque" type="text"  class="Tablas" id="CantidadEmpaque" value="<?=$CantidadEmpaque ?>" size="5" onKeyPress="ObtenerTotalBolsa(event,this.value)" >
                <input name="TotalEmpaque" type="text" class="Tablas" style="background:#FFFF99" id="TotalEmpaque" value="<?=$TotalEmpaque ?>" size="10" readonly="readonly"  >
            </td>
            <td width="333">
                <table width="50" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><img src="../img/Boton_Cancelar.gif" alt="Guardar" width="70" height="20" onClick="confirmar('¿Realmente desea cancelar la Orden de Embarque?', '', 'window.parent.Cancelar();', 'parent.VentanaModal.cerrar();')" style="cursor:pointer"  /></td>
                    <td><img src="../img/Boton_Nuevo.gif" style="cursor:pointer" alt="Nuevo" width="70" height="20" onClick="confirmar('Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'window.parent.limpiar();', 'parent.VentanaModal.cerrar();')"  /></td>
                  </tr>
                </table>
              </td>
          </tr>
          <tr>
            <td height="20" colspan="3" class="Tablas" scope="row"><input name="Emplaye" type="checkbox" class="Txt" id="Emplaye" value="checkbox" onClick="ObtenerPrecioEmplaye();setTimeout('CalcularEmplaye()',1500);" >
              Emplaye &nbsp;&nbsp;&nbsp;&nbsp;
              <input name="TotalEmplaye" type="text" class="Tablas" id="TotalEmplaye" style="background:#FFFF99" size="10" readonly="readonly">
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="36" height="12" scope="row"><div id="txtBolsa">
              <input name="costobolsa" type="hidden" id="costobolsa" value="<?=$costobolsa ?>">
            </div>
                <div id="txtEmplaye">
                  <input name="costoemplaye" type="hidden" id="costoemplaye" value="<?=$costoemplaye ?>">
                  <input name="costoemplayeextra" type="hidden" id="costoemplayeextra" value="<?=$costoemplayeextra ?>">
                  <input name="totalpeso" type="hidden" id="totalpeso" value="<?=$totalpeso ?>">
                  <input name="totalvol" type="hidden" id="totalvol" value="<?=$totalvol ?>">
              </div></td>
            <td width="254" scope="row"><input name="fechahora" type="hidden" id="fechahora" value="<?=$fechahora; ?>">
                <input name="user" type="hidden" id="user" value="<?=$usuario ?>">
                <input name="msg" type="hidden" value="<?=$msg ?>"></td>
            <td width="43" scope="row"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>"></td>
            <td></td>
          </tr>
      </table></th>
    </tr>
  </table>

