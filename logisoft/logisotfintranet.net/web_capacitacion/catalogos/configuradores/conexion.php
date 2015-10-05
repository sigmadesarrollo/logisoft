<?php 

class conexion{

  var $conect;

     function conexion(){

	 }

	 

	 function getCon(){

	 return $this->conect;

	 }

	 

	 function conectar() {

	     if(!($con=@mysql_connect("DBSERVER","root","root")))

		 {

		     echo"Error al conectar a la base de datos";	

			 exit();

	      }

		  if (!@mysql_select_db("pmm_dbpruebas",$con)) {

		   echo "error al seleccionar la base de datos";  

		   exit();

		  }

	       $this->conect=$con;

		   return true;	

	 }

}



?>

