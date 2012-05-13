<?php
	/*
	 * This is the login page.
	 */
	include 'includes/common.php';
	$page  = 'login';
	
	/* if($session->returnUserLoginStatus() == 1)	{
		header('Location:index.php');
	} */
	
	$template->set_page_template($page);
?>