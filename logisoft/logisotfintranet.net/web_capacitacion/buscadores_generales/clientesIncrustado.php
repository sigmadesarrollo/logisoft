<div id="<?=$nombreBuscador?>_fondo" style="width:100%; height:100%; top:0px; left:0px; position:fixed; background:#000; z-index:998; display:none; filter:alpha(opacity=50);">
</div>
<div id="<?=$nombreBuscador?>" style="position:absolute; display:none; background-image:url(../img/fondo_nuevoBuscar.jpg); left: 76px; top: 130px; width: 691px; height: 354px; z-index:999;">
<form name="form1">
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<td height="5px"></td>
    </tr>	
</table>
<table width="600px"  align="center" cellpadding="0" cellspacing="0" border="0" >
    <tr> 
      <td align="center"></td>
    </tr>
    <tr> 
        <td colspan="7" class="FondoTabla" style="font-weight:bold; color:#FFF; background:url(../img/fondo_nuevoBuscar.jpg) top;">
    <table width="671">
        	<tr>
            	<td width="609">
			        Agregar Clientes
                </td>
                <td width="50">
                	<a href="#" class="FondoTabla" onclick="<?=$funcionOcultar?>()" style="color:#FFF">Cerrar</a>
                </td>
            </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td style="background-color:#FFF; padding-left:5px;">
      <table width="672" align="center" cellpadding="0" cellspacing="0" border="0" style="width:500px;">
          <tr> 
            <td width="98" class="Tablas">Nick:</td>
            <td colspan="6" class="Tablas"><input class="Tablas" name="<?=$nombreBuscador?>_nick" type="text" id="<?=$nombreBuscador?>_nick" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_nick').value,'<?=$nombreBuscador?>_nick');" size="40" style="font:tahoma;font-size:9px; text-transform:uppercase" /> 
            </td>
          </tr>
          <tr>
            <td colspan="7" class="Tablas"><table width="200" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="Tablas"><input id="<?=$nombreBuscador?>_rdmoral" selected="true" name="<?=$nombreBuscador?>_rdmoral" type="radio" value="SI" onClick="<?=$nombreBuscador?>_habilitar();" style="width:12px" />
                  Moral</td>
                <td class="Tablas"><input id="<?=$nombreBuscador?>_rdmoral" name="<?=$nombreBuscador?>_rdmoral" type="radio" value="NO" onClick="<?=$nombreBuscador?>_habilitar();"  style="width:12px" />
                  Fisica</td>
              </tr>
            </table></td>
          </tr>
          <tr> 
            <td class="Tablas">Nombre:</td>
            <td colspan="6" class="Tablas"><input class="Tablas" name="<?=$nombreBuscador?>_nombre" type="text" id="<?=$nombreBuscador?>_nombre" size="64" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_nombre').value,'<?=$nombreBuscador?>_nombre');" value="<?=$nombre; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress=""/></td>
          </tr>
          <tr> 
            <td height="22" class="Tablas">Ap. Paterno:</td>
            <td width="240"  class="Tablas" style="width:240px"><input class="Tablas" name="<?=$nombreBuscador?>_paterno" type="text" id="<?=$nombreBuscador?>_paterno"  onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_paterno').value,'<?=$nombreBuscador?>_paterno');"  maxlength="100" value="<?=$paterno; ?>" <? if($rdmoral=="SI"||$rdmoral==""){echo 'disabled'; } ?> style="font:tahoma;font-size:9px; background:#F1F7FA; text-transform:uppercase;width:190px" onKeyPress="" /></td>
            <td width="305"  class="Tablas" style="width:140px">Ap. Materno:</td>
            <td colspan="3" class="Tablas"><input name="<?=$nombreBuscador?>_materno" class="Tablas" type="text" id="<?=$nombreBuscador?>_materno" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_materno').value,'<?=$nombreBuscador?>_materno');"  value="<?=$materno; ?>" <? if($rdmoral=="SI"||$rdmoral==""){echo 'disabled'; } ?> style="font:tahoma;font-size:9px; background:#F1F7FA; text-transform:uppercase;width:190px" onKeyPress=""/></td>
          </tr>
          <tr> 
            <td height="18" class="Tablas">R.F.C.:</td>
            <td class="Tablas"><input name="<?=$nombreBuscador?>_rfc" type="text" class="Tablas" id="<?=$nombreBuscador?>_rfc" maxlength="13" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_rfc').value,'<?=$nombreBuscador?>_rfc'); if(this.value!=''){obtenerRFC(this.value);}" onKeyPress="if(event.keyCode==13 || event.keyCode==9){obtenerRFC(this.value);}" value="<?=$rfc; ?>" style="text-transform:uppercase;width:190px"/></td>
            <td class="Tablas">Email:</td>
            <td colspan="4" class="Tablas"><input name="<?=$nombreBuscador?>_email" class="Tablas" type="text" id="<?=$nombreBuscador?>_email" style="text-transform:lowercase; font:tahoma; font-size:9px;width:190px" onKeyPress=";" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_email').value,'<?=$nombreBuscador?>_email');" value="<?=$email; ?>" /></td>
          </tr>
          <tr> 
            <td class="Tablas">Celular:</td>
            <td class="Tablas"><input name="<?=$nombreBuscador?>_celular" type="text" class="Tablas" id="<?=$nombreBuscador?>_celular" size="20" maxlength="70" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_celular').value,'<?=$nombreBuscador?>_celular');" onKeyPress="" value="<?=$celular; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase;width:190px"/></td>
            <td class="Tablas">Sitio Web: </td>
            <td colspan="4" class="Tablas"><input name="<?=$nombreBuscador?>_web" class="Tablas" type="text" id="<?=$nombreBuscador?>_web" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_web').value,'<?=$nombreBuscador?>_web');" onKeyPress="" value="<?=$web; ?>" style="font:tahoma;font-size:9px;width:190px"/></td>
          </tr>
          
          <tr> 
            <td colspan="7">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="7" align="center" class="Tablas"><table width="666" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td>&nbsp;</td>
                <td width="211">&nbsp;</td>
                <td width="104">&nbsp;</td>
                <td width="231">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td width="27">&nbsp;</td>
              </tr>
              <tr>
                <td width="91" class="Tablas">Calle:</td>
                <td colspan="4"><input name="<?=$nombreBuscador?>_calle" type="text" id="<?=$nombreBuscador?>_calle" class="Tablas" size="38" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_calle').value,'<?=$nombreBuscador?>_calle');" value="<?=$calle; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress=""/>
                  Numero:          <input name="<?=$nombreBuscador?>_numero" type="text" id="<?=$nombreBuscador?>_numero" class="Tablas" size="38" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_numero').value,'<?=$nombreBuscador?>_numero');" value="<?=$calle; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress=""/>        </td>
              </tr>
              <tr>
                <td class="Tablas">Cruce Calles:</td>
                <td colspan="4"><input name="<?=$nombreBuscador?>_entrecalles" class="Tablas" type="text" id="<?=$nombreBuscador?>_entrecalles" size="61" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_entrecalles').value,'<?=$nombreBuscador?>_entrecalles');" value="<?= $entrecalles; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress=""/></td>
              </tr>
              <tr>
                <td class="Tablas">C.P.:</td>
                <td class="Tablas"><input name="<?=$nombreBuscador?>_cp" type="text" id="<?=$nombreBuscador?>_cp" class="Tablas" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_cp').value,'<?=$nombreBuscador?>_cp'); " onKeyPress="return solonumeros(event)" onKeyDown="<?=$nombreBuscador?>_CodigoPostal(event,this.value); ;" onKeyUp="return <?=$nombreBuscador?>_validaCP(event,this.name)"  value="<?= $cp; ?>" size="10" maxlength="5" style="font:tahoma;font-size:9px; text-transform:uppercase" />
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="Tablas">Colonia:</td>
                <td id="<?=$nombreBuscador?>_celcolonia"><input name="<?=$nombreBuscador?>_colonia" type="text" value="<?=$colonia ?>" class="Tablas" id="<?=$nombreBuscador?>_colonia" style=" width:183px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event,'../../buscadores_generales/ajax-list-colonias.php'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=''){setTimeout('obtenerColoniaValida()',1000);}" /></td>
                <td><img src="../img/Buscar_24.gif" style="cursor:pointer" onclick="<?=$funcionOcultar?>_mostrar()"></td>
                </tr>
              <tr>
                <td class="Tablas">Poblaci&oacute;n:</td>
                <td><input name="<?=$nombreBuscador?>_poblacion" type="text" class="Tablas" id="<?=$nombreBuscador?>_poblacion" style="width:120px; background:#F1F7FA;  text-transform:uppercase" readonly=""  value="<?= $poblacion; ?>" /></td>
                <td>Mun./Del.:&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td><input name="<?=$nombreBuscador?>_municipio" type="text" class="Tablas" id="<?=$nombreBuscador?>_municipio" size="20"  style="width:120px;background:#F1F7FA; text-transform:uppercase" readonly="" value="<?=$municipio; ?>" /></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="Tablas">Estado:</td>
                <td><input name="<?=$nombreBuscador?>_estado" type="text" class="Tablas" id="<?=$nombreBuscador?>_estado" size="20" value="<?=$estado; ?>" style="width:120px;background:#F1F7FA; text-transform:uppercase" readonly="" /></td>
                <td>Pa&iacute;s:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td><input name="<?=$nombreBuscador?>_pais" type="text" id="<?=$nombreBuscador?>_pais" class="Tablas" size="20" value="<?=$pais; ?>" style="width:120px; text-transform:uppercase; background-color:#F1F7FA" readonly=""/></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="Tablas">Tel&eacute;fono:</td>
                <td><input name="<?=$nombreBuscador?>_telefono" type="text" class="Tablas" id="<?=$nombreBuscador?>_telefono" size="20" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_telefono').value,'<?=$nombreBuscador?>_telefono');" value="<?= $telefono; ?>" style="width:120px;" onKeyPress="" /></td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp; </td>
                <td>&nbsp;</td>
              </tr>
            </table>
            	
            </td>
          </tr>
          <tr> 
            <td colspan="7" class="Tablas"><table width="100%" border="0" cellpadding="1" cellspacing="0">
                
                <tr>
                  <td width="71" height="15" class="Tablas">&nbsp;</td>
                  <td width="630">&nbsp;</td>
                  <td width="285">&nbsp;</td>
                </tr>
                <tr> 
                  <td height="15" colspan="3" class="Tablas" align="center">
                  <table width="651" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="text-align:center">
                        <img src="<?=$raiz?>img/Boton_Guardar.gif" style="cursor:pointer" onclick="<?=$nombreBuscador?>_registrarCliente()" />
                      </td>
                      </tr>
                  </table></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
  </table>
  </form>
