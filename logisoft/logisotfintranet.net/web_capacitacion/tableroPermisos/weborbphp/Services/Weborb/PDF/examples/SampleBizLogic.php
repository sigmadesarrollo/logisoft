<?php
class SampleBizLogic
{
    public /*DateTime*/ function getDate()
    {
        return  strftime("%A, %c");
    }
    
    public function echoString($str) 
    {
    	return $str;
    }
}
?>
