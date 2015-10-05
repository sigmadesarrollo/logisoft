<?php
/*
Barcode Render Class for PHP using the GD graphics library 
Copyright (C) 2001  Karim Mribti
								
   Version  0.0.7a  2001-04-01  
								
This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.
																  
This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.
											   
You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
																		 
Copy of GNU Lesser General Public License at: http://www.gnu.org/copyleft/lesser.txt
													 
Source code home page: http://www.mribti.com/barcode/
Contact author at: barcode@mribti.com
*/
  
  define (__TRACE_ENABLED__, false);
  define (__DEBUG_ENABLED__, false);
  
  require("barcode.php"); 
  require("c128aobject.php");
  require("c39object.php");
  switch ($_GET[type])
  {
  	case "C39":
			  $obj = new C39Object($_GET[width], $_GET[height], $_GET[style], $_GET[code]);
			  break;
    case "C128A":
			  $obj = new C128AObject($_GET[width], $_GET[height], $_GET[style], $_GET[code]);
			  break;
  }
  //$obj = new C128AObject($_GET[width], $_GET[height], $_GET[style], $_GET[code]);
   
  if ($obj) {
      $obj->SetFont($_GET[font]);   
      $obj->DrawObject($_GET[xres]);
  	  $obj->FlushObject();
  	  $obj->DestroyObject();
  	  unset($obj);  # clean 
  }
?>