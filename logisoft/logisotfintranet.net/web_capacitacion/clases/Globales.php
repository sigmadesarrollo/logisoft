<?

	class Globales{

		var $host			= "";

		var $usuario 		= "";

		var $password 		= "";

		function Globales(){

			$this->host			= "mysql.hostinger.mx";

			$this->usuario 		= "u356875594_pmm";

			$this->password 	= "gqx64p9n";

		}

		

		public function getUsuario(){

			return $this->usuario;

		}

		public function getPassword(){

			return $this->password;

		}

		public function getHost(){

			return $this->host;

		}

	}

?>