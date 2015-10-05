<?php
require_once(WebOrb . "Util/Fpdf/fpdf.php");

class PDF extends FPDF
{
	//------------------------   Bar codes   --------------------------
	var $T128;                                             // tableau des codes 128
	var $ABCset="";                                        // jeu des caract�res �ligibles au C128
	var $Aset="";                                          // Set A du jeu des caract�res �ligibles
	var $Bset="";                                          // Set B du jeu des caract�res �ligibles
	var $Cset="";                                          // Set C du jeu des caract�res �ligibles
	var $SetFrom;                                          // Convertisseur source des jeux vers le tableau
	var $SetTo;                                            // Convertisseur destination des jeux vers le tableau
	var $JStart = array("A"=>103, "B"=>104, "C"=>105);     // Caract�res de s�lection de jeu au d�but du C128
	var $JSwap = array("A"=>101, "B"=>100, "C"=>99);       // Caract�res de changement de jeu
		
	var $inited = false;
	
	protected function init() {
		if ($this->inited) return;
		$this->inited = true;
	    $this->T128[] = array(2, 1, 2, 2, 2, 2);           //0 : [ ]
	    $this->T128[] = array(2, 2, 2, 1, 2, 2);           //1 : [!]
	    $this->T128[] = array(2, 2, 2, 2, 2, 1);           //2 : ["]
	    $this->T128[] = array(1, 2, 1, 2, 2, 3);           //3 : [#]
	    $this->T128[] = array(1, 2, 1, 3, 2, 2);           //4 : [$]
	    $this->T128[] = array(1, 3, 1, 2, 2, 2);           //5 : [%]
	    $this->T128[] = array(1, 2, 2, 2, 1, 3);           //6 : [&]
	    $this->T128[] = array(1, 2, 2, 3, 1, 2);           //7 : [']
	    $this->T128[] = array(1, 3, 2, 2, 1, 2);           //8 : [(]
	    $this->T128[] = array(2, 2, 1, 2, 1, 3);           //9 : [)]
	    $this->T128[] = array(2, 2, 1, 3, 1, 2);           //10 : [*]
	    $this->T128[] = array(2, 3, 1, 2, 1, 2);           //11 : [+]
	    $this->T128[] = array(1, 1, 2, 2, 3, 2);           //12 : [,]
	    $this->T128[] = array(1, 2, 2, 1, 3, 2);           //13 : [-]
	    $this->T128[] = array(1, 2, 2, 2, 3, 1);           //14 : [.]
	    $this->T128[] = array(1, 1, 3, 2, 2, 2);           //15 : [/]
	    $this->T128[] = array(1, 2, 3, 1, 2, 2);           //16 : [0]
	    $this->T128[] = array(1, 2, 3, 2, 2, 1);           //17 : [1]
	    $this->T128[] = array(2, 2, 3, 2, 1, 1);           //18 : [2]
	    $this->T128[] = array(2, 2, 1, 1, 3, 2);           //19 : [3]
	    $this->T128[] = array(2, 2, 1, 2, 3, 1);           //20 : [4]
	    $this->T128[] = array(2, 1, 3, 2, 1, 2);           //21 : [5]
	    $this->T128[] = array(2, 2, 3, 1, 1, 2);           //22 : [6]
	    $this->T128[] = array(3, 1, 2, 1, 3, 1);           //23 : [7]
	    $this->T128[] = array(3, 1, 1, 2, 2, 2);           //24 : [8]
	    $this->T128[] = array(3, 2, 1, 1, 2, 2);           //25 : [9]
	    $this->T128[] = array(3, 2, 1, 2, 2, 1);           //26 : [:]
	    $this->T128[] = array(3, 1, 2, 2, 1, 2);           //27 : [;]
	    $this->T128[] = array(3, 2, 2, 1, 1, 2);           //28 : [<]
	    $this->T128[] = array(3, 2, 2, 2, 1, 1);           //29 : [=]
	    $this->T128[] = array(2, 1, 2, 1, 2, 3);           //30 : [>]
	    $this->T128[] = array(2, 1, 2, 3, 2, 1);           //31 : [?]
	    $this->T128[] = array(2, 3, 2, 1, 2, 1);           //32 : [@]
	    $this->T128[] = array(1, 1, 1, 3, 2, 3);           //33 : [A]
	    $this->T128[] = array(1, 3, 1, 1, 2, 3);           //34 : [B]
	    $this->T128[] = array(1, 3, 1, 3, 2, 1);           //35 : [C]
	    $this->T128[] = array(1, 1, 2, 3, 1, 3);           //36 : [D]
	    $this->T128[] = array(1, 3, 2, 1, 1, 3);           //37 : [E]
	    $this->T128[] = array(1, 3, 2, 3, 1, 1);           //38 : [F]
	    $this->T128[] = array(2, 1, 1, 3, 1, 3);           //39 : [G]
	    $this->T128[] = array(2, 3, 1, 1, 1, 3);           //40 : [H]
	    $this->T128[] = array(2, 3, 1, 3, 1, 1);           //41 : [I]
	    $this->T128[] = array(1, 1, 2, 1, 3, 3);           //42 : [J]
	    $this->T128[] = array(1, 1, 2, 3, 3, 1);           //43 : [K]
	    $this->T128[] = array(1, 3, 2, 1, 3, 1);           //44 : [L]
	    $this->T128[] = array(1, 1, 3, 1, 2, 3);           //45 : [M]
	    $this->T128[] = array(1, 1, 3, 3, 2, 1);           //46 : [N]
	    $this->T128[] = array(1, 3, 3, 1, 2, 1);           //47 : [O]
	    $this->T128[] = array(3, 1, 3, 1, 2, 1);           //48 : [P]
	    $this->T128[] = array(2, 1, 1, 3, 3, 1);           //49 : [Q]
	    $this->T128[] = array(2, 3, 1, 1, 3, 1);           //50 : [R]
	    $this->T128[] = array(2, 1, 3, 1, 1, 3);           //51 : [S]
	    $this->T128[] = array(2, 1, 3, 3, 1, 1);           //52 : [T]
	    $this->T128[] = array(2, 1, 3, 1, 3, 1);           //53 : [U]
	    $this->T128[] = array(3, 1, 1, 1, 2, 3);           //54 : [V]
	    $this->T128[] = array(3, 1, 1, 3, 2, 1);           //55 : [W]
	    $this->T128[] = array(3, 3, 1, 1, 2, 1);           //56 : [X]
	    $this->T128[] = array(3, 1, 2, 1, 1, 3);           //57 : [Y]
	    $this->T128[] = array(3, 1, 2, 3, 1, 1);           //58 : [Z]
	    $this->T128[] = array(3, 3, 2, 1, 1, 1);           //59 : [[]
	    $this->T128[] = array(3, 1, 4, 1, 1, 1);           //60 : [\]
	    $this->T128[] = array(2, 2, 1, 4, 1, 1);           //61 : []]
	    $this->T128[] = array(4, 3, 1, 1, 1, 1);           //62 : [^]
	    $this->T128[] = array(1, 1, 1, 2, 2, 4);           //63 : [_]
	    $this->T128[] = array(1, 1, 1, 4, 2, 2);           //64 : [`]
	    $this->T128[] = array(1, 2, 1, 1, 2, 4);           //65 : [a]
	    $this->T128[] = array(1, 2, 1, 4, 2, 1);           //66 : [b]
	    $this->T128[] = array(1, 4, 1, 1, 2, 2);           //67 : [c]
	    $this->T128[] = array(1, 4, 1, 2, 2, 1);           //68 : [d]
	    $this->T128[] = array(1, 1, 2, 2, 1, 4);           //69 : [e]
	    $this->T128[] = array(1, 1, 2, 4, 1, 2);           //70 : [f]
	    $this->T128[] = array(1, 2, 2, 1, 1, 4);           //71 : [g]
	    $this->T128[] = array(1, 2, 2, 4, 1, 1);           //72 : [h]
	    $this->T128[] = array(1, 4, 2, 1, 1, 2);           //73 : [i]
	    $this->T128[] = array(1, 4, 2, 2, 1, 1);           //74 : [j]
	    $this->T128[] = array(2, 4, 1, 2, 1, 1);           //75 : [k]
	    $this->T128[] = array(2, 2, 1, 1, 1, 4);           //76 : [l]
	    $this->T128[] = array(4, 1, 3, 1, 1, 1);           //77 : [m]
	    $this->T128[] = array(2, 4, 1, 1, 1, 2);           //78 : [n]
	    $this->T128[] = array(1, 3, 4, 1, 1, 1);           //79 : [o]
	    $this->T128[] = array(1, 1, 1, 2, 4, 2);           //80 : [p]
	    $this->T128[] = array(1, 2, 1, 1, 4, 2);           //81 : [q]
	    $this->T128[] = array(1, 2, 1, 2, 4, 1);           //82 : [r]
	    $this->T128[] = array(1, 1, 4, 2, 1, 2);           //83 : [s]
	    $this->T128[] = array(1, 2, 4, 1, 1, 2);           //84 : [t]
	    $this->T128[] = array(1, 2, 4, 2, 1, 1);           //85 : [u]
	    $this->T128[] = array(4, 1, 1, 2, 1, 2);           //86 : [v]
	    $this->T128[] = array(4, 2, 1, 1, 1, 2);           //87 : [w]
	    $this->T128[] = array(4, 2, 1, 2, 1, 1);           //88 : [x]
	    $this->T128[] = array(2, 1, 2, 1, 4, 1);           //89 : [y]
	    $this->T128[] = array(2, 1, 4, 1, 2, 1);           //90 : [z]
	    $this->T128[] = array(4, 1, 2, 1, 2, 1);           //91 : [{]
	    $this->T128[] = array(1, 1, 1, 1, 4, 3);           //92 : [|]
	    $this->T128[] = array(1, 1, 1, 3, 4, 1);           //93 : [}]
	    $this->T128[] = array(1, 3, 1, 1, 4, 1);           //94 : [~]
	    $this->T128[] = array(1, 1, 4, 1, 1, 3);           //95 : [DEL]
	    $this->T128[] = array(1, 1, 4, 3, 1, 1);           //96 : [FNC3]
	    $this->T128[] = array(4, 1, 1, 1, 1, 3);           //97 : [FNC2]
	    $this->T128[] = array(4, 1, 1, 3, 1, 1);           //98 : [SHIFT]
	    $this->T128[] = array(1, 1, 3, 1, 4, 1);           //99 : [Cswap]
	    $this->T128[] = array(1, 1, 4, 1, 3, 1);           //100 : [Bswap]                
	    $this->T128[] = array(3, 1, 1, 1, 4, 1);           //101 : [Aswap]
	    $this->T128[] = array(4, 1, 1, 1, 3, 1);           //102 : [FNC1]
	    $this->T128[] = array(2, 1, 1, 4, 1, 2);           //103 : [Astart]
	    $this->T128[] = array(2, 1, 1, 2, 1, 4);           //104 : [Bstart]
	    $this->T128[] = array(2, 1, 1, 2, 3, 2);           //105 : [Cstart]
	    $this->T128[] = array(2, 3, 3, 1, 1, 1);           //106 : [STOP]
	    $this->T128[] = array(2, 1);                       //107 : [END BAR]
	
	    for ($i = 32; $i <= 95; $i++) 
	    {
	        $this->ABCset .= chr($i);
	    }
	    
	    $this->Aset = $this->ABCset;
	    $this->Bset = $this->ABCset;
	    
	    for ($i = 0; $i <= 31; $i++) 
	    {
	        $this->ABCset .= chr($i);
	        $this->Aset .= chr($i);
	    }
	    
	    for ($i = 96; $i <= 126; $i++) 
	    {
	        $this->ABCset .= chr($i);
	        $this->Bset .= chr($i);
	    }
	    
	    $this->Cset="0123456789";
	
	    for ($i=0; $i<96; $i++) 
	    {  
	        @$this->SetFrom["A"] .= chr($i);
	        @$this->SetFrom["B"] .= chr($i + 32);
	        @$this->SetTo["A"] .= chr(($i < 32) ? $i+64 : $i-32);
	        @$this->SetTo["B"] .= chr($i);
	    }
	}
	
