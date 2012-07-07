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
		<li> <a href="psgmain.php">PSG Main</a> </li>
	</ul>
</div>

<div class="adminBody">
	<div id="leftNavBar">
		<p class="leftNavHeading">View</p>
		<ul>
			<li> <a href="psgmain.php?p=viewposts">View Posts</a></li>
			<li> <a href="psgmain.php?p=viewstats">View Stats</a></li>
		</ul>
		
		<p class="leftNavHeading">Admin </p>
		<ul>
			<li> <a href="psgmain.php?p=newpost">New Post</a></li>
			<li> <a href="psgmain.php?p=newpost">New Mail</a></li>
		</ul>
	</div>
	<div id="rightMainBar">
	
	</div>
</div>

