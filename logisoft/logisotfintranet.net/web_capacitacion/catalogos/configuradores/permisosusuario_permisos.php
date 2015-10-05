<?	session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[empleado]!=""){
		$inner = "INNER JOIN catalogoempleado AS ce ON pgp.idgrupo = ce.grupo
				WHERE ce.id = $_GET[empleado] and permisos_modulos.status=1";
	}else{
		$inner = "";
	}
?>

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
		$inner
		GROUP BY permisos_tablerogpo.id
		ORDER BY nombre ASC";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
	?>
	<h3><a href="#"><?=$f->nombre?></a></h3>
	 <div>
        <?
			$s = "select * from permisos_modulos where grupo=$f->id and status=1";
			$rx = mysql_query($s,$l) or die($s);
			while($fx = mysql_fetch_object($rx)){
		?>
   	   <a href="#"><input type="checkbox" name="checkbox<?=$varopcion?>" id="seltodos" onclick="seleccionar(document.all['formx<?=$varopcion?>'], this.checked)" /><?=htmlentities(substr($fx->nombre,3,strlen($fx->nombre)))?> </a>
            <form action="" method="post" name="formx<?=$varopcion?>">
			<ul class="listaPermisos">
            	<?
					//$s = "select * from permisos_permisos where idmodulo=$fx->id";
					$s = "select permisos_permisos.id, permisos_permisos.descripcion, 
					if(isnull(permisos_empleadospermisos.idempleado),'','checked') checado
					from permisos_permisos 
					left join permisos_empleadospermisos on permisos_permisos.id = permisos_empleadospermisos.idpermiso 
						and permisos_empleadospermisos.idempleado = $_GET[empleado]
					where idmodulo=$fx->id";
					$ry = mysql_query($s,$l) or die($s);
					while($fy = mysql_fetch_object($ry)){
				?>
                	<li><input type="checkbox" name="permisoindividual<?=$fy->id?>" id="permisoindividual" value="<?=$fy->id?>" value="<?=$fy->id?>" onclick="if(!this.checked){document.all.checkbox<?=$varopcion?>.checked = false;}" <?=$fy->checado?> /><?=utf8_encode($fy->descripcion)?></li>
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