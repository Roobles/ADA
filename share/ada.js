
//
// Global variables
//
var CurrentStatus = Array();

//
// Initialization functions
//
function Initialize() {
	setInterval(GetStatus, 1.5 * 1000);
	setInterval(GetOutputs, 10 * 1000);
	SetContentHeight(); window.onresize = SetContentHeight;
	TabSelect("SongSection");
}

// 
// Gobal variable dependent functions
//
function RetrieveStatusProperty(statusName) {
	var prop;
	for (var i in CurrentStatus)
		if(CurrentStatus[i][0] == statusName)
			prop = CurrentStatus[i][1];
	return prop;
}

//
// UI control functions
//
function GotoNowPlaying() {
	$("#SelectSongSection").click();
}

function GotoOptions() {
	$("#SelectOptionsSection").click();
}

function GotoPlaylist() {
	$("#SelectPlaylistSection").click();
}

//
// Event functions
//
function OnKeyUp() {
	var e = window.event;
	ParseKeyInput(e);
}

function ParseKeyInput(e) {
	//TODO: I have the grounds for key commands; I'm just gonna disable it for now.  It's actually a release 2 feature.
	return;
	var key = e.keyCode;
	var id	= e.srcElement.id;

	var searchField = document.search_form.search;

	var playNext	= 78;
	var playPrev	= 66;
	var volUp	= 190;
	var volDown	= 188;
	var toggle	= 77;
	var search	= 191;
	var playing	= 80;
	var options	= 79;
	var playlist	= 76;

	if(id == searchField.id) 
		return;

	switch(key) {
		case playNext:
			NextSong();
			break;
		case playPrev:
			PrevSong();
			break;
		case volUp:
			UpVolume(5);
			break;
		case volDown:
			DownVolume(5);
			break;
		case toggle:
			Toggle();
			break;
		case search:
			searchField.focus();
			searchField.value = "";
			break;
		case playing:
			GotoNowPlaying();
			break;
		case options:
			GotoOptions();
			break;
		case playlist:
			GotoPlaylist();
			break;
	}
}
