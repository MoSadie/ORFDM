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
<script src="http://code.jquery.com/color/jquery.color-2.1.2.min.js"></script>
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

function deleteDeadline(fileName) {
	if (confirm("About to delete the deadline.\nAre you OK with this?")) {
		$.post( "scripts/delete.php", { accessKey: "<?php echo $_POST["accessKey"] ?>", fileName: fileName, fileType: "deadline" }, function() {
			alert("Deadline deleted!\nPlease refresh other viewers to update their display.");
			$("#deadline-" + fileName).slideUp(500, function () {
				$("#deadline-" + fileName).remove();
			});
		}).fail(function (data) {
			alert("Something went wrong! Error code: "+data.status);
			return;
		});
	}
}

function deleteAnnounc(fileName) {
	if (confirm("About to delete the announcement.\nAre you OK with this?")) {
		$.post( "scripts/delete.php", { accessKey: "<?php echo $_POST["accessKey"] ?>", fileName: fileName, fileType: "announc" }, function() {
			alert("Announcement deleted!\nPlease refresh other viewers to update their display.");
			$("#announc-" + fileName).slideUp(500, function () {
				$("#announc-" + fileName).remove();
			});
		}).fail(function (data) {
			alert("Something went wrong! Error code: "+data.status);
			return;
		});
	}
}

function createDeadline() {
	var title = prompt("What is the title of the deadline?");
	if (title == null) { alert("Cancelled creation."); return; }
	var owner = prompt("Who owns the deadline?");
	if (owner == null) { alert("Cancelled creation."); return; }
	var team = prompt("What team is the deadline for?");
	if (team == null) { alert("Cancelled creation."); return; }
	var date = prompt("When is the deadline due? (MM/DD/YYYY)");
	if (date == null) { alert("Cancelled creation."); return; }
	var done = false;
	var description = [];
	do {
		var line = prompt("Please type a line of the description, then click 'OK'\nClick cancel when finished.");
		if (line == null) done = true;
		else description.push(line);
	} while (!done);
	
	$.post( "scripts/create.php", { accessKey: "<?php echo $_POST["accessKey"] ?>", title: title, fileType: "deadline", owner: owner, description: JSON.stringify(description), date: date, team: team }, function(data) {
		alert("Deadline created!\nPlease refresh other viewers to update their display.");
		location.reload();
	}).fail(function (data) {
		alert("Something went wrong! Error code: "+data.status);
		return;
	});
}

function createAnnouncement() {
	var title = prompt("What is the title of the announcement?");
	if (title == null) { alert("Cancelled creation."); return; }
	var owner = prompt("Who owns the announcement?");
	if (owner == null) { alert("Cancelled creation."); return; }
	var dateTime = new Date();
	var date = dateTime.getMonth()+"/"+dateTime.getDay()+"/"+dateTime.getFullYear();
	var done = false;
	var description = [];
	do {
		var line = prompt("Please type a line of the description, then click 'OK'\nClick cancel when finished.");
		if (line == null) done = true;
		else description.push(line);
	} while (!done);
	
	$.post( "scripts/create.php", { accessKey: "<?php echo $_POST["accessKey"] ?>", title: title, fileType: "announc", owner: owner, description: JSON.stringify(description), date: date }, function(data) {
		alert("Deadline created!\nPlease refresh other viewers to update their display.");
		location.reload();
	}).fail(function (data) {
		alert("Something went wrong! Error code: "+data.status);
		return;
	});
}

setInterval(function() { $("#deadlineHolder > .announcement > .date").each(function() {
	var now = new Date();
	var then = Date.parse($(this).text());
	if ((then - now) < 604800000 || then < now) {
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
<div class="header"><h1 style="margin:auto">ORF's Deadline Manager (Managing Mode)</h1><br/><div><button onclick="showAnnounc()">Show Announcements</button> <button onclick="goHome()">Go Back</button> <button onclick="showDeadline()">Show Deadlines</button></div><br/>
<button onclick="createAnnouncement()">Create Announcement</button> <button onclick="createDeadline()">Create Deadline</button></div>
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
			echo "<div id=\"deadline-".$fileinfo->getBaseName('.txt')."\" class=\"announcement\"><div class=\"left\"><h2>".$title."</h2> <h3>".$owner."</h3></div><div class=\"date\">".$date."</div><br/><div class=\"description\">".implode("<br/>",$description)."</div><button onclick=\"deleteDeadline('".$fileinfo->getBaseName('.txt')."')\">Delete Deadline</button></div><br/>";
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
			echo "<div id=\"announc-".$fileinfo->getBaseName('.txt')."\" class=\"announcement\"><div class=\"left\"><h2>".$title."</h2> <h3>".$owner."</h3></div><div class=\"description\">".implode("<br/>",$description)."</div><button onclick=\"deleteAnnounc('".$fileinfo->getBaseName('.txt')."')\">Delete Announcement</button></div><br/>";
		}
}
?>
</div>
</body></html>
