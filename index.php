<?php
require_once "templates/page.php";

templatePage('ROBLOX Forums', function() {
?>
		<!--Forum list-->
		<ul data-role="listview">
			<!--Intro-->
			<li>
				<h1>Welcome</h1>
				<p>Welcome to the mobile version of the ROBLOX forums! This website was developed
				by the ROBLOX users <a href = "http://www.roblox.com/User.aspx?UserName=techboy6601" target = '_blank'>Techboy6601</a>,
				<a href = "http://www.roblox.com/User.aspx?UserName=NXTBoy" target = '_blank'>NXTBoy</a>,
				<a href = "http://www.roblox.com/User.aspx?UserName=Garnished" target = '_blank'>Garnished</a>, and 
				<a href = "http://www.roblox.com/User.aspx?UserName=testedmarkel62">testedmarkel62</a>. Please note
				that it is still in development and not everything is implemented yet. Enjoy!</p>
			</li>
			<!--/Intro-->
			<?php include("includes/panel.php"); ?>
		</ul>
		<!--/Forum list-->
		<br><br>
		&copy; 2013 Deplex Studios &bull; <a href = "credits.php">Credits</a> &bull; <a href = "thread88375831">Changelog</a>
<?
});
