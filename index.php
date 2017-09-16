<html>
<head>
<title>ORFDM+</title>
<link rel="stylesheet" href="css/main.css">
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="http://code.jquery.com/color/jquery.color-2.1.2.js"></script>
<script>
function goToManage() {window.location = "manage.php";}
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
<?php
if (isSet($_GET["view"])) {
	switch ($_GET["view"]) {
		case "deadline":
			echo "$(function() { showDeadline() });";
			break;
		case "announcement":
			echo "$(function() { showAnnounc() });";
			break;
	}
}
?>

setInterval(function() { $("#deadlineHolder > .announcement > .date").each(function() {
	var now = new Date();
	var then = Date.parse($(this).text());
	if ((then - now) < 604800000 || then < now) {
		console.log($(this).text() + " "+ $(this).css("background-color"));
		switch ($(this).css("background-color")) {
			case "rgb(255, 255, 255)":
				$(this).animate({backgroundColor : "red"}, 1000);
				break;
			case "rgb(255, 0, 0)":
				$(this).animate({backgroundColor : "white"}, 1000);
				break;
			default:
				$(this).animate({backgroundColor : "white"}, 1000);
				break;
		}
	}
});
}, 2000);
</script>
</head>
<body>
<div class="header"><h1 style="margin:auto">ORF's Deadline Manager</h1><br/><div><button onclick="showAnnounc()">Show Announcements</button> <button onclick="goToManage()">Manage</button> <button onclick="showDeadline()">Show Deadlines</button></div></div>
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
			foreach ($description as &$line) { $line = rtrim($line); }
			echo "<div class=\"announcement\"><div class=\"left\"><h2>".$title."</h2> <h3>".$owner."</h3></div><div class=\"date\">".$date."</div><br/><div class=\"description\">".implode("<br/>",$description)."</div></div><br/>";
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
			foreach ($description as &$line) { $line = rtrim($line); }
			echo "<div class=\"announcement\"><div class=\"left\"><h2>".$title."</h2> <h3>".$owner."</h3></div><div class=\"description\">".implode("<br/>",$description)."</div></div><br/>";
		}
}
?>
</div>
</body></html>