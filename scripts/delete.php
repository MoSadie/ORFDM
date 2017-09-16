<?php
if (!isSet($_POST["accessKey"]) || !isSet($_POST["fileName"]) || !isSet($_POST["fileType"])) {
	http_response_code(400);
	exit;
}

include "checkLogin.php";
if (!checkLogin($_POST["accessKey"])) {
	http_response_code(401);
	exit;
}

switch($_POST["fileType"]) {
	case "announc":
		$path = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."announcements".DIRECTORY_SEPARATOR.$_POST["fileName"].".txt";
		break;
	case "deadline":
		$path = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."deadlines".DIRECTORY_SEPARATOR.$_POST["fileName"].".txt";
		break;
	default:
		http_response_code(400);
		exit;
}

if (file_exists($path)) {
	if (unlink($path)) {
		http_response_code(200);
		exit;
	} else {
		http_response_code(500);
		exit;
	}
} else {
	http_response_code(404);
	exit;
}
?>