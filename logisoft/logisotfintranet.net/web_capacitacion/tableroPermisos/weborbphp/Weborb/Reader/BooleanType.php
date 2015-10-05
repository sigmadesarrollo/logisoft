<?php
/*******************************************************************
 * BooleanType.php
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


class BooleanType
    implements IAdaptingType
{

    private $m_boolean;

    public function __construct($boolean)
    {
        $this->m_boolean = $boolean;
    }

    public function getDefaultType()
    {
        return "boolean";
    }

    public function defaultAdapt()
    {
        return $this->m_boolean;
    }

    public function adapt($type)
    {
        if ($type instanceof ReflectionClass)
        {
            if ($type->implementsInterface("IAdaptingType"))
            {
                return $this;
            }
        }
        else if ("boolean" == $type)
        {
            return $this->m_boolean;
        }
        else if ("string" == $type)
        {
            return (string) $this->m_boolean;
        }
        else
        {
            throw new ApplicationException("unable to adapt boolean to type " . $type);
        }
    }

    public function canAdaptTo($formalArg)
    {
        if (is_string($formalArg))
        {
            return ("string" == $formalArg)
                || ("boolean" == $formalArg);
        }
        else if ($formalArg instanceof ReflectionClass)
        {
            return $formalArg->implementsInterface("IAdaptingType");
        }
        return FALSE;
    }

}

?>
