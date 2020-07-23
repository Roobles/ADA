<?php $RequiresAuth = false; ?>
<?php require_once("share/header-full.php"); ?>
<?php
	if(!$USE_AUTHENTICATION)
		GoHome();

	if(isset($_POST["user"]) && isset($_POST["pass"])) {
		$user 	= $_POST["user"];
		$pass	= $_POST["pass"];
		$pass 	= EncryptPassword($pass);
		$auth 	= Authenticate($user, $pass);

		if($auth)
			Login($user);
	}
?>


<form id="Login" method="post" action="login.php">
	<h1> Welcome to Ada </h1>
	<div class="inputEntry">
		<label id="UserNameLabel" for="UserName"> User: </label>
		<input id="UserName" type="text" name="user" />
	</div>

	<div class="inputEntry">
		<label id="PasswordLabel" for="Password"> Pass: </label>
		<input id="Password" type="password" name="pass" />
	</div>

	<input id="LoginButton" type="submit" value="Login" />
</form>

<?php require_once("share/footer.php"); ?>
