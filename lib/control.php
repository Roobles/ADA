<?php
	// Includes
	require_once 'lib-mpd.php';

	// Definitions
	define("SONG_NAME", 	"name");
	define("SONG_NUMBER",	"number");
	define("SONG_STATUS",	"status");
	define("SONG_PLAYED", 	"played");
	define("SONG_LENGTH",	"length");
	define("SONG_PROGRESS",	"progress");
	define("SONG_VOLUME",	"volume");
	define("SONG_REPEAT",	"repeat");
	define("SONG_RANDOM",	"random");
	define("SONG_LEFT",	"left");
	define("DIR_TYPE",	"dir");
	define("FILE_TYPE",	"file");
	define("LIB_TEXT",	"txt");

	// MPD functions
	define("COMMAND_STATUS",	"status");
	define("COMMAND_NEXT",		"next");
	define("COMMAND_BACK",		"back");
	define("COMMAND_PLAY",		"play");
	define("COMMAND_TOGGLE",	"toggle");
	define("COMMAND_STOP",		"stop");
	define("COMMAND_VOLUME",	"volume");
	define("COMMAND_OUTPUT",	"output");
	define("COMMAND_PLAYLIST",	"playlist");
	define("COMMAND_SONGS",		"songs");
	define("COMMAND_REPEAT",	"repeat");
	define("COMMAND_RANDOM",	"random");
	define("COMMAND_LOGOUT",	"logout");
	define("COMMAND_LIBRARY",	"lib");
	define("COMMAND_ADD",		"add");
	define("COMMAND_FAST",		"fast");
	define("COMMAND_REW",		"rew");
	define("COMMAND_SEARCH",	"search");

	// Auth functions
	define("COMMAND_HASH",		"hash");

	// Misc
	define("COMMAND_ARGUMENT",	"arg");
	define("COMMAND_ENVIRONMENT",	"env");


	// Global Objects
	$MPD_HOST = isset($MPD_HOST) ? $MPD_HOST : 'localhost';
	$MPD_PORT = isset($MPD_PORT) ? $MPD_PORT : '6600';
	$MPD_PASS = isset($MPD_PASS) ? $MPD_PASS : null;

	$MPD = new Net_MPD($MPD_HOST, $MPD_PORT, $MPD_PASS);	
	$MPD->Common->connect();

	// Public Functions
	function GetInfo() {
		return MPD_GetInfo();
	}

	function GetSongs() {
		return MPD_GetSongs();
	}

	function SearchSongs($exp) {
		$songs 		= GetSongs();
		$filterSongs	= Array();
		foreach($songs as $song)
			if(preg_match(sprintf("/%s/i", $exp), $song[0]))
				array_push($filterSongs, $song);

		return $filterSongs;
	}

	function GetOutputs() {
		return MPD_GetOutputs();
	}

	function PlayNext() {
		return MPD_PlayNext();
	}

	function PlayBack() {
		return MPD_PlayBack();
	}

	function PlaySong($song) {
		return MPD_PlaySong($song);
	}

	function Toggle() {
		return MPD_Toggle();
	}

	function Stop() {
		return MPD_Stop();
	}

	function Volume($volume) {
		return MPD_Volume($volume);
	}

	function Output($outputNo) {
		return MPD_Output($outputNo);
	}

	function GetPlayLists() {
		return MPD_GetPlayLists();
	}

	function SetPlayLists($playNo) {
		return MPD_SetPlayLists($playNo);	
	}

	function Random() {
		return MPD_Random();
	}	
	
	function Repeat() {
		return MPD_Repeat();
	}

	function GetLibrary($location) {
		return MPD_GetLibrary($location);
	}

	function AddSong($songName) {
		return MPD_AddSong($songName);
	}

	function FastForward($seek) {
		return MPD_FastForward($seek);
	}

	function RewindSong($seek) {
		return MPD_RewindSong($seek);
	}

	function SearchLibrary($criteria, $priorLocation) {
		return MPD_SearchLibrary($criteria, $priorLocation);
	}

	function HashPass($password) {
		return AUTH_Hash($password);
	}
	
	// MPD Functions (Implimentation Specific)
	function MPD_GetInfo() {
		global $MPD;
		$songInfo	= $MPD->Playback->getCurrentSong();
		$mpdStatus	= $MPD->Common->getStatus();
		$mpdPlayed	= explode(":", $mpdStatus['time']);

		$name 		= DeriveSongName($songInfo);
		$number		= $songInfo['Pos'];
		$status		= "Unkown";

		switch($mpdStatus['state']) {
			case "play" :
				$status = "Playing";
				break;
			case "pause" :
				$status = "Paused";
				break;
			case "stop" :
				$status = "Stopped";
				break;
		}

		$played		= SecondsToMinutes($mpdPlayed[0]);
		$length		= SecondsToMinutes($mpdPlayed[1]);
		$volume		= $mpdStatus['volume'] == -1 ? "n/a" : $mpdStatus['volume'];
		$repeat		= $mpdStatus['repeat'];
		$random		= $mpdStatus['random'];

		$info = Array(
			SONG_NAME	=> $name,
			SONG_NUMBER	=> $number,
			SONG_STATUS	=> $status,
			SONG_PLAYED	=> $played,
			SONG_LENGTH	=> $length,
			SONG_VOLUME	=> $volume,
			SONG_REPEAT	=> $repeat,
			SONG_RANDOM	=> $random
		);

		return $info;
	}

	function MPD_GetSongs() {
		global $MPD;
		$playlistInfo	= $MPD->Playlist->getPlaylistInfo();

		$songArray = Array();
		foreach($playlistInfo as $song) {
			$posit	= $song['Pos'];
			$name	= DeriveSongName($song);

			array_push($songArray, Array($name, $posit));
		}

		return $songArray;
	}

	function MPD_GetOutputs() {
		global $MPD;

		$mpdOutputs	= $MPD->Admin->getOutputs();
		$mpdOutputs	= $mpdOutputs['outputs'];
		$outputs = Array();
		for ($i = 0; $i < count($mpdOutputs); $i += 2)  {
			$output_num 	= $mpdOutputs[$i]['outputid'];
			$output_name	= $mpdOutputs[$i+1]['outputname'];
			$output_status	= $mpdOutputs[$i+1]['outputenabled'];
			$outputEntry	= Array($output_num, $output_status, $output_name);

			array_push($outputs, $outputEntry);
		}
		return $outputs;
	}

	function MPD_PlayNext() {
		global $MPD;
		$MPD->Playback->nextSong();
		return GetInfo();
	}

	function MPD_PlayBack() {
		global $MPD;
		$MPD->Playback->previousSong();
		return GetInfo();
	}

	function MPD_PlaySong($song) {
		global $MPD;
		$isValid = preg_match("/^\d{1,}$/", $song);

		if($isValid)
			$MPD->Playback->play($song);	

		return GetInfo();
	}

	function MPD_Toggle() {
		global $MPD;
		$MPD->Playback->pause();
		return GetInfo();
	}

	function MPD_Stop() {
		global $MPD;
		$MPD->Playback->stop();
		return GetInfo();
	}

	function MPD_Volume($volume) {
		global $MPD;
		$volumeRegex 	= "/^[pm]?((100)|(\d?\d))$/";
		$isValid 	= preg_match($volumeRegex, $volume);
		$mpdVolume	= $MPD->Common->getStatus(); 
		$mpdVolume	= $mpdVolume['volume'];

		if($isValid && $mpdVolume != -1) {
	
			if(strpos($volume, "p") !== false) {
				$volume	= (int) substr($volume, 1, strlen($volume));
				$volume	= $volume + $mpdVolume > 100 ? 100 : $volume + $mpdVolume;
			}

			if(strpos($volume, "m") !== false) {
				$volume	= (int) substr($volume, 1, strlen($volume));
				$volume	= $mpdVolume - $volume < 0 ? 0 : $mpdVolume - $volume;
			}

			$MPD->Playback->setVolume($volume);
		}

		return GetInfo();
	}

	function MPD_Output($output) {
		global $MPD;

		if(is_numeric($output) && (int) $output >= 0) {
			$outputData = GetOutputs();
			foreach($outputData as $outputEntry)
				if((int)$outputEntry[0] == (int)$output) {
					if ($outputEntry[1])
						$MPD->Admin->disableOutput($outputEntry[0]);
					else
						$MPD->Admin->enableOutput($outputEntry[0]);
				}
		}
		return GetOutputs();
	}

	function MPD_GetPlayLists() {
		global $MPD;
		return $MPD->Playlist->getPlaylists();
	}

	function MPD_SetPlayLists($playNo) {
		$lists = GetPlayLists();
		if(is_numeric($playNo) && (int) $playNo >= 0 && (int) $playNo < count($lists)) 
			MPD_LoadPLayList($lists[(int)$playNo]);

		return $lists;
	}

	function MPD_LoadPlayList($playlistName) {
		global $MPD;

		$MPD->Playlist->clear();
		$MPD->Playlist->loadPlaylist($playlistName);
		$MPD->Playback->play();
	}

	function MPD_ClearPlayList() {
		global $MPD;

		$MPD->Playlist->clear();
	}


	function MPD_Random() {
		global $MPD;
		$curr	= $MPD->Common->getStatus();
		$isOn	= $curr['random'];
		
		$MPD->Playback->random(!$isOn);
		return GetInfo();
	}

	function MPD_Repeat() {
		global $MPD;
		$curr	= $MPD->Common->getStatus();
		$isOn	= $curr['repeat'];
		
		$MPD->Playback->repeat(!$isOn);
		return GetInfo();
	}

	function MPD_GetLibrary($location) {
		global $MPD;
		$info = Array();

		$location = rtrim($location, "/");

		if($location != "") {
			$lastPos 	= strrpos($location, "/");
			$parentLoc	= $lastPos ? $lastPos : 0;
			$parent		= substr($location, 0, $parentLoc);
			array_push($info, BuildLibraryDirectoryArr("..", $parent));
		}
		
		$contents 	= $MPD->Database->getInfo($location);
		$directories	= $contents["directory"];
		$files 		= $contents["file"];

		foreach($directories as $dir)
			array_push($info, BuildLibraryDirectoryArr($dir, $dir));

		foreach($files as $file) {
			$name	= DeriveSongLibraryName($file);
			array_push($info, BuildLibraryFileArr($name, $file["file"]));
		}

		return $info;
	}

	function MPD_AddSong($songName) {
		global $MPD;
		
		$playlist	= $MPD->Playlist->getPlaylistInfo();
		$songInfo	= null;
		$added		= false;

		foreach($playlist as $entry) 
			if ($entry["file"] == $songName) {
				$songInfo	= $entry;
				break;
			}

		if($songInfo == null) {
			$MPD->Playlist->addSong($songName);
			$songInfo	= array_pop($MPD->Playlist->getPlaylistInfo(count($playlist)));
			$added		= true;
		}

		$name 		= DeriveSongName($songInfo);
		$position	= $songInfo["Pos"];
		$addStatus	= $added ? "added" : "played";
		$returnInfo	= Array(
					$addStatus => $name,
					"posit" => $position
				  );
		
		PlaySong($position);	
		return $returnInfo;
	}

	function MPD_FastForward($seek) {
		global $MPD;

		$info 	= $MPD->Common->getStatus();
		$song 	= $info["song"];

		$time 	= explode(":", $info["time"]);
		$end	= $time[1];
		$time 	= $time[0];

		$time 	= $time + $seek > $end ? $end : $time + $seek;
		$MPD->Playback->seek($song, $time);

		return GetInfo();
	}

	function MPD_RewindSong($seek) {
		global $MPD;

		$info 	= $MPD->Common->getStatus();
		$song 	= $info["song"];

		$time 	= explode(":", $info["time"]);
		$time	= $time[0];

		$time	= $time - $seek > 0 ? $time - $seek : 0;
		$MPD->Playback->seek($song, $time);

		return GetInfo();
	}

	function MPD_SearchLibrary($criteria, $priorLocation) {
		global $MPD;

		$info		= Array();
		$library 	= $MPD->Database->getAllInfo($priorLocation);
		$files		= $library['file'];
		$criteria	= sprintf("/%s/i", $criteria);

		array_push($info, BuildLibraryDirectoryArr("..", $priorLocation));
		
		foreach($files as $file) {
			$name	= DeriveSongName($file);
			if(preg_match($criteria, $name))
				array_push($info, BuildLibraryFileArr($name, $file['file']));
		}

		return $info;
	}

	// Auth functions
	function AUTH_Hash($password) {
		global $ADMIN_TOOLS_ON;
		$hash = Array();

		if($ADMIN_TOOLS_ON)
			array_push($hash, EncryptPassword($password));

		return $hash;
	}
	
	// Little helper functions
	function DeriveSongName($songInfo) {
		$artist		= $songInfo["Artist"];
		$title		= $songInfo["Title"];
		$fileName	= $songInfo["file"];

		$invalidName	= "/(^ *unkown)|(track *-? *\d{1,} *$)/i";
		return  $title != "" && !preg_match($invalidName, $artist . $title) ? (sprintf("%s%s", ($artist != "" ? sprintf("%s - ", $artist) : ""), $title)) : $fileName;
	}

	function BuildLibraryDirectoryArr($name, $dir) {
		$dirName 	= sprintf("[ <strong class='directory'>%s</strong> ]", StripParentDirectories($name));
		
		return Array(LIB_TEXT => $dirName, DIR_TYPE => $dir);
	}

	function BuildLibraryFileArr($name, $fileName) {
		return Array(LIB_TEXT => $name, FILE_TYPE => $fileName);
	}

	function DeriveSongLibraryName($songInfo) {
		
		$name = DeriveSongName($songInfo);
		if($name == $songInfo["file"])
			$name = StripParentDirectories($name);

		return $name;
	}

	function StripParentDirectories($fileName) {
		$lastDir	= strrpos($fileName, "/");
		$posit		= $lastDir ? $lastDir + 1 : 0;

		return substr($fileName, $posit);
	}

	function SecondsToMinutes($seconds) {
		return gmdate((int)$seconds > 3600 ? "H:i:s" : "i:s", (int)$seconds);
	}
?>
