<?php

	include '../common.php';
	//This is used by AJAX to log the user in.
	$username = $_POST['username'];
	$password = $_POST['password'];
	$setCookie = $_POST['cookieSet'];

	$login = $user->log_user_in($username, $password, $setCookie);
	
	if($login == 1)	{
		//The user was logged it.
		echo 1;
	} else {
		echo 0;
	}
	
?>