<?php
	
	include 'connect.php';

	$tpl = 'includes/templates/';
	$func = 'includes/functions/';

	include $func . 'functions.php';
	include $tpl . 'header.php';


	// All pages include navbar except ($noNavbar):
	if(!isset($noNavbar)){	include $tpl . 'navbar.html'; }


	