	function BarCode($x,$y,$code,$w,$h) {
		$this->init();
	    $Aguid="";                                                                      // Cr�ation des guides de choix ABC
	    $Bguid="";
	    $Cguid="";
	    for ($i=0; $i < strlen($code); $i++) {
	        $needle=substr($code,$i,1);
	        $Aguid .= ((strpos($this->Aset,$needle)===FALSE) ? "N" : "O"); 
	        $Bguid .= ((strpos($this->Bset,$needle)===FALSE) ? "N" : "O"); 
	        $Cguid .= ((strpos($this->Cset,$needle)===FALSE) ? "N" : "O");
	    }
	
	    $SminiC = "OOOO";
	    $IminiC = 4;
	
	    $crypt = "";
	    while ($code > "") 
	    {
	     
	        $i = strpos($Cguid,$SminiC);
	        if ($i!==FALSE) 
	        {
	            $Aguid [$i] = "N";
	            $Bguid [$i] = "N";
	        }
	
	        if (substr($Cguid,0,$IminiC) == $SminiC) 
	        {
	            $crypt .= chr(($crypt > "") ? $this->JSwap["C"] : $this->JStart["C"]);
	            $made = strpos($Cguid,"N");
	            if ($made === FALSE) $made = strlen($Cguid);
	            if (fmod($made,2)==1) $made--;
	            for ($i=0; $i < $made; $i += 2) $crypt .= chr(strval(substr($code,$i,2)));
	            $jeu = "C";
	        } 
	        else 
	        {
	            $madeA = strpos($Aguid,"N");
	            if ($madeA === FALSE) $madeA = strlen($Aguid);
	            $madeB = strpos($Bguid,"N");
	            if ($madeB === FALSE) $madeB = strlen($Bguid);
	            $made = (($madeA < $madeB) ? $madeB : $madeA );
	            $jeu = (($madeA < $madeB) ? "B" : "A" );
	            $jeuguid = $jeu . "guid";
	
	            $crypt .= chr(($crypt > "") ? $this->JSwap["$jeu"] : $this->JStart["$jeu"]);
	
	            $crypt .= strtr(substr($code, 0,$made), $this->SetFrom[$jeu], $this->SetTo[$jeu]);
	
	        }
	        $code = substr($code,$made);
	        $Aguid = substr($Aguid,$made);
	        $Bguid = substr($Bguid,$made);
	        $Cguid = substr($Cguid,$made);
	    }
	
	    $check=ord($crypt[0]);
	    for ($i=0; $i<strlen($crypt); $i++) 
	    {
	        $check += (ord($crypt[$i]) * $i);
	    }
	    $check %= 103;
	
	    $crypt .= chr($check) . chr(106) . chr(107);
	
	    $i = (strlen($crypt) * 11) - 8;
	    $modul = $w/$i;
	
	    for ($i=0; $i<strlen($crypt); $i++)
	    {
	        $c = $this->T128[ord($crypt[$i])];
	        for ($j=0; $j<count($c); $j++) 
	        {
	            $this->Rect($x,$y,$c[$j]*$modul,$h,"F");
	            $x += ($c[$j++]+$c[$j])*$modul;
	        }
	    }
	}
	
