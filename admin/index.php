<?php 
	session_start();
	$noNavbar = '';
	$pageTitle = "Login";

	if (isset($_SESSION['UsernameSession'])){
		header('Location: Dashboard.php');
	}
	include "ini.php";
	

	// Check if the request method is POST.

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$username = $_POST['user'];
		$password = $_POST['pass'];
		$hashpassword = sha1($password);
		
		// Check if the user exists in Database]]]

		$stmt = $con->prepare("SELECT UserID, Username, Password 
							   FROM users 
							   WHERE Username = ? AND Password = ? AND GroupID = 1 
							   LIMIT 1 ");
		$stmt->execute(array($username, $hashpassword));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();

		if($count > 0){
			$_SESSION['UsernameSession'] = $username;
			$_SESSION['IDSession'] = $row[UserID];
			header('Location: dashboard.php');
			exit();
		}
		
	}
?>
	
 	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" >
		<h4 class="">Admin Login</h4>
		<input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" /> <br>
		<input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password" /> <br>
		<input class= "btn" type="submit" value="Login" />
	</form>
       
<?php
	include $tpl .  "footer.php";
?>	