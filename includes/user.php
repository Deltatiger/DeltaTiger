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
			global $session;
			if($session->return_user_login_status() == 1)	{
				//The User is logged in. So we get the stuff from the session class.
				$this->userId = $session->get_userId_from_session();
				$this->userGroup = get_usergroup_from_id($this->userId);
				$this->userName = get_username_from_id($this->userId);
			} else {
				$this->userId = $this->userGroup = $this->userName = 0;
				//Will get the default user group.
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