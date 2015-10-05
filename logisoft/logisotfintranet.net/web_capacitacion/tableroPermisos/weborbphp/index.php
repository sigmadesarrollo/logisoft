<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WebORB Management Console</title>
<script src="console/AC_OETags.js" language="javascript"></script>
<script src="console/browserdetect.js" language="javascript"></script>
<style type="text/css">
body { margin: 0px; overflow:hidden; }
</style>
<script language="JavaScript" type="text/javascript">
<!--
// -----------------------------------------------------------------------------
// Globals
// Major version of Flash required
var requiredMajorVersion = 9;
// Minor version of Flash required
var requiredMinorVersion = 0;
// Minor version of Flash required
var requiredRevision = 0;
// -----------------------------------------------------------------------------

function moveIFrame(x,y,w,h)
{
    var frameRef=document.getElementById("content");

    frameRef.style.left=x;
    frameRef.style.top=y;
    frameRef.width=w;
    frameRef.height=h;
}

function setIFrameContent( contentSource )
{
	document.getElementById("content").src = contentSource;
}

function hideIFrame()
{
    document.getElementById("content").style.visibility="hidden";
}

function showIFrame()
{
    document.getElementById("content").style.visibility="visible";
}

function openUrl(url)
{
    window.open(url);
}
// -->
</script>
</head>
<body scroll="no">
<script language="JavaScript" type="text/javascript">
<!--
// Version check for the Flash Player that has the ability to start Player Product Install (6.0r65)
var hasProductInstall = DetectFlashVer(6, 0, 65);

// Version check based upon the values defined in globals
var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);


// Check to see if a player with Flash Product Install is available and the version does not meet the requirements for playback
if ( hasProductInstall && !hasRequestedVersion ) {
	// MMdoctitle is the stored document.title value used by the installation process to close the window that started the process
	// This is necessary in order to close browser windows that are still utilizing the older version of the player after installation has completed
	// DO NOT MODIFY THE FOLLOWING FOUR LINES
	// Location visited after installation is complete if installation is required
	var MMPlayerType = (isIE == true) ? "ActiveX" : "PlugIn";
	var MMredirectURL = window.location;
    document.title = document.title.slice(0, 47) + " - Flash Player Installation";
    var MMdoctitle = document.title;

	AC_FL_RunContent(
		"src", "console/playerProductInstall",
		"FlashVars", "MMredirectURL="+MMredirectURL+'&MMplayerType='+MMPlayerType+'&MMdoctitle='+MMdoctitle+"",
		"width", "100%",
		"height", "100%",
		"align", "middle",
		"id", "index",
		"quality", "high",
		"bgcolor", "#869ca7",
		"name", "index",
		"allowScriptAccess","sameDomain",
		"type", "application/x-shockwave-flash",
		"pluginspage", "http://www.adobe.com/go/getflashplayer",
		"onMouseDown", "document.body.focus();",
		"wmode","opaque",
		"swLiveConnect", "true"
	);
} else if (hasRequestedVersion) {
	// if we've detected an acceptable version
	// embed the Flash Content SWF when all tests are passed

if( BrowserDetect.browser == "Safari" )
{
  alert( "Management Console does not work on Safari, please use Firefox" );
}
else if( BrowserDetect.OS == "Windows" || (BrowserDetect.browser == "Firefox" && BrowserDetect.OS == "Mac" ) )
	{
		AC_FL_RunContent(
				"src", "console/console",
				"width", "100%",
				"height", "100%",
				"align", "middle",
				"id", "index",
				"quality", "high",
				"bgcolor", "#869ca7",
				"name", "index",
				"allowScriptAccess","sameDomain",
				"type", "application/x-shockwave-flash",
				"pluginspage", "http://www.adobe.com/go/getflashplayer",
				"onMouseDown", "document.body.focus();",
				"wmode","opaque",
				"swLiveConnect", "true"

		);
	}
	else
	{
		AC_FL_RunContent(
				"src", "console/console",
				"width", "100%",
				"height", "100%",
				"align", "middle",
				"id", "index",
				"quality", "high",
				"bgcolor", "#869ca7",
				"name", "index",
				"style","position:absolute;z-index:-2",
				"allowScriptAccess","sameDomain",
				"type", "application/x-shockwave-flash",
				"pluginspage", "http://www.adobe.com/go/getflashplayer",
				"onMouseDown", "document.body.focus();",
				"wmode","opaque",
				"swLiveConnect", "true"

		);
	}


  } else {  // flash is too old or we can't detect the plugin
    var alternateContent = 'Alternate HTML content should be placed here. '
  	+ 'This content requires the Adobe Flash Player. '
   	+ '<a href=http://www.adobe.com/go/getflash/>Get Flash</a>';
    document.write(alternateContent);  // insert non-flash content
  }
// -->
</script>
<noscript>
  	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
			id="index" width="100%" height="100%"
			codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
			<param name="movie" value="console/console.swf" />
			<param name="quality" value="high" />
			<param name="bgcolor" value="#869ca7" />
			<param name="allowScriptAccess" value="sameDomain" />
			<embed src="console/console.swf" quality="high" bgcolor="#869ca7"
				width="100%" height="100%" name="index" align="middle"
				play="true"
				loop="false"
				quality="high"
				allowScriptAccess="sameDomain"
				type="application/x-shockwave-flash"
				pluginspage="http://www.adobe.com/go/getflashplayer">
			</embed>
	</object>
</noscript>

	<iframe id="content" frameborder="0" style="position:absolute;background-color:transparent;border:0px;visibility:hidden;" />
</body>
</html>
