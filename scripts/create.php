<?php
if (!isSet($_POST["accessKey"]) || !isSet($_POST["fileType"]) || !isSet($_POST["owner"]) || !isSet($_POST["title"]) || !isSet($_POST["date"]) || !isSet($_POST["description"])) {
	http_response_code(400);
	exit;
}

include "checkLogin.php";
if (!checkLogin($_POST["accessKey"])) {
	http_response_code(401);
	exit;
}

$fileName = md5($_POST["owner"].$_POST["title"].$_POST["description"]);

switch($_POST["fileType"]) {
	case "announc":
		$path = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."announcements".DIRECTORY_SEPARATOR.$fileName.".txt";
		break;
	case "deadline":
		$path = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."deadlines".DIRECTORY_SEPARATOR.$fileName.".txt";
		break;
	default:
		http_response_code(400);
		exit;
}

if (file_exists($path)) {
		http_response_code(409);
		exit;
} else {
	$file = fopen($path, "w");
	fwrite($file, $_POST["title"].PHP_EOL.$_POST["owner"].PHP_EOL.$_POST["date"].PHP_EOL);
	$description = json_decode($_POST["description"]);
	foreach($description as $line) {
		fwrite($file, $line.PHP_EOL);
	}
	fclose($file);
	echo $fileName;
	http_response_code(200);
	exit;
}
?>