var _image = null;

function abrirVentanaFotos(imagen, nombre, titulo) {
	_image = new Image();
	_image.src = imagen;
	/*
	var ancho = 400;
	var alto = 250;
	var html = '';
	var styleText = 'font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #000000;';
	var carpeta = 'img/marco-fotos/';
	var cargando = '<div style="width: ' + ancho + 'px; height: ' + alto + 'px; background-color: #FFFFFF; text-align: center;"><img src="' + carpeta + 'cargando.gif" alt="Cargando..."></div>';
	
	if (navigator.userAgent.indexOf('MSIE 6.0') != -1) {
		html += '<table border="0" cellpadding="0" cellspacing="0"><tr>';
		html += '<td style="width: 35px; height: 35px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'superior-izquierda.png\')">&nbsp;</td>';
		html += '<td colspan="2" style="width: ' + ancho + 'px; height: 35px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'superior.png\')">&nbsp;</td>';
		html += '<td style="width: 35px; height: 35px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'superior-derecha.png\')">&nbsp;</td>';
		html += '</tr><tr><td style="width: 35px; height: ' + alto + 'px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'izquierda.png\')">&nbsp;</td>';
		html += '<td colspan="2" style="width: ' + ancho + 'px; height: ' + alto + 'px; background-color: #FFFFFF; ' + styleText + '">';
		html += cargando + '</td>';
		html += '<td style="width: 35px; height: ' + alto + 'px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'derecha.png\')">&nbsp;</td>';
		html += '</tr><tr><td rowspan="3" style="width: 35px; height: 85px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'inferior-izquierda.png\');">&nbsp;</td>';
		html += '<td rowspan="2" style="width: ' + (ancho - 35) + 'px; height: 85px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'inferior.png\');' + styleText + '">&nbsp;' + titulo + '</td>';
		html += '<td style="width: 35px; height: 67px; background-image: url(' + carpeta + 'fondo-cerrar.gif); ' + styleText + '"><img src="' + carpeta + 'cerrarie.gif" style="cursor: pointer;" alt="Cerrar" title="Cerrar ventana" onclick="VentanaModal.cerrar()"></td>';
		html += '<td rowspan="2" style="width: 35px; height: 85px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'inferior-derecha.png\');">&nbsp;</td>';
		html += '<tr><td style="width: 35px; height: 18px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'fondo-sombra.png\'); font-size: 8px;">&nbsp;</td>';
		html += '</tr></table>';
	}
	else {
		html += '<table border="0" cellpadding="0" cellspacing="0">';
		html += '<tr><td style="width: 35px; height: 35px; background-image: url(' + carpeta + 'superior-izquierda.png);">&nbsp;</td>';
		html += '<td colspan="2" style="width: ' + ancho + 'px; height: 35px; background-image: url(' + carpeta + 'superior.png);">&nbsp;</td>';
		html += '<td style="width: 35px; height: 35px; background-image: url(' + carpeta + 'superior-derecha.png);">&nbsp;</td>';
		html += '</tr><tr><td style="width: 35px; height: ' + alto + 'px; background-image: url(' + carpeta + 'izquierda.png);">&nbsp;</td>';
		html += '<td colspan="2" style="width: ' + ancho + 'px; height: ' + alto + 'px; background-color: #FFFFFF; ' + styleText + '">';
		html += cargando + '</td>';
		html += '<td style="width: 35px; height: ' + alto + 'px; background-image: url(' + carpeta + 'derecha.png);">&nbsp;</td>';
		html += '</tr><tr><td style="width: 35px; height: 85px; background-image: url(' + carpeta + 'inferior-izquierda.png);">&nbsp;</td>';
		html += '<td style="width: ' + (ancho - 35) + 'px; height: 85px; background-image: url(' + carpeta + 'inferior.png); ' + styleText + '">&nbsp;' + titulo + '</td>';
		html += '<td style="width: 35px; background-image: url(' + carpeta + 'inferior.png); ' + styleText + '"><img src="' + carpeta + 'cerrar.gif" style="cursor: pointer;" alt="Cerrar" title="Cerrar ventana" onclick="VentanaModal.cerrar()"></td>';
		html += '<td style="width: 35px; height: 85px; background-image: url(' + carpeta + 'inferior-derecha.png);">&nbsp;</td>';
		html += '</tr></table>';
	}
	
	VentanaModal.inicializar();
	VentanaModal.setSombra(false);
	VentanaModal.setSize(ancho + 70, alto + 120);
	VentanaModal.setClaseVentana("");
	VentanaModal.setContenido(html);
	VentanaModal.mostrar();
	*/
	_mostrarFoto(nombre, titulo);
}

