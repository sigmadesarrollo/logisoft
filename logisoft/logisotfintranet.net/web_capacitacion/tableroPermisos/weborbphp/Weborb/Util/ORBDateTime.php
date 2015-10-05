<?php
/*******************************************************************
 * ORBDateTime.php
 * Copyright (C) 2006-2007 Midnight Coders, LLC
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is WebORB Presentation Server (R) for PHP.
 * 
 * The Initial Developer of the Original Code is Midnight Coders, LLC.
 * All Rights Reserved.
 ********************************************************************/

class ORBDateTime
{
	private $monthes = array('jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'may' =>5,
		'jun' => 6, 'jul' => 7, 'aug' => 8, 'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12);
	
    private $m_milliseconds;
    private $m_stringDateTime;
    private $m_timeZone;

    public function __construct($dateTimeMs, $timeZone = null)
    {
        $this->m_timeZone = $timeZone;
        
        if(is_numeric($dateTimeMs))
        {
        	$this->m_stringDateTime = date("n/j/Y h:i:s A", round($dateTimeMs/1000));
        	$this->m_milliseconds = $dateTimeMs;
        }
        else
        {
        	$dateAr = explode(" ", $dateTimeMs);
        	$dateAr[3] = explode(":", $dateAr[3]);
        	if(substr($dateAr[3][1],3) == "AM")
        	{
        		$dateAr[3][1] = substr($dateAr[3][1],0,2);
        	}
        	else
        	{
        		$dateAr[3][0] = $dateAr[3][0]+12;
        		$dateAr[3][1] = substr($dateAr[3][1],0,2);
        	}
        	$this->m_milliseconds = mktime($dateAr[3][0],$dateAr[3][1], 0, $this->monthes[strtolower($dateAr[0])], 
        		$dateAr[1], $dateAr[2])*1000;
        	$this->m_stringDateTime = $dateTimeMs;
        }
        
    }

    public function getTotalMs()
    {
    	return $this->m_milliseconds;
    }

    public function getDateTime()
    {
    	return $this->m_stringDateTime;
    }
    
    public function getTimeZone()
    {
        return $this->m_timeZone;
    }
}

?>
