<?php
/******************************************************************************/
/* Script to add TrueType or Type1 fonts to FPDF                              */
/*                                                                            */
/* author: Y. SUGERE                                                          */
/* version: 1.0                                                               */
/* date: 2003-04-28                                                           */
/* required files: addfont.php, addfontt1.php, addfontttf.php                 */
/* other necessary software: pfm2afm, ttf2pt1, fpdf                           */
/* For more information, see readme.txt                                       */
/*                                                                            */
/* This file processes TrueType fonts                                         */
/******************************************************************************/

require('makefont.php');

function EncodingList()
{
	// list all available encodings
	$d=dir('.');
	while($f=$d->read())
	{
		if(preg_match('/([a-z0-9_-]+)\\.map$/i',$f,$res))
			$enc[]=$res[1];
	}
	$d->close();
	sort($enc);
	echo '<SELECT NAME="enc">';
	foreach($enc as $e)
		printf('<OPTION %s>%s</OPTION>',$e=='cp1252' ? 'SELECTED': '',$e);
	echo '</SELECT>';
}
function getFile() {
	if(isset($_FILES['ttf'])){
		// get font file
		$tmp=$_FILES['ttf']['tmp_name'];
		$RESULT[] = '$tmp_name: ' . $tmp;
		$ttf=$_FILES['ttf']['name'];
		$RESULT[] = '$ttf: ' . $ttf;
		$a=explode('.',$ttf);
		if(strtolower($a[1])!='ttf') 
			$RESULT[] = 'NOT TTF FILE';
			//die('File is not a .ttf');}
		if(!move_uploaded_file($tmp,$ttf))
			$RESULT[] = 'ERROR IN UPLOAD';
			//die('Error in upload');
		$fontname=$_REQUEST['fontname'];
		
		if(empty($fontname))
			$fontname=$a[0];
			
		$RESULT[] = 'FONT NAME: ' . $fontname;
		
		// AFM generation
		$RESULT[] = "ttf2pt1.exe -a " . $ttf . " " . $fontname;
		system("ttf2pt1.exe -a " . $ttf . " " . $fontname);
		// MakeFont call
		//$RESULT[] = 'MAKE FONT CALL ';
		
		//return $RESULT;
		
		MakeFont($ttf,"$fontname.afm",$_REQUEST['enc']);
		copy("$fontname.php","../$fontname.php");
		unlink("$fontname.php");
		if(file_exists("$fontname.z"))
		{
			copy("$fontname.z","../$fontname.z");
			unlink("$fontname.z");
		}
		else
			copy($ttf,"../$ttf");
		unlink("$fontname.afm");
		unlink("$fontname.t1a");
		unlink($ttf);
		echo "<script language='javascript'>alert('Font processed');\n";
		echo "window.location.href='addfont.php';</script>";
		//exit;
	}
	return $RESULT;
}
function result() {
	$RESULT = getFile($RESULT);

	echo '<TABLE width="100%">';
	echo '<tr><td>' . count($RESULT) . '</td></tr>';
	foreach ($RESULT as $item)
		echo "<tr><td>" . $item . "</td></tr>/n";
	echo '</TABLE>';
}
?>
<!doctype html public "-//W3C//DTD HTML 4.0//EN">
<html>
<head>
	<title>Font upload</title>
</head>
<body>
<form action="addfontttf.php" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="5" cellpadding="5" width="300">
	<tr>
		<th align="left" colspan="2">
			Choose the .ttf file:
		</th>
	</tr>
	<tr>
		<td align2="left" colspan="2">
			<input type="file" name="ttf" id="ttf">
		</td>
	</tr>
	<tr>
		<td align="left">
			Font name:
		</td>
		<td align="left">
			<input type="text" name="fontname">
		</td>
	</tr>
	<tr>
		<td align="left">
			Font encoding:
		</td>
		<td align="left">
			<?php EncodingList(); ?>
		</td>
	</tr>
	<tr>
		<td align="center">
			<input type="reset" name="btnSub" value="Clear">
		</td>
		<td align="center">
			<input type="submit" name="btnSub" value="Send">
		</td>
	</tr>
	<tr>
		<td>Last Result</td>
		<td>
		<?php result(); ?>
		</td>
	</tr>
</table>
</form>
</body>
</html>
