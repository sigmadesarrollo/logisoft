<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");

require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/model/PDFMetadata.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/PDFUtil.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/DataUtils.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/lib/Font.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/lib/PDF.php';

abstract class Component
{
	public /*String*/ $id = '';
	
	public /*boolean*/ $onEachPage = false;
	
    public /*int*/ $top = -1;

    public /*int*/ $left = -1;

    public /*int*/ $right = -1;

    public /*int*/ $bottom = -1;
	
	public /*int*/ $height = 0;
	
	public /*int*/ $width = 0;
	
	public /*int*/ $x = 0;
	
	public /*int*/ $y = 0;
	
	
	public /*int*/ $fontSize = 12;
	
	public /*int*/ $strokeColor = 0x000000;
	
	public /*int*/ $fontColor = 0x000000;
	
	public /*String*/ $fontFamily = "Helvetica";
	
	public /*String*/ $fontStyle = "normal";
	
	public /*String*/ $fontWeight = "normal";
	
	//protected
	protected static /*float*/ $baseFontSize = 12;
	
	public /*Number*/function getEndX()
	{
		return $this->x + $this->width;
	}

	public /*Number*/function getEndY()
	{
		return $this->y + $this->height;
	}
	
	public /*float*/function getPageX()
	{
		return $this->x;
	}
	
	public /*float*/function getPageY()
	{
		return $this->y;
	}
	
	protected /*FPDF*/ $componentPdfWriter = null;
	
	protected /*Document*/ $document = null;
	
	public function initComponent(Document $pdfDocument, PDF $pdfWriter,
            /*int*/ $baseX, /*int*/ $baseY,
            /*int*/ $marginTop, /*int*/ $marginLeft, /*int*/ $marginRight, /*int*/ $marginBottom,
            /*int*/ $parentWidth, /*int*/ $parentHeight) 
    {
        //-------------------------------base options-------------------------------
		Log::log(LoggingConstants::MYDEBUG, "INIT COMPONENT: " . get_class($this) . " : " . $this->id);
        if ($pdfDocument != null)
        	$this->document = $pdfDocument;
        	
        if ($pdfWriter != null)
            $this->componentPdfWriter = $pdfWriter;

        //-------------------------------top, left, right, bottom-------------------------------
        if ($this->top != -1)
            $this->y = $this->top;// +marginTop + baseY;


        if ($this->left != -1)
            $this->x = $this->left;// +marginLeft + baseX;


        if ($this->right != -1)
        {
            if ($this->left != -1)
                $this->width = $parentWidth - $this->x - $this->right;// - marginLeft - marginRight;
            else
                $this->x = $parentWidth - $this->right - $this->width;// - marginRight;
        }

        if ($this->bottom != -1)
        {
            if ($this->top != -1)
                $this->height = $parentHeight - $this->y - $this->bottom;// - marginTop - marginBottom;
            else
                $this->y = $parentHeight - $this->bottom - $this->height;// -marginBottom;
        }

        //-------------------------------base coords-------------------------------
        if ($this->baseX != 0)
            $this->x += $this->baseX;

        if ($this->baseY != 0)
            $this->y += $this->baseY;

        //-------------------------------margins-------------------------------
        if ($marginTop != 0)
            $this->y += $marginTop;

        if ($marginLeft != 0)
        
            $this->x += $marginLeft;

        if ($this->right != -1)
        {
            if ($this->left != -1)
                $this->width -= $marginLeft + $marginRight;
            else
                $this->x -= $marginRight;
        }

        if ($this->bottom != -1)
        {
            if ($this->top != -1)
                $this->height -= $marginTop + $marginBottom;
            else
                $this->y -= $marginBottom;
        }
            

        //-------------------------------Meatadata-------------------------------
        if (($this->id != null) && ($this->id != ""))
        {
            /*PDFMetadata*/ $metadata = $pdfDocument->metadata;

            if ($metadata != null)
            {
                /*Property[]*/ $properties = $metadata->getPropertiesByTarget($this->id);
                if (count($properties) != 0)
                {
                    foreach ($properties as $property)
                    {
                        if ($property->name == Property::$SHOW_ON_ALL_PAGES)
                        {
                            $this->onEachPage = true;
                        }
                        else if ($property->name == Property::$EXTEND_TO_PAGEBOTTOM)
                        {
                            $this->height = $parentHeight - $this->y - $marginBottom;
                            if ($this->bottom != -1)
                            {
                                $this->height -= $this->bottom;
                            }
                        }
                        else
                        {
                        	if (($property->value == null) || ($property->value == "")) $property->value = true;
                            DataUtils::setValue($this, $property->name, $property->value);
                        }
                    }
                }
            }
        }
	}
	
	public function write()
	{
		//throw new DocumentException("Override function 'write'");
		Log::log(LoggingConstants::MYDEBUG, "WRITE COMPONENT: " . get_class($this) . " : " . $this->id);	
	}
	
	public function getFont($newFontColor = -1, $newFontWeight = "", $newFontStyle = "")
	{
		$result = new Font();
		$result->setFontSize($this->fontSize);
		$result->setFontFamily($this->fontFamily);
		$result->setFontStyle($newFontWeight !== "" ? $newFontWeight : $this->fontWeight, 
							  $newFontStyle !== "" ? $newFontStyle : $this->fontStyle);
	    $result->setFontColor($newFontColor !== -1 ? $newFontColor : $this->fontColor);
	    return $result;
	}
	
	public function setFont($font)
	{
		$this->fontSize = $font->getFontSize();
		$this->fontFamily = $font->getFontFamily();
	 	$this->fontWeight = $font->fontWeight();
	 	$this->fontStyle = $font->fontStyle();
	 	$this->fontColor = $font->getFontColor();
	}
}
?>