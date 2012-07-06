<!--
    This is the header file used throughout the Site and contains all the includes used in the 
    ajax/jquery/css stuff. Also Contains the basic site structure. Nothing else
-->
<!DOCTYPE html>
<html lang ="en">
    <head>
        <title><?php echo (isset($this->templateVars['TITLEHERE'])) ? $this->templateVars['TITLEHERE'] : ''; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="/Deltatiger/templates/Enigma/enigma.css" />
		<script type="text/javascript" src="/Deltatiger/templates/Enigma/ajax/ajax.js" ></script>
    </head>
    <body>
        <div id="header">
            <img src="/Deltatiger/templates/Enigma/images/logo.jpg" width =100% height = 150px />
        </div>
    <!--
        The rest is continued in the navbar page
    -->
<!--
This contains the nav bar icons to link to other pages
-->

<div class="navbar">
    <ul>
        <li> <a href ="index.php"> Index </a> </li>
        <li> <a href ="blog.php"> Blog </a> </li>
        <li> <a href ="projects.php"> Projects </a> </li>
        <?php if(!$this->templateVars['USERLOGGEDIN'] ) { ?>
        <li> <a href ="login.php"> Login </a> </li>
        <?php } else { ?>
        <li> <a href="control.php"> Control Panel </a></li>
        <?php } ?>
        <?php if($this->templateVars['USERGROUP'] == '1' ) { ?>
        <li> <a href="admin/index.php"> Admin Area </a></li>
        <?php } ?>
    </ul>
</div>
<div id="body">
	<div id="projects">
		<?php echo (isset($this->templateVars['PROJECTS'])) ? $this->templateVars['PROJECTS'] : ''; ?>	
	</div>
</div>
<!--
    This page is the footer and the final template of the page.
-->
<div id="footer">
    The Page Was Compiled on  14 : 34 : 21 20127 06-Jul-2012.
</div>
</body>
</html>