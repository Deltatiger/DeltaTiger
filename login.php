<?php
	/*
	 * This is the login page.
	 */
	include 'includes/common.php';
	$page = 'login_form';
	//FIXME. logout thing needs to be set up.
		
	if($session->return_user_login_status() === TRUE)	{
		header('Location:index.php');
	}
	
	$template->set_page_template($page, 'Login - Form');
?>