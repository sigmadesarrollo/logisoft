<?
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Permisos Usuario</title>

	<script src="../../javascript/ajax.js"></script>
    <link rel="stylesheet" href="../../tableroPermisos/javascript/estilosjs/form.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../../tableroPermisos/pages/css/reseter.css" />
    <link rel="stylesheet" type="text/css" href="../../tableroPermisos/pages/css/permisos/jquery-ui-1.7.2.custom.css" />
    <script type="text/javascript" src="../../tableroPermisos/pages/js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="../../tableroPermisos/pages/js/jquery-ui-1.7.2.custom.min.js"></script>
    
    <script language="javascript" src="../../javascript/ClaseMensajes.js"></script>


<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

.listaPermisos {
	list-style-type:none;
	padding-left:23px;
}

#contenedorAcordeon{
	width:400px;
	align:center;
	text-align:left;
	float:left;
	margin:0px 0px 0px 0px;
	vertical-align:top;
}
-->
</style></head>
<script>
	var mens = new ClaseMensajes();

	$(document).ready(function(){
			$("#accordion").accordion({
				header: "h3",
				autoHeight: false,
				collapsible: true
			});
		});
</script>
<body>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:2px solid #016193;" align="center">
	<tr>
    	<td style="text-align:center; background-color:#016193; color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px;">PERMISOS PARA TABLEROS
    	</td>
    </tr>
	<tr>
    	<td style="text-align:center">
        	<table width="484" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="62">Usuario:</td>
                <td width="49"><input type="text" name="idempleado" style="width:40px" onblur="if(this.value==''){ document.all.nombreempleado.value=''; document.all.user.value='';}" onkeypress="if(event.keyCode==13){BuscarEmpleado(this.value)}" /></td>
                <td width="29">
                <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="mens.popup('../../buscadores_generales/buscarEmpleadoGen.php?funcion=BuscarEmpleado', 620, 400, 'ventana', 'Busqueda');" />                </td>
                <td width="232"><input type="text" name="nombreempleado" readonly="readonly" style="width:200px" /></td>
                <td width="128"><input type="text" name="user" style="width:90px" readonly="readonly" /></td>
            </tr>
        </table>
    	</td>
    </tr>
    <tr>
    	<td style="text-align:center"  id="contenedorTree">
 <div id="contenedorAcordeon" style="vertical-align:top; float:none">
   <div id="accordion">
   	<?
		$varopcion 		= 0;
		$varopcion2 	= 0;
	
		$s = "SELECT permisos_tablerogpo.nombre AS nombre, permisos_tablerogpo.id
		FROM permisos_tablerogpo
		INNER JOIN permisos_modulos ON permisos_tablerogpo.id = permisos_modulos.grupo
		INNER JOIN permisos_permisos AS pp ON permisos_modulos.id = pp.idmodulo
		INNER JOIN permisos_grupospermisos AS pgp ON pp.id = pgp.idpermiso
		GROUP BY permisos_tablerogpo.id
		ORDER BY nombre ASC";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
	?>
	<h3><a href="#"><?=$f->nombre?></a></h3>
	 <div>
        <?
			$s = "select * from permisos_modulos where grupo=$f->id";
			$rx = mysql_query($s,$l) or die($s);
			while($fx = mysql_fetch_object($rx)){
		?>
   	   <a href="#"><input type="checkbox" name="checkbox<?=$varopcion?>" id="seltodos" onclick="seleccionar(document.all['formx<?=$varopcion?>'], this.checked)" /><?=substr($fx->nombre,3,strlen($fx->nombre))?> </a>
            <form action="" method="post" name="formx<?=$varopcion?>">
			<ul class="listaPermisos">
            	<?
					$s = "select * from permisos_permisos where idmodulo=$fx->id";
					$ry = mysql_query($s,$l) or die($s);
					while($fy = mysql_fetch_object($ry)){
				?>
                	<li><input type="checkbox" name="permisoindividual<?=$fy->id?>" id="permisoindividual" value="<?=$fy->id?>" onclick="if(!this.checked){document.all.checkbox<?=$varopcion?>.checked = false;}" /><?=$fy->descripcion?></li>
                <?
					}
				?>
            </ul>
            </form>
		<?
			
			$varopcion++;
			}
		?>
     </div>
     <?
		}
	 ?>
	</div>
 </div> 
        
        </td>
    </tr>
    <tr>
    	<td style="text-align:center"><img src="../../img/Boton_Guardar.gif" onclick="guardarPermisos();" style="cursor:pointer" /></td>
    </tr>
</table>
</body>
</html>
<script>		
		function seleccionar(form, valor){
			for(var i=0; i<form.elements.length; i++){
				if(form.elements[i].type=='checkbox'){
					form.elements[i].checked=valor;
				}
			}
		}
		
		function BuscarEmpleado(valor){
			consultaTexto("resBuscarEmpleado","permisosusuario_con.php?accion=1&idempleado="+valor);
		}
		
		function resBuscarEmpleado(datos){
			var u = document.all;
			var obj = eval(convertirValoresJson(datos));
			
			u.idempleado.value = obj.id;
			u.nombreempleado.value = obj.nombre;
			u.user.value = obj.user;
			consultaTexto("ponerChecks","permisosusuario_permisos.php?empleado="+obj.id);
		}
		
		function ponerChecks(datos){
			document.all.contenedorTree.innerHTML = datos;
			$("#accordion").accordion({
				header: "h3",
				autoHeight: false,
				collapsible: true
			});
		}
		
		function limpiarTodo(valor){
			var opciones = document.all.permisoindividual;
			for(var i=0; i<opciones.length; i++){
				opciones[i].checked = false;
			}
			var opciones2 = document.all.seltodos;
			for(var i=0; i<opciones2.length; i++){
				opciones2[i].checked = false;
			};
		}
		
		function guardarPermisos(){
			if(document.all.nombreempleado.value==""){
				mens.show("A","Seleccione el empleado","¡Atencion!","idempleado");
				return false;
			}
			var seleccionados = "";
			var opciones = document.all.permisoindividual;
			for(var i=0; i<opciones.length; i++){
				if(opciones[i].checked == true){
					if(seleccionados!="")
						seleccionados += ","
					seleccionados += opciones[i].value;
				}
			}
			consultaTexto("resGuardar","permisosusuario_con.php?accion=2&idempleado="+document.all.idempleado.value+
			"&permisos="+seleccionados+"&random="+Math.random());
		}
		
		function resGuardar(datos){
			if(datos.indexOf("guardo")>-1){
				mens.show("I","Los permisos del usuario han sido actualizados","¡Atencion!");
			}else{
				mens.show("A","Error "+datos,"¡Atencion!");
			}
		}
		
		window.onload = function (){
			//limpiarTodo();	
			//alert("ke pedo");
			mens.iniciar("../../javascript");
		}
</script>
