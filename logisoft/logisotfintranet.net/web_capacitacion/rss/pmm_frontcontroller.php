<?php
//include 'php/sessionmanager.php';
include 'php/rssmanager.php';
include '../Conectar.php';
$enlace=conectarse('pmm_dbpruebas');
    switch (@$_REQUEST['action']) {
        case 'isloged':    

            $respuesta=array();
            $respuesta['isloged']=SessionManager::isloged($_REQUEST['usuarioip']);
            $respuesta['success']=true;
            echo json_encode($respuesta);
            break;
        case 'marcar':
            $respuesta=array();
            try{                
                $usuario=$_REQUEST['usuarioid'];                
                $post=new PostLeido($usuario,$_REQUEST['titulo'],$_REQUEST['postid']);
                RssManager::marcar($post);
                $respuesta['message']='post marcado';
                $respuesta['success']="".true."";
            }catch(Exception $e){
                $respuesta['success']="".false."";
                $respuesta['message']=$e->getMessage();
            }                               
            echo json_encode($respuesta);
            break;
        case 'login':
            session_register('USUARIOID');
			$_SESSION['USUARIOID']=$_REQUEST['usuarioid'];
            session_register('isloged');
            $_SESSION['isloged']=true;

            $respuesta=array();
            $respuesta['isloged']=true;
            $respuesta['success']=true;
            echo json_encode($respuesta);
            break;
		case 'escribir':
		
			session_register($_REQUEST['cadena']);
			$_SESSION[$_REQUEST['cadena']]=$_REQUEST['valor'];
    }

    /*
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
    }*/
    


?>
