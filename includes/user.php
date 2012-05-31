<?php
	/*
	 * Author : DeltaTiger
	 * Desc : This class is used to perform all the user specific calculation.
	 */
	class user	{
		//Some private variables that will be required.
		private $userId;
		private $userGroup;
		private $userName;
		
		//The construct method will use the session class to get the userId and other stuff if they are set.
		function __construct()	{
			global $session, $template;
			if($session->return_user_login_status() == TRUE)	{
				//The User is logged in. So we get the stuff from the session class.
				$this->userId = $session->get_userId_from_session();
				$this->userGroup = $session->get_usergroup_from_session();
				$this->userName = get_username_from_id($this->userId);
			} else {
				$this->userId = $this->userGroup = $this->userName = 0;
				//Will get the default user group.
				global $db;
				$sql = "SELECT `config_value` FROM `{$db->return_DB_name()}`.`dt_config` WHERE `config_name` = 'Default User Group'";
				$query = $db->query($sql);
				$result = mysql_fetch_object($query);
				$this->userGroup = $result->config_value;
			}
			$template->set_template_var('USERGROUP', $this->userGroup);
		}
		
		function log_user_in($username, $password, $setCookie)	{
			global $db;
			$usernameClean = strtolower(mysql_real_escape_string(trim($username)));
			$passwordClean = mysql_real_escape_string(trim($password));
			$passwordMd5 = smd5($passwordClean);
			$sql = "SELECT `user_id`,`password` FROM `{$db->return_DB_name()}`.`dt_user_info` WHERE `username_clean` = '{$usernameClean}'";
			$query = $db->query($sql);
			if(mysql_num_rows($query) > 0)	{
				$result = mysql_fetch_object($query);
				if($result->password == $passwordMd5)	{
					//The password is a match.
					global $session;
					$userId = $result->user_id;
					$session->user_login($userId, $setCookie);
					return 1;
				} else	{
					return 0;
				} 
			} else {
				return 0;
			}
		}
		
		function returnUserName()	{
			return $this->userName;
		}
		
		function returnUserId()	{
			return $this->userId;
		}
		
		function returnUserGroup()	{
			return $this->userGroup;
		}
	}
?>