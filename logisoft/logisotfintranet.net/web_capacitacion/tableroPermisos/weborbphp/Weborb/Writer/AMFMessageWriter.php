<?php
/*******************************************************************
 * AMFMessageWriter.php
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


class AMFMessageWriter
    implements ITypeWriter
{

    public function __construct()
    {
    }

    public function isReferenceableType()
    {
        return false;
    }

    public function write(&$obj, IProtocolFormatter $writer)
    {
        $writer->beginWriteMessage($obj);
        $writer->writeMessageVersion($obj->getVersion());
        $headers = $obj->getResponseHeaders();
        $bodies = $obj->getResponseBodies();
		
        if(LOGGING)
        {
	        Log::log(LoggingConstants::DEBUG, "got headers " . count($headers));
	        Log::log(LoggingConstants::DEBUG, "got bodies " . count($bodies));
	        Log::log(LoggingConstants::DEBUG, "message version: " . $obj->getVersion());
        }
        
        $headerCount = count($headers);
        $writer->directWriteShort($headerCount);

        for ($index = 0; $index <$headerCount; $index ++)
        {
            MessageWriter::writeObject($headers[$index], $writer);
        }

        $bodyCount = count($bodies);
        $writer->directWriteShort($bodyCount);

        for ($index = 0; $index <$bodyCount; $index ++)
        {
            MessageWriter::writeObject($bodies[$index], $writer);
        }

        $writer->endWriteMessage();
    }

}

?>
