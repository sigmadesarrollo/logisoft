<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	
	if($_GET[estado]=='SUSTITUCION'){
		$validacion = (($_SESSION[IDSUCURSAL]!=1)?" AND sucursal = 'ninguna' ":"");
	}else{
		$validacion = " AND sucursal = $_SESSION[IDSUCURSAL] ";
	}
	
	$get=@mysql_query("SELECT count(*) FROM guiasventanilla_cs WHERE estado = '$_GET[estado]' $validacion ") or die("SE MURIO");
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
      <td width="27%" class="FondoTabla">Folio</td>
      <td width="73%" class="FondoTabla">Fecha</td>
    </tr>
    <tr>
      <td height="300px" colspan="2" valign="top" class="Tablas"><table width="100%" border="0" align="center" class="Tablas">
          <?
		$get =@mysql_query("select folioguia as id, date_format(fechacreacion, '%d/%m/%Y') as fecha 
							from guiasventanilla_cs WHERE estado = '$_GET[estado]' 
							$validacion
							limit $st,$pp",$link) or die("SE MURIO");		
			while($row=@mysql_fetch_array($get)){
			?>
				<tr >
       <td width="128" class="Tablas" >
<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[id];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[id];?></span></td>
            <td width="303" class="Tablas"><?=$row[fecha]; ?></td>
            <td width="51"></td>
          </tr>	
		<?	}
		
		?>

      </table></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarGuiasDCS.php?funcion=$_GET[funcion]&estado=$_GET[estado]&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>