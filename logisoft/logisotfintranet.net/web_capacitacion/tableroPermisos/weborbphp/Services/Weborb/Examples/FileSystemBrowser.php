﻿<?php
/*******************************************************************
 * FileSystemBrowser.php
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
require_once("FolderItem.php");
require_once("FileItem.php");
class FileSystemBrowser
{
    public function getRoot()
    {
        return $this->getDirectory(WebOrb);
    }

    public function getDirectory($fullPath)
    {
        $rootInfo = array();
        $scandir = scandir($fullPath);
		foreach ($scandir as $entry)
        {
	       	if($entry != '.' && $entry != '..')
			{				
	        	if(is_dir($fullPath.$entry))
				{
					$folder = new FolderItem();
					$folder->getAtribute($fullPath, $entry);
					$rootInfo[] = $folder;	            	 
				}
			}
        }

        foreach ($scandir as $entry)
        {
			if(is_file($fullPath.$entry))
			{
				$file = new FileItem();
				$file->getFileSize($fullPath.$entry);
				$file->getAtribute($fullPath, $entry);
				$rootInfo[] = $file;	            	 
			}
        }        

        return $rootInfo;
    }
}
?>