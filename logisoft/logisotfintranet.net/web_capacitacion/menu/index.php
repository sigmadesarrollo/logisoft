<? 	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administrador</title>
</head>

<frameset rows="*" cols="158,*" frameborder="no" border="0" framespacing="0">
  <frame src="izquierda.php" name="f_reportes" scrolling="No" noresize="noresize" id="leftFrame" title="leftFrame" />
  <frameset rows="126,*" cols="*" framespacing="0" frameborder="no" border="0">
    <frame src="top.php" name="f_top" scrolling="No" noresize="noresize" id="topFrame" title="topFrame" />
    <frameset rows="*" cols="*,152" framespacing="0" frameborder="no" border="0">
		<frameset rows="*,0" cols="*" framespacing="0" frameborder="no" border="0">
			<frame src="webministator.php" name="f_base" scrolling="auto" noresize="noresize" id="topFrame" title="topFrame" />
			<frame src="puntovta_procesos.php" name="f_abajo" scrolling="No" noresize="noresize" id="topFrame" title="topFrame" />
		</frameset>
		<frame src="ventasd.php" name="f_derecho" scrolling="No" noresize="noresize" id="topFrame" title="topFrame" />
	</frameset>
  </frameset>
</frameset>
<noframes><body>
</body>
</noframes></html>

