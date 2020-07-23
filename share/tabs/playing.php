<script type='text/javascript'>
	//---- Inclusion of Current Playlist Module ----//
	
	// Fuctions
	function UpdateSongs(data) {
		var dataLength = data.length;
		var songList = new Array();

		for(var i=0; i < data.length; i += 3) {
			var songName    = data[i + 1][1];
			var songNum     = data[i + 2][1];

			var songPlayClick       = SelectMethod(PlaySong, songNum);
			var songPlaySelectItem  = new SelectItem(songName, songPlayClick);

			var songItems           = new Array(songPlaySelectItem);
			var songRow             = new ListRow(songItems);

			songList.push(songRow);
		}

		if (PlayingList == null)
			PlayingList = new SelectListWidget("SongSelect");

		PlayingList.Print(songList);
	}

	function GetSongs(searchCrit){
		SendCommand("songs", searchCrit, UpdateSongs);
	}
</script>

<?php
	class PlayingTab extends AdaTab
	{
		function __construct($divName, $tabText) {
			parent::__construct($divName, $tabText);
			$this->SearchMethod = "GetSongs";
		}

		protected function PrintTabContent() {
			$this->PrintSongs();
		}

		// Private methods
		private function PrintSongs() {
			echo "<div id='SongList'>\n";
			$this->PrintSongSelect();
			echo "</div>\n";
		}

		private function PrintSongSelect() {
			echo "<div id='SongSelect'></div>";
			echo "<script type='text/javascript'> GetSongs(); </script>";
		}
	}
?>

