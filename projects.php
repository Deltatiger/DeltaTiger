<?php
	/*
	 * @author : Deltatiger.
	 * @firstBuild : 25-04-2012
	 * @description : This page contains the projects sorted and viewable.
	 */

	include 'includes/common.php';
	
	if(isset($_GET['orderby']))	{
		if($_GET['orderby'] == 'topr')	{
			//So we sort them by order of rating sorted by desc
			$sql = "SELECT `project_name`,`project_id`,`project_type`,`project_view_count`,`project_rating` FROM `{$db->returnDBName()}`.`dt_project_info` ORDER BY desc";
			$query = $db->query($sql);
			$projects = '';
			if(mysql_num_rows($query) <= 0)	{
				//No projects found. So lets tell them none was found.
				$projects = 'No Projects Found';
			} else {
				$projects .= '<table class="project_table">';
				while($result = mysql_fetch_object($query)){
					$projects .= '<tr>';
					$projects .= '<td class="project_name">'.$result->project_name.'</td>';
					$projects .= '<td class="project_type">'.$result->project_type.'</td>';
					$projects .= '<td class="project_view_count">'.$result->project_view_count.'</td>';
					$projects .= '<td class="project_rating">'.$result->project_rating.'/5</td>';
					$projects .= '</tr>';
				}
				$projects .= '</table>';
			}
		}
	}
?>