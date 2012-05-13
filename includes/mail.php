<?php

	class Dmail	{
		//This class is mainly used to send messages to the subscribers and stuff.
		public static function subscriberMail()	{
			//Now this sends a mail to all the people in the subscribers list.
		}
		
		public static function sendAdminMail($subject, $message)	{
			//This is used in error reporting
			//First we get the main admins email from the configs
			$ROOT_PATH = '/xampp/htdocs/DeltaTiger/';
			$mailFile = md5('rootadminmail').'.txt';
			if( $handle =fopen($ROOT_PATH.'includes/configs/'.$mailFile, 'r'))	{
				$mailId = fread($handle , filesize($ROOT_PATH.'includes/configs/'.$mailFile));
				fclose($handle);
			} else {
				//Serious error. Cant do anything .
			}
			$from = 'Your Website Encountered an Error';
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: sender@sender.com' . "\r\n";
			echo mail(trim($mailId), $subject , $message, $headers);
		}
		
		public static function sendPmNote()	{
			//This sends a notification to the an induvigual person. Maybe a commenter.
		}
	}
	
	Dmail::sendAdminMail('Encountered Error in Website', 'Same as subject ffs');
?>