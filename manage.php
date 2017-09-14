<?php
if (!isSet($_POST["accessKey"])) {
	include "login.php";
	exit;
}
include "scripts/checkLogin.php";
if (!checkLogin($_POST["accessKey"])) {
	$error = "Something went wrong...";
	include "login.php";
	exit;
}

?>
<html>
<head>
<title>ORFDM+ Managing Mode</title>
<link rel="stylesheet" href="css/main.css">
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script>
function goHome() {window.location = ".";}
function showAnnounc() {
	$("#deadlineHolder").fadeOut(500, function () {
		$("#announcHolder").fadeIn(500);
	});
}
function showDeadline() {
	$("#announcHolder").fadeOut(500, function () {
		$("#deadlineHolder").fadeIn(500);
	});
}

function deleteDeadline(fileName) {
	alert(fileName);
}
</script>
</head>
<body>
<div class="header"><h1 style="margin:auto">ORF's Deadline Manager (Managing Mode)</h1><br/><div><button onclick="showAnnounc()">Show Announcements</button> <button onclick="goHome()">Go Back</button> <button onclick="showDeadline()">Show Deadlines</button></div></div>
</br>
<div id="deadlineHolder" style="display: none">
<?php
	$dir = new DirectoryIterator(__DIR__."\deadlines");
	foreach ($dir as $fileinfo) {
		if (!$fileinfo->isDot() && $fileinfo->isFile()) {
			$file = array_reverse(file($fileinfo->getPathname()));
			$title = rtrim(array_pop($file));
			$owner = rtrim(array_pop($file));
			$date = rtrim(array_pop($file));
			$description = array_reverse($file);
			echo "<div id=\"deadline-".$fileinfo->getBaseName('txt')."\" class=\"announcement\"><div class=\"left\"><h2>".$title."</h2> <h3>".$owner."</h3></div><div class=\"date\"><p style=\"font-size:1.5em; color:black;\">".$date."</p></div><br/><div class=\"description\">".implode("<br/>",$description)."</div><button onclick=\"deleteDeadline('".$fileinfo->getBaseName('txt')."')</div><br/>";
		}
}
?>
</div>
<div id="announcHolder">
<?php
	$dir = new DirectoryIterator(__DIR__."\announcements");
	foreach ($dir as $fileinfo) {
		if (!$fileinfo->isDot() && $fileinfo->isFile()) {
			$file = array_reverse(file($fileinfo->getPathname()));
			$title = rtrim(array_pop($file));
			$owner = rtrim(array_pop($file));
			$date = rtrim(array_pop($file));
			$description = array_reverse($file);
			echo "<div id=\"announc-".$fileinfo->getBaseName('txt')."\" class=\"announcement\"><div class=\"left\"><h2>".$title."</h2> <h3>".$owner."</h3></div><div class=\"description\">".implode("<br/>",$description)."</div></div><br/>";
		}
}
?>
</div>
</body></html>
