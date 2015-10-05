<?php
 class Property 
 {
	public static $PAGE_MARGINS = "PageMargins";
	public static $PAGE_FORMAT = "PageFormat";
	
	public static $SHOW_ON_ALL_PAGES = "showOnAllPages";
	public static $EXTEND_TO_PAGEBOTTOM = "extendToPageBottom";
	public static $ENABLE_PAGE_ROLLOVER = "contentPageRollover";
	public static $CELL_RENDERER = "cellRendererClass";
	public static $HEADER_RENDERER = "headerRendererClass";

	public static $FORM_ACTION_URL = "formActionURL";

    public static $PAGE_FORMAT_A4 = "A4";
    public static $PAGE_FORMAT_A4_ALBUM = "A4album";

    public $target;
    public $name;
    public $value = true;
}
 
?>