	//------------------------   text rotation   --------------------------
	var $angle=0;
	
	public function Rotate($angle,$x=-1,$y=-1)
	{
	    if($x==-1)
	        $x=$this->x;
	    if($y==-1)
	        $y=$this->y;
	    if($this->angle!=0)
	        $this->_out('Q');
	    $this->angle=$angle;
	    if($angle!=0)
	    {
	        $angle*=M_PI/180;
	        $c=cos($angle);
	        $s=sin($angle);
	        $cx=$x*$this->k;
	        $cy=($this->h-$y)*$this->k;
	        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
	    }
	}
	
	//--------------------------   my parameters check   ---------------------------
	public function AddFont($fontFamily, $fontStyle = "", $file='')
	{
		if ($file !== "")
		{ 
			parent::AddFont($fontFamily, $fontStyle, $file);
			return;
		}
			
		$style = strtolower($fontStyle);
		if ($style == "ib")
			$style = "bi";
		$fontName = strtolower($fontFamily) . $style . ".php";
		
		if (file_exists(WebOrb . "Util/Fpdf/font" . $fontName))
			parent::AddFont($fontFamily, $fontStyle);
		else
			parent::AddFont("verdana", $fontStyle);
	}
	
	public function SetFont($family, $style='', $size=0) 
	{
		$style = strtolower($style);
		if ($style == "ib")
			$style = "bi";
		$fontName = strtolower($family) . $style . ".php";
		
		if (file_exists(WebOrb . "Util/Fpdf/font" . $fontName))
			parent::SetFont($family, $style, $size);
		else
			parent::SetFont("verdana", $style, $size);
	}
	
