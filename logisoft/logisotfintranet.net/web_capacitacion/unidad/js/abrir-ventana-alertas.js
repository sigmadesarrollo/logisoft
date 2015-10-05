function alerta(mensaje, titulo,caja) {	
	var alto = '50';
	var ancho = '300';
	var icono = 'alerta.gif';
	VentanaModal.inicializar();
	VentanaModal.setSize(ancho, alto);
	VentanaModal.setClaseVentana("");
	VentanaModal.setIdVentana("ventana-modal-ventana");
	
	var src = 'img/ventana-info/';
	var styleTitulo = 'font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #000000;';
	var styleMensaje = 'font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #000000; height: ' + (alto - 150) + 'px; vertical-align: top;';
	
	if (titulo == null || titulo == '')  
		titulo = '&nbsp;';
		
	if (mensaje == null || mensaje == '')  
		mensaje = '&nbsp;';
	if (mensaje == null || mensaje == '')  
		mensaje = '&nbsp;';
	else {
		while (mensaje.indexOf("\n") != -1)
			mensaje = mensaje.replace("\n", "<br>");
	}
		
	var html = ''
	+ '<table border="0" cellspacing="0" cellpadding="0">'
	+ '<tr> '
	+ '<td style="' + bgImg(src + "superior-izquierda.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "superior.png") + ' height: 15px; width: ' + (ancho - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "superior-derecha.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '</tr><tr>'
	+ '<td style="' + bgImg(src + "izquierda.png") + ' width: 15px; height: ' + (alto - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="background-color: #ffffff;"><table width="100%" border="0" cellspacing="5" cellpadding="0">'
	+ '<tr><td style="width: 55px; height: 55px;"><img src="' + src + icono + '" alt="Alerta"></td>'
	+ '<td style="' + styleTitulo + '">' + titulo + '</td></tr>'
	+ '<tr><td>&nbsp;</td>'
	+ '<td style="' + styleMensaje + '">' + mensaje + '</td></tr>'
	+ '<tr><td colspan="2" align="center">'
	+ '<img src="' + src + 'aceptar.gif" alt="Aceptar" title="Aceptar" style="cursor: pointer;" onclick="window.parent.document.form1.' + caja + '.focus(); VentanaModal.cerrar()">'
	+ '</td></tr></table>'
	+ '</td><td style="' + bgImg(src + "derecha.png") + ' width: 15px; height: ' + (alto - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '</tr><tr><td style="' + bgImg(src + "inferior-izquierda.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "inferior.png") + ' height: 15px; width: ' + (ancho - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "inferior-derecha.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '</tr></table>';
	
	VentanaModal.setContenido(html);
	VentanaModal.mostrar();
}

function info(mensaje, titulo) {
	var alto = '50';
	var ancho = '300';
	var icono = 'info.gif';
	VentanaModal.inicializar();
	VentanaModal.setSize(ancho, alto);
	VentanaModal.setClaseVentana("");
	VentanaModal.setIdVentana("ventana-modal-ventana");
	
	var src = 'img/ventana-info/';
	var styleTitulo = 'font-family: Tahoma, Arial, Verdana, Helvetica, sans-serif; font-size: 11px; font-weight: bold; color: #000000;';
	var styleMensaje = 'font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #000000; height: ' + (alto - 150) + 'px; vertical-align: top; text-align: left;';
	
	if (titulo == null || titulo == '')  
		titulo = '&nbsp;';

	if (mensaje == null || mensaje == '')  
		mensaje = '&nbsp;';
	else {
		while (mensaje.indexOf("\n") != -1)
			mensaje = mensaje.replace("\n", "<br>");
	}
		
	var html = ''
	+ '<table border="0" cellspacing="0" cellpadding="0">'
	+ '<tr> '
	+ '<td style="' + bgImg(src + "superior-izquierda.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "superior.png") + ' height: 15px; width: ' + (ancho - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "superior-derecha.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '</tr><tr>'
	+ '<td style="' + bgImg(src + "izquierda.png") + ' width: 15px; height: ' + (alto - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="background-color: #ffffff;"><table width="100%" border="0" cellspacing="5" cellpadding="0">'
	+ '<tr><td style="width: 55px; height: 55px;"><img src="' + src + icono + '" alt="Info"></td>'
	+ '<td style="' + styleTitulo + '">' + titulo + '</td></tr>'
	+ '<tr><td>&nbsp;</td>'
	+ '<td style="' + styleMensaje + '">' + mensaje + '</td></tr>'
	+ '<tr><td colspan="2" align="center">'
	+ '<img src="' + src + 'aceptar.gif" alt="Aceptar" title="Aceptar" style="cursor: pointer;" onclick="VentanaModal.cerrar()">'
	+ '</td></tr></table>'
	+ '</td><td style="' + bgImg(src + "derecha.png") + ' width: 15px; height: ' + (alto - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '</tr><tr><td style="' + bgImg(src + "inferior-izquierda.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "inferior.png") + ' height: 15px; width: ' + (ancho - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "inferior-derecha.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '</tr></table>';
	
	VentanaModal.setContenido(html);
	VentanaModal.mostrar();
	setTimeout("VentanaModal.cerrar()", 5000);
}

function confirmar(mensaje, titulo, accionTrue, accionFalse) {
	var alto = '200';
	var ancho = '500';
	var icono = 'icono.gif';
	VentanaModal.inicializar();
	VentanaModal.setSize(ancho, alto);
	VentanaModal.setClaseVentana("");
	VentanaModal.setIdVentana("ventana-modal-ventana");
	
	var src = 'img/ventana-confirmar/';
	var styleTitulo = 'font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #000000;';
	var styleMensaje = 'font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #000000; height: ' + (alto - 150) + 'px; vertical-align: top;';
	
	if (titulo == null || titulo == '')  
		titulo = '&nbsp;';
	
	if (mensaje == null || mensaje == '')  
		mensaje = '&nbsp;';
	else {
		while (mensaje.indexOf("\n") != -1)
			mensaje = mensaje.replace("\n", "<br>");
	}
		
	var html = ''
	+ '<table border="0" cellspacing="0" cellpadding="0">'
	+ '<tr> '
	+ '<td style="' + bgImg(src + "superior-izquierda.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "superior.png") + ' height: 15px; width: ' + (ancho - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "superior-derecha.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '</tr><tr>'
	+ '<td style="' + bgImg(src + "izquierda.png") + ' width: 15px; height: ' + (alto - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="background-color: #ffffff;"><table width="100%" border="0" cellspacing="5" cellpadding="0">'
	+ '<tr><td style="width: 55px; height: 55px;"><img src="' + src + icono + '" alt="Confirmar"></td>'
	+ '<td style="' + styleTitulo + '">' + titulo + '</td></tr>'
	+ '<tr><td>&nbsp;</td>'
	+ '<td style="' + styleMensaje + '">' + mensaje + '</td></tr>'
	+ '<tr><td colspan="2" align="center">'
	+ '<img src="' + src + 'aceptar.gif" alt="Aceptar" title="Aceptar" style="cursor: pointer;" onclick="VentanaModal.cerrar();' + accionTrue + '">'
	+ '<img src="' + src + 'cancelar.gif" alt="Cancelar" title="Cancelar" style="cursor: pointer;" onclick="VentanaModal.cerrar();' + accionFalse + '">'
	+ '</td></tr></table>'
	+ '</td><td style="' + bgImg(src + "derecha.png") + ' width: 15px; height: ' + (alto - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '</tr><tr><td style="' + bgImg(src + "inferior-izquierda.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "inferior.png") + ' height: 15px; width: ' + (ancho - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "inferior-derecha.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '</tr></table>';
	
	VentanaModal.setContenido(html);
	VentanaModal.mostrar();
}

function error(mensaje, titulo) {
	var alto = '200';
	var ancho = '500';
	var icono = 'icono.gif';
	VentanaModal.inicializar();
	VentanaModal.setSize(ancho, alto);
	VentanaModal.setClaseVentana("");
	VentanaModal.setIdVentana("ventana-modal-ventana");
	
	var src = 'img/ventana-error/';
	var styleTitulo = 'font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #000000;';
	var styleMensaje = 'font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #000000; height: ' + (alto - 150) + 'px; vertical-align: top;';
	
	if (titulo == null || titulo == '')  
		titulo = '&nbsp;';
		
	if (mensaje == null || mensaje == '')  
		mensaje = '&nbsp;';
	else {
		while (mensaje.indexOf("\n") != -1)
			mensaje = mensaje.replace("\n", "<br>");
	}
		
	var html = ''
	+ '<table border="0" cellspacing="0" cellpadding="0">'
	+ '<tr> '
	+ '<td style="' + bgImg(src + "superior-izquierda.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "superior.png") + ' height: 15px; width: ' + (ancho - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "superior-derecha.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '</tr><tr>'
	+ '<td style="' + bgImg(src + "izquierda.png") + ' width: 15px; height: ' + (alto - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="background-color: #ffffff;"><table width="100%" border="0" cellspacing="5" cellpadding="0">'
	+ '<tr><td style="width: 55px; height: 55px;"><img src="' + src + icono + '" alt="Error"></td>'
	+ '<td style="' + styleTitulo + '">' + titulo + '</td></tr>'
	+ '<tr><td>&nbsp;</td>'
	+ '<td style="' + styleMensaje + '">' + mensaje + '</td></tr>'
	+ '<tr><td colspan="2" align="center">'
	+ '<img src="' + src + 'aceptar.gif" alt="Aceptar" title="Aceptar" style="cursor: pointer;" onclick="VentanaModal.cerrar()">'
	+ '</td></tr></table>'
	+ '</td><td style="' + bgImg(src + "derecha.png") + ' width: 15px; height: ' + (alto - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '</tr><tr><td style="' + bgImg(src + "inferior-izquierda.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "inferior.png") + ' height: 15px; width: ' + (ancho - 30) + 'px; font-size: 8px;">&nbsp;</td>'
	+ '<td style="' + bgImg(src + "inferior-derecha.png") + ' width: 15px; height: 15px; font-size: 8px;">&nbsp;</td>'
	+ '</tr></table>';
	
	VentanaModal.setContenido(html);
	VentanaModal.mostrar();
}

function bgImg(imgSrc) {
	if (VentanaModal.MSIE) 
		return " filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src='" + imgSrc + "'); ";
	else
		return " background-image: url(" + imgSrc + "); ";
}