<?php
	class PlaylistsTab extends AdaTab
	{
		function __construct($divName, $tabText) {
			parent::__construct($divName, $tabText);
		}

		public function PrintTabContent() {
			$this->PrintPlaylists();
		}

		private function PrintPlaylists() {
			$playlists 	= GetPlayLists();
			$selectArgs	= Array();
			for($i=0; $i<count($playlists); $i++) {
				$onClick 	= sprintf("PlayPlaylist(%s);", $i);
				$spanValue	= $playlists[$i];	

				array_push($selectArgs, Array("onClick" => $onClick, "spanValue" => $spanValue));
			}

			PrintSelect($selectArgs);
		}
	}
?>
