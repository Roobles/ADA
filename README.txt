
===================================================
  Ada: Arbitrarily Derived Acronym (Music Player)
===================================================

About:
Ada is a minimalistic PHP/ajax web client front end to MPD.  It has three ultimate goals: simplicity,
compatibility, and completeness.  A large portion of that affects deployment/installation.  It is Ada's
goal to be installed and running upon extraction to a php enabled web server.  For that reason, Ada will
never have a database, require write permissions, or rely on libraries/extensions uncommon in a given 
LAMP setup.  

Ada is currently supported on the Apache web server with PHP modules enabled.  Futher, Ada is supposed
to work and be visually consistent across these web browsers: IE (as far back as IE 6), Firefox, Chrome,
Safari, and Opera.

By the time Ada V. 1.0 is released, Ada should be capable of performing every operation exposed by MPD
api.  It will then be Ada's goal to update its functionality as mpd itself is updated.



Installation:
If you are reading this, and it is in a php enabled web directory, Ada should already be installed and 
working for any unsecured localhost mpd setup.  (If that is not the case, it would be much appreciated if
you would fill out a bug report at https://roobles.webhop.org/bugzilla.)  That said, the official 
installation instructions are:

1) Setup a php enabled apache web server, and enter a web directory (https recommended.)
2) Pull Ada's source from subversion: 'svn co "http://roobles.webhop.org/svn/Ada" ./ada'
3) Configure ada/config.php, setting the proper host, port, and credentials for the mpd server.

[Optional]
4) Change ownership of Ada: 'chown root.[group httpd is running as] -R ada'
5) Change permissions to: 'chmod 750 -R ada'
6) Configure ada/config.php to use authentication, if that is desired. 
	6a) Enable admin tools in the configuration.
	6b) Visit index.php in a web browser, logging in as the default user  [hackme:pleasehackme].
	6c) Click on the admin tab, enter your desired password, and copy the hashed output.
	6d) In config.php, replase 'hackme' with your desired user, and hackme's hash with the output
	    from 6c.  If desired, add additional users using the same process.
	6e) It's advisable to disable admin tools when not actively adding users.
	6f) Yes, a very tedious process.  It's the cost of not requiring a db or write access.
	 


Feedback:
If you come across any bugs, have any suggestions, or wish to submit a patch of any sort, please
visit https://callstack.org/bugzilla to do so.  Current website is also at: 
http://callstack.org/adasite/.

