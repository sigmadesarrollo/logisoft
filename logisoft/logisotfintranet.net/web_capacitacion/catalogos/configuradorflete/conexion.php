<?php 


class conexion{


  var $conect;


     function conexion(){


	 }


	 


	 function getCon(){


	 return $this->conect;


	 }


	 


	 function conectar() {


	     if(!($con=@mysql_connect("localhost","pmm","gqx64p9n")))


		 {


		     echo"Error al conectar a la base de datos";	


			 exit();


	      }


		  if (!@mysql_select_db("pmm_curso",$con)) {


		   echo "error al seleccionar la base de datos";  


		   exit();


		  }


	       $this->conect=$con;


		   return true;	


	 }


}





?>


