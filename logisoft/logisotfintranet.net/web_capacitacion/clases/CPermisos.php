<?
	class CPermiso{
		var $c;
		function CPermiso(){
			if($this->c=@mysql_connect("mysql.hostinger.mx","u356875594_pmm","gqx64p9n")){
				if (!mysql_select_db("u356875594_pmm", $this->c)){
					exit();
				}
			}else{
				exit();
			}	
		}
		
		public function verificarPermiso($permiso,$empleado){
			$s = "SELECT idempleado
			FROM permisos_empleadospermisos
			WHERE idpermiso IN($permiso) AND idempleado = $empleado";
			$r = mysql_query($s,$this->c) or die($s);
			if(mysql_num_rows($r)<1){
				return utf8_decode("try{ alerta3('Usted no tiene los permisos para ejecutar esta acción','¡Atención!');	return false; }catch(e){ try{ mens.show('A','Usted no tiene los permisos ejecutar esta acción','¡Atención!'); return false; }catch(e){ alert('Usted no tiene los permisos para ejecutar esta acción'); return false; } }");
			}
		}
		
		public function checarPermiso($permiso,$empleado){
			$s = "SELECT idempleado
			FROM permisos_empleadospermisos
			WHERE idpermiso IN($permiso) AND idempleado = $empleado";
			$r = mysql_query($s,$this->c) or die($s);
			if(mysql_num_rows($r)<1){
				return "false";
			}else{
				return "true";
			}
		}
	}
?>