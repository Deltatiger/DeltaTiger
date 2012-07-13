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
		$filePath = $ROOT_PATH.'psg/files/';
		$files = '';
		$fileCount = 0;
		foreach($_FILES as $file)	{
			if($file['error'] <= 0)	{
				move_uploaded_file($file['tmp_name'], $filePath.$file['name']);
				$files .= $file['name'].',';
				$fileCount++;
			}
		}
		$files = substr($files, 0 ,strlen($files) - 1);
		
		$userId = $session->get_userId_from_session();
		$time = time();
		$sql = "INSERT INTO `{$db->return_DB_name()}`.`dt_psg_posts`(`author`,`content_files`,`content_count`,`desc`,`title`,`time`) VALUES ('{$userId}','{$files}','{$fileCount}','{$postDetails}','{$postTitle}','{$time}')";
		$db->query($sql);
		//TODO Show a message saying the message was entered.
	}
	$template->set_page_template('psgmain1', 'PSG - Main');
?>