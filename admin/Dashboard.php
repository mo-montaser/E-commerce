<?php
	session_start();

	$pageTitle = "Dashboard";

	if (isset($_SESSION['UsernameSession'])){
		include 'ini.php';
		
		//dashboard page:
?>
		<h2>Dashboard</h2>
		<h3>Total Members</h3>
		<p><?php echo countItems('UserID', 'users'); ?></p>
		<h3>Pending Members</h3>
		<p><a href="members.php?page=Pending"><?php echo checkItem("RegStatus", "users", 0); ?></a></p>
		<h3>Total Items</h3>
		<p>1500</p>
		<h3>Total Comments</h3>
		<p>3000</p>
<?php
	

	}
	else{
		header('Location: index.php');
		exit();
	}