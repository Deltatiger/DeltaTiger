<?php
	class session {
	private $userLoginStatus = FALSE;
	
	function __construct(){
		/*
		 * @description: This function checks if a cookie or a session is set and does the required action.
		 */
		global $db;
		$newSessionId = $this->new_session_id();
		$currentTime = time();
		$currentIp = $_SERVER['REMOTE_ADDR'];
		$currentBrowser = get_browser(null, true);
		$currentBrowser = $currentBrowser['browser'];
		
		$sessionSet = isset($_SESSION['session_id']) ? TRUE : FALSE;
		$cookieSet = isset($_COOKIE['cookie_id']) ? TRUE: FALSE;
		
		if($sessionSet == TRUE)	{
			//A session is set. Lets check its validity.
			$sql  = "SELECT `last_active_time` , `create_ip` , `last_browser` , `login_status` FROM `{$db->return_DB_name()}`.`dt_session_info` WHERE `session_id` = '{$_SESSION['session_id']}'";
			$query = $db->query($sql);
			if(mysql_num_rows($query) > 0)	{
				//A session of the sesssion id was found. Lets update it.
				$result = mysql_fetch_object($query);
				//Check the time , ip and if usernot logged in check if cookie is set. Do the same when session is nto set.
				$lastActiveTime = $result->last_active_time;
				if($currentTime - $lastActiveTime <= 300)	{
					//Its seems valid. Lets check the IP to be sure. Also the browser.
					$userLastIp = $result->create_ip;
					$userLastBrowser = $result->last_browser;
					if($userLastIp == $currentIp && $userLastBrowser == $currentBrowser)	{
						//The Ips and the Browsers match. So its an all go.
						$userLoginStatus = $result->login_status;
						if($userLoginStatus == '1')	{
							//The session is fine. Lets update it.
							$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `last_active_time` = '{$currentTime}' WHERE `session_id` = '{$_SESSION['session_id']}'";
							$query = $db->query($sql);
							$this->userLoginStatus = TRUE;
						} else {
							//The user is not logged in. Lets check if a cookie is set or just update last_Active_time
							if($cookieSet === TRUE)	{
								//A cookie is set. Lets check the cookies validity and then do the needy.
								$cookieId = $_COOKIE['cookie_id'];
								$sql = "SELECT `last_active_time`,`user_agent`,`create_ip`,`user_id` FROM `{$db->return_DB_name()}`.`dt_cookie_info` WHERE `cookie_id` = '{$cookieId}'";
								$query = $db->query($sql);
								if(mysql_num_rows($query) > 0)	{
									$result = mysql_fetch_object($query);
									if($currentTime - $result->last_active_time <= 604800)	{
										if($currentIp == $result->create_ip && $currentBrowser == $result->user_agent)	{
											//All clear.
											$userId = $result->user_id;
											$userGroup = get_usergroup_from_id($userId);
											$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `user_id` = '{$userId}', `user_group` = '{$userGroup}', `last_active_time` = '{$currentTime}', `login_status` = '1' ";
											$query = $db->query($sql);
											$sql = "UPDATE `{$db->return_DB_name()}`.`dt_cookie_info` SET `last_active_time` = '{$currentTime}' WHERE `cookie_id` = '{$cookieId}'";
											$query = $db->query($sql);
											$this->userLoginStatus = TRUE;
										} else {
											//Wrong cookie.
											setcookie('setcookie','',-10,'/');
											$sql = "DELETE FROM `{$db->return_DB_name()}`.`dt_cookie_info` WHERE `cookie_id` = '{$cookieId}'";
											$query = $db->query($sql);
											$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `last_active_time` = '{$currentTime}'";
											$query = $db->query($sql);
											$this->userLoginStatus = FALSE;
										}
									} else {
										//Cookie is long over due. Lets unset it.
										setcookie('setcookie','',-10,'/');
										$sql = "DELETE FROM `{$db->return_DB_name()}`.`dt_cookie_info` WHERE `cookie_id` = '{$cookieId}'";
										$query = $db->query($sql);
										$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `last_active_time` = '{$currentTime}'";
										$query = $db->query($sql);
										$this->userLoginStatus = FALSE;
									}
								} else {
									//No cookie is found in the DB. Lets unset it.
									setcookie('setcookie','',-10,'/');
									$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `last_active_time` = '{$currentTime}'";
									$query = $db->query($sql);
									$this->userLoginStatus = FALSE;
								}
							} else {
								//No cookie is set.
								$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `last_active_time` = '{$currentTime}'";
								$query = $db->query($sql);
								$this->userLoginStatus = FALSE;
							}
						}
					} else {
						//The ip and browser dont match.
						$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `user_id` = '0', `user_group` = '0', `last_active_time` = '{$currentTime}', `login_status` = '0'";
						$query = $db->query($sql);
						$this->userLoginStatus = FALSE;
					}
				} else {
					//Session is too old. Set a new one.
					$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `user_id` = '0', `user_group` = '0', `last_active_time` = '{$currentTime}', `login_status` = '0'";
					$query = $db->query($sql);
					$this->userLoginStatus = FALSE;
				}
			} else {
				//No session is found in the DB.
				$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info` (`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`last_browser`,`login_status`) VALUES ('{$newSessionId}','0','0','{$currentTime}','{$currentTime}','{$currentIp}','{$currentBrowser}','0')";
				$query = $db->query($sql);
				$this->userLoginStatus = FALSE;
			}
		} else {
			//NO session is set. Check if cookie is set and if it is then log them in
			if($cookieSet == TRUE)	{
				$cookieId = $_COOKIE['cookie_id'];
				$sql = "SELECT `last_active_time`,`user_agent`,`create_ip`,`user_id` FROM `{$db->return_DB_name()}`.`dt_cookie_info` WHERE `cookie_id` = '{$cookieId}'";
				$query = $db->query($sql);
				if(mysql_num_rows($query) > 0)	{
					//Checking time , IP, agent
					$result = mysql_fetch_object($query);
					if($currentTime - $result->last_active_time <= 604800)	{
						if($result->create_ip == $currentIp && $result->user_agent == $currentBrowser)	{
							//All Clear.
							$userId = $result->user_id;
							$userGroup = get_usergroup_from_id($userId);
							$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info` (`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`last_browser`,`login_status`) VALUES ('{$newSessionId}','{$userId}','{$userGroup}','{$currentTime}','{$currentTime}','{$currentIp}','{$currentBrowser}','1')";
							$query = $db->query($sql);
							$sql = "UPDATE `{$db->return_DB_name()}`.`dt_cookie_info` SET `last_active_time` = '{$currentTime}' WHERE `cookie_id` = '{$cookieId}'";
							$query = $db->query($sql);
							$this->userLoginStatus = TRUE;
						}
					} else {
						//Too Old.
						setcookie('cookie_id','',-10,'/');
						$sql = "DELETE FROM `{$db->return_DB_name()}`.`dt_cookie_info` WHERE `session_id` = '{$cookieId}'";
						$query = $db->query($sql);
						$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info` (`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`last_browser`,`login_status`) VALUES ('{$newSessionId}','0','0','{$currentTime}','{$currentTime}','{$currentIp}','{$currentBrowser}','0')";
						$query = $db->query($sql);
						$this->userLoginStatus = FALSE;
					}
				} else {
					//Cookie is not in DB.
					setcookie('cookie_id','',-10,'/');
					$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info` (`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`last_browser`,`login_status`) VALUES ('{$newSessionId}','0','0','{$currentTime}','{$currentTime}','{$currentIp}','{$currentBrowser}','0')";
					$query = $db->query($sql);
					$this->userLoginStatus = FALSE;
				}
			}
		}
		$_SESSION['session_id'] = $newSessionId;
		
		global $template;
		$template->set_template_var('USERLOGGEDIN', $this->userLoginStatus);
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
	
	public function get_usergroup_from_session()	{
		global $db;
		$sessionId = $_SESSION['session_id'];
		$sql = "SELECT `user_group` FROM `{$db->return_DB_name()}`.`dt_session_info` WHERE `session_id` = '{$sessionId}'";
		$query= $db->query($sql);
		$result = mysql_fetch_object($query);
		return $result->user_group;
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
			$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_cookie_info` (`set_time`,`last_active_time`,`user_agent`,`create_ip`,`cookie_id`,`user_id`) VALUES ('{$currentTime}','{$currentTime}','{$userAgent}','{$userIp}','{$cookieId}','{$userId}')";
			$query = $db->query($sql);
			setcookie('cookie_id', $cookieId, $expireTime, '/');
			}
	}
}
?>