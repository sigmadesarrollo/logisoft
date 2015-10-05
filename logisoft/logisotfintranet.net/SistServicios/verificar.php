<?php
	session_start();
	if(empty($_SESSION['DATOSUSUARIO'])){
		$_SESSION[MENSAJE] = "NO";
		die("<script language='JavaScript'>
		parent.AbrirSlide();
		parent.botonSlide();
		window.location.href='Principal.php';
		</script>");
	}
?>