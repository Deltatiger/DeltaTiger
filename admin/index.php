<?php
	//This is the admin index page.
	include '../includes/common.php';
	
	header('Location:adminlogin.php');
	
	$template->set_page_template('admin_index');
	
?>