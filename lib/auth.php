<?php
	// BASE DEFINITIONS
	define("UNKNOWN_USER", 		"user_unknown");
	define("LOGIN_FILE",		"login.php");
	define("MAIN_PAGE",		"index.php");
	define("DEFAULT_HASH_SALT", 	"22f5bed5db23a8fadc4cb92ad0d00fe1");

	// COMPOUND DEFINITIONS
	define("LOGIN_REGEX",	sprintf("/%s$/i", LOGIN_FILE));

	// SESSION NAMES
	define("SESSION_USER", "user");




	//------  Start of Authentication Process ------//
	if($USE_AUTHENTICATION) { 
		if(!IsLoggedIn() && !IsSafePage())
			RedirectPage(LOGIN_FILE);
	}
	else if(IsLoggedIn())
		Logout();
	//------  End of Auth ------//




	// Utility Functions
	function RedirectPage($page_name) {
		header( sprintf('Location: %s', $page_name));
		die("You don't belong here.");
	}

	function GoHome() {
		RedirectPage(MAIN_PAGE);
	}
	
	function GetCredentials() {
		global $User_Credentials;
		return isset($User_Credentials) ? $User_Credentials : Array(Array());
	}

	function Authenticate($user, $pass) {

		$isLegit        = false;
		$creds          = GetCredentials();

		foreach($creds as $cred) {
			if($cred[0] != $user)
				continue;
			if($cred[1] == $pass)
			{
				$isLegit = true;
				break;
			}
		}

		return $isLegit;
	}

	function EncryptPassword($pass) {
		global $MD5_HASH_SALT;
		if (!isset($MD5_HASH_SALT) || $MD5_HASH_SALT == "")
			$MD5_HASH_SALT = DEFAULT_HASH_SALT;

		return md5($MD5_HASH_SALT . $pass);
	}

	function IsLoggedIn() {
		$loggedIn = isset($_SESSION[SESSION_USER]) && $_SESSION[SESSION_USER] != UNKOWN_USER;
		return $loggedIn;
	}

	function IsSafePage() {
		global $RequiresAuth;
		if(!isset($RequiresAuth))
			$RequiresAuth = true;

		return !$RequiresAuth;
	}

	function Login($user) {
		$_SESSION[SESSION_USER] = $user;
		GoHome();
	}

	function Logout() {
		unset($_SESSION[SESSION_USER]);
		GoHome();
	}
?>
