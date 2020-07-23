<?php
	// 	+------------------------------+
	//	|    Configuration Options     |	
	// 	+------------------------------+

	// --------------- MPD Configuration ------------------------
	// The information necessary to connect to MPD.  Currently must have full privs.  Scaled privs to come in the future.
	$MPD_HOST	= '127.0.0.1';
	$MPD_PORT	= '6600';
	$MPD_PASS	= null;

	// --------------- Security ---------------------------------
	// Determines whether there will or won't be an authentication process.  Understand
	// that the authentication process is recommended, but is NOT secure.  The 
	// hashed passwords will still be on web space, and transmission will
	// be in plaintext.  For this reason and others, under all circumstances it is 
	// recommended to host ADA with ssl/tsl.
	$USE_AUTHENTICATION 	= false;

	// This setting turns the admin tools page on or off.  Admintools is used for 
	// acquiring the hashed value for authentications.  It is recommended to keep
	// the page off unless actively adding users.
	$ADMIN_TOOLS_ON 	= false;

	// Set your own salt against the MD5 hash.  The philosophy of Ada (no need for write access, no assumptions made about
	// the underlying file structure, etc..) requires having the salt in this location by default.  It is HIGHLY recommended
	// to move the salt somewhere else that php will have read access to, but is still outside of the web directory.
	$MD5_HASH_SALT 		= "22f5bed5db23a8fadc4cb92ad0d00fe1";

	// Authentication of users: 
	// Authentication is handled by a two dimensional array with [n][0] = user, [n][1] = hashed password.
	// The default is user:hackme pass:pleasehackme.  For obvious reasons, you should change this if you go with authentication.
	// Use admintools.php to see what the hash of the password would come out to.
	$User_Credentials 	= Array(
					Array(	
						"hackme",
						"5852d8922e55384bbc6828f76b1425c2"
					)
				);
	
	// --------------- UI and Appearance ------------------------
	// Give the name of a file existing in the css directory that is to be used for the theme.  
	$ADA_THEME		= "ada-theme-dark.css";
?>
