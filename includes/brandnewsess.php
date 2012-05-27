<?php
	class session	{
		
		private $userLoginStatus = FALSE;
		
		function __construct()	{
			//Makes the initial conditions.
			if(isset($_SESSION['session_id']))	{
				$sessionSet = TRUE;
			} else {
				$sessionSet = FALSE;
			}
			
			if(isset($_COOKIE['cookie_id']))	{
				$cookieSet = TRUE;
			} else {
				$cookieSet = FALSE;
			}
		}
		
		private function new_session_id() {
			//Lets generate a random 6 char long string sha1'ed, check it in db and then send it to them.
			$stringToCrpyt = generateRandString(6);
			$encrpytedString = sha1($stringToCrpyt);
		
			global $db;
		
			$sql = "SELECT `session_id` FROM `{$db->return_DB_name()}`.`dt_session_info` WHERE `session_id` = '{$encrpytedString}'";
			$query = $db->query($sql);
		
			while(mysql_num_rows($query) > 0) {
				$stringToCrypt = generateRandString(6);
				$encrpytedString = sha1($stringToCrpyt);
				$query = $db->query($sql);
			}
			return $encrpytedString;
		}
		
		private function new_cookie_id() {
			//Same as the session id but its 8 chars long and also md5's the sha1 string.
			$stringToCrypt = generateRandString(8);
			$encryptStr = md5(sha1($stringToCrypt));
		
			global $db;
		
			$sql = "SELECT `create_ip` FROM `{$db->return_DB_name()}`.`dt_cookie_info` WHERE `cookie_id` = '{$encryptStr}'";
			$query = $db->query($sql);
		
			while(mysql_num_rows($query) > 0) {
				$stringToCrypt = generateRandString(8);
				$encryptStr = md5(sha1($stringToCrypt));
				$query = $db->query($sql);
			}
		
			return $encryptStr;
		}
		
		public function return_user_login_status() {
			return $this->userLoginStatus;
		}
		
		public function get_userId_from_session() {
			global $db;
			$sessionId = $_SESSION['session_id'];
			$sql = "SELECT `user_id` FROM `{$db->return_DB_name()}`.`dt_session_info` WHERE `session_id` = '{$sessionId}'";
			$query = $db->query($sql);
			$result = mysql_fetch_object($query);
			return $result->user_id;
		
		}
		
		public function user_login($userId, $setCookie) {
			//Since we are updating the session data we better change the session ID too.
			global $db;
			$userGroup = get_usergroup_from_id($userId);
			$sessionId = $_SESSION['session_id'];
			$newSessionId = $this->new_session_id();
			$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `user_id` = '{$userId}', `user_group` = '{$userGroup}', `login_status` = '1' WHERE `session_id` = '{$sessionId}'";
			$query = $db->query($sql);
			$_SESSION['session_id'] = $newSessionId;
		
			if($setCookie == 1) {
				//So the userneeds to set the cookie for a week. Lets set it up.
				$browser = get_browser(null, true);
				$userAgent = $browser['browser'];
				$userIp = $_SERVER['REMOTE_ADDR'];
				$cookieId = $this->new_cookie_id();
				$currentTime = time();
				$expireTime = $currentTime + 604800;
				global $ROOT_PATH;
				$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_cookie_info` (`set_time`,`user_agent`,`create_ip`,`cookie_id`,`user_id`) VALUES ('{$currentTime}','{$userAgent}','{$userIp}','{$cookieId}','{$userId}')";
				$query = $db->query($sql);
				setcookie('cookie_id', $cookieId, $expireTime, '/');
			}
		}
	}
?>