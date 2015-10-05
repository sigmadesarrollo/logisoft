<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/

	include('../Conectar.php');
	$link=Conectarse('webpmm');
	
	$condicion="where ac.idsucursal = '$_SESSION[IDSUCURSAL]'";

	
	$get=mysql_query('SELECT count(ac.folio),ac.factura,CONCAT_WS(\' \',cc.nombre,cc.paterno,cc.materno)AS nombre
							FROM abonodecliente ac 
							INNER JOIN catalogocliente AS cc ON cc.id=ac.idcliente '.$condicion.' ',$link) or die("Error en la linea ".__LINE__.mysql_error($link));
	$total =@mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="select.js"></script>
<link href="../evaluacion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<form name="buscar" >
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="14%" class="FondoTabla">Folio</td>
      <td width="18%" class="FondoTabla">Factura</td>
      <td width="68%" class="FondoTabla">Nombre</td>
    </tr>
    <tr  height="300px" valign="top">
      <td colspan="3" class="Tablas"><table width="100%" border="0" align="center" cellspacing="0" class="Tablas">
          <?
		$get =mysql_query('SELECT ac.folio,ac.factura,CONCAT_WS(\' \',cc.nombre,cc.paterno,cc.materno)AS nombre
							FROM abonodecliente ac 
							INNER JOIN catalogocliente AS cc ON cc.id=ac.idcliente  '.$condicion.'  limit '.$st.','.$pp,$link)or die("Error en la linea ".__LINE__.mysql_error($link));;		
			while($row=mysql_fetch_array($get)){
			?>
				<tr >
       <td width="65" class="Tablas" >
<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[folio];?>'); try{parent.VentanaModal.cerrar();}catch(e){parent.mens.cerrar();}" style="cursor: pointer; color:#0000FF"><?=$row[folio];?></span></td>
            <td width="83" class="Tablas"><?=$row[factura]; ?></td>
            <td width="334"><?=$row[nombre]; ?></td>
          </tr>	
		<?	}
		
		?>

      </table></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarAbonoDeClienteGen.php?funcion=$_GET[funcion]&con=$_GET[con]&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>
<? //} ?>