<?php
	
	include '../includes/common.php';
	
	if(isset($_POST['admin_blogpost_back']))	{
		$blogPostTitle = $_POST['blogpost_title_back'];
		$blogPostBody = $_POST['blogpost_body_back'];
		
		$template->set_template_vars(array(
			'BLOGPOSTTITLE'		=> $blogPostTitle,
			'BLOGPOSTBODY'		=> $blogPostBody
		));
	} else {
		$template->set_template_vars(array(
			'BLOGPOSTTITLE'		=> '',
			'BLOGPOSTBODY'		=> ''
		));
	}
			
	$template->set_page_template('admin_blogpost');
?>