<?php

	/*
		Category page
	*/

	session_start();
	$pageTitle = "Category";

	if(isset($_SESSION['UsernameSession'])){
		
		include 'ini.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {


		} elseif ($do == 'Add') { 

  
		} elseif ($do == 'Insert') {


		} elseif ($do == 'Edit') {


		} elseif ($do == 'Update') {


		} elseif ($do == 'Delete') {


		} elseif ($do == 'Activate') {
		
		}	

	 	include $tpl . 'footer.php';

	} else {

		header('Location: index.php');
		exit();
	
	} 