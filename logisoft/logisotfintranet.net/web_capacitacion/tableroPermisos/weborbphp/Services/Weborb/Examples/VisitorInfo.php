<?php
	class VisitorInfo
	{
        public /*int*/ $totalHits = 0;
        public /*Hashtable*/ $browserTypes = array();
        
        public /*Hashtable*/function getVisitorsInfo()
        {
        	if(Cache::get("VisitorInfo") != null)
        	{
        		$visitorInfo = Cache::get("VisitorInfo");
        		$this->totalHits = $visitorInfo->totalHits;
        		$this->browserTypes = $visitorInfo->browserTypes;
        	}
        	
            $this->totalHits++;

            $userAgent = $_SERVER['HTTP_USER_AGENT'];

            if( !array_key_exists($userAgent, $this->browserTypes ) )
                $this->browserTypes[ $userAgent ] = 0;

            /*int*/ $browserTypeHits = $this->browserTypes[ $userAgent ];
            $this->browserTypes[ $userAgent ] = $browserTypeHits + 1;

           /* Hashtable*/ $result = array();            
            $result[ "totalHits" ] = $this->totalHits;
            
            foreach( $this->browserTypes as $key => $value )
                $result[ $key ] = $value;
                
			Cache::put("VisitorInfo", $this);
			
            return $result;
        }
       
	}
?>