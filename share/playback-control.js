var playback = new PlaybackControl();

//
// Constructor
//
function PlaybackControl() {

	//Properties
	this.lastOppTimeout	= null;
	this.lastOppOccured	= false;
	this.seekAmountBase	= 5;
	this.seekAmount		= 0;
	this.seekIncriment	= 4;
	this.seekMax		= 20;
	this.intervalInSeconds	= 1;
	this.delayInSeconds	= 1;

	// Public Methods
	this.FastForward 	= SetFastForward;
	this.Rewind		= SetRewind;	
	this.Clear		= ClearPlaybackFeatures;
	this.Next		= function () { this.Exec(NextSong); }
	this.Prev		= function () { this.Exec(PrevSong); }
	this.Toggle		= function () { this.Exec(Toggle);  }

	// Private Methods
	this.Trigger		= TriggerPlaybackFeature;
	this.Queue		= QueueFeatureMethod;
	this.Exec		= ExecuteExternalOpperation;
	this.FastForwardInc	= FastForwardIncriment;
	this.RewindInc		= RewindIncriment;
	this.GetSeek		= GetSeekIncriment;
}


//
// Public Methods
//
function SetFastForward() {
	var fastInc = FastForwardClosure(this);
	this.Trigger(fastInc);
}

function SetRewind() {
	var rewInc = RewindClosure(this);
	this.Trigger(rewInc);
}

function ExecuteExternalOpperation(operation) {
	if(!this.lastOppOccured) 
		operation();

	this.Clear();
}

function ClearPlaybackFeatures() {
	this.lastOppOccured 	= false;
	this.seekAmount		= this.seekAmountBase;
	StopPlaybackFeature(this.lastOppTimeout);
}


//
// Private Methods 
//
function TriggerPlaybackFeature(featureMethod) {
	this.Clear();
	this.Queue(featureMethod, this.delayInSeconds);
}

function QueueFeatureMethod(featureMethod, timeDelay) {
	if(timeDelay == undefined)
		timeDelay = this.intervalInSeconds;

	this.lastOppTimeout = setTimeout(featureMethod, timeDelay * 1000);
}

function FastForwardIncriment() {
	FastForward(this.GetSeek());	
	this.lastOppOccured = true;
	
	var fastInc = FastForwardClosure(this);
	this.Queue(fastInc);
}

function RewindIncriment() {
	Rewind(this.GetSeek());	
	this.lastOppOccured = true;
	
	var rewInc = RewindClosure(this);
	this.Queue(rewInc);
}

function GetSeekIncriment() {
	var seekInc 	= this.seekAmount + this.seekIncriment;
	this.seekAmount = seekInc > this.seekMax ? this.seekMax : seekInc;

	return this.seekAmount;
}

//
// Helper Functions
//
function StopPlaybackFeature(timeout) {
	if(timeout != null) {
		clearTimeout(timeout);
		timeout = null;
	}
}

function FastForwardClosure(control) {
	var playbackClosure = function() {
		control.FastForwardInc();
	}

	return playbackClosure;
}

function RewindClosure(control) {
	var playbackClosure = function() {
		control.RewindInc();
	}

	return playbackClosure;
}
