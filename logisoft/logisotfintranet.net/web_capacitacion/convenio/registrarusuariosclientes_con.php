<?
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_POST[accion]==1){
		$s = "SELECT UPPER(CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno)) cliente, cc.id,
		cu.usuario, cu.password, cu.activado, cc.email
		FROM catalogocliente cc
		LEFT JOIN catalogocliente_usuarios cu ON cc.id = cu.idcliente
		WHERE cc.id = $_POST[cliente];";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			
			$f->cliente = cambio_texto($f->cliente);
			$f->email = cambio_texto($f->email);
			$f->usuario = cambio_texto($f->usuario);
			
			$datos = json_encode($f);
			$datos = str_replace("null","''",str_replace("&#32;","",$datos));
			echo "(".$datos.")";
		}else{
			echo "({'id':null})";
		}
		
	}
	
	if($_POST[accion]==2){
		$s = "SELECT * FROM catalogocliente_usuarios WHERE idcliente = '$_POST[idcliente]'";
		$r = mysql_query($s,$l) or die($s);
		if($_POST[email]!=""){
			$s = "UPDATE catalogocliente SET email = '$_POST[email]' where id = $_POST[idcliente]";
			mysql_query($s,$l) or die($s);
			
			$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Documento sin t&iacute;tulo</title>
			</head>
			<body>
			<table width="550" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="100" rowspan="6"><img src="http://www.pmmentuempresa.com/web/img/logo.jpg" width="95" height="100" /></td>
				<td width="450" height="30" align="center"><strong>PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</strong></td>
			  </tr>
			  <tr>
				<td height="19" align="center"><strong>Informaci&oacute;n para el cliente</strong></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td><strong>Estimado(a):</strong> '.$f->nombrecliente.' </td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td colspan="2"><p>
				A través de este correo le informamos que su cuenta para http://www.pmmentuempresa.com ya ha sido activada. Por medio de este portal usted podrá utilizar sus folios de guías empresariales adquiridos mediante el convenio que nos ha solicitado.<br><br>
			Para poder ingresar a este portal, deberá ingresar los siguientes datos. <br><br>
			Datos para su cuenta personal: <br><br>
				Usuario:	'.$_POST[usuario].'<br>
				Password:	'.$_POST[password].'<br><br>
			Si tiene alguna duda, puede dirigirse a nuestra sección de Asistencia y Soporte en: <br>http://www.pmm.com.mx 
				</p></td>
			  </tr>
			</table>
			</body>
			</html>';
			
			$direccion = $_POST[email];
			$asunto = 'PMM en tu empresa';
			$cabeceras = "From: soporte@pmmintranet.net\r\nContent-type: text/html\r\n";
	
			if(!empty($direccion)){
				mail($direccion,$asunto,$body,$cabeceras);
				$correos = $direccion;
			}

		}
		if(mysql_num_rows($r)>0){
			$s = "UPDATE catalogocliente_usuarios SET usuario = '$_POST[usuario]',
			password='$_POST[password]', activado='$_POST[activar]'
			WHERE idcliente = '$_POST[idcliente]'";
			mysql_query($s,$l) or die($s);
		}else{
			$s = "insert into catalogocliente_usuarios SET usuario = '$_POST[usuario]',
			password='$_POST[password]', activado='$_POST[activar]',
			idcliente = '$_POST[idcliente]'";
			mysql_query($s,$l) or die($s);
		}
		
		echo "guardado";
	}
	
	if($_POST[accion]==3){
		$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Documento sin t&iacute;tulo</title>
			</head>
			<body>
			<table width="550" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="100" rowspan="6"><img src="http://www.pmmentuempresa.com/web/img/logo.jpg" width="95" height="100" /></td>
				<td width="450" height="30" align="center"><strong>PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</strong></td>
			  </tr>
			  <tr>
				<td height="19" align="center"><strong>Informaci&oacute;n para el cliente</strong></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td><strong>Estimado(a):</strong> '.$f->nombrecliente.' </td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td colspan="2"><p>
				A través de este correo le informamos que su cuenta para http://www.pmmentuempresa.com ya ha sido activada. Por medio de este portal usted podrá utilizar sus folios de guías empresariales adquiridos mediante el convenio que nos ha solicitado.<br><br>
			Para poder ingresar a este portal, deberá ingresar los siguientes datos. <br><br>
			Datos para su cuenta personal: <br><br>
				Usuario:	'.$_POST[usuario].'<br>
				Password:	'.$_POST[password].'<br><br>
			Si tiene alguna duda, puede dirigirse a nuestra sección de Asistencia y Soporte en: <br>http://www.pmm.com.mx 
				</p></td>
			  </tr>
			</table>
			</body>
			</html>';
			
			$direccion = $_POST[email];
			$asunto = 'PMM en tu empresa';
			$cabeceras = "From: soporte@pmmintranet.net\r\nContent-type: text/html\r\n";
	
			if(!empty($direccion)){
				mail($direccion,$asunto,$body,$cabeceras);
				$correos = $direccion;
			}
			
			echo "enviado";
	}
?>
