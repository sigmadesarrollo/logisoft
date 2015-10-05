<?
	/*session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Permisos Grupo</title>

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
	var datosRecibidos='';
	var datosAgregados='';
	var datosQuitados='';

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
        <select name="nombregrupo" onchange="if(this.value!=''){pedirGrupo(this.value)}">
                <option value="">.::seleccione::.</option>
            <?
                $s = "SELECT id, nombre FROM permisos_grupos";
                $get = mysql_query($s,$l);		
                while($f=@mysql_fetch_object($get)){
            ?>
                <option value="<?=$f->id?>"><?=$f->nombre?></option>
            <?
                }
            ?>
        </select>
    	</td>
    </tr>
    <tr>
    	<td style="text-align:center">
 <div id="contenedorAcordeon" style="vertical-align:top; float:none">
   <div id="accordion">
   	<?
		$varopcion 		= 0;
		$varopcion2 	= 0;
	
		$s = "SELECT permisos_tablerogpo.nombre, permisos_tablerogpo.id
		FROM permisos_tablerogpo
		INNER JOIN permisos_modulos ON permisos_tablerogpo.id = permisos_modulos.grupo
		INNER JOIN permisos_permisos AS pp ON permisos_modulos.id = pp.idmodulo
		INNER JOIN permisos_grupospermisos AS pgp ON pp.id = pgp.idpermiso
		WHERE permisos_modulos.status = 1
		GROUP BY permisos_tablerogpo.id
		ORDER BY nombre ASC";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
	?>
	<h3><a href="#"><?=$f->nombre?></a></h3>
	 <div>
        <?
			$s = "select * from permisos_modulos where grupo=$f->id and status=1 order by nombre";
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
                	<li><input type="checkbox" name="permisoindividual<?=$fy->id?>" id="permisoindividual" value="<?=$fy->id?>" onclick="if(!this.checked){document.all.checkbox<?=$varopcion?>.checked = false;} revisarValor(<?=$fy->id?>,this.checked);" /><?=$fy->descripcion?></li>
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
					revisarValor(form.elements[i].value,valor);
					form.elements[i].checked=valor;
				}
			}
		}
		
		function guardarPermisos(){
			if(document.all.nombregrupo.value==""){
				mens.show("A","Proporcione el nombre del Grupo","¡Atencion!","nombregrupo");
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
			var agregados = "";
			var quitados = "";
			
			if(datosAgregados!=',')
				agregados = "&agregados="+datosAgregados.substring(1,datosAgregados.length-1);
			if(datosQuitados!=',')
				quitados = "&quitados="+datosQuitados.substring(1,datosQuitados.length-1);
			
			consultaTexto("resGuardar","permisosgrupo_con.php?accion=4&folio="+document.all.nombregrupo.value+
			agregados+quitados+"&permisos="+seleccionados+"&random="+Math.random());
		}
		
		function resGuardar(datos){
			if(datos.indexOf("guardo")>-1){
				mens.show("I","Los permisos han sido guardados","¡ATENCION!");
				var datosAgregados='';
				var datosQuitados='';
			}else{
				mens.show("A","Error al guardar "+datos,"¡ATENCION!");
			}
		}
		
		function revisarValor(valor,checado){
			if(checado){
				if(datosRecibidos.indexOf(":'"+valor+"'")<0){
					if(datosAgregados.indexOf(","+valor+",")<0)
						datosAgregados+=valor+",";
				}
				if(datosQuitados.indexOf(","+valor+",")>-1){
					datosQuitados = datosQuitados.replace(","+valor+",",",");
				}
			}else{
				if(datosRecibidos.indexOf(":'"+valor+"'")>-1){
					if(datosQuitados.indexOf(","+valor+",")<0)
						datosQuitados+=valor+",";
				}
				if(datosAgregados.indexOf(","+valor+",")>-1){
					datosAgregados = datosAgregados.replace(","+valor+",",",");
				}
			}
		}
		
		function pedirGrupo(valor){
			datosRecibidos='';
			datosAgregados=',';
			datosQuitados=',';
			consultaTexto("resPedirGrupo","permisosgrupo_con.php?accion=2&folio="+valor+"&random="+Math.random());
		}
		
		function resPedirGrupo(datos){
			datosRecibidos = datos;
			var obj = eval(datos);
			limpiarTodo(false);
			var permisos = obj.permisos;
			for(var i=0; i<permisos.length; i++){
				if(document.all['permisoindividual'+permisos[i].idpermiso])
					document.all['permisoindividual'+permisos[i].idpermiso].checked = true;
			}
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
		
		window.onload = function (){
			limpiarTodo();	
			mens.iniciar("../../javascript");
		}
</script>
