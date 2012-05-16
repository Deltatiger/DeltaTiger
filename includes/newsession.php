<?php
	class session	{
		private $userLoginStatus = FALSE;

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
							$this->userLoginStatus = FALSE;
						} else {
							//Session is valid. Lets just check the Ip and be done with it.
							$sessionMakeIp = $result->create_ip;
							if($sessionMakeIp == $userCurrentIp)	{
								//Ip's match. So lets update the session.
								$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `last_active_time` = '{$currentTime}' WHERE `session_id` = '{$sessionId}'";
								$query = $db->query($sql);
								$_SESSION['session_id'] = $newSessionId;
								$this->userLoginStatus = $result->login_status;
							} else {
								//Ip's dont match. Lets unset the session and make a new one.
								$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `user_id` = '0', `user_group` = '0', `create_time` = '{$currentTime}', `last_active_time` = '{$currentTime}', `create_ip` = '{$userCurrentIp}', `login_status` = '0' WHERE `session_id` = '{$sessionId}'";
								$query = $db->query($sql);
								$_SESSION['session_id'] = $newSessionId;
								$this->userLoginStatus = FALSE;
							}
						}
					} else {
						//The session is not in the table. Lets give them a legit one.
						$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info`(`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$newSessionId}', '0','0','{$currentTime}','{$currentTime}','{$userCurrentIp}', '0')";
						$query = $db->query($sql);
						$_SESSION['session_id'] = $newSessionId;
						$this->userLoginStatus = FALSE;
					}
				} else {
					//Cookie is set. There are only few things to check. Lets start.
					$cookieId = $_COOKIE['cookie_id'];
					$sql = "SELECT `set_time`,`user_agent`,`create_ip`,`user_id` FROM `{$db->return_DB_name()}`.`dt_cookie_info` WHERE `cookie_id` = '{$cookieId}'";
					$query = $db->query($sql);
					if(mysql_num_rows($query) > 0)	{
						//First lets check if the user agent and IP match. Else its a waste to check time.
						$result = mysql_fetch_object($query);
						
						$cookieIp = $result->create_ip;
						$cookieUserAgent = $result->user_agent;
						$userId = $result->user_id;
						
						$userIp = $_SERVER['REMOTE_ADDR'];
						$browser = get_browser(null , ture);
						$userAgent = $browser['browser'];
						$currentTime = time();
						$newSessionId = $this->new_session_id();
						
						if($userAgent = $cookieUserAgent && $userIp == $cookieIp)	{
							//Seems legit. Lets check the time and go accordingly.
							$cookieMkTime = $result->set_time;
							if($currentTime = $cookieMkTime <= 604800)	{
								//Seems the cookie is valid. Lets go ahead and set up the proper session.
								$userGroup = get_usergroup_from_id($userId);
								//Lets check if the user already has a session set. If so lets just update it with the stuff.(?)
								$sql = "SELECT `session_id`,`create_ip` FROM `{$db->return_DB_name()}`.`dt_session_info` WHERE `user_id` = '{$userId}'";
								$query = $db->query($sql);
								if(mysql_num_rows($query) > 0)	{
									//Lets just keep note whether the session is set or not.
									$sessionSet = FALSE;
									while ($row = mysql_fetch_object($query))	{
										if($result->create_ip == $cookieIp)	{
											//So the cookie and the session have the same Ip. Its an exact match. Lets log them in
											$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `user_id` = '{$userId}', `user_group` = '{$userGroup}', `last_active_time` = '{$currentTime}', `login_status` = '1' WHERE `session_id` = '{$result->session_id}'";
											$query = $db->query($sql);
											$sessionSet = TRUE;
										}
									}
									if($sessionSet == FALSE)	{
										//Seems none of the IP's matched. Lets create a new session for them.
										$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info`(`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$newSessionId}','{$userId}','{$userGroup}','{$currentTime}','{$currentTime}','{$userIp}','1')";
										$db->query($sql);
									}
								} else {
									//No sessions set for the user. Lets set a new one.
									$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info`(`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$newSessionId}','{$userId}','{$userGroup}','{$currentTime}','{$currentTime}','{$userIp}','1')";
									$db->query($sql);
								}
								$this->userLoginStatus = TRUE;
								$_SESSION['session_id'] = $newSessionId;
							}  else {
								//Cookie is past due time.
								//TODO only ask the user the password and not the username.
								//No session is set and the cookie is overdue. Lets check for a DB entry and modify the session record if found
								$sql = "SELECT `session_id` FROM `{$db->return_DB_name()}`.`dt_session_info` WHERE `user_id` = '{$userId}' AND `create_ip` = '{$cookieIp}'";
								$query = $db->query($sql);
								if(mysql_num_rows($query) > 0)	{
									$result = mysql_fetch_object($query);
									$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `user_id` = '0', `user_group` = '0', 'last_active_time' = '{$currentTime}', `login_status` = '0' WHERE `session_id` = '{$result->session_id}'";
									$query = $db->query($sql);
								} else {
									//Seems no session was set. Let just make a new one.
									$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info`(`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$newSessionId}','0','0','{$currentTime}','{$currentTime}','{$userIp}','0')";
									$query = $db->query($sql);
								}
								$this->userLoginStatus = FALSE;
								$_SESSION['session_id'] = $newSessionId;
								setcookie('cookie_id', '' , '-100', '/');
							}
						} else {
							//The IP and user agent dont match. Unset cookie and check for session and modify it.
							$sql = "SELECT `session_id` FROM `{$db->return_DB_name()}`.`dt_session_info` WHERE `user_id` = '{$userId}' AND `create_ip` = '{$cookieIp}'";
							$query = $db->query($sql);
							if(mysql_num_rows($query) > 0)	{
								//A session for the user is there. Lets just unset stuff.
								$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `user_id` = '0', `user_group` = '0', 'last_active_time' = '{$currentTime}', `login_status` = '0' WHERE `session_id` = '{$result->session_id}'";
								$db->query($sql);
							} else {
								//no session set. Create a new blank session.
								$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info`(`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$newSessionId}','0','0','{$currentTime}','{$currentTime}','{$userIp}','0')";
								$query = $db->query($sql);
							}
							
							$this->userLoginStatus = FALSE;
							$_SESSION['session_id'] = $newSessionId;
							setcookie('cookie_id', '' , '-100', '/');
						}
					} else {
						//No entry found for the cookie. Unset it and create a blank session for the user.
						setcookie('cookie_id', '' , '-100', '/');
						$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info`(`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$newSessionId}','0','0','{$currentTime}','{$currentTime}','{$userIp}','0')";
						$query = $db->query($sql);
						$this->userLoginStatus = FALSE;
						$_SESSION['session_id'] = $newSessionId;
					}
				}
			} else {
				//No session or anything is set. Lets make a new one
				$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info`(`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$newSessionId}', '0','0','{$currentTime}','{$currentTime}','{$userCurrentIp}', '0')";
				$query = $db->query($sql);
				$_SESSION['session_id'] = $newSessionId;
				$this->userLoginStatus = FALSE;
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
		
		public function user_login($userId, $setCookie)	{
			//Since we are updating the session data  we better change the session ID too.
			global $db;
			$userGroup = get_usergroup_from_id($userId);
			$sessionId = $_SESSION['session_id'];
			$newSessionId = $this->new_session_id();
			$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$newSessionId}', `user_id` = '{$userId}', `user_group` = '{$userGroup}', `login_status` = '1' WHERE  `session_id` = '{$sessionId}'";
			$query = $db->query($sql);
			
			if($setCookie == 1)	{
				//TODO apply the set cookie process.
			}
		}
	}
?>