<?php
	//This page is mainly to view blog images in expanded form.
	include 'includes/common.php';
	
	if(isset($_GET['imageId']) && isset($_GET['imageExt']))		{
		$imageId = $_GET['imageId'];
		$imageExt = $_GET['imageExt'];
		$imagePath = 'blogs/images/'.$imageId.'.'.$imageExt;
		
		$template->set_template_vars(array(
			'IMAGEPATH'		=> 		$imagePath
		));
		$template->set_page_template('viewblogimage');
	} else {
		header('Location:blog.php');
	}
?>