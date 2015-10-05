<?php session_start();
	$Datos = split("##",$_SESSION[DATOSUSUARIO]);
	$sNombre = $Datos[2];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Registro PMM</title>
<?php include("librerias.php"); ?>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/slide.css" rel="stylesheet" type="text/css" />
<script>
	jQuery(document).ready(function(){
		jQuery("#spanUser").text('<?php echo $sNombre; ?>');
		jQuery("#btnLogin").click(function(){
			if(jQuery("#eUser").val()==""){
				alert("Debe ingresar su Usuario."); jQuery("#eUser").focus();
				return false;
			}
			if(jQuery("#ePwd").val()==""){
				alert("Debe ingresar su Password."); jQuery("#ePwd").focus();
				return false;
			}
			jQuery.post("login.php",{eUser:jQuery("#eUser").val(),ePwd:jQuery("#ePwd").val(),val:Math.random()},function(datos){
				if(datos.indexOf("ok")>-1){
					$("div#panel").slideUp("slow");	
					$("#toggle a").toggle();
					jQuery("#eUser,#ePwd").val("");
					jQuery("#spanUser").text(datos.split(",")[1]);
					jQuery("#pagina0").attr("src","Principal.php");
					return false;
				}else{
					alert("Usuario o Password Incorrectos.");
					return false;
				}
			});
		});
		jQuery("#btnAceptar").click(function(){
			if(jQuery("#eFolio").val()==""){
				alert("Debe capturar Folio"); jQuery("#eFolio").focus(); return false;
			}
			if(jQuery("#cbServicio").val()=="0"){
				alert("Debe seleccionar Servicio"); jQuery("#cbServicio").focus(); return false;
			}
			bloquearPantalla(true);
			var datos = "";
			switch(jQuery("#cbServicio").val()){
				case "1":
					datos = "altaMantenimiento.php?Folio="+jQuery("#eFolio").val()+"&val="+Math.random();					
				break;
				case "2":
					datos = "altaForaneos.php?Folio="+jQuery("#eFolio").val()+"&val="+Math.random();
				break;
				case "3":
					datos = "altaMobiliario.php?Folio="+jQuery("#eFolio").val()+"&val="+Math.random();
				break;
				case "4":
					datos = "altaPapeleria.php?Folio="+jQuery("#eFolio").val()+"&val="+Math.random();
				break;
			}
			jQuery("#eFolio").val("");
			jQuery("#cbServicio").val(0);
			jQuery("#pagina0").attr("src",datos);
		});
	});		
</script>
</head>
<body class="pmm_body">
	<div id="srm-cuerpo-principal">
    	<div id="Head">
        	<?php include("Head.php"); ?>
        </div>
        <div style="height:auto">
        	<table width="100%" border="0">
              <tr>
                <td width="22%" valign="top">
                	<?php include("Menu.php"); ?>
                </td>
                <td width="78%"><iframe name="pagina0" id="pagina0" scrolling="auto" align="top" 
                width="100%" height="600px" src="Principal.php" frameborder="0" allowtransparency="true"></iframe></td>
              </tr>
			  <tr>
			  	<td colspan="2"><?php include("pie.php"); ?></td>
			  </tr>
            </table>
        </div>
    </div>
</body>
</body>
</html>