<?php
	function get_new_image_id()		{
		global $db;
		
		$sql = "SELECT `blog_post_picture_id` FROM `deltatiger`.`dt_blog_posts` ORDER BY `blog_post_time` DESC";
		$query = $db->query($sql);
		
		if(mysql_num_rows($query) > 0 )	{
			//Now lets return the first value.
			$pictureId = mysql_result($query, 0);
		}
		$finalId = $pictureId + rand(1 ,100);
		//TODO check this code with the existing stuff just to make sure it does not exist.
		return $finalId;
	}
	
	function get_up_image_type($typeFull)	{
		if($typeFull == 'image/png')	{ return '.png'; }
		if(($typeFull == 'image/jpeg') || ($typeFull == 'image/pjpeg'))	{ return '.jpeg';}
 	}
	
	function create_thumbnail_image($imageFileCode, $imageFileExt, $filePath)		{
		global $ROOT_PATH;
		$baseImage = $filePath.$imageFileCode.$imageFileExt;
		if(!file_exists($baseImage))	{
			return false;
		} else {
			$thumbnailCode = 't_'.$imageFileCode;
			$thumbnailPath = $ROOT_PATH."blogs/thumbnails/";
			$imageSize = getimagesize($baseImage);
			$imageWidth = $imageSize[0];
			$imageHeight = $imageSize[1];
			
			$newImage = imagecreatetruecolor(100, 100);
			switch($imageFileExt)	{
				case '.jpg':
					//This is to create a file with jpg extension.
					$oldImage = imagecreatefromjpeg($baseImage);
					imagecopyresized($newImage, $oldImage, 0, 0 ,0 ,0, 100, 100, $imageWidth , $imageHeight);
					imagejpeg($newImage, $thumbnailPath.$thumbnailCode.'.jpg' );
					break;
				case '.jpeg':
					//This is to create a file with jpg extension.
					$oldImage = imagecreatefromjpeg($baseImage);
					imagecopyresized($newImage, $oldImage, 0, 0 ,0 ,0, 100, 100, $imageWidth , $imageHeight);
					imagejpeg($newImage, $thumbnailPath.$thumbnailCode.'.jpeg' );
					break;
				case '.png':
					//This is to create a file with png extension.
					$oldImage =imagecreatefrompng($baseImage);
					imagecopyresized($newImage, $oldImage, 0, 0 ,0 ,0, 100, 100, $imageWidth , $imageHeight);
					imagejpeg($newImage, $thumbnailPath.$thumbnailCode.'.png' );
					break;
				default:
					return false;
			}
		}
		return true;
	}
	
	function clearAllBlogData()	{
		//This function cleas all the blog data including DB stuff and images.
		//FAIL. move the function to a seperate page to avoid problems.
		global $db, $ROOT_PATH;
		
		
		//First we will get all the images used in the blogs so that we can delete them .
		$sql = "SELECT `blog_post_picture_id`, `blog_post_picture_ext` FROM `deltatiger`.`dt_blog_posts`";
		$query = $db->query($sql);
		if(mysql_num_rows($query) > 0)	{	
			while($result = mysql_fetch_object($query))	{
				if(file_exists($ROOT_PATH.'blogs/images/'.$result->blog_post_picture_id.'.'.$result->blog_post_picture_ext))	{
					unlink($ROOT_PATH.'blogs/images/'.$result->blog_post_picture_id.'.'.$result->blog_post_picture_ext);
				}
				if(file_exists($ROOT_PATH.'blogs/thumbnails/t_'.$result->blog_post_picture_id.'.'.$result->blog_post_picture_ext))	{
					unlink($ROOT_PATH.'blogs/thumbnails/t_'.$result->blog_post_picture_id.'.'.$result->blog_post_picture_ext);
				}
			}
		}
		//Deleting Database stuff
		$sql = "DELETE FROM `deltatiger`.`dt_blog_posts`";
		$result = $db->query($sql);
		$sql = "DELETE FROM `deltatiger`.`dt_blog_comments";
		
	}
	
	function generateRandString($length)	{
		//This generates a random string of $length charecters long
		$randomString = '';
		$range = 'abcdefghijklmnopqrstuvwxyz1234567890<>?:"{}!@#$%^&*()_+';
		for($i = 0; $i < $length; $i++)	{
			@$randomString .= $range[rand(0,55)];
		}
		return $randomString;
	}
	
	function smd5($string)	{
		//This function just creates the md5 first and then the sha1 of the given string.
		$md5String = md5($string);
		$sha1String = sha1($md5String);
		return $sha1String;
	}
	
	function get_usergroup_from_id ($user , $userIdProvided = 1)	{
		//FIXME this seems to cause lot of problems. Fix is required in the user class
		if($user == '0' || empty($user) || $user == 0)	{
			return false;
		}
		global $db;
		if($userIdProvided == 1)	{
			//User id is given.
			$sql = "SELECT `user_group` FROM `{$db->return_DB_name()}`.`dt_user_info` WHERE `user_id` = '{$user}'";
		} else {
			//User name provided.
			$userNameClear = strtolower($user);
			$sql = "SELECT `user_group` FROM `deltatiger`.`dt_user_info` WHERE `user_name_clean` = '{$userNameClean}'";
		}
		$query = $db->query($sql);
		$result = mysql_fetch_object($query);
		return $result->user_group;
	}
	
	function get_username_from_id($userId)	{
		if($userId == '0' || empty($userId) || $userId == 0)	{
			return false;
		}
		global $db;
		$sql = "SELECT `username` FROM `{$db->return_DB_name()}`.`dt_user_info` WHERE `user_id` = '{$userId}'";
		$query = $db->query($sql);
		$result = mysql_fetch_object($query);
		return $result->username;
	}
?>