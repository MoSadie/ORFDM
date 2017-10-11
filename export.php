<?xml version="1.0" encoding="utf-8"?>
<DeadlineDB xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <DeadLineList>
	<?php
	$dir = new DirectoryIterator(__DIR__."\deadlines");
	foreach ($dir as $fileinfo) {
		if (!$fileinfo->isDot() && $fileinfo->isFile()) {
			$file = array_reverse(file($fileinfo->getPathname()));
			$title = rtrim(array_pop($file));
			$author = rtrim(array_pop($file));
			$dueDate = rtrim(array_pop($file));
			$description = array_reverse($file);
			echo "<DeadLineEntry>
      <Author>"+$author+"</Author>
      <Title>"+$title+"</Title>
      <DueDate>"+$dueDate+"</DueDate>
      <Description>"+$description+"</Description>
      <Team>"+$team+"</Team>
    </DeadLineEntry>"
		}
	}
	?>
	</DeadLineList>
</DeadlineDB>
	