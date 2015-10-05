<?  session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	} 
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');	
	
	require_once('../clases/CNumeroaLetra.php');	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<object id=factory viewastext style="display:none"
classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"
codebase="smsx.cab#Version=6,4,438,06">
</object>
<STYLE>
	H1.SaltoDePagina{ PAGE-BREAK-AFTER: always }
</STYLE>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <?
  
 	$numalet = new CNumeroaletra; 
	
	$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS gerente FROM catalogoempleado 
	WHERE id=".$_GET[gerente]."";
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
	$gerente = cambio_texto($f->gerente);
	
	if($_GET[nolleva]==0){
		$ar = split(",",$_GET[empleados]);		
			$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS responsable FROM catalogoempleado 
			WHERE id=".$ar[0]."";
			$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
			$responsable = cambio_texto($f->responsable);
			
			$numalet->setNumero($ar[1]);
				?>
					
					 <table width="450" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
						<tr>
						  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td colspan="2"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								  <tr>
									<td width="69" rowspan="5"><img src="../img/logo.jpg" alt="F" width="55" height="60"></td>
									<td colspan="3" align="center">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</td>
								  </tr>
								  <tr>
									<td align="center">&nbsp;</td>
									<td align="center">&nbsp;</td>
									<td align="right">FECHA: &nbsp;<?=$_GET[fecha] ?></td>
								  </tr>
								  <tr>
									<td colspan="3" align="center">&nbsp;</td>
								  </tr>
								  <tr>
									<td width="208" >VALE PROVISIONAL DE CAJA </td>
									<td width="14" align="right">&nbsp;</td>
									<td width="155" align="left"><? echo "$ ".$ar[1]; ?></td>
								  </tr>
								  <tr>
									<td colspan="3"><?=$numalet->letra(); ?></td>
									</tr>
								</table></td>
							  </tr>
							  <tr>
								<td colspan="2">CONCEPTO:</td>
							  </tr>
							  <tr>
								<td colspan="2"><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
								  <tr>
									<td><p>Debido a que existier&oacute;n diferencias en el cierre de caja, se descontara </p>
									  <p>a
										<?=$responsable ?>
										, dicha cantidad por medio de nomina.</p>
									  <p>&nbsp;</p></td>
								  </tr>
								</table></td>
							  </tr>
							  
							  <tr>
								<td colspan="2">&nbsp;</td>
							  </tr>
							  <tr>
								<td width="26%">AUTORIZADO POR:  </td>
								<td width="74%"><?=$gerente ?></td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td>FIRMA:_______________________________</td>
							  </tr>
							  <tr>
								<td colspan="2">&nbsp;</td>
							  </tr>
							  <tr>
								<td>RESPONSABLE:</td>
								<td><?=$responsable ?></td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td>FIRMA:_______________________________</td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							  </tr>
						  </table></td>
						</tr>
					  </table>
			<?
	}else{
		$arr = split(":",$_GET[empleados]);		
		$ar = "";
		
		for($i=0; $i<count($arr); $i++){			
			$ar = split(",",$arr[$i]);
			for($k=0; $k<count($ar)/2; $k++){					
					$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS responsable FROM catalogoempleado 
					WHERE id=".$ar[0]."";
					$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
					$responsable = cambio_texto($f->responsable);
					
					$numalet->setNumero($ar[1]);
				?>
					
					 <table width="450" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
						<tr>
						  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td colspan="2"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								  <tr>
									<td width="69" rowspan="5"><img src="../img/logo.jpg" alt="F" width="55" height="60"></td>
									<td colspan="3" align="center">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</td>
								  </tr>
								  <tr>
									<td align="center">&nbsp;</td>
									<td align="center">&nbsp;</td>
									<td align="right">FECHA: &nbsp;<?=$_GET[fecha] ?></td>
								  </tr>
								  <tr>
									<td colspan="3" align="center">&nbsp;</td>
								  </tr>
								  <tr>
									<td width="208" >VALE PROVISIONAL DE CAJA </td>
									<td width="14" align="right">&nbsp;</td>
									<td width="155" align="left"><? echo "$ ".$ar[1]; ?></td>
								  </tr>
								  <tr>
									<td colspan="3"><?=$numalet->letra(); ?></td>
									</tr>
								</table></td>
							  </tr>
							  <tr>
								<td colspan="2">CONCEPTO:</td>
							  </tr>
							  <tr>
								<td colspan="2"><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
								  <tr>
									<td><p>Debido a que existier&oacute;n diferencias en el cierre de caja, se descontara </p>
									  <p>a
										<?=$responsable ?>
										, dicha cantidad por medio de nomina.</p>
									  <p>&nbsp;</p></td>
								  </tr>
								</table></td>
							  </tr>
							  
							  <tr>
								<td colspan="2">&nbsp;</td>
							  </tr>
							  <tr>
								<td width="26%">AUTORIZADO POR:  </td>
								<td width="74%"><?=$gerente ?></td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td>FIRMA:_______________________________</td>
							  </tr>
							  <tr>
								<td colspan="2">&nbsp;</td>
							  </tr>
							  <tr>
								<td>RESPONSABLE:</td>
								<td><?=$responsable ?></td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td>FIRMA:_______________________________</td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							  </tr>
						  </table></td>
						</tr>
					  </table>
					<H1 class=SaltoDePagina> </H1>
					
			<? }
		 } 
	
	}	?>
	
 
</form>
</body>
</html>
<script>
	function printpr(){
		/*var OLECMDID = 7;
		var PROMPT = 1; // 2 DONTPROMPTUSER 
		var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 PAGEFOOTER=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
		document.body.insertAdjacentHTML('beforeEnd', WebBrowser); 
		WebBrowser1.ExecWB(OLECMDID, PROMPT);
		WebBrowser1.outerHTML = "";*/
		factory.printing.Print(false);
	}
	printpr();
	window.close();
</script>