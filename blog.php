<?php

    include 'includes/common.php';
    
    //Now lets get all the blog posts from the database
    $sql = "SELECT `blog_post_id`,`blog_post_title`,`blog_post_time`,`blog_post_body`,`blog_post_comment_count`,`blog_post_picture_id`,`blog_post_picture_ext` FROM `deltatiger`.`dt_blog_posts` ORDER BY `blog_post_id` DESC";
	$query = $db->query($sql);
	$blogPost = '';
	if(mysql_num_rows($query) > 0)	{
		while($result = mysql_fetch_object($query))	{
			$blogPostId = $result->blog_post_id;
			$blogPostTitle = $result->blog_post_title;
			$blogPostBody = $result->blog_post_body;
			$blogPostTime = $result->blog_post_time;
			$blogPostCommentCount = $result->blog_post_comment_count;
			$blogPostPictureFile = $result->blog_post_picture_id.'.'.$result->blog_post_picture_ext;
			$blogPostPicturePath = 'blogs/thumbnails/t_'.$blogPostPictureFile;
			$blogPost .= '<div class ="blogPost">';
			$blogPost .= '<div class ="blogPostTitle"> <a href="blogpost.php?type=view&id='.$blogPostId.'">'.$blogPostTitle.'</a></div>';
			$blogPost .= '<div class ="blogPostPictureThumb"> <img src="'.$blogPostPicturePath.'" align="center"/></div>';
			$blogPost .= '<div class ="blogPostBody">'.$blogPostBody.'</div>';
			$blogPost .= '<div class ="blogPostTime">'.$blogPostTime.'</div>';
			$blogPost .= '<div class ="blogPostCommentCount"> There are '.$blogPostCommentCount.' comments entered. </div>';
			$blogPost .= '</div>';
		}
	} else {
		$blogPost = '<div class="emptyBlog"> Currently There Are No Blog Posts. Please Visit Later. </div>';
	}
	$template->set_template_vars(array(
		'BLOGPOST'	=> $blogPost
	));
    
    $template->set_page_template('blog', 'Blog Page');
?>
