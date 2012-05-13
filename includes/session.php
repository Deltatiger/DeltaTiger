<?php
/**
 * Description of session
 *
 * @author DeltaTiger
 */

//TODO review this class. 
//FIXME session is creating new entry each time we refresh. Somethign wrong.
class session {
    private $userLoggedIn;
    	
	//Ok in the constructer we check if the session is set and update it accordingly.
	function __construct()	{
		session_start();
		global $db;
		if(isset($_SESSION['session_id']) || isset($_COOKIE['cookie_id']))	{
			if(isset($_SESSION['session_id']))	{
				//Ok we got a session. So Lets check it and if less than 5 min lets unset it.
				$sql = "SELECT `user_id`,`create_time`,`last_active_time`, `create_ip` FROM `{$db->return_DB_name()}`.`dt_session_info` WHERE `session_id` = '{$_SESSION['session_id']}'";
				$query = $db->query($sql);
				$userIp = $_SERVER['REMOTE_ADDR'];
				if(mysql_num_rows($query) < 1)	{
					//No session found. So lets unset it and create a new one.
					$sessionId = $this->new_session_id();
					$sessionCreateTime = time();
					//FIXME useing 0 for user_id , user_group. Fix it.
					$_SESSION['session_id'] = $sessionId;
					$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info`(`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$sessionId}','0','0','{$sessionCreateTime}', '{$sessionCreateTime}', '{$userIp}', '0')";
					$db->query($sql);
					$this->userLoggedIn = 0;
				} else {
					//So we got one eh. Lets check if it is less than 5 mins old.
					$result = mysql_fetch_object($query);
					$sessionCreateTime = $result->create_time;
					$currentTime = time();
					if(($currentTime - $sessionCreateTime) >= 300)	{
						//To old. Make them logout.
						$sessionId = $this->new_session_id();
						$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$sessionId}', `user_id` = '0', `user_group` = '0', `create_time` = '{$currentTime}',`last_active_time` = '{$currentTime}', `create_ip` = '{$userIp}', `login_status` = '0' WHERE `session_id` = '{$_SESSION['session_id']}'";
						$query = $db->query($sql);
						$this->userLoggedIn = 0;
						$_SESSION['session_id'] = $sessionId;
					} else {
						//Okay so the session is valid. Lets update it
						$sessionId = $this->new_session_id();
						$_SESSION['session_id'] = $sessionId;
						//Also check if the user ip from the DB and current ip match.
						$currentIp = $_SERVER['REMOTE_ADDR'];
						if($currentIp == $result->create_ip)	{
							$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$sessionId}', `last_active_time` = '{$currentTime}', `create_ip` = '{$userIp}' , `login_status` = '1' WHERE `session_id` = '{$_SESSION['session_id']}'";
							$query = $db->query($sql);
							$this->userLoggedIn = 1;
						} else {
							//Ips dont match. Log them out.
							$sql = "UPDATE `{$db->return_DB_name()}`.`dt_session_info` SET `session_id` = '{$sessionId}', `last_active_time` = '{$currentTime}', `create_ip` = '{$userIp}' , `login_status` = '0' WHERE `session_id` = '{$_SESSION['session_id']}'";
							$query = $db->query($sql);
							$this->userLoggedIn = 0;
						}
					}
				}
			} else {
				//A cookie is set. Lets just get stuff from the db and set up a session.
				$cookieId = $_COOKIE['cookie_id'];
				$sql = "SELECT `set_time`,`user_agent`,`create_ip`,`user_id` FROM `{$db->return_DB_name()}`.`dt_cookie_info` WHERE `cookie_id` = '{$cookieId}'";
				$query = $db->query($sql);
				if(mysql_num_rows($query) > 0)	{
					//Cookie must be less than a week old.
					$result = mysql_fetch_object($query);
					$currentTime = time();
					$cookieMakeTime = $result->set_time;
					if($currentTime - $cookieMakeTime <= 604800)	{
						//So the cookie is still valid. Lets just check the other stuff
						$browser = get_browser(null, true);
						$userAgent = $browser['browser'];
						$userCurrentIp = $_SERVER['REMOTE_ADDR'];
						if($userCurrentIp == $result->create_ip && $userAgent == $result->user_agent)	{
							//Ok so the cookie is correct. So lets set it up in a session.
							$sessionId = $this->new_session_id();
							$userId = $result->user_id;
							$userGroup = get_usergroup_from_id($userId);
							$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info` (`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$sessionId}','{$userId}','{$userGroup}','{$currentTime}','{$currentTime}','{$userCurrentIp}', '1')";
							$db->query($sql);
							$_SESSION['session_id'] = $sessionId;
							$this->userLoggedIn = 1;
						} else {
							//Ok wrong place. so lets unset cookie.
							$sessionId = $this->new_session_id();
							$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info`(`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$sessionId}','0','0','{$currentTime}','{$currentTime}','{$userIp}','0')";
							$db->query($sql);
							setcookie('cookie_id', '' , '-100', '/');
							$_SESSION['session_id'] = $sessionId;
							$this->userLoggedIn = 0;
						}
					} else {
						//Its outdated. Distruction nessecary.
						$sql = "DELETE FROM `{$db->return_DB_name()}`.`dt_cookie_info` WHERE `cookie_id` = '{$cookieId}'";
						$db->query($sql);
						setcookie('cookie_id', '' , '-100', '/');
						$sessionId = $this->new_session_id();
						$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info`(`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$sessionId}','0','0','{$currentTime}','{$currentTime}','{$userIp}','0') ";
						$_SESSION['session_id'] = $sessionId;
						$this->userLoggedIn = 0;
					}
				} else {
					//Hmm. No entry in the database. So lets unset it.
					$sessionId = $this->new_session_id();
					$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info`(`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$sessionId}','0','0','{$currentTime}','{$currentTime}','{$userIp}','0') ";
					$db->query($sql);
					setcookie('cookie_id', '' , '-100', '/');
					$this->userLoggedIn = 0;
					$_SESSION['session_id'] = $sessionId;
				}
			}
		} else {
			//No cookie or session. Lets give them a new one.
			$sessionId = $this->new_session_id();
			$sessionCreateTime = time();
			$userIp = $_SERVER['REMOTE_ADDR'];
			$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_session_info` (`session_id`,`user_id`,`user_group`,`create_time`,`last_active_time`,`create_ip`,`login_status`) VALUES ('{$sessionId}','0','0','{$sessionCreateTime}','{$sessionCreateTime}','{$userIp}','0')";
			$query = $db->query($sql);
			$_SESSION['session_id'] = $sessionId;
			$this->userLoggedIn = 0;
		}
	}
	
	public function userLogin ($userId , $setCookie)	{
		//This logs the user in by setting the session and if required also the cookie
		global $db;
		$userGroup = getUserGroup($userId);
		$createTime = time();
		$userCurrentIp = $_SERVER['REMOTE_ADDR'];
		$currentSessionId = $_SESSION['session_id'];
		$sql = "UPDATE `deltatiger`.`dt_session_info` SET `user_id` = '{$userId}', `user_group` = '{$userGroup}', `create_time` = '{$createTime}',`create_ip` = '{$userCurrentIp}' WHERE `session_id` = '{$currentSessionId}'";
		$db->query($sql);
		$this->userLoggedIn = 1;
		
		if($setCookie == 1)	{
			//We need to set up the cookie. 
			$browser = get_browser(null, true);
			$userAgent = $browser['browser'];
			$cookieId = $this->new_session_id();
			$sql = "INSERT INTO `deltatiger`.`dt_cookie_info`(`set_time`,`user_agent`,`create_ip`,`cookie_id`,`user_id`) VALUES ('{$createTime}','{$userAgent}','{$userCurrentIp}','{$cookieId}','{$userId}')";
			$db->query($sql);
			setcookie('cookie_id',$cookieId,'604800','/');
		}
	}	
	
	public function return_user_login_status()	{
		return $this->userLoggedIn;
	}
	
	public function get_userId_from_session()	{
		global $db;
		$sql = "SELECT `user_id` FROM `deltatiger`.`dt_session_info` WHERE `session_id` = '{$_SESSION['session_id']}'";
		$query = $db->query($sql);
		$result = mysql_fetch_object($query);
		return $result->user_id;
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
	
}

?>
