<?php
function checkLogin($password) {
	$dir = new DirectoryIterator(dirname(__DIR__).DIRECTORY_SEPARATOR."password".DIRECTORY_SEPARATOR);
	foreach ($dir as $fileinfo) {
		if (!$fileinfo->isDot() && $fileinfo->isFile()) {	
			echo $fileinfo->getPathname();
			if (password_verify($password,file($fileinfo)[0])) {
				return true;
			}
		}
	}	
	return false;
}
?>