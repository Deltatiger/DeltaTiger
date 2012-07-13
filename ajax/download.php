<?php
	include '../includes/common.php';
	$fileName = $_GET['name'];
	$filePath = $ROOT_PATH.'/psg/files/'.$fileName;
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header("Content-Disposition: attachment;filename=$fileName ");
	header("Content-Transfer-Encoding: binary ");
	readfile($filePath);
	
?>