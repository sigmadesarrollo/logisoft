<?php
    session_set_save_handler (
    'SessionManager::open',
    'SessionManager::close',
    'SessionManager::read',
    'SessionManager::write',
    'SessionManager::destroy',
    'SessionManager::gc');

$SessionTableName= "phpsessions";
/*
function mysql_session_open($sess_path, $session_name){
    return SessionManager::open();
}
function mysql_session_close(){
    return SessionManager::close();
}
function mysql_session_read($key){
    return SessionManager::read($key);
}
function mysql_session_write($key,$val){
    return SessionManager::write($key,$val);
}
function mysql_session_destroy($key){
   return SessionManager::destroy($key);
}
function mysql_session_gc($maxlifetime){
    return SessionManager::gc($maxlifetime);
}*/

class SessionManager{
    
    public static function isloged($ip){
		global $SessionTableName;
        global $enlace;
        $query="SELECT COUNT(*) FROM ". $SessionTableName." WHERE ip = '$ip' AND data like '%isloged|b:1%'";
		
            $ipExists = mysql_result(mysql_query($query,$enlace), 0);
		return ($ipExists>0 )? true : false;
		
    }
    public function getid($ip){
        #busca en la tabla de usuarios logeados, el usuario_id  mediante su ip
        #si no lo encuentra
		global $SessionTableName;
        global $enlace;
        $query="SELECT data FROM ". $SessionTableName." WHERE ip = '$ip' AND data like '%USUARIOID%'";		
            $data = @mysql_result(mysql_query($query,$enlace), 0);
		if ($data=="" ){ 
			throw new Exception('el usuario debe iniciar una sesion para marcar el post');
		}else{
			return SessionManager::extraerid($data);
		}
    }
	
	public static function extraerid($data){	#optimo con expreciones regulares
		$datos=split(";",$data);
		
		for ($i=0;$i<sizeof($datos);$i++){
			$dato= $datos[$i];
			
			if (substr_count($dato, 'USUARIOID')>0){
				$dato=split(":",$dato);
				$val =$dato[2];
				$val-=0;				
				$tamaño=strlen($dato[2]);				
				 $val = substr ($dato[2], 1, $tamaño-2); // devuelve "bcd"				
				return $val;
			}
			
		}
		
	}
    
    public static function open(){
        # @mysql_pconnect("www.movimientobiosphera.com","bioces4r","1234asdf")or die(mysql_error());
        # mysql_select_db("biosphera_jom")or die(mysql_error());


        return true;
    }

    public static function close(){
        SessionManager::gc(18);
        return true;
    }

    public static function read($key){
        global $SessionTableName;
        global $enlace;
        $SessionID = addslashes(session_id());
        $session_data = mysql_query("SELECT Data FROM $SessionTableName
        WHERE SessionID = '$SessionID'",$enlace) or die(mysql_error());
        if (mysql_numrows($session_data) == 1) {
            return mysql_result($session_data, 0);
        } else {
            return false;
        }
    }
    private static function refresh(){

    }
    public static function write($key,$val){
        
        global $SessionTableName;
        global $enlace;

        $SessionID = addslashes(session_id());
        $val = addslashes($val);
        $query="SELECT COUNT(*) FROM $SessionTableName
            WHERE SessionID = '$SessionID'";
        $SessionExists =@ mysql_result(mysql_query($query,$enlace), 0);
        if ($SessionExists == 0) {
            $ip=$_SERVER["REMOTE_ADDR"];
            
            $query="SELECT COUNT(*) FROM $SessionTableName
                WHERE ip = '$ip'";
            $ipExists = mysql_result(mysql_query($query,$enlace), 0);

           // if ($ipExists >0) throw new Exception("solo puede iniciar una sesion a la vez") ;

            $query="INSERT INTO $SessionTableName
               (SessionID, LastActive, Data,ip)
                VALUES ('$SessionID', UNIX_TIMESTAMP(NOW()), '$val','$ip' )";
            $retval = mysql_query($query,$enlace) or die(mysql_error());
        } else {
            $query="UPDATE $SessionTableName SET
                Data = '$val', LastActive = UNIX_TIMESTAMP(NOW())
                WHERE SessionID = '$SessionID'";
            $retval = mysql_query($query,$enlace) or die(mysql_error());
            if (mysql_affected_rows() == 0) {
                error_log("unable to update session data for session $SessionID");
            }
        }
        return $retval;
    }
    
    public static function destroy($key){
         global $SessionTableName;
         global $enlace;
         $SessionID = addslashes(session_id());
         $retval = mysql_query("DELETE FROM $SessionTableName
            WHERE SessionID = '$SessionID'",$enlace) or die(mysql_error());
         return $retval;
     }

     public static function gc($maxlifetime){
        global $SessionTableName;
        global $enlace;
         $CutoffTime = time() - $maxlifetime;
         $retval = mysql_query("DELETE FROM $SessionTableName
            WHERE LastActive < $CutoffTime",$enlace) or die(mysql_error());
         return $retval;
     }
}
?>
