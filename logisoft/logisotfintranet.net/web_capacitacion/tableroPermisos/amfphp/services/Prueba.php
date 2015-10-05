<?
	class Prueba{
		
		function Prueba(){
			return "desde php";
		}
		
		function cosas(){
			return "desde php";
		}
		
		function saludo(){
			$obj->id='0000';
			$obj->nombres='el nombre';
			$obj->ciudad='una ciudad';
			$obj->telefono='9848034';
			$obj->sexo='hombre';
			$obj->fecha_nacimiento='20/20/2010';
			$arr=array();
			$arr[]=$obj;
			return $arr;
		}
	}
?>