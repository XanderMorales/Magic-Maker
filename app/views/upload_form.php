
<html>
<head>
<title></title>
</head>
<body>

<h1>Upload Power Point File</h1>
<?php
if(isset(Controller::$view_msg['error']))
{
	echo '<ul>';
	echo Controller::$view_msg['error'];
	echo '</ul>';
}
?>
<form name="upload" method="post" action="index.php" enctype="multipart/form-data">
<input type="hidden" name="page" value="upload_ppt" />
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo Config::$setting['upload.max_file_size']?>" />
<input type="hidden" name="email" value="alex.morales@webeditors.com" />
Choose a ppt file to upload: <input name="file" type="file" />
<br />
<input type="submit" value="Upload File" />
</form>

</body>
</html>