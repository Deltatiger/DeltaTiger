<?php

	include '../includes/common.php';
	
	$blogPostTitleErrors = false;
	$blogPostBodyErrors = false;
	$blogPostPictureErrors = false;
	
	if(isset($_POST['blogpost_preview']))	{
		$blogPostTitle = $_POST['blog_post_title'];
		$blogPostBody = $_POST['blog_post_body'];
		$errorMessages = "ERRORS :";
		
		//Now lets check them and make sure that the content are valid.
		if(strlen(trim($blogPostTitle)) <= 0)	{
			$blogPostTitleErrors = true;
			$errorMessages.= "Title field is too short. <br />";
		}
		if(strlen(trim($blogPostBody)) <= 20)	{
			$blogPostBodyErrors = true;
			$errorMessages .= "The Blog field is too short. <br />";
		}
		
		//Now lets Check the uploaded image but before that lets see if there is an error with the title and body
		if($blogPostTitleErrors == false && $blogPostBodyErrors == false)	{
			if($_FILES["blog_post_picture"]["error"] > 0) {
				$blogPostPictureErrors = true;
				$errorMessages .= "The Picture Has An Error. <br />";
			} elseif ($_FILES['blog_post_picture']['size'] > 0 && $_FILES['blog_post_picture']['size'] < 5000000 && is_uploaded_file($_FILES['blog_post_picture']['tmp_name']))	{
				if (($_FILES['blog_post_picture']['type'] == 'image/jpeg') || ($_FILES['blog_post_picture']['type'] == 'image/pjpeg') || ($_FILES['blog_post_picture']['type'] == 'image/png'))	{
					$imageFileCode = get_new_image_id();
					$uploadDir = $ROOT_PATH.'blogs/images/';
					$tempName = $_FILES['blog_post_picture']['tmp_name'];
					$fileExt = get_up_image_type($_FILES['blog_post_picture']['type']);
					$fileFullPath = $uploadDir.$imageFileCode.$fileExt;
					move_uploaded_file($tempName, $fileFullPath);
					//Now Before We update the database we should check if the file does exists.
					if(file_exists($fileFullPath))	{
						//Ok the file is here now lets put the code value so that AJAX can do the rest.
						$pictureUpCodeField = '<input type="text" value="'.$imageFileCode.$fileExt.'" name="image_file_code" id="image_file_code" hidden="hidden" />';
					} else {
						$blogPostPictureErrors = true;
						$pictureUpCodeField = "";
						$errorMessages .= 'The Picture Does Not Exists after uploading.';
					}
					//Now lets create a thumbnail for display.
					if(create_thumbnail_image($imageFileCode, $fileExt, $uploadDir) == false)	{
						$blogPostPictureErrors = true;
						$errorMessages .= "The Thumbnail could not be created >> {$imageFileCode} >> {$fileExt}";
						$imageTagCode = "";
					} else {
						$thumbnailPath = '/DeltaTiger/blogs/thumbnails/t_'.$imageFileCode.$fileExt;
						$imageTagCode = '<img src="'.$thumbnailPath.'" />';
					}
				} else {
					$blogPostPictureErrors = true;
					$errorMessages .= "Invalid Type :".$_FILES['blog_post_picture']['type'];
				}
			} else {
				//The file is too small or too large.
				$blogPostPictureErrors = true;
				$errorMessages .= "The Blog Post Picture is Too large. <br />";
			}
		}
	} else {
		//this page should not be open.
		die("Wrong Page.");
	}
	//Ok so we need to make sure that there are no errors before we post the entry.
	if($blogPostTitleErrors == false && $blogPostBodyErrors == false && $blogPostPictureErrors == false)	{
		$ajaxFieldCode = '<input type ="submit" value="Post Blog" onClick="postblog();" />';
	} else {
		$ajaxFieldCode = $errorMessages;
	}
		
	$template->set_template_vars(array(
		'BLOGPOSTTITLE' => $blogPostTitle,
		'BLOGPOSTBODY' => nl2br($blogPostBody),
		'BLOGPOSTBODYBACK' => $blogPostBody,
		'THUMBNAILCODE' => $imageTagCode,
		'PICTURECODE'	=> $blogPostPictureErrors == true ? '' : $pictureUpCodeField,
		'AJAXFIELDCODE' => $ajaxFieldCode
	));
	
	$template->set_page_template('admin_p_blogpost');
?>