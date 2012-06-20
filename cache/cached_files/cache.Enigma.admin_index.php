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
<div class="navbar">
	<ul>
		<li> Admin Index </li>
		<li> Statistics </li>
		<li> Portal </li>
	</ul>
</div>

<div class="adminBody">
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
<div id="adminfooter">
    The Page Was Compiled on  05 : 35 : 21 20126 20-Jun-2012.
</div>
</body>
</html>