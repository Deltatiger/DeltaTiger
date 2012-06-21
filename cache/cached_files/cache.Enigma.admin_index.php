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
			<!-- TODO add a decent logo here. -->
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
			<div class="adminLeftNavSubBar">
				<p class="aNavBarHead">
					Testing Heading.
				</p>
				<ul>
					<li> Testing1 </li>
					<li> Testing 2</li>
					<li> Testing 3 </li>
				</ul>
			</div>
		</div>
		<div id="adminRightMainBar">
			This is the right main bar.
		</div>
</div>

<!--
    This page is the footer and the final template of the page.
-->
<div id="adminfooter">
    The Page Was Compiled on  04 : 34 : 06 20126 21-Jun-2012.
</div>
</body>
</html>