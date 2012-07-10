<?php
	class template	{
		
		//The stuff required
		private $templatePath;
		private $templateVars;
		private $currentTheme;
		
		function __construct()	{
			//We see if the template exists.
			global $db, $ROOT_PATH;
			$sql = "SELECT `config_value` FROM `{$db->returnDBName()}`.`dt_config` WHERE `config_name` = 'theme'";
			$query = $db->query($sql);
			if(mysql_num_rows($query) < 1)	{
				die('Theme is Not set. Please Contact Admin Here.');
			}
			$result = mysql_fetch_object($query);
			$theme = $result->config_value;
			
			$path = $ROOT_PATH.'templates/';
			$filePath = $path.$theme;
			if(!is_dir($filePath))	{
				//Template files not found. So lets Exit out of here.
				die('Template Files Not found in '.$filePath.'. Contact Admin Here.');
			}
			$this->currentTheme = $theme;
			$this->templatePath = $filePath.'/';
		}
		
		public function setNewTheme($themeName)	{
			global $db;
			$sql = "UPDATE `dt_config`.`{$db->returnDBName}` SET `config_value` = '{$themeName}' WHERE `config_name` = 'theme'";
			$query = $db->query($sql);
		}
		
		public function set_page_template($templateName , $pageTitle = "")	{
			$this->set_template_var('TITLEHERE', $pageTitle);
			//Now lets include the required page.
			if($this->check_cache_validity($templateName) == 1)	{
				//So we have a valid file. Lets include it.
				global $ROOT_PATH;
				$cacheFilePath = $ROOT_PATH.'cache/cached_files/cache.'.$this->currentTheme.'.'.$templateName.'.php';
				include $cacheFilePath;
			}
		}
		
		public function set_template_vars($theVars)	{
			if(!is_array($theVars))	{
				die('Wrong usage of function setTemplateVars. Use setTemplateVar instead.');
			}
			foreach($theVars as $varName => $varValue)	{
				$this->templateVars[$varName] = $varValue;
			}
		}
		
		public function set_template_var($varName, $varValue)	{
			$this->templateVars[$varName] = $varValue;
		}
		
		private function make_template_cache($fileName)	{
			$cache = new cache();
			$cache->make_cache_file($fileName, $this->currentTheme, $this->templateVars);
		}
	
		private function check_cache_validity($templateName)	{
			//Now lets get the required data from the DB and verify it .
			global $db;
			$sql = "SELECT `cache_time` FROM `{$db->returnDBName()}`.`dt_cache_info` WHERE `cache_page_name` = '$templateName'";
			$query = $db->query($sql);
			if(mysql_num_rows($query) >= 1)	{
				//So the page has not been cached. So Lets check if it is available in the first place.
				$filePath = $this->templatePath.$templateName.'.html';
				if(!file_exists($filePath))	{
					//So the file does not exist. Lets Through out an error and shut down.
					die('Template file '.$templateName.' not found.');
					//Lets remove the DB entry.
					$sql = "DELETE FROM `{$db->returnDBName()}`.`dt_cache_info` WHERE `cache_page_name` = '$templateName'";
					$query = $db->query($sql);
				} else {
					$result = mysql_fetch_object($query);
					$cacheTime = $result->cache_time;
					$currentTime = time();
					global $ROOT_PATH;
					if($currentTime - $cacheTime > 604800)	{
						//The template file is invalid. Lets delete it and make a new one.
						$tempFilePath = $ROOT_PATH.'cache/cached_files/cache.'.$this->currentTheme.'.'.$templateName.'.php';
						unlink($tempFilePath);
						//Making a new one.
						$this->make_template_cache($templateName);
						return true;
					} else {
						//So the template should be valid. Lets check if it is there.
						$tempFilePath = $ROOT_PATH.'cache/cached_files/cache.'.$this->currentTheme.'.'.$templateName.'.php';
						if(!file_exists($tempFilePath))	{
							//So the file is not there. It must have been deleted by delete_cache_files()
							//So lets make a new one.
							$this->make_template_cache($templateName);
							return true;
						} else {
							//So the file exists and it is valid.
							return true;
						}
					}
				}
			} else {
				//So there isn't cached yet. Lets make a new one.
				$this->make_template_cache($templateName);
				return true;
			}
		}
		
		public function return_temp_path()	{
			return $this->templatePath;
		}
	}
?>