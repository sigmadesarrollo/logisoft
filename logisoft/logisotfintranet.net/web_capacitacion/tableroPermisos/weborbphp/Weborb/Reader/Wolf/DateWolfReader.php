<?php

	class DateWolfReader implements IXMLTypeReader
	{
        public /*IAdaptingType*/function read(DOMNode $element, ParseContext $parseContext)
        {
            /*double*/ $ticks = trim($element->textContent);
//            /*DateTime*/ $oldDate = new DateTime( 1970, 1, 1 );
//            DateTime correctDate = oldDate.AddMilliseconds( ticks );
            return new ORBDateTime($ticks);
        }
        
    }

?>