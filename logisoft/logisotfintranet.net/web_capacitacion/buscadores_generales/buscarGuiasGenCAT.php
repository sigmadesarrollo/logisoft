<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	if($_GET[devolucion]=="dev"){
		$criterio = " WHERE estado<>'ENTREGADA'";
	}
	$get=@mysql_query("SELECT COUNT(guia) FROM 
					(SELECT id AS guia, date_format(fecha,'%d/%m/%Y') as fecha FROM guiasventanilla ".$criterio."
					UNION
					SELECT id AS guia, date_format(fecha,'%d/%m/%Y') as fecha FROM guiasempresariales ".$criterio.") t");
	$total =@mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="select.js"></script>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<form name="buscar" >
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="30%" class="FondoTabla">Guia</td>
      <td width="25%" class="FondoTabla">Fecha</td>
      <td width="45%" class="FondoTabla">Estado</td>
	  
    </tr>
    <tr>
      <td colspan="3" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?
					 
		$get =@mysql_query('SELECT guia, fecha, estado FROM 
				(SELECT id AS guia, date_format(fecha,"%d/%m/%Y") as fecha, estado FROM guiasventanilla '.$criterio.'
				UNION
				SELECT id AS guia, date_format(fecha,"%d/%m/%Y") as fecha, estado FROM guiasempresariales '.$criterio.') t 
				limit '.$st.','.$pp,$link);		
			while($row=@mysql_fetch_array($get)){
			?>
				<tr >
       <td width="143" class="Tablas" >
<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[0];?></span></td>
            <td width="117" class="Tablas"><?=$row[1]; ?></td>
			<td width="186" class="Tablas"><?=$row[2]; ?></td>
            <td width="32"></td>
          </tr>	
		<?	}
		
		?>

      </table></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarGuiasGenCAT.php?funcion=$_GET[funcion]&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>
<? //} ?>