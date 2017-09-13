<html>
<head>
<title>ORFDM+</title>
<script src="https://code.jquery.com/jquery-3.2.1.js"/>
<script>
$(function() {
	Debug.print("Hi")
})
</script>
</head>
<body>
<?php
	$dir = new DirectoryIterator(__DIR__."/data");
	foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot() && @fileinfo->isFile()) {
        echo "<div class=\"announcement\">".$fileinfo->getPath()."</div>";
    }
}
?>
</body></html>