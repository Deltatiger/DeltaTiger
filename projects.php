<?php
	/*
	 * @author : Deltatiger.
	 * @firstBuild : 25-04-2012
	 * @description : This page contains the projects sorted and viewable.
	 */

	include 'includes/common.php';
	$templateName = 'projects';
	$pageName = 'Projects - Home';
	
	//We see if any modifier is enables. IF so use it else dont.
	if(isset($_GET['orderby']))	{
		switch($_GET['orderby'])	{
			case 'topr':
				$orderBy = 'ORDER BY `project_rating` DESC';
				break;
			case 'topd':
				$orderBy = 'ORDER BY `project_download_count` DESC';
				break;
			case 'ntopr':
				$orderBy = 'ORDER BY `project_rating`';
				break;
			case 'ntopd':
				$orderBy = 'ORDER BY `project_download_count`';
				break;
			case 'topv':
				$orderBy = 'ORDER BY `project_view_count`';
				break;
			default:
				$orderBy = '';
		}
	} else {
		$orderBy = '';
	}
	
	//Now lets get the actual projects from the DB
	$sql = "SELECT `project_name`, `project_id`,`project_type`,`project_view_count`, `project_download_count`,`project_rating` FROM `{$db->return_DB_name()}`.`dt_project_info` {$orderBy}";
	$query = $db->query($sql);
	$projects = '';
	if(mysql_num_rows($query) > 0)		{
		$projects .= '<table class="project_table">';
		while($result = mysql_fetch_object($query)){
			$projects .= '<tr>';
			$projects .= '<td class="project_name"><a href="viewporject.php?projectid='.$result->project_id.'>"'.$result->project_name.'</a></td>';
			$projects .= '<td class="project_type">'.$result->project_type.'</td>';
			$projects .= '<td class="project_view_count">'.$result->project_view_count.'</td>';
			$projects .= '<td class="project_download_count">'.$results->project_download_count.'</td>';
			$projects .= '<td class="project_rating">'.$result->project_rating.'/5</td>';
			$projects .= '</tr>';
		}
		$projects .= '</table>';
	} else {
		$projects .= 'No Projects Found. Try Again Later';
	}
	
	$template->set_template_var('PROJECTS', $projects);
	
	$template->set_page_template($templateName, $pageName);
?>