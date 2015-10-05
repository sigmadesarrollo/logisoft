<?
	session_start();
	require_once("Conectar.php");

	class RollsBack{
		#################################################################################
		# Clase RollsBack                                                               #
		# Clase creada para hacer los rollback de manera manual en caso de errores      #
		#################################################################################
		
		var $guia			= "";
		var $evaluacion 	= "";
		var $sucursal		= "";
		var $idusuario		= "";
		var $recoleccion	= "";
		var $bolsaempaque	= "";
		var $procesoGuiasV;
		var $procesoGuiasE;
		var $correos;
		var $errores;
		
		public function RollsBack(){
			#asignando los usuarios a enviar los errores
			$this->correos		= array();
			$this->correos[]	= "ipartida@tecnika.com.mx";
			$this->correos[]	= "lortega@tecnika.com.mx";
			$this->definicionMsgErrores();
		}
		
		public function prepararDatosGuiaV($guia,$evaluacion,$sucursal,$idusuario, $recoleccion){
			#datos necesitados
			$this->guia 		= $guia;
			$this->evaluacion 	= $evaluacion;
			$this->sucursal 	= $sucursal;
			$this->idusuario 	= $idusuario;
			$this->recoleccion	= $recoleccion;
			#se preparan las instrucciones para guia de ventanilla en caso de haber error
			$this->intruccionesGuiaVentanilla();
		}
		
		public function prepararDatosGuiaE($guia,$evaluacion,$sucursal,$idusuario, $bolsaempaque){
			#datos necesitados
			$this->guia 		= $guia;
			$this->evaluacion 	= $evaluacion;
			$this->sucursal 	= $sucursal;
			$this->idusuario 	= $idusuario;
			$this->bolsaempaque	= $bolsaempaque;
			#se preparan las instrucciones para guia de ventanilla en caso de haber error
			$this->intruccionesGuiaEmpresarial();
		}
		
		public function intruccionesGuiaVentanilla(){
			#instrucciones para guias de ventanilla
			$this->procesoGuiasV = array();
			#0
			$this->procesoGuiasV[] = "delete from guiasventanilla where id = '".$this->guia."'";
			#1
			$this->procesoGuiasV[] = "update evaluacionmercancia set estado = 'GUARDADO' 
									  where folio = ".$this->evaluacion." and sucursal = ".$this->sucursal."";
			#2
			$this->procesoGuiasV[] = "delete from guias_valorotros where guia = '".$this->guia."'";
			#3
			$this->procesoGuiasV[] = "call proc_VentasVsPresupuesto('CAN_GUIA_VE','".$this->guia."',".$this->sucursal.");";
			#4
			$this->procesoGuiasV[] = "delete from historialmovimientos where folio LIKE '".$this->guia."%'";
			#5
			$this->procesoGuiasV[] = "UPDATE asignacionpapeletasrecdetalle SET utilizado = 0 
									WHERE folios = '".$this->recoleccion."' AND sucursal = ".$this->sucursal;
			#6
			$this->procesoGuiasV[] = "delete from reporte_auditoria_liquidacion where guia = '".$this->guia."'";
			#7
			$this->procesoGuiasV[] = "delete from reportes_ventas where folio = '".$this->guia."'";
			#8
			$this->procesoGuiasV[] = "delete from formapago where guia = '".$this->guia."'";
			#9
			$this->procesoGuiasV[] = "delete from guia_rastreo where noguia = '".$this->guia."'";
			#10
			$this->procesoGuiasV[] = "delete from pagoguias where guia = '".$this->guia."'";
			#11
			$this->procesoGuiasV[] = "delete from guiaventanilla_detalle where idguia = '".$this->guia."'";
			#12
			$this->procesoGuiasV[] = "delete from guiaventanilla_unidades where idguia = '".$this->guia."'";
			#13
			$this->procesoGuiasV[] = "INSERT INTO seguimiento_guias 
									SET guia = ".$this->evaluacion.", ubicacion = ".$this->sucursal.",
									estado = 'EVALUACION', unidad=null, fecha=CURRENT_DATE, hora = CURRENT_TIME,
									usuario = ".$this->idusuario;
			#14
			$this->procesoGuiasV[] = "delete from seguimiento_guias where guia = '".$this->guia."'";
		}
		
		public function intruccionesGuiaEmpresarial(){
			#instrucciones para guias de ventanilla
			$this->procesoGuiasE = array();
			#0
			$this->procesoGuiasE[] = "delete from guiasempresariales where id = '".$this->guia."'";
			#1
			$this->procesoGuiasE[] = "update evaluacionmercancia set estado = 'GUARDADO' 
									  where folio = ".$this->evaluacion." and sucursal = ".$this->sucursal."";
			#2
			$this->procesoGuiasE[] = "delete from guias_valorotros where guia = '".$this->guia."'";
			#3
			$this->procesoGuiasE[] = "delete from historialmovimientos where folio LIKE '".$this->guia."%'";
			#4
			$this->procesoGuiasE[] = "delete from guia_rastreo where noguia = '".$this->guia."'";
			#5
			$this->procesoGuiasE[] = "UPDATE asignacionbolsasdetalle SET utilizado = 1 
									WHERE sucursal = '".$this->sucursal."' limit ".(($this->bolsaempaque=="")?"0":$this->bolsaempaque)."";
			#6
			$this->procesoGuiasE[] = "delete from pagoguias where guia = '".$this->guia."'";
			#7
			$this->procesoGuiasE[] = "delete from reportes_ventas where folio = '".$this->guia."'";
			#8
			$this->procesoGuiasE[] = "delete from guiasempresariales_detalle where id = '".$this->guia."'";
			#9
			$this->procesoGuiasE[] = "delete from guiasempresariales_unidades where idguia = '".$this->guia."'";
			#10
			$this->procesoGuiasE[] = "INSERT INTO seguimiento_guias 
									SET guia = ".$this->evaluacion.", ubicacion = ".$this->sucursal.",
									estado = 'EVALUACION', unidad=null, fecha=CURRENT_DATE, hora = CURRENT_TIME,
									usuario = ".$this->idusuario;
			#11
			$this->procesoGuiasE[] = "delete from seguimiento_guias where guia = '".$this->guia."'";
		}
		
		public function rollBackGuiaventanilla($indice, $consulta, $conexion){
			#se envia el correo con el error antes de hacer el rollback
			$consulta .= "\r\n\r\n Mysql:\r\n".mysql_error($conexion);
			$datos = $this->obtenerProcesos($conexion);
			$consulta .= "\r\n\r\n Procesos:\r\n".$datos;
			$this->enviaErrorBase($consulta);
			
			$cnx = new Conectar("webpmm");
			$l = $cnx->iniciar();
			
			#verificar las consultas con el indice del error para hacer las consultas correctas
			for($i=count($this->procesoGuiasV); $i>=0; $i--){
				if($indice>$i){
					$s = $this->procesoGuiasV[$i];
					mysql_query($s,$l) or $this->errorInterno($s."\r\n\r\n Mysql:\r\n".mysql_error($l), $this->errores['xml']);
					#en caso de haber un error en el rollback se usa la funcion errorInterno
				}
			}
			die($this->errores['xml']);
		}
		
		public function rollBackGuiaempresarial($indice, $consulta, $conexion){
			#se envia el correo con el error antes de hacer el rollback
			$consulta .= "\r\n\r\n Mysql:\r\n".mysql_error($conexion);
			$datos = $this->obtenerProcesos($conexion);
			$consulta .= "\r\n\r\n Procesos:\r\n".$datos;
			$this->enviaErrorBase($consulta);
			
			$cnx = new Conectar("webpmm");
			$l = $cnx->iniciar();
			
			#verificar las consultas con el indice del error para hacer las consultas correctas
			$ejecutadas = "";
			for($i=count($this->procesoGuiasE); $i>=0; $i--){
				if($indice>$i){
					$s = $this->procesoGuiasE[$i];
					mysql_query($s,$l) or 
					$this->errorInterno($s."\r\n\r\n $ejecutadas [indice $indice][i => $i] \r\n\r\n Mysql:\r\n".mysql_error($l), $this->errores['xml']);
					#en caso de haber un error en el rollback se usa la funcion errorInterno
				}
			}
			die($this->errores['xml']);
		}
		
		public function definicionMsgErrores($xml=null,$txt=null){
			if($xml!=null)
				$this->errores['xml'] = $xml;
			else
				$this->errores['xml'] = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"utf-8\"> 
				<datos>
					<guardado>0</guardado>
					<consulta>Problemas de conexion. Por favor oprima guardar de nuevo para reintentar. Favor de notificar al area de sistemas en caso de intentar varias veces y no poder continuar.</consulta>
				</datos>
				</xml>";
				
			if($txt!=null)
				$this->errores['txt'] = $txt;
			else
				$this->errores['txt'] = "Problemas de conexion. Por favor oprima guardar de nuevo para reintentar. Favor de notificar al area de sistemas en caso de intentar varias veces y no poder continuar.";
		}
		
		public function enviaErrorBase($e){
			#obteniendo todas las variables enviadas por post
			reset ($_POST);
			$p="";
			while (list ($clave, $val) = each ($_POST)) {
				$p.="$clave => $val\r\n";
			}
			
			#obteniendo todas las variables enviadas por get
			reset ($_GET);
			$g="";
			while (list ($clave, $val) = each ($_GET)) {
				$g.="$clave => $val\r\n";
			} 
			
			#enviando el correo
			$this->enviarEmail($e,$p,$g);
		}
		
		public function errorInterno($e,$msg){
			$this->enviarEmail($e,"","");
			die($msg);
		}
		
		public function enviarEmail($e,$p,$g){
			#se crea una cadena de envio para el contenido del correo, archivo, fecha y hora, 
			#además del usuario y la consulta que provoco el error
			$cadenaCorreo = "\r\n".$_SERVER['SERVER_NAME']."
			\r\n\r\nFecha (Hora): ".date("d-m-Y (h:i a)")." 
			\r\n\r\nUsuario: ".$_SESSION[NOMBREUSUARIO]."\r\n\r\n\r\n 
			Consulta:\r\n $e";
			
			#se valida si va a llevar datos de get O post
			if($p!=""){
				$cadenaCorreo .= "\r\n\r\n POST:\r\n $p";
			}else if($g!=""){
				$cadenaCorreo .= "\r\n\r\n GET:\r\n $g";
			}else{
				$cadenaCorreo .= "\r\n\r\n ERROR EN LA CLASE AL EJECUTAR EL ROLLBACK";
			}
			
			for($i=0; $i<count($this->correos); $i++){
				$correos = mail($this->correos[$i], "ERROR en ${_SERVER['SCRIPT_NAME']}", $cadenaCorreo,"From: pmmintranet.net\r\n");
			}
		}
		
		public function obtenerProcesos($l){
			$datos="";
			$r = mysql_query("SHOW PROCESSLIST",$l);
			
			$datos = "COMMAND|TIME|STATE|INFO\r\n";
			while($f = mysql_fetch_object($r)){
				if($f->db == 'pmm_curso'){
					$datos .= $f->Command."|".$f->Time."|".$f->State."|((".str_replace(chr(13)," ",$f->Info)."))\r\n";
				}
			}
			return $datos;
		}
	}
?>