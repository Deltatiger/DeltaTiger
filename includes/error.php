<?php

	class Derror	{
		//This class is mainly used to deal with the major errors by adding a log entry and send a mail
		
		public static function setError($errorDesc, $errorPage , $errorType, $errorSendMail = 0)	{
			//We enter into the log then send a mail.
			//We also make a mysql db error entry so that minor errors can be stored there.
			$ROOT_PATH = '/xampp/htdocs/DeltaTiger/';			
			if($errorType == 'mysql')	{
				//Ok so this is a serious problem. Send Mail then enter into log file
				//Also have to do some basic debugging to make it logically capable.
				$errorTime = date('G:i:s / d-m-Y');
				$logFilePath = $ROOT_PATH.'includes/log.txt';
				if(!$handle = fopen($logFilePath , 'a+'))	{
					//Serious Error. Kill Everything.
					die('Shit Happens');
				}
				$errorMessage = "\n {$errorTime} \t || \t{$errorDesc} \n";
				if(fwrite($handle, $errorMessage) === false)	{
					die('Failed to Write to log file');
				}
				unset($handle);
			} else {
				//So the mysql still works. so far.
				include $ROOT_PATH.'includes/mydb.php';
				$db = new mydb(); //We may not have invoked the global $db yet.
				$sql = 'INSERT INTO `deltatiger`.`dt_error_log`(`page`,`desc`) VALUES (\''.$errorPage.'\',\''.$errorDesc.'\')';
				$query = $db->query($sql);
			}
			
			if($errorSendMail == 1)		{
				//Send mail
			}
			
		}
	}
	
	Derror::setError('This is a test','test','test');
?>