	public function Error($msg)
	{
		$this->log("PDF ERROR: " . $msg);
		parent::Error($msg);
	}
	
	public function log($msg)
	{
		Log::log(LoggingConstants::MYDEBUG, $msg);
	}
	//-----------------------------   alpha channels   -----------------------------------------
	
	
	//Private properties
	var $tmpFiles = array(); 
	
	/*******************************************************************************
	*                                                                              *
	*                               Public methods                                 *
	*                                                                              *
	*******************************************************************************/
	function Image($file,$x,$y,$w=0,$h=0,$type='',$link='', $isMask=false, $maskImg=0)
	{
	    //Put an image on the page
	    if(!isset($this->images[$file]))
	    {
	        //First use of image, get info
	        if($type=='')
	        {
	            $pos=strrpos($file,'.');
	            if(!$pos)
	                $this->Error('Image file has no extension and no type was specified: '.$file);
	            $type=substr($file,$pos+1);
	        }
	        $type=strtolower($type);
	        $mqr=get_magic_quotes_runtime();
	        
	        set_magic_quotes_runtime(0);
	        
	        if($type=='jpg' || $type=='jpeg')
	            $info=$this->_parsejpg($file);
	        elseif($type=='png'){
	            $info=$this->_parsepng($file);
	            if($info=='alpha')
	                return $this->ImagePngWithAlpha($file,$x,$y,$w,$h,$link);
	        }
	        else
	        {
	            //Allow for additional formats
	            $mtd='_parse'.$type;
	            if(!method_exists($this,$mtd))
	                $this->Error('Unsupported image type: '.$type);
	            $info=$this->$mtd($file);
	        }
	        set_magic_quotes_runtime($mqr);
	        
	        if($isMask){
	            if(in_array($file,$this->tmpFiles))
	                $info['cs']='DeviceGray'; //hack necessary as GD can't produce gray scale images
	            if($info['cs']!='DeviceGray')
	                $this->Error('Mask must be a gray scale image');
	            if($this->PDFVersion<'1.4')
	                $this->PDFVersion='1.4';
	        }
	        $info['i']=count($this->images)+1;
	        if($maskImg>0)
	            $info['masked'] = $maskImg;
	        $this->images[$file]=$info;
	    }
	    else
	        $info=$this->images[$file];
	    //Automatic width and height calculation if needed
	    if($w==0 && $h==0)
	    {
	        //Put image at 72 dpi
	        $w=$info['w']/$this->k;
	        $h=$info['h']/$this->k;
	    }
	    if($w==0)
	        $w=$h*$info['w']/$info['h'];
	    if($h==0)
	        $h=$w*$info['h']/$info['w'];
	        
	    if(!$isMask)
	        $this->_out(sprintf('q %.2f 0 0 %.2f %.2f %.2f cm /I%d Do Q',$w*$this->k,$h*$this->k,$x*$this->k,($this->h-($y+$h))*$this->k,$info['i']));
	    if($link)
	        $this->Link($x,$y,$w,$h,$link);
	        
//	    return $info['i'];
	}
	
