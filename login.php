<?php
	/*
	 * This is the login page.
	 */
	include 'includes/common.php';
	$page = 'login_form';
	
	if($session->return_user_login_status() === TRUE)	{
		header('Location:index.php');
	}
	
	$template->set_page_template($page, 'Login - Form');
?>