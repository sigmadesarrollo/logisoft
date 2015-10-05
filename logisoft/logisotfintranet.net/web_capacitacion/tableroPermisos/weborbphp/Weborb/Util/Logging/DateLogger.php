<?php
/*******************************************************************
 * AbstractLogger.php
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

class DateLogger extends AbstractLogger
{
	public $initializedDate;

	public function __construct()
	{
		$this->initializedDate = getdate();
	}

	protected function getFileName()
	{
		return $this->initializedDate['year']."-".$this->initializedDate['mon']."-".$this->initializedDate['mday'].".log";
	}

	protected function checkPolicy()
	{
		if($this->sameDay())
			return;

		$this->closeFile();
	}

	private function sameDay()
	{
		$now = getDate();

		return 	$now["year"] == $this->initializedDate["year"]
				 && $now["month"] == $this->initializedDate["month"]
				 && $now["mday"] == $this->initializedDate["mday"];
	}
}
?>