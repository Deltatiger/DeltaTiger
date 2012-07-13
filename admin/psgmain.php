<?php
	//This is the main page of PSG related page.
	include '../includes/common.php';
	
	//So here we maintain a record of all the stuff sent so for , from whom , ip, time etc etc
	
	if(isset($_GET['p']))	{
		$pageLink = $_GET['p'];
	}
	if(isset($_POST['submitPost']))	{
		//So a form is submitted and hence we fill up the rightMainBar with the content.
		//FIXME complete this
		$postTitle = $_POST['postTitle'];
		$postDetails = $_POST['postDetails'];
		if(strlen($postTitle) >= 5 && strlen($postDetails) >= 15)	{
			//Starting file processing
			foreach($_FILES as $file)	{
				
			}
		}
	}
	$template->set_page_template('psgmain1', 'PSG - Main');
?>