<?php
	/*
	 * This is the login page.
	 */
	include 'includes/common.php';
	$page = 'login_form';
		
	if($session->return_user_login_status() == 1)	{
		header('Location:index.php');
	}
	
	$template->set_page_template($page, 'Login - Form');
?>