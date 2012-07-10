<?php
	include '../../includes/common.php';
	
	if(!isset($_GET['input']))	{
		die('Oops You shouldn\'t be Here');
	} else {
		$pageName = $_GET['input'];
	}
	$templateName = 'aj_'.$pageName;
	/*
	 * So There may be n number of pages here so we have to get it and include (?) only the required code .
	 */
	$content = '';
	switch($pageName)	{
		case 'viewposts':
			//This gets all the posts from the DB and displays the results on the DB
			$sql = "SELECT `author`,`content_count`,`id`,`title`,`time` FROM `{$db->return_DB_name()}`.`dt_psg_posts`";
			$query = $db->query($sql);
			if(mysql_num_rows($query) > 0)		{
				$content .= '<table class="psgPostsView">';
				$content .= '<tr><th>Title </th> <th> Content-Count </th> <th> Author </th> <th> Time </th></tr>';
				while($row = mysql_fetch_object($query))	{
					$authorName = get_username_from_id($row->author);
					$content .= '<tr> <td> <a href="#" id="aj_viewpost_id" data-test="'.$row->id.'">'.$row->title.'</a></td>';
					$content .= '<td>'.$row->content_count.'</td>';
					$content .= '<td>'.$authorName.'</td>';
					$content .= '<td>'.$row->time.'</td></tr>';
				}
				$content .= '</table>';
			} else {
				$content = "No Posts Found. Please Visit Again";
			}
			break;
		case 'viewstats':
			/*
			 *	The stats that will be displayed here are: 
			 *  => No. of posts
			 *  => No. of comments
			 *  => No. of files
			 *  => Total Size of the content
			 */
			$sql = "SELECT `content_files` FROM `{$db->return_DB_name()}`.`dt_psg_posts`";
			$query = $db->query($sql);
			$noOfPosts = mysql_num_rows($query);
			$filePath = $ROOT_PATH.'/psg/files/';
			$totalSize = 0.0;
			$totalCount = 0;
			while($row = mysql_fetch_object($query))	{
				//We calculate the size of each object
				//(Each object is sperated by a ,. So we explode it up
				$stringOfContent = $row->content_files;
				$arrayOfFiles = explode(',', $stringOfContent);
				foreach($arrayOfFiles as $fileName)		{
					$fileName = $filePath.trim($fileName);
					if(file_exists($fileName))	{
						$totalSize += round(filesize($fileName) / 1024, 2);
						$totalCount++;
					}
				}
			}
			$content = '<table class="psgPostsStats"';
			$content .= '<tr> <th> No. Of Posts  </th> <td> '.$noOfPosts.' </td> </tr>';
			$content .= '<tr> <th> No. Of Files  </th> <td> '.$totalCount.' </td> </tr>';
			$content .= '<tr> <th> Size of Files </th> <td> '.$totalSize.' KB </td> </tr>';
			$content .= '</table>';
			break;
		case 'newpost':
			$content = '';
			//We give them some input fields and then store all the stuff they give.
			$content .= '<p class="aNewPostHeading"> New Post </p>';
			$content .= '<div class="aNewPostLeftPane"> Post Title </div>';
			$content .= '<div class="aNewPostRightPane"> <input type="text" name="postTitle" /> </div>';
			break;
		case 'newmail':
			break;
	}
	
	$template->set_template_var('CONTENT', $content);
	//Now we load the template.
	$template->set_page_template($templateName);
?>