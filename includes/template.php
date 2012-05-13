<?php
/**
 * Template File for DeltaTiger Site
 *
 * @author DeltaTiger
 */

//TODO major makeover plox.
//For now we go with a single theme set via Admin panel

class template {
    
		protected $templateVars = array();
		protected $currentTheme;
        private $template_path;

        public function __construct()	{
            //Lets get the theme set in the config table in the db
            global $db, $ROOT_PATH;

            $sql = "SELECT (`config_value`) FROM `{$db->returnDBName()}`.`dt_config` WHERE `config_name` = 'theme'";
            $query = $db->query($sql);

            if(mysql_num_rows($query) < 1)	{
                //If the default theme is not set or has been tampered with just die()
				//TODO send an error message to the admin/owner
                die('Default Template Not Set. Page Killed');
            } else {
                //Since it is found lets put it in a private var.
                $result = mysql_fetch_object($query);
                $this->currentTheme = $result->config_value;
            }
            //now lets check if a template folder of the name we got is found
            $file_path = "{$ROOT_PATH}templates/{$this->currentTheme}";
            if(!is_dir($file_path))	{
                die('Template files not found ');
            }

            //If it gets past this point its ready to be used.
            $this->template_path = $file_path."/";
            //If it exists then just asign it else create and then assign
            $cachePath = $ROOT_PATH.'cache/cached_files';
            if(is_dir($cachePath))	{
                $this->cache_path = "{$ROOT_PATH}cache/cached_files/";
            } else {
                //We got to make the 'cache' folder first then the 'cached_files' folder.
                mkdir('cache');
                mkdir($cachePath);
                $this->cache_path = "{$ROOT_PATH}cache/cached_files/";
            }
        }

        public function setNewTheme($themeName)	{
            //This checks if the given theme exists from somewhere and then we include it :?. Have to give it a thought.
            $filePath = "{$ROOT_PATH}templates/{$themeName}";
            if(!is_dir($filePath))	{
                //Theme folder not found
                die('New Theme Not Found. Put The folders in template/ and Try again');
            } else {
                //It exists. So let change the config table to reflect the change
				global $db;
				$sql = "UPDATE `{$db->returnDBName()}`.`dt_config` SET `config_value` = '{$themeName}' WHERE `config_name` = 'theme'";
				$result = $db->query($sql);
				//Since the theme is changed we must delete all the cache folder so the new ones are applied.
				$this->delete_cache();
            }
        }

        public function setTemplateVars($templateVars)	{
            //This just sets the template vars to be used in the variables
            if(!is_array($templateVars))	{
                die('No Vars Set');
            } else {
                foreach($templateVars as $varName => $varValue)	{
                        $this->templateVars[$varName] = $varValue;
                }
            }
        }

        public function setPageTemplate($templateName, $pageTitle)	{
        	//First we put the title of the page in the template vars so it works good.
        	$this->setTemplateVars(array('PAGETITLE'	=> $pageTitle));
        	
            //Now we check the validity of the requested template and then set it after some modification.
            if($this->checkCacheValidity($templateName) == false)	{
                if($this->makeTemplateCache($templateName) == false)	{
                        die('Template not cached due to errors');
                } else {
                        include "{$this->cache_path}cache.inc.{$this->currentTheme}.{$templateName}.php";
                }
            } else {
                include "{$this->cache_path}cache.inc.{$this->currentTheme}.{$templateName}.php";
            }
        }

        private function checkCacheValidity($template)	{
            //This checks if the template was made and cache anywhere between 0hrs - 168 hrs (i.e. a week)
            $sql = "SELECT `cache_time` FROM `".mydb::returnDBName()."`.`dt_cache_info` WHERE `cache_page_name` = '{$template}'";
            $query = mysql_query($sql);
            if(mysql_num_rows($query) <= 0)	{
                //Seems there is no entry in the db. Lets delete the file if it exists 
                $filePath = "{$this->cache_path}cache.inc.{$this->currentTheme}.{$template}.php";
                if(file_exists($filePath))	{
                    unlink($filePath);
                }
                return false;
            } else {
                $result = mysql_fetch_object($query);
                $time_of_cache = $result->cache_time;
                $time_now = time();
                if(($time_now - $time_of_cache) <= 604800)	{
                    //just to be sure lets see if the file is still there before we jump to conclusions
                    $file_path = "{$this->cache_path}cache.inc.{$this->currentTheme}.{$template}.php";
                    if(!file_exists($file_path))	{
                        return false;
                    }
                    //Ok everything is fine so lets give the green light
                    return true;
                } else {
                    //We dont need the cache so we delete it and return false so we can make a new one
                    $temp_path = "{$this->cache_path}cache.inc.{$this->currentTheme}.{$template}.php";
                    if(file_exists($temp_path))	{
                        unlink($temp_path);
                    }
                    return false;
                }
            }
        }
        
