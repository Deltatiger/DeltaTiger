<?php

	include './common.php';
	//This is used by AJAX to log the user in.
	$userName = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	
	$userNameClean = strtolower($userName);
	$sql = "SELECT `password` FROM `deltatiger`.`dt_user_info` WHERE `username_clean` = '{$userNameClean}'";
	$query = $db->query($sql);
	if(mysql_num_rows($query) > 0)	{
		$result= mysql_fetch_object($query);
		if($password == $result->password)	{
			//log the user in.
			return 1;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
?>