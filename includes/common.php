<?php
	session_start();
	$ROOT_PATH = $_SERVER['DOCUMENT_ROOT'].'/DeltaTiger/';

	include $ROOT_PATH.'includes/mydb.php';
	$db = new mydb();
	//These are some custom functions used in places.
	include $ROOT_PATH.'includes/functions/functions_general.php';

	//TODO put this is a function cause for admin pages it should not affect the state

	$query = "SELECT `config_value` FROM `deltatiger`.`dt_config` WHERE `config_name` = 'site_on'";
	$query = $db->query($query);
	if(mysql_num_rows($query) <= 0) {
		//No site_on statement. Guess it should be off then
		die("Site is Tempraryly Closed");
	} else {
		$result = mysql_fetch_object($query);
		if($result->config_value == "0")    {
			die("Site is Tempraryly Closed");
		}
	}
	//So the site is open. Lets set the vars for the required stuff
	
	include $ROOT_PATH.'includes/newtemplate.php';
	$template = new template();
	include $ROOT_PATH.'includes/cache.php';
	include $ROOT_PATH.'includes/newsession.php';
	$session = new session();
	include $ROOT_PATH.'includes/user.php';
	$user = new user();
	
?>
