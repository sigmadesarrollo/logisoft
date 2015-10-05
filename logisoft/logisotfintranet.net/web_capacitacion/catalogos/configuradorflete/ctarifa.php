<?php 
//referenciamos la clase conexion
include_once("conexion.php");

//implementamos la clase empleado
class ctarifa{
 //constructor	
 function ctarifa(){
 }	


function eliminar($columnas,$zonai,$intervalozona){
   //creamos el objeto $con a partir de la clase conexion
   $con = new conexion;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "delete from configuracion";
     $result = @mysql_query($query);

     $query = "delete from configuraciondetalles";
     $result = @mysql_query($query);
     
 
      for ($r=0;$r<=8;$r++){ 
        
       if ($r==0) {$vtarifai=0;$vtarifai2=5;}
       if ($r==1) {$vtarifai=6;$vtarifai2=10;}
       if ($r==2) {$vtarifai=11;$vtarifai2=20;} 
       if ($r==3) {$vtarifai=21;$vtarifai2=30;} 
       if ($r==4) {$vtarifai=31;$vtarifai2=40;} 
       if ($r==5) {$vtarifai=41;$vtarifai2=50;} 
       if ($r==6) {$vtarifai=51;$vtarifai2=60;} 
       if ($r==7) {$vtarifai=61;$vtarifai2=70;} 
       if ($r==8) {$vtarifai=71;$vtarifai2=999999;} 

       $vzonai=$zonai;
       $vzonai2=$zonai; 
       for ($i=1;$i<=$columnas;$i++){  

         $vzonai2=$vzonai2+$intervalozona;     

      	 $query = "insert into configuraciondetalles (columna,renglon,zoi,zof,kgi,kgf) values($i,$r,$vzonai,$vzonai2,$vtarifai,$vtarifai2)";
	     $result = @mysql_query($query);         

         $vzonai=$vzonai2+1;

       }
       
      }

	 if (!$result)
	   return false;
	 else
	   return $result;
   }
 }

 
 // consulta los empledos de la BD
 function consultar(){
   //creamos el objeto $con a partir de la clase conexion
   $con = new conexion;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select a.*,b.zonaf,b.intervalozona from configuraciondetalles a left join configuracion b on b.zonai=a.zoi  order by renglon,columna";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
 }

 // consulta los empledos de la BD
 function consultar2(){
   //creamos el objeto $con a partir de la clase conexion
   $con = new conexion;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select a.*,b.zonaf,b.intervalozona from configuraciondetalles a left join configuracion b on b.zonai=a.zoi where a.renglon=0  order by renglon,columna";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
 }






 //inserta un nuevo empleado en la base de datos
 function crear($nom,$dep,$suel){
   $con = new conexion;
   if($con->conectar()==true){
     $query = "INSERT INTO empleados (nombres, departamento, sueldo) 
	 VALUES ('$nom','$dep',$suel)";
     $result = @mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;
   }
 }


 //inserta un nueva tarifa en la base de datos
 function crear2($reglon,$columna,$kgi,$kgf,$zoi,$zof){
   $con = new conexion;
   if($con->conectar()==true){
     $query = "INSERT INTO configuraciondetalles (reglon,columna,kgi,kgf,zoi,zof) 
	 VALUES ('$reglon','$columna','$kgi','$kgf','$zoi','$zof')";
     $result = @mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;
   }
 }


 // actualizar un nuevo empleado en la base de datos
 function actualizar($cod,$nom,$dep,$suel){
   $con = new conexion;
   if($con->conectar()==true) {
     $query = "UPDATE empleados SET nombres='$nom', departamento='$dep', sueldo='$suel' 
	 WHERE idempleado=$cod";
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
       return true;
   }
 }

 // actualizar un nuevo empleado en la base de datos
 function grabar($col2,$ren2,$valor){
   $con = new conexion;
   if($con->conectar()==true) {
     $query = "UPDATE configuraciondetalles SET costo=$valor where renglon =$ren2 and columna=$col2";
	 
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
       return true;
   }
 }




 // consulta empleado por su codigo
 function consultarid($cod){
   $con = new conexion;
   if($con->conectar()==true){
     $query = "SELECT * FROM empleados WHERE idempleado=$cod";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  
 }
}
?>
