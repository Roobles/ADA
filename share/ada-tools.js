//
//  Generic functions
//
function IsEmpty(strVal) {
	return strVal == null || strVal == "";
}

function HideElement(element) {
	element.className = "hidden";
}

function ClearElement(element) {
	element.className = "";
}

function DisplayDivById(divName, isDisplayed) {

	var element 	= document.getElementById(divName);

       	if(!element) 
       		return false;	       

	if(isDisplayed) 
		ClearElement(element);
	else
		HideElement(element);

	return true;
}

//
// Javascript Workarounds
//
function TrueEscape(url) {
	url = escape(url);
	url = url.replace(/\+/g, "%2B");

	return url;
}

//
// CSS hacks/workarounds.   Cause... Yeah, I'm that desperate at this point.
//
function SetContentHeight() {
	var heightRatio		= .85;
	var minHeight		= 50;
	var approxPadding	= 175;
	var sectionHeight 	= Math.floor((GetWindowHeight() - approxPadding) * heightRatio);
	var contentDiv		= document.getElementById("TabbedContent");

	if(sectionHeight < minHeight)
		sectionHeight	= minHeight;

	if(contentDiv != undefined)
		contentDiv.style.height = sectionHeight + "px";
}

function GetWindowHeight() {
	var winHeight = 0;
	if( typeof( window.innerWidth ) == 'number' ) 
		winHeight = window.innerHeight; //Non-IE

	else if( document.documentElement &&  document.documentElement.clientHeight ) 
		winHeight = document.documentElement.clientHeight; //IE 6+ in 'standards compliant mode'

	else if( document.body && document.body.clientHeight ) 
		winHeight = document.body.clientHeight; //IE 4 compatible

	return winHeight;
}

//
// Closure functions
//
function SelectMethod(method, para) {
	function selectFunc() { method(para); }
	return selectFunc;
}
