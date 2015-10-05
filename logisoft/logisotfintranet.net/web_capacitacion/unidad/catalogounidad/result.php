<?
	include('../Conectar.php');
	$link=Conectarse('pmm');
	$unidad=$_GET['unidad'];
	$descripcion=$_GET['descripcion'];
	$economico=$_GET['economico'];
	
	if($economico!=""){
		  $sql="select * from catalogounidad where neconomico='$economico'";
   if (mysql_num_rows(mysql_query($sql,$link))){
   		   $sql="SELECT CU.tipounidad, CT.descripcion, CU.neconomico, CU.ntarjeta,CU.cvolumen,CU.ckilos FROM 	
catalogounidad CU INNER JOIN catalogotipounidad CT ON CU.tipounidad=CT.codigo
WHERE CU.neconomico='$economico'";
			$res=mysql_query($sql,$link);
			$row=mysql_fetch_array($res);
			$unidad=$row[0];
			$descripcion=$row[1];
			$economico=$row[2];
			$circulacion=$row[3];
			$cvolumen=$row[4];
			$ckilos=$row[5];
		}
	else{
			//$unidad="008";
			//$descripcion="desc 008";
			//$economico="0001";
			$circulacion="";
			$cvolumen="";
			$ckilos="";
	
	}
}
?> 

        <table width="396" height="238" border="0" align="center">
          <tr> 
            <td width="84" height="27"><span class="Tablas"><strong>No. Economico:</strong></span></td>
            <td colspan="3"> <input name="economico" type="text" id="economico"  onBlur="feconomico();"  value="<?=$economico ?>" size="50"  ></td>
          </tr>
          <tr> 
            <td height="26"><strong>Descripci&oacute;n:</strong></td>
            <td colspan="3"><input name="descripcion" type="text" id="descripcion" style="background-color: #FFFF99;" value="<?=$descripcion ?>" size="50" readonly="readonly"></td>
          </tr>
          <tr> 
            <td height="42"><span class="Tablas"><strong>T. Unidad:</strong></span></td>
            <td colspan="3"><input name="unidad" type="text" id="unidad" style="background-color: #FFFF99;" value="<?= $unidad ?>" size="10" maxlength="4" readonly="readonly"> 
              <img name="image" type="image" onClick="javascript:popUp('buscarcatalogotipounidades.php')" src="../../img/Buscar_24.gif" alt="buscar" align="absbottom" width="24" height="23"></td>
          </tr>
          <tr> 
            <td height="42"><span class="Tablas"><strong>Tarj. Circulaci&oacute;n:</strong></span></td>
            <td colspan="3"><input name="circulacion" type="text" id="circulacion" value="<?=$circulacion ?>" size="50" onKeyPress="return tabular(event,this)" ></td>
          </tr>
          <tr> 
            <td height="42"><span class="Tablas"><strong>Cap. Volumen:</strong></span></td>
            <td width="90"><input name="cvolumen" type="text" id="cvolumen" onKeyPress="return tabular(event,this)" value="<?=$cvolumen ?>" size="18"   ></td>
            <td width="64"><strong class="Tablas">Cap. Kilos:&nbsp;</strong></td>
            <td width="140"><strong class="Tablas"> 
              <input name="ckilos" type="text" id="ckilos" onKeyPress="return tabular(event,this)"  onKeyDown="return Numeros(event)" value="<?=$ckilos ?>" size="18" >
              </strong></td>
          </tr>
          <tr> 
            <td height="34" colspan="4"><table width="190" border="0" align="right">
                <tr> 
                  <td width="90"><img name="image" type="image" title="Guardar" onClick="validar();" src="../../img/Boton_Guardar.gif" alt="guardar" width="90" height="24" /></td>
                  <td width="90"><img name="image" type="image" title="Nuevo" onClick="Limpiar();" src="../../img/Boton_Nuevo.gif" alt="nuevo" width="90" height="24" /></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td height="23"><span class="Tablas"> 
              <input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
              </span></td>
            <td colspan="3">&nbsp;</td>
          </tr>
        </table>
     
