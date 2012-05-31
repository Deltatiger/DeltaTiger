<?php
	class cache	{
		//This class takes care of all the caching jobs
		//TODO Clean up the class
		private $cachePath;
		private $templateVars;
		
		function __construct()	{
			//Lets set up the cache path then move on.
			global $ROOT_PATH, $db;
			$cachePath = $ROOT_PATH.'cache/cached_files';
			if(is_dir($cachePath))	{
				//So the folder exists. Lets put this in a var
				$this->cachePath = $cachePath.'/';
			} else {
				//The folder does not exist. Lets make one.
				$cachePath = $ROOT_PATH.'cache';
				if(is_dir($cachePath))	{
					$cachePath = $ROOT_PATH.'cache/cached_files';
					mkdir($cachePath);
				} else {
					mkdir($cachePath);
					$cachePath = $cachePath.'/cached_files';
					mkdir($cachePath);
				}
				$this->cachePath = $cachePath.'/';
			}
		}
		
		public function make_cache_file($fileName, $theme, $templateVars)	{
			global $ROOT_PATH;
			$templateFilePath = $ROOT_PATH.'templates/'.$theme.'/'.$fileName.'.html';
			//We put the template vars in a private var so we can acces it from the
			//replace_temp_vars method.
			$this->templateVars = $templateVars;
			if(file_exists($templateFilePath))	{
				//So the File exists so lets make a cache from it.
				$templateFileContents = file_get_contents($templateFilePath);
				//Now lets include the files that are in the template.
				$templateFileContents = $this->include_files($templateFileContents);
				//Now lets replace the conditions with php tags.
				$templateFileContents = $this->replace_conditions($templateFileContents);
				//Now lets replace all the template vars in the template with the temp_vars given in the template class
				$templateFileContents = $this->replace_temp_vars($templateFileContents);
				//Now we write the details into a new file.
				$cacheFilePath = $this->cachePath.'cache.'.$theme.'.'.$fileName.'.php';
				//Finally lets replace the {CACHETIME} with current time in d-M-y
				$currentTime = date(' H : i : s on d-M-Y');
				$templateFileContents = str_replace('{CACHETIME}', $currentTime, $templateFileContents);
				$fp = fopen($cacheFilePath, 'w');
				fwrite($fp, $templateFileContents);
				fclose($fp);
				//Now we add the entry into a DB.
				global $db;
				//If a entry exists we update it. Else we add a new one.
				$currentTime = time();
				$sql = "SELECT `cache_time` FROM `{$db->returnDBName()}`.`dt_cache_info` WHERE `cache_page_name` = '$fileName'";
				$query = $db->query($sql);
				if(mysql_num_rows($query) > 0)	{
					//The query exists . SO lets update the time.
					$sql = "UPDATE `{$db->returnDBName()}`.`dt_cache_info` SET `cache_time` = '$currentTime' WHERE `cache_page_name` = '$fileName'";
					$query = $db->query($sql);
				} else {
					$sql = "INSERT INTO `{$db->returnDBName()}`.`dt_cache_info`(`cache_time`,`cache_page_name`) VALUES ('{$currentTime}','{$fileName}')"; 
					$query = $db->query($sql);				}
			} else {
				die($templateFilePath);
			}
		}
		
		private function replace_conditions($fileContents)	{
			//This function replaces all the conditions that are in the template file.
			//The generel format is <!-- IF|ELSEIF (not) VARNAME && | || ... -->
			preg_match_all("/<!--[ ]*(IF|ELSEIF)[ ]*([a-z]*?[ ]*[A-Z0-9_]*[ ]*?(== '[a-zA-Z0-9]*')*?)*-->/", $fileContents, $allCondStrings);
			$condStrings = $allCondStrings[0];
			foreach($condStrings as $condString)	{
				//Now we got one single cond string in storage.
				$condStrArray = explode(' ', $condString);
				//The top two elements are not needed.
				array_shift($condStrArray);
				array_pop($condStrArray);
				//Now comes the hard part.
				$condition = '';
				foreach($condStrArray as $condArrayPart)	{
					switch(trim($condArrayPart))	{
						case 'IF':
							//The if condition. <?php if(
							$condition .= '<?php if(';
							break;
						case 'ELSEIF':
							//The else if condition. <?php } else if (
							$condition .= '<?php } else if(';
							break;
						case 'not':
							//This is a not condition. '!'
							$condition .= '!';
							break;
						case 'and':
							//This is a and condition. '&&'
							$condition .= ' && ';
							break;
						case 'or':
							//This is a or condition. '||'
							$condition .= ' || ';
							break;
						case '==':
							$condition .= ' == ';
							break;
						default:
							//Now this is a variable variable or a value for a condition
							if(substr_count($condArrayPart, "'") > 0)	{
								$condition .= $condArrayPart;
							} else {
								$condition .= '$this->templateVars[\''.$condArrayPart.'\']';
							}
					}
				}
				$condition.= ' ) { ?>';
				$fileContents = str_replace($condString, $condition, $fileContents);
			}
			$fileContents = str_replace('<!-- ELSE -->', '<?php } else { ?>', $fileContents);
			$fileContents = str_replace('<!-- ENDIF -->', '<?php } ?>', $fileContents);
			return $fileContents;
		}
		
		private function replace_temp_vars($fileContents)	{
			foreach($this->templateVars as $varName => $varValue)	{
				$varFileForm = '{'.$varName.'}';
				$varFileValue = '<?php echo (isset($this->templateVars[\''.$varName.'\'])) ? $this->templateVars[\''.$varName.'\'] : \'\'; ?>';
				$fileContents = str_replace($varFileForm , $varFileValue, $fileContents);
			}
			return $fileContents;
		}
		
		private function include_files($fileContents)	{
			//Now we take the file contents and replace the files and give it back.
			//The format of the include string is <!-- #INCLUDE filename.ext -->
			preg_match_all('/<!--[ ]*#INCLUDE[ ]*[a-zA-Z0-9]+.[a-z]*[ ]*-->/', $fileContents, $includeStrs);
			$includeStrings = $includeStrs[0];
			global $ROOT_PATH, $template;
			foreach($includeStrings as $includeString)	{
				//Now we see if those files exists.
				$incStrStart = strpos($includeString, '<!-- #INCLUDE') + 13;
				$incStrEnd = strpos($includeString, '-->');
				$incFileName = trim(substr($includeString, $incStrStart, ($incStrEnd - $incStrStart)));
				$templatePath = $template->return_temp_path().$incFileName;
				if(!file_exists($templatePath))	{
					echo 'The File '.$incFileName.' was not Found. <br />';
				}
				$fileData = file_get_contents($templatePath);
				$fileContents = str_replace($includeString, $fileData, $fileContents);
			}
			return $fileContents;
		}
		
		public static function delete_cache_files()	{
			//Just Deletes The Cache from a manual command Rather Than on auto delete of invalid file
			$cachePath = 'cache/cached_files/';
			if($handle = opendir($cachePath))	{
				//We get the file names and store them in an array for deletion
				while(false !== ($file = readdir($handle)))	{
					if(!is_dir("{$cachePath}{$file}"))	{
						unlink("{$cachePath}{$file}");
					}
				}
			}
		}
	}
?>