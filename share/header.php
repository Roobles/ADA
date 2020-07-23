<?php
	session_start();
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>Ada</title>
	<?php require("config.php"); ?>
	<?php require("lib/auth.php"); ?>
	<script type='text/javascript' src='lib/jquery.js'></script>
	<?php 
		if(!isset($ADA_THEME))
			$ADA_THEME = "ada-theme-dark.css";

		IncludeStyleSheet("ada-nuke.css");
		IncludeStyleSheet("ada-structural.css");
		IncludeStyleSheet($ADA_THEME);
	?>
	<link rel="icon" type="image/vnd.microsoft.icon" href="images/logo.ico" />
</head>
<body onkeyup="OnKeyUp();">
	<div id="Container">
	
<?php
	// Some header methods.

	function IncludeStyleSheet($name) {
		echo sprintf("<link type='text/css' rel='stylesheet' href='css/%s' />", $name);
	}
?>
