<?php
$file = $_GET['file'];
//echo $file;
header("Content-disposition: attachment; filename=".urlencode($file));
header("Content-type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Description: File Transfer"); 
readfile('../'.$file);
?> 