function _mostrarFoto(nombre, titulo) {
	if (_image == null) {
		setTimeout("_mostrarFoto('" + nombre + "', '" + titulo + "')", 10);
	} 
	else if (_image.width == 0 || _image.height == 0) {
		setTimeout("_mostrarFoto('" + nombre + "', '" + titulo + "')", 10);
	}
	else {
		var ancho = _image.width;
		var alto = _image.height;
		var html = '';
		var carpeta = 'img/marco-fotos/';
		var styleText = 'font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #000000;';
		if (navigator.userAgent.indexOf('MSIE 6.0') != -1) {
			html += '<table border="0" cellpadding="0" cellspacing="0"><tr>';
			html += '<td style="width: 35px; height: 35px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'superior-izquierda.png\')">&nbsp;</td>';
			html += '<td colspan="2" style="width: ' + ancho + 'px; height: 35px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'superior.png\')">&nbsp;</td>';
			html += '<td style="width: 35px; height: 35px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'superior-derecha.png\')">&nbsp;</td>';
			html += '</tr><tr><td style="width: 35px; height: ' + alto + 'px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'izquierda.png\')">&nbsp;</td>';
			html += '<td colspan="2" style="width: ' + ancho + 'px; height: ' + alto + 'px; background-color: #FFFFFF; ' + styleText + '">';
			html += '<img src="' + _image.src + '" name="' + nombre + '" alt="' + titulo + '" title="' + titulo + '"></td>';
			html += '<td style="width: 35px; height: ' + alto + 'px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'derecha.png\')">&nbsp;</td>';
			html += '</tr><tr><td rowspan="3" style="width: 35px; height: 85px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'inferior-izquierda.png\');">&nbsp;</td>';
			html += '<td rowspan="2" style="width: ' + (ancho - 35) + 'px; height: 85px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'inferior.png\');' + styleText + '">&nbsp;' + titulo + '</td>';
			html += '<td style="width: 35px; height: 67px; background-image: url(' + carpeta + 'fondo-cerrar.gif); ' + styleText + '"><img src="' + carpeta + 'cerrarie.gif" style="cursor: pointer;" alt="Cerrar" title="Cerrar ventana" onclick="VentanaModal.cerrar()"></td>';
			html += '<td rowspan="2" style="width: 35px; height: 85px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'inferior-derecha.png\');">&nbsp;</td>';
			html += '<tr><td style="width: 35px; height: 18px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=\'' + carpeta + 'fondo-sombra.png\'); font-size: 8px;">&nbsp;</td>';
			html += '</tr></table>';
		}
		else {
			html += '<table border="0" cellpadding="0" cellspacing="0">';
			html += '<tr><td style="width: 35px; height: 35px; background-image: url(' + carpeta + 'superior-izquierda.png);">&nbsp;</td>';
			html += '<td colspan="2" style="width: ' + ancho + 'px; height: 35px; background-image: url(' + carpeta + 'superior.png);">&nbsp;</td>';
			html += '<td style="width: 35px; height: 35px; background-image: url(' + carpeta + 'superior-derecha.png);">&nbsp;</td>';
			html += '</tr><tr><td style="width: 35px; height: ' + alto + 'px; background-image: url(' + carpeta + 'izquierda.png);">&nbsp;</td>';
			html += '<td colspan="2" style="width: ' + ancho + 'px; height: ' + alto + 'px; background-color: #FFFFFF; ' + styleText + '">';
			html += '<img src="' + _image.src + '" name="' + nombre + '" alt="' + titulo + '" title="' + titulo + '"></td>';
			html += '<td style="width: 35px; height: ' + alto + 'px; background-image: url(' + carpeta + 'derecha.png);">&nbsp;</td>';
			html += '</tr><tr><td style="width: 35px; height: 85px; background-image: url(' + carpeta + 'inferior-izquierda.png);">&nbsp;</td>';
			html += '<td style="width: ' + (ancho - 35) + 'px; height: 85px; background-image: url(' + carpeta + 'inferior.png); ' + styleText + '">&nbsp;' + titulo + '</td>';
			html += '<td style="width: 35px; background-image: url(' + carpeta + 'inferior.png); ' + styleText + '"><img src="' + carpeta + 'cerrar.gif" style="cursor: pointer;" alt="Cerrar" title="Cerrar ventana" onclick="VentanaModal.cerrar()"></td>';
			html += '<td style="width: 35px; height: 85px; background-image: url(' + carpeta + 'inferior-derecha.png);">&nbsp;</td>';
			html += '</tr></table>';
		}
	}
	VentanaModal.inicializar();
	VentanaModal.setSombra(false);
	VentanaModal.setSize(ancho + 70, alto + 120);
	VentanaModal.setContenido(html);
	VentanaModal.mostrar();
}