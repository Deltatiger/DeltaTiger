<?php

    include 'includes/common.php';
	//So there are two things that can be done. View a post or Edit it which is admin only.

    if(isset($_GET['type']))    {
	//TODO redo this part.
        $type = $_GET['type'];
        if($type == "edit") {
            //We check the session and then allow an edit or something. Must recheck the idea more clearly.
        } else if ($type == "view") {
            if(isset($_GET['id']))  {
                $id = $_GET['id'];
                $sql = "SELECT `blog_post_title`,`blog_post_time`,`blog_post_body`,`blog_post_comment_count`,`blog_post_picture_id`,`blog_post_picture_ext` FROM `deltatiger`.`dt_blog_posts` WHERE `blog_post_id` = '{$id}'";
                $query = $db->query($sql);
                if(mysql_num_rows($query) > 0)  {
                    $result = mysql_fetch_object($query);
                    $blogPostTitle = $result->blog_post_title;
                    $blogPostTime = $result->blog_post_time;
                    $blogPostBody = $result->blog_post_body;
                    $blogPostCommentCount = $result->blog_post_comment_count;
                    $blogPostPictureId = $result->blog_post_picture_id;
					$blogPostPictureExt = $result->blog_post_picture_ext;
					
					$template->set_template_vars(array(
						'POSTTITLE'			=> $blogPostTitle,
						'POSTTIME'			=> 'Posted On : '.$blogPostTime,
						'POSTBODY'			=> $blogPostBody,
						'POSTCOMMENTCOUNT'  => 'There are '.$blogPostCommentCount.' comment(s).',
						'POSTPICTUREPATH'	=> 'blogs/images/'.$blogPostPictureId.'.'.$blogPostPictureExt,
						'POSTTHUMBPATH'		=> 'blogs/thumbnails/t_'.$blogPostPictureId.'.'.$blogPostPictureExt,
						'POSTIMAGEID'		=> $blogPostPictureId,
						'POSTIMAGEEXT'		=> $blogPostPictureExt
					));
                } else {
					//So we dont have post with the given id. Error page should be called.
					//Error::callError();
				}
            } else {
				header('Location:blog.php');
			}
        }
    }  else {
		header('Location:blog.php');
	}
	
	$template->set_page_template('blogpost');
?>
