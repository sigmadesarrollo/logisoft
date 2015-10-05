<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Menu</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<link href="indexestilo.css" rel="stylesheet" type="text/css">
<script language="javascript" src="js/lib.js"></script>
<script language="javascript" src="ajax.js"></script>
<script type="text/javascript" src="web_capacitacion/javascript/ClaseMensajes.js"></script>
<style type="text/css">

<!--

.Estilo2 {font-family: "Tahoma", Courier, mono}

.Estilo3 {	color: #FFFFFF;

	font-weight: bold;

}

-->

</style>
<script>

var mens = new ClaseMensajes();
mens.iniciar("web_capacitacion/javascript");

function popUp(URL) {
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id 
	+ "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=0,fullscreen=1,width=" + (screen.availWidth-(screen.availWidth*.006)) + 
	",height=" + (screen.availHeight-32) + ",left = 0,top = -0.5');");
}

function validar(){ 
	if (document.getElementById('usuario').value==""){ 
		document.getElementById('usuario').focus();
		mens.show("A","Por favor proporcione el usuario","¡Atencion!");
		return false;
	}else if(document.getElementById('password').value==""){ 
		document.getElementById('password').focus();
		mens.show("A","Por favor proporcione la contraseña","¡Atencion!");
		return false;
	}else{ 
		consultaTexto("respuestaLogin", "index_consultas3.php?usuario="+document.getElementById('usuario').value+
		"&password="+document.getElementById('password').value+"&aleatorio="+Math.random());
	} 
}

function respuestaLogin(datos){
	if(datos.indexOf("Ya inicio Sesion")>-1){
		mens.show("A","Ya se ha iniciado sesion desde otro computador","¡Atencion!");
	}else if(datos.indexOf("Usuario y password incorrecto")>-1){
		mens.show("A","Usuario y password incorrecto","¡Atencion!");
	}else{
		mens.show("I","Bienvenido","¡Atencion!");
		document.all.usuario.value = "";
		document.all.password.value = "";
	}
}

function validarEntrada(menu){
	if(menu == 'PMM EN SU EMPRESA'){
		window.open("http://www.pmmentuempresa.com/");
	}else if(menu == 'migracion'){
		window.open("software/");
	}else{
		consultaTexto('resValidarEntrada',"validarTablero3.php?accion=1&modulo="+menu);
	}
}

function resValidarEntrada(datos){
	if(datos.indexOf('acceso denegado')>-1){
		mens.show("A","Acceso denegado","¡Atencion!");
	}else{
		popUp(datos);
	}
}
</script>
</head>
<body>
<div id="contenedorp">
	<div id="cuerpo">
    	<div id="headerp">
                <div id="logo">
                </div>
                <div id="menus">
                  <ul> 
                    <li><a href="#">inicio</a></li>
                    <li><a href="rastreo.php">rastreo</a></li>
                    <li><a href="#">cobertura</a></li>
                    <li><a href="#" onclick="window.open('clientes/web_capacitacion/guias/cotizarguia.php','','width=620 height=570')">cotizador</a></li>
                    <li><a href="#">servicios</a></li>
                    <li><a href="index.php">login</a></li>
                    </ul>
                </div>
                <div id="menus1">
                  Sistema para Presentación PMM</div>
        	</div>
            <div id="cuadroFotos">
    		  <div id="contenedorlogin">
               <div id="loginimg"></div>
                  <div id="logincontenido">
                   	<h1>Login&nbsp;&nbsp;&nbsp;</h1>
                    	<div id="cajaslogin">
                       <label>usuario:</label><br /><input type="text" id="usuario" name="usuario"  style="text-transform:uppercase" />
                       <label>password:</label><br /><input type="password" id="password" name="password" style="text-transform:uppercase" onkeypress="if(event.keyCode==13){validar();}" />
                       </div>
                       <div id="mitexto">
	                       <a id="mensajelogin">&nbsp;</a>
                       </div>
                       <div id="mitexto">
	                       <a href="#" onclick="validar();">Entrar</a>
                       </div>
                    </div>
                </div>
                <div id="cuadroIconos">
                	<div id="filaiconos">

                	<div id="icono" onclick="validarEntrada('PANEL DE CONTROL')">

                    	<img src="images/pmm-icons/05panel-control.png" />

                		<div id="texto">Panel de Control</div>

                    </div>

                    <div id="icono" onclick="validarEntrada('OPERACIONES Y SERVICIOS')">

                    	<img src="images/pmm-icons/04centro-operaciones.png" />

                		<div id="texto">Centro de Operaciones</div>

                    </div>

                    <div id="icono">

                    	<img src="images/pmm-icons/03portal-comercial.png" />

                		<div id="texto">Portal Comercial</div>

                    </div>

                    <div id="icono" onclick="validarEntrada('ADMINISTRACION')">

                    	<img src="images/pmm-icons/02unidad-administrativa.png" />

                		<div id="texto">Unidad Administrativa</div>

                    </div>

                    <div id="icono" onclick="validarEntrada('PUNTO DE VENTA')">

                    	<img src="images/pmm-icons/01punto-de-venta.png" />

                		<div id="texto">Punto de Venta</div>

                    </div>

                    </div>

                    

                    <div id="filaiconos">

                	<div id="icono" onclick="validarEntrada('PMM EN SU EMPRESA')">

                    	<img src="images/pmm-icons/09pmm-en-tu-empresa.png" />

                		<div id="texto">PMM en tu Empresa</div>

                    </div>

                    <div id="icono" onclick="validarEntrada('COBRANZA')">

                    	<img src="images/pmm-icons/08cobranza.png" />

                		<div id="texto">Cobranza</div>

                    </div>

                    <div id="icono" onclick="validarEntrada('CORM')">

                    	<img src="images/pmm-icons/07corm.png" />

                		<div id="texto">Corm</div>

                    </div>

                    <div id="icono" onclick="validarEntrada('VENTAS')">

                    	<img src="images/pmm-icons/06ventas.png" />

                		<div id="texto">Ventas</div>

                    </div>

                    </div>

                    

                    <div id="filaiconos">

                        <div id="icono" onclick="validarEntrada('DIRECCION GENERAL')">
    
                            <img src="images/pmm-icons/11director-general.png" />
    
                            <div id="texto">Director General</div>
    
                        </div>
    
                      <div id="icono" onclick="validarEntrada('GERENTE SUCURSAL')">
    
                            <img src="images/pmm-icons/10gerente.png" />
    
                            <div id="texto">Gerente</div>
    
                        </div>
                      <div id="icono" onclick="validarEntrada('migracion')" style="margin-right:380px">
    
                            <img src="images/pmm-icons/12migracion.png" />
    
                        <div id="texto">Software</div>
    
                        </div>

                    </div>
                </div>
            </div>    
    </div>
  </div>
</body>
</html>