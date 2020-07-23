<script type="text/javascript">
	//---- Inclusion of Library Tab Module ----//
	//Properties
	var CurrentLibraryLocation;

	//Functions
	function UpdateLibrary(data) {

		var dataLength          = data.length;
		var libraryList         = new Array();

		for(var i=0; i < dataLength; i += 3) {
			var text        = data[i + 1][1];
			var type        = data[i + 2][0];
			var file        = data[i + 2][1];

			var libraryName         = text;
			var libraryClick        = SelectMethod(type == "dir" ? GetLibrary : AddSong, file);
			var librarySelectItem   = new SelectItem(libraryName, libraryClick);

			var libraryItems        = new Array(librarySelectItem);
			var libraryRow          = new ListRow(libraryItems);

			libraryList.push(libraryRow);
		}

		if (LibraryList == null)
			LibraryList = new SelectListWidget("LibrarySection");

		LibraryList.Print(libraryList);
	}

	function GetLibrary(libLoc){
		CurrentLibraryLocation = libLoc;
		SendCommand("lib", libLoc, UpdateLibrary);
	}

	function SearchLibrary(criteria) {
			SendCommand("search", criteria, UpdateLibrary, CurrentLibraryLocation);
	}

	function AddSong(file){
		        SendCommand("add", file, UpdateLibraryPlay);
	}

	function UpdateLibraryPlay(data) {
		var addStatus           = data[0][0];

		if(addStatus == "added")
			GetSongs();
	}
</script>
<?php
	class LibraryTab extends AdaTab
	{
		function __construct($divName, $tabText) {
			parent::__construct($divName, $tabText);
			$this->SearchMethod = "SearchLibrary";
		}

		protected function PrintTabContent() {
			$this->PrintLibrary();
		}

		// Private methods
		private function PrintLibrary() {
			echo "<div id='LibraryList'>\n";
			$this->PrintLibrarySelect();
			echo "</div>\n";
		}

		private function PrintLibrarySelect() {
			echo "<div id='LibrarySelect'></div>";
			echo "<script type='text/javascript'> GetLibrary(); </script>";
		}
	}
?>

