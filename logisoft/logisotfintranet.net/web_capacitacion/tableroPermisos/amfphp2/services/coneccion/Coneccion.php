<?
	class Coneccion{
		var $host = "localhost";
		var $user = "pmm";
		var $pass = "guhAf2eh";
		var $base = "pmm_curso";
		var $l;
		
		public function Coneccion(){
			$this->l = mysql_connect($this->host, $this->user, $this->pass);
			mysql_select_db($this->base,$this->l);
		}
		
		public function consultar($s){
			$r = mysql_query($s,$this->l) or die($s);
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$arre[] = $f;
				}
				return $arre;
			}else{
				return 0;
			}
		}
	}
?>