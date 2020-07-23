<?php 
	if(!HasCliArgs())
		require("share/header.php"); 
?>
<?php require("lib/control.php"); ?>
<?php
	$info = Array();

	unset($command);
	unset($arg);
	unset($env);

	if(isset($_GET["command"])) 	$command = utf8_encode(urldecode($_GET["command"]));
	if(isset($argv[1])) 		$command = utf8_encode($argv[1]);


	if(isset($command)) {

		if(isset($_GET[COMMAND_ARGUMENT]))	$arg = utf8_encode(urldecode($_GET[COMMAND_ARGUMENT]));
		if(isset($argv[2]))			$arg = utf8_encode($argv[2]);

		if(isset($_GET[COMMAND_ENVIRONMENT]))	$env = utf8_encode(urldecode($_GET[COMMAND_ENVIRONMENT]));
		if(isset($argv[3]))			$env = utf8_encode($argv[2]);

		switch ($command) {
			case COMMAND_STATUS:
				$info 	= GetInfo();
				break;

			case COMMAND_NEXT:
				$info 	= PlayNext();
				break;

			case COMMAND_BACK:
				$info 	= PlayBack();
				break;

			case COMMAND_PLAY:
				$info	= isset($arg) ? PlaySong($arg) : GetInfo();
				break;

			case COMMAND_TOGGLE:
				$info 	= Toggle();
				break;

			case COMMAND_STOP:
				$info 	= Stop();
				break;

			case COMMAND_VOLUME:
				$info	= isset($arg) ? Volume($arg) : GetInfo();
				break;

			case COMMAND_OUTPUT:
				$info 	= isset($arg) ? Output($arg) : GetOutputs();
				break;

			case COMMAND_PLAYLIST:
				$info 	= isset($arg) ? SetPlayLists($arg) : GetPlayLists();
				break;

			case COMMAND_SONGS:
				$info 	= isset($arg) ? SearchSongs($arg) : GetSongs();
				break;

			case COMMAND_RANDOM:
				$info	= Random();
				break;

			case COMMAND_REPEAT:
				$info	= Repeat();
				break;

			case COMMAND_LIBRARY:
				$info	= isset($arg) ? GetLibrary($arg) : GetLibrary("");
				break;

			case COMMAND_ADD:
				if (isset($arg)) $info= AddSong($arg);
				break;

			case COMMAND_FAST:
				if (isset($arg)) $info= FastForward($arg);
				break;

			case COMMAND_REW:
				if (isset($arg)) $info= RewindSong($arg);
				break;

			case COMMAND_SEARCH:
				if (isset($arg)) $info= SearchLibrary($arg, $env);
				break;
			
			case COMMAND_LOGOUT:
				Logout();
				break;

			case COMMAND_HASH:
				if(isset($arg)) $info = HashPass($arg);
				break;

		}
	}
	print_r($info);
	
?>
<?php 
	if(!HasCliArgs())
		require("share/footer.php"); 

	function HasCliArgs() {
		global $argc;
		return $argc > 1;
	}
?>
