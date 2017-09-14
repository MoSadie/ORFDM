<html>
<head>
<title>ORFDM+</title>
</head>
<body>
<?php
	$dir = new DirectoryIterator(__DIR__."\data");
	foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot() && $fileinfo->isFile()) {
        echo "<div class=\"announcement\">".$fileinfo->getPathname()."</div>";
    }
}
?>
</body></html>