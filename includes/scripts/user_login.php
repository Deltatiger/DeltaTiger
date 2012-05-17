<?php

	include '../common.php';
	//This is used by AJAX to log the user in.
	$userName = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	$cookieSet = $_POST['cookieSet'];
	
	$userNameClean = strtolower($userName);
	$sql = "SELECT `password` FROM `{$db->return_DB_name()}`.`dt_user_info` WHERE `username_clean` = '{$userNameClean}'";
	$query = $db->query($sql);
	if(mysql_num_rows($query) <= 0)	{
		//FIXME need to echo out this things.
		//No username found.
		return 2;
	} else {
		$result= mysql_fetch_object($query);
		$smd5Password = smd5($password);
		if($smd5Password == $result->password)	{
			//Its a match.
			return 1;
		} else {
			//Not a match
			return 0;
		}
	}
?>