	// needs GD 2.x extension
	// pixel-wise operation, not very fast
	function ImagePngWithAlpha($file,$x,$y,$w=0,$h=0,$link='')
	{
	    $tmp_alpha = tempnam('.', 'mska');
	    $this->tmpFiles[] = $tmp_alpha;
	    $tmp_plain = tempnam('.', 'mskp');
	    $this->tmpFiles[] = $tmp_plain;
	
	    list($wpx, $hpx) = getimagesize($file);
	    $img = imagecreatefrompng($file);
	    $alpha_img = imagecreate( $wpx, $hpx );
	
	    // generate gray scale pallete
	    for($c=0;$c<256;$c++)
	        ImageColorAllocate($alpha_img, $c, $c, $c);
	
	    // extract alpha channel
	    $xpx=0;
	    while ($xpx<$wpx){
	        $ypx = 0;
	        while ($ypx<$hpx){
	            $color_index = imagecolorat($img, $xpx, $ypx);
	            $col = imagecolorsforindex($img, $color_index);
	            imagesetpixel($alpha_img, $xpx, $ypx, $this->_gamma( (127-$col['alpha'])*255/127) );
	            ++$ypx;
	        }
	        ++$xpx;
	    }
	
	    imagepng($alpha_img, $tmp_alpha);
	    imagedestroy($alpha_img);
	
	    // extract image without alpha channel
	    $plain_img = imagecreatetruecolor ( $wpx, $hpx );
	    imagecopy($plain_img, $img, 0, 0, 0, 0, $wpx, $hpx );
	    imagepng($plain_img, $tmp_plain);
	    imagedestroy($plain_img);
	    
	    //first embed mask image (w, h, x, will be ignored)
	    $maskImg = $this->Image($tmp_alpha, 0,0,0,0, 'PNG', '', true); 
	    
	    //embed image, masked with previously embedded mask
	    $this->Image($tmp_plain,$x,$y,$w,$h,'PNG',$link, false, $maskImg);
	}
	
