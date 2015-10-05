<?
// Funcion para el manejo del TreeView
function init_menu($nombre,$url) {
		if (empty($url)) { $url="null"; }
		else { $url = "'$url'"; }
		echo "<script language='JavaScript'>\n\r";
		echo "\n\r var TREE_ITEMS = [ \n\r";
		echo "	['$nombre', $url,  \n\r";
}
function end_menu() {
		echo "	\n\r";
		echo "	] ];\n\r";
		echo "</script>";
}

function init_folder($nombre,$url) {
		if (empty($url)) { $url="null"; }
		else { $url = "'$url'"; }

		echo "			['$nombre', $url, \n\r";
}

function end_folder() {
		echo "			],\n\r";
}

function Koption($nombre,$url) {
		if (empty($url)) { $url="null"; }
		else { $url = "'$url'"; }
		echo "				['$nombre', $url],\n\r";
}
?>