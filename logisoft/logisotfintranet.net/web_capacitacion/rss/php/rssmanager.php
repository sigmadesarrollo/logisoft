<?php

class RssManager{
    public static function marcar($post){
        global $enlace;
        try{
            $fecha=date('Y-m-d G-i-s',time());
            $query ="SELECT * FROM rss_leidos  WHERE usuario_id =$post->usuario AND post_id='$post->postid' ";
            
            if (!$result = mysql_query($query, $enlace)) throw new Exception(mysql_error());

            

            if (mysql_num_rows($result)>0)return;
            //$idsesion=session_id();
            $query = "INSERT INTO rss_leidos  (post_id,usuario_id , url,fecha,titulo)
            VALUES ('$post->postid',$post->usuario, '','$fecha','$post->titulo')";
            

            if (!$result = mysql_query($query, $enlace)) throw new Exception(mysql_error()) ;
        }catch(Exception $e){
            echo "ERROR";
            echo $e->getMessage();
        }
    }
}

class PostLeido{
    var $usuario;
    var $titulo;
    var $postid;
    public function PostLeido($usuario,$titulo,$postid){
        $errores=array();
        if (is_numeric($usuario)){
            $this->usuario=$usuario;
        }else{
            $errores[]="el usuario_id ($usuario) es incorrecto, debe ser numérico";
        }
        if ($titulo==""){
            $errores[]="debe asignar un t&iacute;tulo al post.";
        }else{
            $this->titulo=$titulo;
        }        
        if (is_numeric($postid)){
            $this->postid=$postid;
        }else{
            $errores[]="el post_id ($postid) es incorrecto, debe ser numerico";
        }
        $tamaño=sizeof($errores);

        if ($tamaño>0){
            $cadena='';
            for($i=0;$i<$tamaño;$i++){
                $cadena.=$errores[$i].' \r ';
            }
            throw new Exception( $cadena);
        }

    }

}



?>
