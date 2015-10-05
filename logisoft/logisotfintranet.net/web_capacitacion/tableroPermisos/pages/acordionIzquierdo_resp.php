<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link  href="../css/estilosclaseacordeon.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../javascript/ClaseAcordeon.js"></script>
<script>
	function mostrarPagina(pagina){
		parent.document.all.pagina.src = pagina;
	}

</script>
</head>

<body>
    <table width="230" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
	  	<td align="left">
			<ul class="acc" id="acc">
              <?
			  	$s = "SELECT permisos_tablerogpo.nombre AS grupo, permisos_tablerogpo.id
				FROM permisos_tablerogpo
				INNER JOIN permisos_modulos ON permisos_tablerogpo.id = permisos_modulos.grupo
				INNER JOIN permisos_empleadospermisos AS pm ON permisos_modulos.id = pm.idpermiso
				WHERE pm.idempleado = $_SESSION[IDUSUARIO]
				GROUP BY grupo
				ORDER BY grupo ASC";
				$r = mysql_query($s,$l) or die($s);
				while($f = mysql_fetch_object($r)){
			  ?>
			  <li>
				<h3><?=$f->grupo?></h3>
				<div class="acc-section">
					<div class="acc-content">
                    	<?
							$s = "SELECT permisos_modulos.nombre AS modulo, permisos_modulos.vinculo
							FROM permisos_modulos
							INNER JOIN permisos_empleadospermisos AS pm ON permisos_modulos.id = pm.idpermiso
							WHERE pm.idempleado = $_SESSION[IDUSUARIO] AND permisos_modulos.grupo = $f->id
							ORDER BY grupo ASC, modulo ASC";
							$rx = mysql_query($s,$l) or die($s);
							while($fx = mysql_fetch_object($rx)){
						?>
						<table width="100%" class="<?=($cf)?'fila1':'fila2'; $cf=!$cf;?>" border="0" cellpadding="0" cellspacing="0">
							<tr>
							  <td align="left" onclick="mostrarPagina('<?=$fx->vinculo?>')"><?=$fx->modulo?></td>
							</tr>
						</table>
                        <?
							}
						?>
					</div>
				</div>
			  </li>
              <?
			  	}
			  ?>
			</ul>		
		</td>
 	  </tr>     
	</table>

</body>
</html>
<script>
		var parentAccordion=new TINY.accordion.slider("parentAccordion");
		parentAccordion.init("acc","h3",1,-1);
		
		var nestedAccordion=new TINY.accordion.slider("nestedAccordion");
		nestedAccordion.init("nested","h3",1,-1);
</script>
