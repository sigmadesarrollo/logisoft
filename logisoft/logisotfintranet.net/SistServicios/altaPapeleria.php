<?php include("verificar.php"); 
	require_once("Conectar.php");
	$Datos = split("##",$_SESSION[DATOSUSUARIO]);	
	$sId = $Datos[0]; $sNumEmpleado = $Datos[1]; $sNombre = $Datos[2]; $sAdmin = $Datos[3];
	$sSucursal = $Datos[4]; $sPrefijo = $Datos[5]; $sidSucursal = $Datos[6];
	$Array = obtenerFolio("papeleria",$sidSucursal,$sPrefijo);
	$Folio = $Array["Folio"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema Papeleria</title>
<?php include("librerias.php"); ?>
<script>
	jQuery(document).ready(function(){
		jQuery("#lb3,#lb4,#lb5").css("color","#900");
		jQuery("#s0").click(function(){document.location.href = 'Principal.php';});
		jQuery("#s1").click(function(){document.location.href = 'altaMantenimiento.php';});
		jQuery("#s2").click(function(){document.location.href = 'altaForaneos.php';});
		jQuery("#s3").click(function(){document.location.href = 'altaMobiliario.php';});	
		jQuery("#btnNuevo").click(function(){ Limpiar(true); });
		if(!jQuery.browser.msie){
			jQuery("#eHora").css({width:"75px",textAlign:"center"});
		}else{
			jQuery("#eHora").css({width:"70px",textAlign:"center"});
		}
		
		if('<?php echo $_GET[Folio]; ?>'!=''){
			jQuery.post("consultas.php",{accion:'Pape',eFolio:'<?php echo $_GET[Folio]; ?>',val:Math.random()},function(datos){				
				if(datos.indexOf("noencontro")<0){
					var obj = eval(datos);
					Limpiar(false);
					jQuery("#hFolio").val('<?php echo $_GET[Folio]; ?>');
					jQuery("#tdFolio").text('<?php echo $_GET[Folio]; ?>');
					jQuery("#eSucursal").val(obj.Sucursal);
					jQuery("#eUsuario").val(obj.Usuario);
					jQuery("#ePedido").val(obj.Pedido);
					jQuery("#eCosto").val(obj.Costo);
					jQuery("#eProveedor").val(obj.Proveedor);
					jQuery("#eFecha").val(obj.Fecha);
					jQuery("#eHora").val(obj.Hora);
					if(obj.Autorizado=="SI"){
						alert("El servicio con el Folio #"+obj.Folio+" YA fue Autorizado");
					}
					if(obj.Autorizado=="NO"){
						alert("El servicio con el Folio #"+obj.Folio+" NO fue Autorizado");
					}
					if(obj.Autorizado=="SI" || obj.Autorizado=="NO"){
						jQuery("#btnGuardar,#btnAutorizar,#btnNAutorizar").attr("disabled",true);
						jQuery(":input[type=text], #ePedido").attr("readOnly",true);
						jQuery("#eFecha").attr("disabled",true);
					}
				}else{
					alert("El numero de Folio #<?php echo $_GET[Folio]; ?> NO existe.");
				}
				parent.bloquearPantalla(false);
			});
		}
		if("<?php echo $sAdmin; ?>"=="SI"){
			jQuery(":input[type=text], #ePedido").attr("readOnly",true);
			jQuery("#eFecha").attr("disabled",true);
		}
		jQuery("#btnGuardar").click(function(){
			if(jQuery("#ePedido").val()==""){
				jQuery("#lb3").text("* Campo requerido"); jQuery("#ePedido").focus(); return false;
			}else{ jQuery("#lb3").text("*"); }
			
			if(jQuery("#eCosto").val()==""){
				jQuery("#lb4").text("* Campo requerido"); jQuery("#eCosto").focus(); return false;
			}else{ jQuery("#lb4").text("*"); }
			
			if(jQuery("#eProveedor").val()==""){
				jQuery("#lb5").text("* Campo requerido"); jQuery("#eProveedor").focus(); return false;
			}else{ jQuery("#lb5").text("*"); }
			
			jQuery(this).attr("disabled",true);
			parent.bloquearPantalla(true);
			jQuery.ajax({type:"POST",url:"registrar.php",
				   data:"accion=Pap&"+$("form").serialize()+"&val="+Math.random(),
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
			jQuery.post("registrar.php",{accion:"AutPap",hFolio:'<?php echo $_GET[Folio]; ?>',Autoriza:aut,val:Math.random()},function(datos){
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
		jQuery(":input[type=text], #ePedido, #hFolio").val("");
		jQuery("#tdFolio").text("");		
		jQuery("#ePedido").focus();
		jQuery("#eSucursal").val("<?php echo $sSucursal; ?>");
		jQuery("#eUsuario").val("<?php echo $sNombre; ?>");
		jQuery("#eFecha").val("<?php echo date ("d/m/Y"); ?>");
		if(Bool){
			jQuery("#lb3,#lb4,#lb5").text("*");
			jQuery("#btnGuardar,#eFecha").attr("disabled",false);
			jQuery(":input[type=text], #ePedido").attr("readOnly",false);			
			jQuery.post("consultas.php",{accion:'',tabla:'papeleria',val:Math.random()},function(datos){
				jQuery("#tdFolio").text(datos);
			});
		}		
	}
</script>
<style>
	.sLink{cursor:pointer; color:#006; font-size:14px; text-decoration:underline;}
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
	<table width="100%" border="0" cellpadding="3" cellspacing="1">
      <tr>
      	<td colspan="2">
        	<span class="sLink" id="s0">Inicio</span> || <span class="sLink" id="s1">Mantenimiento Vehicular</span> || <span class="sLink" id="s2">Vehiculos Foraneos</span> || <span class="sLink" id="s3">Mobiliario y Equipo</span>
        </td>
      </tr>
      <tr>
      	<td colspan="2">
        <span style="color:#006; font-size:25px; font-weight:bold">
        	Sistema Papeleria
        </span><br />
        Ingrese los datos correspondientes al servicio de soporte:</td>
      </tr>
      <tr>
        <td width="16%">Folio:<input type="hidden" id="hFolio" name="hFolio"/></td>
        <td width="84%" id="tdFolio" style="color:#900; font-weight:bold"><?php echo $Folio; ?></td>
      </tr>
      <tr>
        <td>Sucursal:</td>
        <td><input type="text" name="eSucursal" id="eSucursal" readonly="" value="<?php echo $sSucursal; ?>" />
        </td>
      </tr>
      <tr>
        <td>Nombre Usuario: </td>
        <td><input type="text" name="eUsuario" id="eUsuario" readonly="" value="<?php echo $sNombre; ?>" /></td>
      </tr>
      <tr>
        <td valign="top">Pedido:</td>
        <td><textarea name="ePedido" id="ePedido" cols="45" rows="5"></textarea>
        <label id="lb3">*</label></td>
      </tr>
      <tr>
        <td>Costo:</td>
        <td><input type="text" name="eCosto" id="eCosto" onkeypress="return tiposMoneda(event,this.value);"/>
		<label id="lb4">*</label></td>
      </tr>
      <tr>
        <td>Proveedor:</td>
        <td><input type="text" name="eProveedor" id="eProveedor" /><label id="lb5">*</label></td>
      </tr>
     <tr>
        <td>Dia y Hora:</td>
        <td><input type="text" name="eFecha" id="eFecha" value="<?php echo date ("d/m/Y"); ?>" 
		style="width:70px; text-align:center"/>
          <input type="text" name="eHora" id="eHora" /></td>
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