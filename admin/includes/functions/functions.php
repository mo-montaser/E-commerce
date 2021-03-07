<?php

// Page Title function

function getTitle(){
	
	global $pageTitle;

	if(isset($pageTitle)) {
		echo $pageTitle;
	} else {
		echo 'Default';
	}
} 

// Redirect function

function redirectHome($msg, $url = null, $seconds = 3) {

	if ($url === null) {
 		
 		$url  = 'index.php';
 		$link = 'Home Page';
 	
 	} else {

 		if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){

 			$url  = $_SERVER['HTTP_REFERER'];
 			$link = 'Previous Page';
 		
 		} else {

 			$url  = 'index.php';	
 			$link = 'Home Page';
 		}  
 	}

 	echo "<h2>" . $msg . "<h2>";
 	echo "<h2> You will be redirected to " . $link . " after "  . $seconds . " seconds <h2>";
	header("refresh:$seconds; url=$url");
 	exit();
}

 // Check Items function in database

function checkItem($select, $from, $value) {

 	global $con; 

 	$statment = $con->prepare( "SELECT $select FROM $from WHERE $select = ?");
 	$statment->execute(array($value));

 	return $statment->rowCount();
}

// Count number of items in database funcion

function countItems($item, $table){

	global $con;

	$stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
	$stmt2->execute();

	return $stmt2->fetchColumn();
}



