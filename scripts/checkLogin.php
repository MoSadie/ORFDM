<?php
function checkLogin($password) {
	$dir = new DirectoryIterator(dirname(__DIR__).DIRECTORY_SEPARATOR."password".DIRECTORY_SEPARATOR);
	foreach ($dir as $fileinfo) {
		if (!$fileinfo->isDot() && $fileinfo->isFile()) {	
			$hash = file($fileinfo);
			echo $hash[0];
			if (password_verify($password,$hash[0])) {
				return true;
			}
		}
	}	
	return false;
}
?>