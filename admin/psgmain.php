<?php
	//This is the main page of PSG related page.
	include '../includes/common.php';
	
	//So here we maintain a record of all the stuff sent so for , from whom , ip, time etc etc
	$pageLink = $_GET['p'];
	
	$template->set_page_template('psgmain1', 'PSG - Main');
?>