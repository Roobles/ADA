<script type='text/javascript'>
	//Variables
	var CurrentSelectedTab;

	// Functions
	function SearchActivation() {
		var searchInfo 		= document.forms['SearchSection'].search.value;
		var searchMethod 	= GetSearchMethod();
		searchMethod(searchInfo);

		if(CurrentSelectedTab != "LibrarySection")
			GotoNowPlaying();
	}

	function TabSelect() {
		if(arguments.length < 1)
			return;

		CurrentSelectedTab = arguments[0];

		SelectTab(CurrentSelectedTab, true);
		for(var i=1; i < arguments.length; i++)
			SelectTab(arguments[i], false);
	}

	function SelectTab(divName, isSelected) {
		DisplayDivById(divName, isSelected);
		DisplayTabSelected(divName + "Tab", isSelected);
	}

	function DisplayTabSelected(divName, isSelected) {
		var element     = document.getElementById(divName);

		if(!element)
			return false;

		element.className = isSelected ? "tabSelected" : "";
	}

	function GetSearchMethod()
	{
		//TODO: This is embarassing.  Seriously.  Come up with a real solution.
		return CurrentSelectedTab == "LibrarySection" ? SearchLibrary : GetSongs;
	}
</script>

<?php
	//Requires
	require("tabs/playing.php");
	require("tabs/actions.php");
	require("tabs/library.php");
	require("tabs/playlists.php");
	require("tabs/admin.php");

	$ActiveTabs = GetTabs();

	//Base Definition of Tab Sections
	abstract class AdaTab 
	{
		public $DivName;
		public $TabText;
		public $SearchMethod;

		function __construct($divName, $tabText) {
			$this->DivName 	= $divName;
			$this->TabText 	= $tabText;
		}

		public function PrintContent() {
			echo sprintf("<div id='%s' class='hidden'>", $this->DivName);
			$this->PrintTabContent();
			echo "</div>";
		}

		protected abstract function PrintTabContent();
	}

	// Print Functions
	
	function PrintControlNest() {
		PrintSearchSection();
		PrintTabs();
	}

	// Build an unordered list of clickable links.  The links are spans; each span has an id of "Select" + [The tab content's DivName].  Each list item
	// has an id of [The tab content's DivName] + "Tab".
	function PrintTabs() {
		global $ActiveTabs;
		
		echo "<div id='TabControls'>";
		echo "<ul>";
		foreach($ActiveTabs as $activeTab) {
			$tabArgs	= BuildTabSelectArgs($ActiveTabs, $activeTab);

			$divName	= sprintf("Select%s", $activeTab->DivName);
			$onClick	= sprintf("TabSelect(%s);", $tabArgs);
			$tabText	= $activeTab->TabText;
				
			$tabLink	= BuildClickableLink($divName, $onClick, $tabText);

			echo sprintf("<li id='%sTab'> %s </li>", $activeTab->DivName, $tabLink);
		}
		echo "</ul>";
		echo "</div>";
	}

	function PrintSearchSection() {
		global $ActiveTabs;

		$searchSection = "
			<form id=\"SearchSection\" onsubmit=\"SearchActivation(); return false;\" action=\"index.php\">
				<div>
					<label id=\"SearchLabel\" for=\"SongSearch\"> Search: </label>
					<input type='text' name='search' id='SongSearch'/>
				</div>
			</form>";

		echo $searchSection;
	}

	function PrintTabbedSections() {
		global $ActiveTabs;

		foreach($ActiveTabs as $tab)
			$tab->PrintContent();
	}

	// Utility Functions
	function GetTabs() {
		global $ADMIN_TOOLS_ON;

		$playingTab 	= new PlayingTab("SongSection", "Playing");
		$actionsTab	= new ActionsTab("OptionsSection", "Actions");
		$libraryTab	= new LibraryTab("LibrarySection", "Library");
		$playlistTab	= new PlaylistsTab("PlaylistSection", "Playlists");
		$adminTab	= new AdminTab("AdminSection", "Admin");

		$tabArr	= Array (
			$libraryTab,
			$playingTab,
			$actionsTab,
			$playlistTab
		);

		if($ADMIN_TOOLS_ON)
			array_push($tabArr, $adminTab);

		return $tabArr;
	}

	// This function is used to build the parameters to the TabSelect JavaScript function.
	function BuildTabSelectArgs($ActiveTabs, $activeTab) {

		$tabArgs = QuoteArg($activeTab->DivName);
		foreach($ActiveTabs as $incTab) {
			if($incTab->DivName == $activeTab->DivName) continue;
			$tabArgs .= sprintf(", %s", QuoteArg($incTab->DivName));
		}

		return $tabArgs;
	}
	
	function QuoteArg($argument) {
		return sprintf("\"%s\"", $argument);
	}
?>

