<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");

require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/Component.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/ITemplateNodeContainer.php';

class Container extends Component implements ITemplateNodeContainer 
{

    /**
     * margin Top
     */
    public /*int*/ $marginTop = 0;

    /**
     * margin left
     */
    public /*int*/ $marginLeft = 0;

    /**
     * margin right
     */
    public /*int*/ $marginRight = 0;

    /**
     * margin bottom
     */
    public /*int*/ $marginBottom = 0;
    
	public /*Component[]*/ $children;
	
//	public function write(/*PdfWriter*/ $pdfWriter, /*int*/ $baseX = null, 
//		/*int*/ $baseY = null, /*int*/ $marginTop = null, /*int*/ $marginLeft = null, 
//		/*int*/ $marginRight = null, /*int*/ $marginBottom = null)
//	{
//		parent::write($pdfWriter, $baseX, $baseY, $marginTop, $marginLeft, 
//			$marginRight, $marginBottom);
//		foreach ($this->children as $child) 
//		{
//			$child->write($pdfWriter, $this->x, $this->y, $this->marginTop, $this->marginLeft, $this->marginRight, $this->marginBottom);
//		}
//	}

	public function write() 
	{
		parent::write();
		foreach ($this->children as $child) {
			if (($child != null) && (get_class($child) != "") && (!$child->onEachPage)) $child->write();
		}
	}
	
    public /*Component*/ function getComponentById(/*String*/ $itemId)
    {
        /*Component*/ $result = null;

        foreach ($this->children as $component)
        {
        	if (is_a($component, "Container"))
                $result = $component->getComponentById(itemId);

            if ($result == null)
                $result =
                    (($component->id != null) && ($component->id != "") && ($component->id == $itemId))
                         ? $component
                         : null;

            if ($result != null) return $result;
        }

        return null;
    }

    public /*void*/ function initComponent(/*Document*/ $pdfDocument, /*PdfWriter*/ $pdfWriter,
            /*int*/ $baseX, /*int*/ $baseY,
            /*int*/ $marginTop, /*int*/ $marginLeft, /*int*/ $marginRight, /*int*/ $marginBottom,
            /*int*/ $parentWidth, /*int*/ $parentHeight
            )
    {
        parent::initComponent($pdfDocument, $pdfWriter, $baseX, $baseY, $marginTop, $marginLeft, $marginRight, $marginBottom, $parentWidth, $parentHeight);

        foreach ($this->children as $component)
        	if (get_class($component) != "")
            $component->initComponent($pdfDocument, $pdfWriter, $this->x, $this->y, $this->marginTop, $this->marginLeft, $this->marginRight, $this->marginBottom, $this->width, $this->height);
    }	
	
	public /*String*/function getFieldName() 
	{
		return "children";
	}

	public /*Class*/function getItemClass() 
	{
		return new ReflectionClass("Component");
	}
}
?>