<?
	include("Globales.php");
	
	class Conectar{
		
		var $conexion = "";
		var $base = "";
		
		function Conectar($base){
			$this->conexion = "";
			if($base=="webpmm")
				$base = "u356875594_pmm";
			$this->base 	= $base;
		}
		
		function iniciar(){
			$gbl = new Globales();
			
			if($this->conexion=mysql_connect($gbl->getHost(),$gbl->getUsuario(),$gbl->getPassword())){
				if (!mysql_select_db($this->base, $this->conexion)){
					echo "Error seleccionando a la base de datos.";
					exit();
				}else{
					return $this->conexion;
				}
			}else{
				echo "Error conectando a la base de datos.";
				exit();
			}
		}
	}
?>