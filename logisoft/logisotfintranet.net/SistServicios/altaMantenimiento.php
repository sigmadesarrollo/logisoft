<?php include("verificar.php"); 
	require_once("Conectar.php");
	$Datos = split("##",$_SESSION[DATOSUSUARIO]);	
	$sId = $Datos[0]; $sNumEmpleado = $Datos[1]; $sNombre = $Datos[2]; $sAdmin = $Datos[3];
	$sSucursal = $Datos[4]; $sPrefijo = $Datos[5]; $sidSucursal = $Datos[6];
	$Array = obtenerFolio("mantenimiento",$sidSucursal,$sPrefijo);
	$Folio = $Array["Folio"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Mantenimiento Vehicular</title>
<?php include("librerias.php"); ?>
<script>
	jQuery(document).ready(function(){
		jQuery("#lb3,#lb4,#lb5,#lb6,#lb7,#lb8,#lb9").css("color","#900");
		jQuery("#eFecha").mask("99/99/9999");
		jQuery("#s0").click(function(){document.location.href = 'Principal.php';});
		jQuery("#s1").click(function(){document.location.href = 'altaForaneos.php';});
		jQuery("#s2").click(function(){document.location.href = 'altaMobiliario.php';});
		jQuery("#s3").click(function(){document.location.href = 'altaPapeleria.php';});		
		jQuery("#btnNuevo").click(function(){ Limpiar(true); });
		if(!jQuery.browser.msie){
			jQuery("#eHora").css({width:"75px",textAlign:"center"});
		}else{
			jQuery("#eHora").css({width:"70px",textAlign:"center"});
		}
		if('<?php echo $_GET[Folio]; ?>'!=''){
			jQuery.post("consultas.php",{accion:'Mant',eFolio:'<?php echo $_GET[Folio]; ?>',val:Math.random()},function(datos){				
				if(datos.indexOf("noencontro")<0){
					var obj = eval(datos);
					Limpiar(false);
					jQuery("#hFolio").val('<?php echo $_GET[Folio]; ?>');
					jQuery("#tdFolio").text('<?php echo $_GET[Folio]; ?>');
					jQuery("#eSucursal").val(obj.Sucursal);
					jQuery("#eUsuario").val(obj.Usuario);
					jQuery("#eUnidad").val(obj.Unidad);
					jQuery("#eKilometraje").val(obj.Kilometraje);
					jQuery("#ePlacas").val(obj.Placas);
					jQuery("#eServicios").val(obj.Servicios);
					jQuery("#eCosto").val(obj.Costo);
					jQuery("#eProveedor").val(obj.Proveedor);
					jQuery("#eFecha").val(obj.Fecha);
					jQuery("#eHora").val(obj.Hora);
					jQuery("#cbEntrega").val(obj.TiempoEntrega);
					if(obj.Autorizado=="SI"){
						alert("El servicio con el Folio #"+obj.Folio+" YA fue Autorizado");
					}
					if(obj.Autorizado=="NO"){
						alert("El servicio con el Folio #"+obj.Folio+" NO fue Autorizado");
					}
					if(obj.Autorizado=="SI" || obj.Autorizado=="NO"){
						jQuery("#btnGuardar,#btnAutorizar,#btnNAutorizar").attr("disabled",true);
						jQuery(":input[type=text], #eServicios").attr("readOnly",true);
						jQuery("#cbEntrega,#eFecha").attr("disabled",true);
					}
				}else{
					alert("El numero de Folio #<?php echo $_GET[Folio]; ?> NO existe.");
				}
				parent.bloquearPantalla(false);
			});
		}
		
		if("<?php echo $sAdmin; ?>"=="SI"){
			jQuery(":input[type=text], #eServicios").attr("readOnly",true);
			jQuery("#cbEntrega,#eFecha").attr("disabled",true);
		}
		
		jQuery("#btnGuardar").click(function(){
			if(jQuery("#eUnidad").val()==""){
				jQuery("#lb3").text("* Campo requerido"); jQuery("#eUnidad").focus(); return false;
			}else{ jQuery("#lb3").text("*"); }
			
			if(jQuery("#eKilometraje").val()==""){
				jQuery("#lb4").text("* Campo requerido"); jQuery("#eKilometraje").focus(); return false;
			}else{ jQuery("#lb4").text("*"); }
			
			if(jQuery("#ePlacas").val()==""){
				jQuery("#lb5").text("* Campo requerido"); jQuery("#ePlacas").focus(); return false;
			}else{ jQuery("#lb5").text("*"); }
			
			if(jQuery("#eServicios").val()==""){
				jQuery("#lb6").text("* Campo requerido"); jQuery("#eServicios").focus(); return false;
			}else{ jQuery("#lb6").text("*"); }
			
			if(jQuery("#eCosto").val()==""){
				jQuery("#lb7").text("* Campo requerido"); jQuery("#eCosto").focus(); return false;
			}else{ jQuery("#lb7").text("*"); }
			
			if(jQuery("#eProveedor").val()==""){
				jQuery("#lb8").text("* Campo requerido"); jQuery("#eProveedor").focus(); return false;
			}else{ jQuery("#lb8").text("*"); }
			
			if(jQuery("#cbEntrega").val()==""){
				jQuery("#lb9").text("* Campo requerido"); jQuery("#cbEntrega").focus(); return false;
			}else{ jQuery("#lb9").text("*"); }
			
			jQuery(this).attr("disabled",true);
			parent.bloquearPantalla(true);
			jQuery.ajax({type:"POST",url:"registrar.php",
				   data:"accion=Mant&"+$("form").serialize()+"&val="+Math.random(),
				   success:function(datos){
						if(datos.indexOf("ok")>-1){
							if(jQuery("#hFolio").val()==""){
								alert("Los datos se guardar\u00f3n satisfactoriamente.");							
								jQuery("#tdFolio").text(datos.split("##&&##")[1]+" Use \u00e9ste n\u00famero de Folio para realizar las b\u00fasquedas o modificaciones.");
								jQuery("#hFolio").val(datos.split("##&&##")[1]);	
							}else{
								alert("Los datos se modificar\u00f3n satisfactoriamente.");
							}
						}else{
							alert("Hubo un error al guardar: "+datos.split("##&&##")[1]);
						}
						jQuery("#btnGuardar").attr("disabled",false);
						parent.bloquearPantalla(false);
						return false;
				   }
			});
		});
		
		jQuery("#btnAutorizar,#btnNAutorizar").click(function(){
			jQuery(this).attr("disabled",true);
			parent.bloquearPantalla(true);
			var aut = ((jQuery(this).attr("name")=="btnAutorizar")?"SI":"NO");
			jQuery.post("registrar.php",{accion:"AutFora",hFolio:'<?php echo $_GET[Folio]; ?>',Autoriza:aut,val:Math.random()},function(datos){
				if(datos.indexOf("ok")>-1){
					alert("Los datos se guardar\u00f3n satisfactoriamente.");
					jQuery("#btnAutorizar,#btnNAutorizar").attr("disabled",true);
				}else{
					alert("Hubo un error al guardar: "+datos.split("##&&##")[1]);
					jQuery("#btnAutorizar,#btnNAutorizar").attr("disabled",false);
				}
				parent.bloquearPantalla(false);
			});
		});
	});
	
	function Limpiar(Bool){
		jQuery(":input[type=text], #eServicios, select, #hFolio").val("");
		jQuery("#tdFolio").text("");		
		jQuery("#eUnidad").focus();
		jQuery("#eSucursal").val("<?php echo $sSucursal; ?>");
		jQuery("#eUsuario").val("<?php echo $sNombre; ?>");
		jQuery("#eFecha").val("<?php echo date ("d/m/Y"); ?>");
		if(Bool){
			jQuery("#lb3,#lb4,#lb5,#lb6,#lb7,#lb8,#lb9").text("*");
			jQuery("#btnGuardar,#cbEntrega,#eFecha").attr("disabled",false);
			jQuery(":input[type=text], #eServicios").attr("readOnly",false);			
			jQuery.post("consultas.php",{accion:'',tabla:'mantenimiento',val:Math.random()},function(datos){
				jQuery("#tdFolio").text(datos);
			});
		}		
	}
	
</script>
<style>
	.sLink{cursor:pointer; color:#006; font-size:14px; text-decoration:underline;}
</style>
</head>

<body style="background:transparent;">
<form id="form1" name="form1" method="post" action="">
	<table width="100%" border="0" cellpadding="2" cellspacing="1">
	  <tr>
      	<td colspan="2">
        	<span class="sLink" id="s0">Inicio</span> || <span class="sLink" id="s1">Vehiculos Foraneos</span> || <span class="sLink" id="s2">Mobiliario y Equipo</span> || <span class="sLink" id="s3">Papeleria</span>        </td>
      </tr>
      <tr>
      	<td colspan="2">
        <span style="color:#006; font-size:25px; font-weight:bold">
        	Sistema de Mantenimiento Vehicular        </span><br />
        Ingrese los datos correspondientes al servicio de soporte, <br /> los campos con asterico son obligatorios:</td>
      </tr>
      <tr>
        <td width="16%">Folio:<input type="hidden" id="hFolio" name="hFolio"/></td>
        <td width="84%" id="tdFolio" style="color:#900; font-weight:bold"><?php echo $Folio; ?></td>
      </tr>
      <tr>
        <td>Sucursal:</td>
        <td><input type="text" name="eSucursal" id="eSucursal" readonly="" value="<?php echo $sSucursal; ?>" />
        <label id="lb1"></label></td>
      </tr>
      <tr>
        <td>Nombre Usuario: </td>
        <td><input type="text" name="eUsuario" id="eUsuario" readonly="" value="<?php echo $sNombre; ?>" /></td>
      </tr>
      <tr>
        <td>No. Unidad:</td>
        <td><input type="text" name="eUnidad" id="eUnidad" />
        <label id="lb3">*</label></td>
      </tr>
      <tr>
        <td>Kilometraje:</td>
        <td><input type="text" name="eKilometraje" id="eKilometraje" />
        <label id="lb4">*</label></td>
      </tr>
      <tr>
        <td>Placas:</td>
        <td><input type="text" name="ePlacas" id="ePlacas" />
        <label id="lb5">*</label></td>
      </tr>
      <tr>
        <td valign="top">Servicios:</td>
        <td valign="top"><textarea name="eServicios" id="eServicios" cols="45" rows="5"></textarea>
        <label id="lb6">*</label></td>
      </tr>
      <tr>
        <td>Costo:</td>
        <td><input type="text" name="eCosto" id="eCosto" onkeypress="return tiposMoneda(event,this.value);" />
        <label id="lb7">*</label></td>
      </tr>
      <tr>
        <td>Proveedor:</td>
        <td><input type="text" name="eProveedor" id="eProveedor" />
        <label id="lb8">*</label></td>
      </tr>
      <tr>
        <td>Dia y Hora:</td>
        <td><input type="text" name="eFecha" id="eFecha" value="<?php echo date ("d/m/Y"); ?>" 
		style="width:70px; text-align:center"/>
		<input type="text" name="eHora" id="eHora" /></td>
      </tr>
      <tr>
        <td>Tiempo Entrega:</td>
        <td>
        	<select id="cbEntrega" name="cbEntrega">
            	<option value="" selected="selected">0</option>
				<?php
					for($i=1;$i<=31;$i++){
						echo "<option value=".$i.">".$i."</option>";
					}
				?>
            </select>
        	&nbsp;D&iacute;a(s)
        
        <label id="lb9">*</label> </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="100%" border="0">
          <tr>
           <?php
		   		if($sAdmin=="SI"){ ?>
				<td width="14%"><input type="button" id="btnAutorizar" name="btnAutorizar" value="Autorizar" /></td>
            	<td width="86%"><input type="button" id="btnNAutorizar" name="btnNAutorizar" value="No Autorizar" /></td>
			<?php	}else{ ?>
				<td width="14%"><input type="button" id="btnGuardar" name="btnGuardar" value="Ingresar" /></td>
            	<td width="86%"><input type="button" id="btnNuevo" name="btnNuevo" value="Limpiar" /></td>
			<?php	}   ?>
          </tr>
        </table></td>
      </tr>	  
</table>
</form>
</body>
</html>