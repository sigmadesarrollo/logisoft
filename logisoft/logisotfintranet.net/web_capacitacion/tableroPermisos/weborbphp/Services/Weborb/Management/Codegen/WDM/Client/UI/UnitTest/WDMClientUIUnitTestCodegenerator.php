<?php
/*******************************************************************
 * WDMClientUIUnitTestCodegenerator.php
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
require_once("WDMClientUIUnitTestCreateCodegenerator.php");
require_once("WDMClientUIUnitTestUpdateCodegenerator.php");
require_once("WDMClientUIUnitTestDeleteCodegenerator.php");
require_once("WDMClientUIUnitTestFindFirstCodegenerator.php");
require_once("WDMClientUIUnitTestFindLastCodegenerator.php");

class WDMClientUIUnitTestCodegenerator extends WDMCodegenerator
{
	protected $tableName;

	public function __construct(/*string*/ $tableName)
	{
		$this->tableName = $tableName;
	}	
	
	protected function doGenerate()
	{
		$this->generatePart(new WDMClientUIUnitTestCreateCodegenerator($this->tableName));
		$this->generatePart(new WDMClientUIUnitTestUpdateCodegenerator($this->tableName));
		$this->generatePart(new WDMClientUIUnitTestDeleteCodegenerator($this->tableName));
		$this->generatePart(new WDMClientUIUnitTestFindFirstCodegenerator($this->tableName));
		$this->generatePart(new WDMClientUIUnitTestFindLastCodegenerator($this->tableName));
	}
}
?>