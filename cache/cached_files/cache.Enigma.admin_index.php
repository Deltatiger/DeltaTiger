<!DOCTYPE html>
<html>
	<head>
		<title><?php echo (isset($this->templateVars['TITLEHERE'])) ? $this->templateVars['TITLEHERE'] : ''; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="/Deltatiger/templates/Enigma/admin_enigma.css" />
		<script type="text/javascript" src="/Deltatiger/templates/Enigma/ajax/ajax.js" ></script>
	</head>
	<body>
		<div id="header">
            <img src="/Deltatiger/templates/Enigma/images/logo.jpg" width =100% height = 150px />
        </div>
<div id="navbar">
	<ul>
		<li> Admin - Index </li>
		<li> Statistics </li>
		<li> Portal </li>
	</ul>
</div>

<div id="body">
		<div id="adminLeftNavBar">
			This is the left nav bar.
		</div>
		<div id="adminRightMainBar">
			This is the right main bar.
		</div>
</div>

<!--
    This page is the footer and the final template of the page.
-->
<div id="footer">
    The Page Was Compiled on  07 : 06 : 56 20126 06-Jun-2012.
</div>
</body>
</html>