</div>
<div id="<?=$nombreBuscador?>_buscarColonia" style="position:absolute; display:none; background-image:url(../img/fondo_nuevoBuscar.jpg); left: 199px; top: 189px; width: 492px; height: 295px; z-index:1000;">
	<table width="472" height="270" class="FondoTabla" align="center">
    	<tr style="color:#FFF">
        	<td height="16">
            	<table width="465" border="0" cellpadding="0" cellspacing="0">
                	<tr>
		            	<td width="362">Buscar Colonias</td>
                        <td width="53"><a href="#" class="FondoTabla" onclick="<?=$funcionOcultar?>_colonias()" style="color:#FFF">Cerrar</a></td>
                    </tr>
                </table>
          </td>
        </tr>
        <tr style="background-color:#FFF">
        	<td height="230">
            	<table width="466">
                	<tr style="color:#333">
                    	<td width="48" class="Tablas">Colonia</td>
                        <td width="106"><input type="text" name="<?=$nombreBuscador?>_buscaColonia" 
                        id="<?=$nombreBuscador?>_buscaColonia" style="width:100px" /></td>
                        <td width="43" class="Tablas">Ciudad</td>
                        <td width="98"><input type="text" name="<?=$nombreBuscador?>_buscaCiudad" 
                        id="<?=$nombreBuscador?>_buscaCiudad" style="width:100px" /></td>
                        <td width="99">
                        <img src="<?=$raiz?>img/Boton_Generar.gif" style="cursor:pointer" onClick="<?=$nombreBuscador?>_buscarColonias()" align="absbottom">
                        </td>
                    </tr>
                	<tr>
                	  <td height="218" colspan="5" style="background-color:#000">
                      	<table border="0" cellpadding="0" cellspacing="0" id="<?=$nombreBuscador?>_coloniasEncontradas">
					      </table>
                      </td>
               	  </tr>
                	<tr>
                	  <td colspan="5">
                      	<div id="<?=$nombreBuscador?>_paginado_colonias" align="center" style="display:none; width:363px; height:15px;">
                            <table width="162" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                            <td>
                          <img src="<?=$raiz?>img/first.gif" name="d_primero" width="16" height="16" style="cursor:pointer"  onclick="<?=$nombreBuscador?>_pagprimero()" /> 
                          <img src="<?=$raiz?>img/previous.gif" name="d_atrasdes" width="16" height="16" style="cursor:pointer" onclick="<?=$nombreBuscador?>_paganterior()" /> 
                          </td>
                          <td>
                          <div style="position:relative; width:50px; height:15px; text-align:center; color:#333" id="<?=$nombreBuscador?>_pagina1"></div>
                          </td>
                          <td>
                          <img src="<?=$raiz?>img/next.gif" name="d_sigdes" width="16" height="16" style="cursor:pointer" onclick="<?=$nombreBuscador?>_pagsiguiente()" /> 
                          <img src="<?=$raiz?>img/last.gif" name="d_ultimo" width="16" height="16" style="cursor:pointer" onclick="<?=$nombreBuscador?>_pagultimo()" />
                          </td>
                          </tr>
                          </table>
                        </div>
                      </td>
              	  </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<script>
	function <?=$nombreBuscador?>_habilitar(){
		if(document.all.<?=$nombreBuscador?>_rdmoral[1].checked == true){
			document.getElementById('<?=$nombreBuscador?>_paterno').disabled=false
			document.getElementById('<?=$nombreBuscador?>_materno').disabled=false
			document.getElementById('<?=$nombreBuscador?>_paterno').style.backgroundColor='';
			document.getElementById('<?=$nombreBuscador?>_materno').style.backgroundColor='';
		}else if(document.all.<?=$nombreBuscador?>_rdmoral[0].checked== true){
			document.getElementById('<?=$nombreBuscador?>_paterno').disabled=true
			document.getElementById('<?=$nombreBuscador?>_paterno').value="";
			document.getElementById('<?=$nombreBuscador?>_materno').disabled=true
			document.getElementById('<?=$nombreBuscador?>_materno').value="";
			document.getElementById('<?=$nombreBuscador?>_paterno').style.backgroundColor='#F1F7FA';
			document.getElementById('<?=$nombreBuscador?>_materno').style.backgroundColor='#F1F7FA';
		}
	}
	
	function <?=$funcionMostrar?>(){
		<?=$nombreBuscador?>_limpiar();
		document.getElementById('<?=$nombreBuscador?>_fondo').style.display='';
		document.getElementById('<?=$nombreBuscador?>').style.display='';
		document.getElementById('<?=$nombreBuscador?>_nick').focus();
	}
	function <?=$funcionOcultar?>(){
		document.getElementById('<?=$nombreBuscador?>_fondo').style.display='none';
		document.getElementById('<?=$nombreBuscador?>').style.display='none';
	}
	function <?=$nombreBuscador?>_trim(cadena,caja){
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
	
	function <?=$nombreBuscador?>_ObtenerColoniaClic(codigopostal,colonia,poblacion,municipio,estado,pais){
		document.getElementById('<?=$nombreBuscador?>_celcolonia').innerHTML = Input;
		document.getElementById('<?=$nombreBuscador?>_cp').value = codigopostal;
		document.getElementById('<?=$nombreBuscador?>_colonia').value = colonia;
		document.getElementById('<?=$nombreBuscador?>_poblacion').value = poblacion;
		document.getElementById('<?=$nombreBuscador?>_municipio').value = municipio;
		document.getElementById('<?=$nombreBuscador?>_estado').value = estado;
		document.getElementById('<?=$nombreBuscador?>_pais').value = pais;
	}
	
	function <?=$nombreBuscador?>_validaCP(e,obj){
		tecla=(document.all) ? e.keyCode : e.which;
		if((tecla==8 || tecla==46)&& document.getElementById(obj).value==""){
		document.getElementById('<?=$nombreBuscador?>_colonia').value=""; document.getElementById('<?=$nombreBuscador?>_poblacion').value="";
		document.getElementById('<?=$nombreBuscador?>_municipio').value=""; document.getElementById('<?=$nombreBuscador?>_estado').value="";
		document.getElementById('<?=$nombreBuscador?>_pais').value="";
		}	
	}
	
	function <?=$nombreBuscador?>_CodigoPostal(e,cp){
		tecla=(document.all) ? e.keyCode : e.which;
		if((tecla==13 || tecla==9) && cp!=""){
			
			consulta("<?=$nombreBuscador?>_mostrarPostal","<?=$raiz?>buscadores_generales/consultasColonias.php?accion=1&cp="+cp+"&sid="+Math.random());
		}
	}
	
	function <?=$nombreBuscador?>_mostrarPostal(datos){
		var Input = '<input name="<?=$nombreBuscador?>_colonia" type="text" class="Tablas" id="<?=$nombreBuscador?>_colonia" style=" width:183px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,\'getCountriesByLetters\',event,\'../../buscadores_generales/ajax-list-colonias.php\'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=\'\'){setTimeout(\'obtenerColoniaValida()\',1000);}" />';

		var combo1 = "<select name='<?=$nombreBuscador?>_colonia' id='<?=$nombreBuscador?>_colonia' class='Tablas' style='width:183px;font:tahoma;font-size:9px' onKeyPress='return tabular(event,this)'>";
		
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		document.getElementById('<?=$nombreBuscador?>_colonia').value=""; 
		document.getElementById('<?=$nombreBuscador?>_poblacion').value="";
		document.getElementById('<?=$nombreBuscador?>_municipio').value="";
		document.getElementById('<?=$nombreBuscador?>_estado').value="";
		document.getElementById('<?=$nombreBuscador?>_pais').value="";	
		
		if(con>0){
			if(datos.getElementsByTagName('total').item(0).firstChild.data>1){
				document.getElementById('<?=$nombreBuscador?>_celcolonia').innerHTML = combo1;
				var combo = document.all.<?=$nombreBuscador?>_colonia;
				combo.options.length = null;
				uOpcion = document.createElement("OPTION");
				uOpcion.value=0;
				uOpcion.text="..:: Selecciona ::..";
				combo.add(uOpcion);
				var total =datos.getElementsByTagName('total').item(0).firstChild.data;
				for(i=0;i<total;i++){	
					uOpcion = document.createElement("OPTION");
					uOpcion.value=datos.getElementsByTagName('colonia').item(i).firstChild.data;
					uOpcion.text=datos.getElementsByTagName('colonia').item(i).firstChild.data;
					combo.add(uOpcion);
				}
				document.getElementById('<?=$nombreBuscador?>_cp').value=datos.getElementsByTagName('cp').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_colonia').value=datos.getElementsByTagName('colonia').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_poblacion').value=datos.getElementsByTagName('poblacion').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_municipio').value=datos.getElementsByTagName('municipio').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_estado').value=datos.getElementsByTagName('estado').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_pais').value=datos.getElementsByTagName('pais').item(0).firstChild.data;
				setTimeout("document.getElementById('<?=$nombreBuscador?>_telefono').focus()",500);
			}else{	
				document.getElementById('<?=$nombreBuscador?>_celcolonia').innerHTML = Input;
				document.getElementById('<?=$nombreBuscador?>_cp').value=datos.getElementsByTagName('cp').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_colonia').value=datos.getElementsByTagName('colonia').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_poblacion').value=datos.getElementsByTagName('poblacion').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_municipio').value=datos.getElementsByTagName('municipio').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_estado').value=datos.getElementsByTagName('estado').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_pais').value=datos.getElementsByTagName('pais').item(0).firstChild.data;
				setTimeout("document.getElementById('<?=$nombreBuscador?>_telefono').focus()",500);
			}
		}else{			
			alerta2("El Código Postal no existe",'¡Atención!','<?=$nombreBuscador?>_cp');
			document.getElementById('<?=$nombreBuscador?>_celcolonia').innerHTML = Input;
			document.getElementById('<?=$nombreBuscador?>_cp').focus();
		}
	}
	
	var <?=$nombreBuscador?>_tabla1 = new ClaseTabla();
	
	<?=$nombreBuscador?>_tabla1.setAttributes({
		nombre:"<?=$nombreBuscador?>_coloniasEncontradas",
		campos:[
			{nombre:"N_Colonia", medida:200, alineacion:"left", datos:"colonia"},
			{nombre:"N_Poblacion", medida:200, alineacion:"left", datos:"poblacion"},
			{nombre:"N_Cp", medida:4, alineacion:"left", tipo:"oculto", datos:"codigopostal"},
			{nombre:"N_Municipio", medida:4, alineacion:"left", tipo:"oculto", datos:"municipio"},
			{nombre:"N_Estado", medida:4, alineacion:"right", tipo:"oculto", datos:"estado"},
			{nombre:"N_Pais", medida:4, alineacion:"right", tipo:"oculto", datos:"pais"},
			{nombre:"idPais", medida:4, alineacion:"right", tipo:"oculto", datos:"idcol"},
			{nombre:"idPais", medida:4, alineacion:"right", tipo:"oculto", datos:"idpob"},
			{nombre:"idPais", medida:4, alineacion:"right", tipo:"oculto", datos:"idmun"},
			{nombre:"idPais", medida:4, alineacion:"right", tipo:"oculto", datos:"idest"},
			{nombre:"idPais", medida:4, alineacion:"right", tipo:"oculto", datos:"idpais"}
		],
		filasInicial:14,
		alto:200,
		seleccion:true,
		eventoDblClickFila:"<?=$nombreBuscador?>_ponerColonia()",
		ordenable:false,
		nombrevar:"<?=$nombreBuscador?>_tabla1"
	});
	var <?=$nombreBuscador?>_buscPag = "";
	<?=$nombreBuscador?>_tabla1.create();
	var <?=$nombreBuscador?>_buscPagIndice = 0;
	
	function <?=$funcionOcultar?>_colonias(){
		document.getElementById('<?=$nombreBuscador?>_buscarColonia').style.display='none';
	}
	
	function <?=$funcionOcultar?>_mostrar(){
		document.getElementById('<?=$nombreBuscador?>_buscarColonia').style.display='';
	}
	
	function <?=$nombreBuscador?>_ponerColonia(){
		var Input = '<input name="<?=$nombreBuscador?>_colonia" type="text" class="Tablas" id="<?=$nombreBuscador?>_colonia" style=" width:183px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,\'getCountriesByLetters\',event,\'../../buscadores_generales/ajax-list-colonias.php\'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=\'\'){setTimeout(\'obtenerColoniaValida()\',1000);}" />';
		document.getElementById('<?=$nombreBuscador?>_celcolonia').innerHTML = Input;
		
		var fila = <?=$nombreBuscador?>_tabla1.getSelectedRow();
		document.getElementById('<?=$nombreBuscador?>_cp').value=fila.codigopostal; 
		document.getElementById('<?=$nombreBuscador?>_colonia').value=fila.colonia; 
		document.getElementById('<?=$nombreBuscador?>_poblacion').value=fila.poblacion;
		document.getElementById('<?=$nombreBuscador?>_municipio').value=fila.municipio;
		document.getElementById('<?=$nombreBuscador?>_estado').value=fila.estado;
		document.getElementById('<?=$nombreBuscador?>_pais').value=fila.pais;	
		<?=$funcionOcultar?>_colonias();
	}
	
	function <?=$nombreBuscador?>_buscarColonias(){
		var colonia = document.getElementById('<?=$nombreBuscador?>_buscaColonia').value;
		var ciudad = document.getElementById('<?=$nombreBuscador?>_buscaCiudad').value;
		consultaTexto("<?=$nombreBuscador?>_respuestaColonia","<?=$raiz?>buscadores_generales/buscarColoniasCiudad.php?colonia="+colonia+"&ciudad="+ciudad);
	}
	
	function <?=$nombreBuscador?>_respuestaColonia(datos){
		<?=$nombreBuscador?>_buscPagIndice = 0;
		document.getElementById('<?=$nombreBuscador?>_paginado_colonias').style.display='none';
		try{
			<?=$nombreBuscador?>_buscPag = eval(datos);
			<?=$nombreBuscador?>_tabla1.setJsonData(<?=$nombreBuscador?>_buscPag[0].datos);
			if(<?=$nombreBuscador?>_buscPag.length>1)
				document.getElementById('<?=$nombreBuscador?>_paginado_colonias').style.display='';
		}catch(e){
			alert(datos);
		}
		<?=$nombreBuscador?>_ponerPagina()
	}
	
	function <?=$nombreBuscador?>_pagprimero(){
		<?=$nombreBuscador?>_buscPagIndice = 0;
		<?=$nombreBuscador?>_tabla1.setJsonData(<?=$nombreBuscador?>_buscPag[0].datos);
		<?=$nombreBuscador?>_ponerPagina();
	}
	
	function <?=$nombreBuscador?>_pagultimo(){
		<?=$nombreBuscador?>_buscPagIndice = <?=$nombreBuscador?>_buscPag.length-1;
		<?=$nombreBuscador?>_tabla1.setJsonData(<?=$nombreBuscador?>_buscPag[<?=$nombreBuscador?>_buscPag.length-1].datos);
		<?=$nombreBuscador?>_ponerPagina();
	}
	
	function <?=$nombreBuscador?>_pagsiguiente(){
		if(<?=$nombreBuscador?>_buscPagIndice+1 < <?=$nombreBuscador?>_buscPag.length){
			<?=$nombreBuscador?>_buscPagIndice++;
			<?=$nombreBuscador?>_tabla1.setJsonData(<?=$nombreBuscador?>_buscPag[<?=$nombreBuscador?>_buscPagIndice].datos);
		}else
			alerta3("Has llegado al final","¡ATENCION!");
		<?=$nombreBuscador?>_ponerPagina()
	}
	
	function <?=$nombreBuscador?>_paganterior(){
		if(<?=$nombreBuscador?>_buscPagIndice-1<0)
			alerta3("Has llegado al inicio","¡ATENCION!");
		else{
			<?=$nombreBuscador?>_buscPagIndice--;
			<?=$nombreBuscador?>_tabla1.setJsonData(<?=$nombreBuscador?>_buscPag[<?=$nombreBuscador?>_buscPag.length-1].datos);
		}
		<?=$nombreBuscador?>_ponerPagina()
	}
	
	function <?=$nombreBuscador?>_ponerPagina(){
		document.getElementById('<?=$nombreBuscador?>_pagina1').innerHTML=(<?=$nombreBuscador?>_buscPagIndice+1)+"-"+(<?=$nombreBuscador?>_buscPag.length);
	}
	
	function obtenerRFC(rfc){
		consultaTexto("mostrarRfc","<?=$raiz?>catalogos/cliente/consultaCredito_con.php?accion=3&rfc="+rfc+"&val="+Math.random());
	}

	function mostrarRfc(datos){	
		if(datos.indexOf("no encontro")<0){	
			var obj = eval("("+convertirValoresJson(datos)+")");
			if(document.all.<?=$nombreBuscador?>_rdmoral[0].checked==true && obj.rfc.replace("&#32;","")!=""){
				alerta3('El R.F.C.:'+u.<?=$nombreBuscador?>_rfc.value.toUpperCase()
				+' esta asignado al cliente '+obj.cliente.toUpperCase(), '¡ATENCION!');
				return false;
			}
		}else{
			u.<?=$nombreBuscador?>_email.focus();
		}
	}
	
	function <?=$nombreBuscador?>_registrarCliente(){
		if (document.all.<?=$nombreBuscador?>_nick.value.length == 0){
			alerta('Debe capturar por lo menos un Nick', '¡Atención!','<?=$nombreBuscador?>_nick');
			return false;
		}
		if (document.getElementById('<?=$nombreBuscador?>_nombre').value==""){
				alerta('Debe capturar Nombre', '¡Atención!','<?=$nombreBuscador?>_nombre');
				return false;
		}
		if(document.all.<?=$nombreBuscador?>_rdmoral[1].checked){		
			if(document.getElementById('<?=$nombreBuscador?>_paterno').value==""){
					alerta('Debe capturar Apellido Paterno', '¡Atención!','<?=$nombreBuscador?>_paterno');
					return false;				
			}
			if(document.getElementById('<?=$nombreBuscador?>_materno').value==""){
					alerta('Debe capturar Apellido Materno', '¡Atención!','<?=$nombreBuscador?>_materno');
					return false;				
			}
		}
		
		/*else{
			if(document.getElementById('<?=$nombreBuscador?>_rfc').value==""){
					alerta('Debe capturar el RFC', '¡Atención!','<?=$nombreBuscador?>_rfc');
					return false;				
			}
		}*/
		
		if(document.getElementById('<?=$nombreBuscador?>_calle').value==""){
			alerta('Debe capturar la Calle.', '¡Atención!','<?=$nombreBuscador?>_calle');
			return false;
		}
		/*if(document.getElementById('<?=$nombreBuscador?>_entrecalles').value==""){
			alerta('Debe capturar las Calles.', '¡Atención!','<?=$nombreBuscador?>_entrecalles');
			return false;
		}*/
		if(document.getElementById('<?=$nombreBuscador?>_cp').value==""){
			alerta('Debe capturar el Codigo Postal.', '¡Atención!','<?=$nombreBuscador?>_cp');
			return false;
		}
		if(document.getElementById('<?=$nombreBuscador?>_colonia').value==""){
			alerta('Debe capturar Colonia.', '¡Atención!','<?=$nombreBuscador?>_colonia');
			return false;
		}
		
		var var_moral = 'NO';
		if(document.all.<?=$nombreBuscador?>_rdmoral[0].checked){
			var_moral = 'SI';
		}
		
		consultaTexto("regresarClienteG_clientes","<?=$raiz?>buscadores_generales/guardarCliente.php?rdmoral="+var_moral+
	 		"&nombre="+document.getElementById('<?=$nombreBuscador?>_nombre').value+
			"&paterno="+document.getElementById('<?=$nombreBuscador?>_paterno').value+
	"&materno="+document.getElementById('<?=$nombreBuscador?>_materno').value+"&rfc="+document.getElementById('<?=$nombreBuscador?>_rfc').value+
	"&email="+document.getElementById('<?=$nombreBuscador?>_email').value+"&celular="+document.getElementById('<?=$nombreBuscador?>_celular').value+
	"&web="+document.getElementById('<?=$nombreBuscador?>_web').value+
	"&calle="+document.getElementById('<?=$nombreBuscador?>_calle').value+"&numero="+document.getElementById('<?=$nombreBuscador?>_numero').value+
	"&crucecalles="+document.getElementById('<?=$nombreBuscador?>_entrecalles').value+"&cp="+document.getElementById('<?=$nombreBuscador?>_cp').value+
	"&colonia="+document.getElementById('<?=$nombreBuscador?>_colonia').value+"&poblacion="+document.getElementById('<?=$nombreBuscador?>_poblacion').value+
	"&municipio="+document.getElementById('<?=$nombreBuscador?>_municipio').value+"&estado="+document.getElementById('<?=$nombreBuscador?>_estado').value+
	"&pais="+document.getElementById('<?=$nombreBuscador?>_pais').value+"&telefono="+document.getElementById('<?=$nombreBuscador?>_telefono').value);
		
	}
	
	function regresarClienteG_clientes(objetoS){
		if(objetoS.indexOf('ok')>-1){
			<?=$funcionOcultar?>();
			<?=$funcion?>(objetoS.split(",")[1]);
		}else{
			alerta3("ERROR AL GUARDAR");
		}
	}
	
	function <?=$nombreBuscador?>_limpiar(){
		 document.getElementById('<?=$nombreBuscador?>_rdmoral').checked=false;
		 document.getElementById('<?=$nombreBuscador?>_nombre').value="";
		 document.getElementById('<?=$nombreBuscador?>_paterno').value="";
		 document.getElementById('<?=$nombreBuscador?>_materno').value="";
		 document.getElementById('<?=$nombreBuscador?>_rfc').value="";
		 document.getElementById('<?=$nombreBuscador?>_celular').value="";
		 document.getElementById('<?=$nombreBuscador?>_email').value="";
		 document.getElementById('<?=$nombreBuscador?>_web').value="";
		 document.getElementById('<?=$nombreBuscador?>_calle').value="";
		 document.getElementById('<?=$nombreBuscador?>_numero').value="";
		 document.getElementById('<?=$nombreBuscador?>_entrecalles').value="";
		 document.getElementById('<?=$nombreBuscador?>_cp').value="";
		 document.getElementById('<?=$nombreBuscador?>_colonia').value="";
		 document.getElementById('<?=$nombreBuscador?>_poblacion').value="";
		 document.getElementById('<?=$nombreBuscador?>_municipio').value="";
		 document.getElementById('<?=$nombreBuscador?>_estado').value="";
		 document.getElementById('<?=$nombreBuscador?>_pais').value="";
		 document.getElementById('<?=$nombreBuscador?>_telefono').value="";
	}
</script>