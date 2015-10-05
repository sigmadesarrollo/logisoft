<?php
#   Un simple Front Controller.

  #  include 'php/feedmanager.php';

    #para el web
 #   include '../web_pruebas/Conectar.php';
   

#  supongo que esto sera un recurso del sistema.

    /*
    */
   # $enlace=conectarse('phpmyadmin');
#   $enlace=conectarse('pmm_wordpress');
#obtengo la url del emisor de feeds, esta direccion debe estar almacenada en la tabla rss_config con id=1
#    $urlRSS=getFeed_url();

 #esto es solo para pruebas
  #  if (session_id() == "") session_start();
/*
    try{
        $user=validarUsuario();
        $feed_url=validarFeed();
        $titulo= utf8_decode  ( @$_REQUEST['titulo']) ;
        $postid=utf8_decode  ( @$_REQUEST['postid']);
    }catch(Exception $e){
        echo "<br />error: ".$e;
        return; #FINALIZA
    }
*/
    

    switch (@$_REQUEST['action']) {
        case 'isconected':            
            $respuesta=array();
            $respuesta['conectado']=false;
            $respuesta['success']=true;
            echo json_encode($respuesta);
            break;
        case 'marcar':
            $respuesta=array();
            $respuesta['marcado']=true;
            $respuesta['success']=true;
            echo json_encode($respuesta);
            break;
    }

    function validarUsuario(){
        #si el usuario es valido devuelve el id del usuario
        #si no es valido aki termina todo
        //$_SESSION['USUARIOID'] = 3;
        #
        if ($_SESSION['IDUSUARIO']==""){
            $usuario=0;
        }else{
            $usuario=$_SESSION['IDUSUARIO'];
        }
        
        return $usuario;
        //return $_SESSION['IDUSUARIO'];
    }
    
   
?>