        //TODO modify this one and reflect the changes 

        private function makeTemplateCache($template)	{
            //Now lets make a new template
            $templateFile = "{$this->template_path}{$template}.html";
            if(file_exists($templateFile))	{
                $templateBodyFile = file_get_contents($templateFile);
                /*Now we have the entire file in a variable. Lets replace the vars with <?php ?> tags */
                foreach($this->template_vars as $varName => $varValue)	{
                    $varTrueName = '{'.$varName.'}';
                    $varTrueValue = '<?php echo (isset($this->template_vars[\''.$varName.'\'])) ? $this->template_vars[\''.$varName.'\'] : \'\'; ?>';
                    $templateBodyFile = str_replace( $varTrueName, $varTrueValue, $templateBodyFile);
                }
				//Now the main body of the file is ready we also need to add the header and footer and also the nav bar
				$headerFile = "{$this->template_path}header.html";
				if(file_exists($headerFile))	{
					$templateHeaderFile = file_get_contents($headerFile);
					//We need to add the <title>
					$varTrueName = "{TITLEHERE}";
					$varTrueValue = $template;
					$templateHeaderFile = str_replace($varTrueName , $varTrueValue, $templateHeaderFile);
				}
				//The Navbar needs nothing special. Just add it with the rest of the html
				$navBarFile = "{$this->template_path}navbar.html";
				if(file_exists($headerFile))	{
					$templateNavBarFile = file_get_contents($navBarFile);
				}
				//Now for the footer file. Just need to put a cache make time.
				$footerFile = "{$this->template_path}footer.html";
				if(file_exists($headerFile))	{
					$templateFooterFile = file_get_contents($footerFile);
					//We need to add the <title>
					$varTrueName = "{CACHEMAKETIME}";
					$varTrueValue = date('d-m-Y');
					$templateFooterFile = str_replace($varTrueName , $varTrueValue, $templateFooterFile);
				}
                //Now the stuff is written to the cache file
				$templateFileContents = "";
				$templateFileContents = $templateHeaderFile.$templateNavBarFile.$templateBodyFile.$templateFooterFile;
                $cacheFileName = 'cache.inc.'.$this->currentTheme.'.'.$template.'.php';
                $handle = fopen("{$this->cache_path}{$cacheFileName}",'w');
                fwrite($handle, $templateFileContents);
                fclose($handle);

                //Checking if the file has been made . If yes then returning true else false
                if(file_exists("{$this->cache_path}{$cacheFileName}"))	{
                    //lets see if this is a first time we are caching
                    $sql = "SELECT `cache_time` FROM `deltatiger`.`dt_cache_info` WHERE `cache_page_name` = '{$template}'";
                    $query = mysql_query($sql);
                    if(mysql_num_rows($query) <= 0)	{
                            //Not yet inserted so this is a first time
                            $sql = "INSERT INTO `deltatiger`.`dt_cache_info` (`cache_page_name`,`cache_time`) VALUES ('{$template}','".time()."')";
                            $query = mysql_query($sql);
                    } else {
                            $sql = "UPDATE `deltatiger`.`dt_cache_info` SET `cache_time` = ".time()." WHERE `cache_page_name` = '{$template}'";
                            $query = mysql_query($sql);
                    }
                    return true;
                } else {
                    return false;
                }
            }
            else {
                //The template file is missing. This has to be addressed by sending an email to the admin about the issue.
				//TODO setup email error notification
                die('Template File Missing . No Cache available');
            }
        }

}

?>
