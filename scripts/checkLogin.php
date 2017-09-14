<?php
function checkLogin(password) {
	$dir = new DirectoryIterator(__DIR__."\password");
	foreach ($dir as $fileinfo) {
		if (!$fileinfo->isDot() && $fileinfo->isFile()) {		
			if (password_verify(password,$fileinfo->getBasename('txt'))) {
				return true;
			}
		}
	}	
	return false;
}
?>