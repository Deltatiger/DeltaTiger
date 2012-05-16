<?php
	class session	{
		//FIXME. Rewrite the session class again. Starting from Scratch.
		private $userLoginStatus;

		function __construct(){
			//Checks if the session is set and acts accordingly.
			global $db;
			$currentTime = time();
			$newSessionId = $this->new_session_id();
			$userCurrentIp = $_SERVER['REMOTE_ADDR'];
						
			if(isset($_SESSION['session_id']) || isset($_COOKIE['cookie_id']))	{
				if(isset($_SESSION['session_id']))	{
					//Now a session is set. Lets check if the session is valid.
					$sessionId = $_SESSION['session_id'];
					$sql = "SELECT * FROM `{$db->return_DB_name()}`.`dt_session_info` WHERE `session_id` = '{$sessionId}'";
					$query = $db->query($sql);
					if(mysql_num_rows($query) > 0)	{
						//Session exists. Lets check it and then do what is needed.
						$result = mysql_fetch_object($query);
						$sessionMakeTime = $result->last_active_time;
						if($currentTime - $sessionMakeTime >= 300)	{
							//session is too old. Lets unset it.
							$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `user_id` = '0', `user_group` = '0', `create_time` = '{$currentTime}', `last_active_time` = '{$currentTime}', `create_ip` = '{$userCurrentIp}', `login_status` = '0' WHERE `session_id` = '{$sessionId}'";
							$query = $db->query($sql);
							$_SESSION['session_id'] = $newSessionId;
							$this->userLoginStatus = 0;
						} else {
							//Session is valid. Lets just check the Ip and be done with it.
							$sessionMakeIp = $result->create_ip;
							if($sessionMakeIp == $userCurrentIp)	{
								//Ip's match. So lets update the session.
								$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `last_active_time` = '{$currentTime}' WHERE `session_id` = '{$sessionId}'";
								$query = $db->query($sql);
								$_SESSION['session_id'] = $newSessionId;
								$this->userLoginStatus = 1;
							} else {
								//Ip's dont match. Lets unset the session and make a new one.
								$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `user_id` = '0', `user_group` = '0', `create_time` = '{$currentTime}', `last_active_time` = '{$currentTime}', `create_ip` = '{$userCurrentIp}', `login_status` = '0' WHERE `session_id` = '{$sessionId}'";
								$query = $db->query($sql);
								$_SESSION['session_id'] = $newSessionId;
								$this->userLoginStatus = 0;
							}
						}
					} else {
						//The session is not in the table. Lets give them a legit one.
						$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info`(`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$newSessionId}', '0','0','{$currentTime}','{$currentTime}','{$userCurrentIp}', '0')";
						$query = $db->query($sql);
						$_SESSION['session_id'] = $newSessionId;
						$this->userLoginStatus = 0;
					}
				} else {
					//COOKIE is set. check it up.
					//FIXME finish the cookies.
				}
			} else {
				//No session or anything is set. Lets make a new one
				$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info`(`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$newSessionId}', '0','0','{$currentTime}','{$currentTime}','{$userCurrentIp}', '0')";
				$query = $db->query($sql);
				$_SESSION['session_id'] = $newSessionId;
				$this->userLoginStatus = 0;
			}
		}
		
		private function new_session_id()	{
			//Lets generate a random 6 char long string sha1'ed, check it in db and then send it to them.
			$stringToCrpyt = generateRandString(6);
			$encrpytedString = sha1($stringToCrpyt);
			
			global $db;
			
			$sql = "SELECT `session_id` FROM `{$db->return_DB_name()}`.`dt_session_info` WHERE `session_id` = '{$encrpytedString}'";
			$query = $db->query($sql);
			
			while(mysql_num_rows($query) > 0)	{
				$stringToCrypt = generateRandString(6);
				$encrpytedString = sha1($stringToCrpyt);
				$query = $db->query($sql);
			}
			
			return $encrpytedString;
		}
		
		public function return_user_login_status()	{
			return $this->userLoginStatus;
		}
		
		public function get_userId_from_session()	{
			global $db;
			$sessionId = $_SESSION['session_id'];
			$sql = "SELECT `user_id` FROM `{$db->return_DB_name()}`.`dt_session_info` WHERE `session_id` = '{$sessionId}'";
			$query = $db->query($sql);
			$result = mysql_fetch_object($query);
			return $result->user_id;
			
		}
	}
?>