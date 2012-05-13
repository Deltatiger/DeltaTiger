<?php
	/*
	 * This is the script to deal with posting the blog to the database.
	 */
	//TODO apply some error messages and check the stuff entered.
	
	include '../common.php';
	
	$blogPostTitle = mysql_real_escape_string($_POST['blog_post_title']);
	$blogPostBody = mysql_real_escape_string($_POST['blog_post_body']);
	$blogPostPicture = mysql_real_escape_string($_POST['blog_post_picture_name']);
	
	$blogPostPicture = explode(".", $blogPostPicture);
	$blogPostPictureId = $blogPostPicture[0];
	$blogPostPictureExt = $blogPostPicture[1];
	
	$sql = "INSERT INTO `deltatiger`.`dt_blog_posts`(`blog_post_title`,`blog_post_body`,`blog_post_comment_count`,`blog_post_picture_id`,`blog_post_picture_ext`) VALUES ('{$blogPostTitle}', '{$blogPostBody}','0','{$blogPostPictureId}','{$blogPostPictureExt}')";
	$query = $db->query($sql);
	if($query != 0)	{
		echo 1;
	} else {
		echo mysql_error();
	}
?>