	function Close()
	{
	    parent::Close();
	    // clean up tmp files
	    foreach($this->tmpFiles as $tmp)
	        @unlink($tmp);
	}
	
	/*******************************************************************************
	*                                                                              *
	*                               Private methods                                *
	*                                                                              *
	*******************************************************************************/
	function _putimages()
	{
	    $filter=($this->compress) ? '/Filter /FlateDecode ' : '';
	    reset($this->images);
	    while(list($file,$info)=each($this->images))
	    {
	        $this->_newobj();
	        $this->images[$file]['n']=$this->n;
	        $this->_out('<</Type /XObject');
	        $this->_out('/Subtype /Image');
	        $this->_out('/Width '.$info['w']);
	        $this->_out('/Height '.$info['h']);
	
	        if(isset($info['masked']))
	            $this->_out('/SMask '.($this->n-1).' 0 R');
	
	        if($info['cs']=='Indexed')
	            $this->_out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal'])/3-1).' '.($this->n+1).' 0 R]');
	        else
	        {
	            $this->_out('/ColorSpace /'.$info['cs']);
	            if($info['cs']=='DeviceCMYK')
	                $this->_out('/Decode [1 0 1 0 1 0 1 0]');
	        }
	        $this->_out('/BitsPerComponent '.$info['bpc']);
	        if(isset($info['f']))
	            $this->_out('/Filter /'.$info['f']);
	        if(isset($info['parms']))
	            $this->_out($info['parms']);
	        if(isset($info['trns']) && is_array($info['trns']))
	        {
	            $trns='';
	            for($i=0;$i<count($info['trns']);$i++)
	                $trns.=$info['trns'][$i].' '.$info['trns'][$i].' ';
	            $this->_out('/Mask ['.$trns.']');
	        }
	        $this->_out('/Length '.strlen($info['data']).'>>');
	        $this->_putstream($info['data']);
	        unset($this->images[$file]['data']);
	        $this->_out('endobj');
	        //Palette
	        if($info['cs']=='Indexed')
	        {
	            $this->_newobj();
	            $pal=($this->compress) ? gzcompress($info['pal']) : $info['pal'];
	            $this->_out('<<'.$filter.'/Length '.strlen($pal).'>>');
	            $this->_putstream($pal);
	            $this->_out('endobj');
	        }
	    }
	}
	
	// GD seems to use a different gamma, this method is used to correct it again
	function _gamma($v){
	    return pow ($v/255, 2.2) * 255;
	}
	
	// this method overwriing the original version is only needed to make the Image method support PNGs with alpha channels.
	// if you only use the ImagePngWithAlpha method for such PNGs, you can remove it from this script.
	function _parsepng($file)
	{
	    //Extract info from a PNG file
	    $f=fopen($file,'rb');
	    if(!$f)
	        $this->Error('Can\'t open image file: '.$file);
	    //Check signature
	    if(fread($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10))
	        $this->Error('Not a PNG file: '.$file);
	    //Read header chunk

	    $buf = fread($f,4);
	    if(fread($f,4)!='IHDR')
	        $this->Error('Incorrect PNG file: '.$file);
	        
	    $w=$this->_readint($f);
	    $h=$this->_readint($f);
	    $bpc=ord(fread($f,1));
	    if($bpc>8)
	        $this->Error('16-bit depth not supported: '.$file);
	    $ct=ord(fread($f,1));
	    if($ct==0)
	        $colspace='DeviceGray';
	    elseif($ct==2)
	        $colspace='DeviceRGB';
	    elseif($ct==3)
	        $colspace='Indexed';
	    else {
	        fclose($f);      // the only changes are 
	        return 'alpha';  // made in those 2 lines
	    }

	    if(ord(fread($f,1))!=0)
	        $this->Error('Unknown compression method: '.$file);
	    if(ord(fread($f,1))!=0)
	        $this->Error('Unknown filter method: '.$file);
	    if(ord(fread($f,1))!=0)
	        $this->Error('Interlacing not supported: '.$file);
	    fread($f,4);
	    $parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
	    //Scan chunks looking for palette, transparency and image data
	    $pal='';
	    $trns='';
	    $data='';

	    do
	    {
	        $n=$this->_readint($f);
	        $type=fread($f,4);
	        if($type=='PLTE')
	        {
	            //Read palette
	            $pal=fread($f,$n);
	            fread($f,4);
	        }
	        elseif($type=='tRNS')
	        {
	            //Read transparency info
	            $t=fread($f,$n);
	            if($ct==0)
	                $trns=array(ord(substr($t,1,1)));
	            elseif($ct==2)
	                $trns=array(ord(substr($t,1,1)),ord(substr($t,3,1)),ord(substr($t,5,1)));
	            else
	            {
	                $pos=strpos($t,chr(0));
	                if($pos!==false)
	                    $trns=array($pos);
	            }
	            fread($f,4);
	        }
	        elseif($type=='IDAT')
	        {
	            //Read image data block
	            $data.=fread($f,$n);
	            fread($f,4);
	        }
	        elseif($type=='IEND')
	            break;
	        else
	            fread($f,$n+4);
	    }
	    while($n);
	    if($colspace=='Indexed' && empty($pal))
	        $this->Error('Missing palette in '.$file);
	    fclose($f);
	    return array('w'=>$w,'h'=>$h,'cs'=>$colspace,'bpc'=>$bpc,'f'=>'FlateDecode','parms'=>$parms,'pal'=>$pal,'trns'=>$trns,'data'=>$data);
	}
	
	public function _readint($f)
	{
		//Read a 4-byte integer from stream
		$a = unpack('Ni',$this->_readstream($f,4));
		return $a['i'];
	}
	
	public function _readstream($f, $n)
	{
		//Read n bytes from stream
		$res='';
		while($n>0 && !feof($f))
		{
			$s=fread($f,$n);
			if($s===false)
				$this->Error('Error while reading stream');
			$n-=strlen($s);
			$res.=$s;
		}
		if($n>0)
			$this->Error('Unexpected end of stream');
		return $res;
	}	
}

?>
