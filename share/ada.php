<?php
	//includes
	require("tab.php");
	
	// Global definitions
	define("INDEX_SEARCH",          "search");
        define("MAX_NAME_LENGTH",       70);

	// Section Builders
	function PrintHeaderTools () {
		 if(IsLoggedIn())
			 echo BuildClickableUnorderedLink("LogOut", "Logout();", "Logout");
	}

	// Utility Functions
	function PrintButton($name, $submitContent, $mouseDownHandler, $mouseOutHandler) {
		echo BuildButton($name, $submitContent, $mouseDownHandler, $mouseOutHandler);
	}

	function PrintSelect($selectArgs) {
		$styleArr = Array (
			"TableStyle01",
			"TableStyle02"
		);

		echo "<ul>\n";
		for($i = 0; $i < count($selectArgs); $i++)
			echo sprintf(
					"<li class='%s'> %s </li>\n", 
					$styleArr[$i % count($styleArr)], 
					BuildClickableLink("", sprintf("%s return false;", $selectArgs[$i]['onClick']), $selectArgs[$i]['spanValue'])
			);
		
		echo "</ul>\n";
	}

	function PrintCheckboxList($checkboxArgs) {
		echo "<ul>\n";
		foreach($checkboxArgs as $checkboxArg) {
			echo sprintf(
				"\t<li> <input %s class='outputEntry' onclick='%s' type='checkbox' /> %s </li>\n", 
				isset($checkboxArg['checkId']) ? sprintf("id='%s'", $checkboxArg['checkId']) : "",
				$checkboxArg['checkHandler'],
				$checkboxArg['checkLabel']
			);
		}
		echo "</ul>\n";
	}

	function BuildButton($name, $clickHandler, $mouseDownHandler, $mouseOutHandler) {
		$eventString	= "";
		$eventString	= IsEmpty($clickHandler) 	? $eventString : sprintf("%s onclick='%s'", $eventString, $clickHandler);
		$eventString	= IsEmpty($mouseDownHandler) 	? $eventString : sprintf("%s onmousedown='%s'", $eventString, $mouseDownHandler);
		$eventString	= IsEmpty($mouseOutHandler) 	? $eventString : sprintf("%s onmouseout='%s'", $eventString, $mouseOutHandler);

		$button 	= sprintf("<input id='Button%s' type='button' value='%s' %s/>", $name, $name, $eventString);

		return $button;
	}

	function BuildClickableUnorderedLink($id, $onClick, $spanVal) {
		return sprintf("<li> %s </li>", BuildClickableLink($id, $onClick, $spanVal));
	}

	function BuildClickableLink($id, $onClick, $spanVal) {
		$idText = isset($id) && $id != "" ? sprintf("id='%s'", $id) : "";
		return sprintf("<span %s class='clickable' onclick='%s'> %s </span>", $idText, $onClick, $spanVal);
	}

	function IsEmpty($strVal) {
		return !isset($strVal) || $strVal == null || $strVal == "";
	}
?>
