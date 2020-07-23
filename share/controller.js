
var LibraryList 		= null;
var PlayingList 		= null;


//
// Controller.php interoperability functions.
//
function SendCommand(commandStr, argStr, delegateFunc, argEnv) {
	if(argStr == undefined)
		argStr = "";

	if(delegateFunc == undefined)
		delegateFunc = UpdateData;

	if(argEnv == undefined)
		argEnv = "";
	
	argStr 		= TrueEscape(argStr);
	commandStr	= TrueEscape(commandStr);
	argEnv		= TrueEscape(argEnv);

	$.ajax({
		type: 		"GET",
		cache:		false,
		url:		"controller.php?" + "command=" + commandStr + "&arg=" + argStr + "&env=" + argEnv,
		success:	function(output) {
			var data = ParseController(output);
			delegateFunc(data);
		}
	});
}

function ParseController(output) {
	var data_lines = output.match(/\[.*\] *=>.*\n/g);
	var data = new Array();

	if(data_lines)
		for(var i=0; i < data_lines.length; i++) {
			var line 	= data_lines[i];
			var splitterLoc	= line.indexOf("=>");
			var name	= line.substring(1, splitterLoc - 2);
			var value	= line.substring(splitterLoc + 3, line.length - 1);
			data[i]		= new Array(name,value);
		}
	return data;
}

//
// High level control hooks.  
// 
function GetStatus(){
	SendCommand("status");
}

function GetOutputs(){
	SendCommand("output", "", UpdateOutputs);	
}

function GetHash(){
	var hashVal = document.hash_form.hash.value;
	if(hashVal)
		SendCommand("hash", hashVal, UpdateHash);
}

function NextSong(){
	SendCommand("next");	
}

function PrevSong(){
	SendCommand("back");
}

function PlaySong(songName){
        SendCommand("play", songName);
}

function PlayPlaylist(playlistNumber){
	SendCommand("playlist", playlistNumber, UpdatePlaylist);
}

function Stop() {
	SendCommand("stop");
}

function Toggle() {
	SendCommand("toggle");
}

function Repeat() {
	SendCommand("repeat");
}

function Random() {
	SendCommand("random");
}

function Logout() {
	SendCommand("logout");
	window.location = "login.php";
}

function SetVolume(volume) {
	SendCommand("volume", volume);
}

function UpVolume(val) {
	SetVolume("p" + val);
}

function DownVolume(val) {
	SetVolume("m" + val);
}

function Output(no) {
	SendCommand("output", no, UpdateOutputs);
}

function FastForward(seek) {
	SendCommand("fast", seek);	
}

function Rewind(seek) {
	SendCommand("rew", seek);	
}

//
// SendCommand() Delegate Functions
//
function UpdateData(data) {
	// First, let's set the global cache of last retrieved settings.
	CurrentStatus = data;
	
	// Now, let's build up what we're interested in and see if it all exists.
	var songName, played, length, left, songStatus, progress, volume, repeat, random;

	for (var i in data) {
		var info = data[i];
		switch(info[0]) {
			case "name":
				songName 	= info[1];
				break;
			case "status":
				songStatus 	= info[1];
				break;
			case "played":
				played		= info[1];
				break;
			case "length":
				length		= info[1];
				break;
			case "volume":
				volume		= info[1];
				break;
			case "repeat":
				repeat		= info[1];
				break;
			case "random":
				random		= info[1];
				break;
		}
	}

	WriteStatusData(songName, songStatus, played, length, volume, repeat, random);
}

function UpdateOutputs(data) {
	var dataLength = data.length;
	for(var i=0; i < data.length; i += 4) {
		var outputNo   		= data[i + 1][1];
		var outputEnabled	= data[i + 2][1];
		var outputName		= data[i + 3][1];
		var outputId		= "Output" + outputNo;

		var checkbox		= document.getElementById(outputId);
		checkbox.checked 	= outputEnabled == "1";
	}
}

function UpdateHash(data) {
	if(data.length > 0) {
		var hashVal = data[0][1];
		$("#HashSpan").text(hashVal);
	}
}

function UpdatePlaylist(data) {
	GetSongs();
	GotoNowPlaying();
}

//
// Misc Helper functions.
//
function WriteStatusData(songName, songStatus, played, length, volume, repeat, random) {

	if(!IsEmpty(songName)) {
		$("#SongName").text(songName);
		document.title = "Ada: " + songName + " ";
	}
	
	if(!IsEmpty(played))
		$("#SongPlayed").text(played + " / ");

	if(!IsEmpty(length))
		$("#SongLength").text(length);
	
	if(!IsEmpty(songStatus)) {
		$("#SongStatus").text(songStatus + ": ");
		document.getElementById("ButtonPause").value = songStatus == "Playing" ? "Pause" : "Play";
	}

	if(!IsEmpty(volume)) {
		$("#SongVolumeValue").text(volume == "n/a" ? "" : volume + "%");
		$("#SongVolume").text(volume == "n/a" ? "" : "Volume:");
		document.getElementById("VolumeUp").className 	= volume == "n/a" ? "hidden" : "";
		document.getElementById("VolumeDown").className = volume == "n/a" ? "hidden" : "";
	}

	if(!IsEmpty(repeat)) 
		document.getElementById("RepeatCheck").checked = repeat == 1;
	
	if(!IsEmpty(random)) 
		document.getElementById("RandomCheck").checked = random == 1;
} 

