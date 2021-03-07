<?php

	/*
		Manage members page
		(Add | Edit | Delete) members. 
	*/

	session_start();
	$pageTitle = "Members";

	if(isset($_SESSION['UsernameSession'])){
		
		include 'ini.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if($do == 'Manage'){

			//For pending members

			$query = (isset($_GET['page']) && $_GET['page'] == 'Pending') ? 'AND RegStatus = 0' : '';

			$stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
			$stmt->execute();
			$rows = $stmt->fetchAll();
?>
			<h1>Manage Members</h1>

			<table>
				<tr>
					<td>#ID</td>
					<td>Username</td>
					<td>Email</td>
					<td>Fullname</td>
					<td>Registered Date</td>
					<td>Control</td>
				</tr>
				<tr>
					<?php 
						foreach ($rows as $row) {
							echo "<tr>";
								echo "<td>" . $row['UserID'] . "</td>";
								echo "<td>" . $row['Username'] . "</td>";
								echo "<td>" . $row['Email'] . "</td>";
								echo "<td>" . $row['FullName'] . "</td>";
								echo "<td>" . $row['Date'] . "</td>";
								echo "<td>" . "</td>";
								echo "<td>
										<a href='members.php?do=Edit&userid=" . $row['UserID'] . "''>Edit</a>
										<a href='members.php?do=Delete&userid=" . $row['UserID'] . "'>Delete</a>";
										if ($row['RegStatus'] == 0) {
										echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "'> Activate</a>";
										}									 
									echo"</td>";
							echo "</tr>";
						}
					?>	
				</tr>
			</table>

			<a href="members.php?do=Add">Add New Member</a>


<?php
		} elseif ($do == 'Add') { ?>
									
									<!-- HTML Add FORM -->

			<form class="login" action="<?php echo "?do=Insert"?>" method="POST" >
				<h2 class="">Add new member</h2>				
				<input type="text" name="user" autocomplete="off" required="required" />Username*<br>
				<input type="password" name="pass" autocomplete="new-password" required="required" />Password*<br>
				<input type="email" name="email" autocomplete="off" required="required" />Email*<br>
				<input type="text" name="full-name" autocomplete="off" required="required" />Full name*<br>
				<input class= "btn" type="submit" value="Add Member" />
			</form>

<?php   
		} elseif ($do == 'Insert'){

			//Insert Member page

			if ($_SERVER['REQUEST_METHOD'] == 'POST'){

				echo "<h1>Insert Member</h1>";

				$user 	= $_POST['user'];
				$pass 	= $_POST['pass'];
				$e 		= $_POST['email'];
				$full 	= $_POST['full-name'];

				$hashpass = sha1($_POST['pass']); 
				
				// Validate EDIT form

				$formErrors = array();

				if (strlen($user) < 4) {
					$formErrors[] = "Username can't be less than 4 characters";
				}
				if (empty($user)) {
					$formErrors[] = "Username can't be empty";
				}
				if (empty($pass)) {
					$formErrors[] = "password can't be empty";
				}
				if (empty($e)) {
					$formErrors[] = "Email can't be empty";
				}
				if (empty($full)) {
					$formErrors[] = "Fullname can't be empty";
				}

				foreach ($formErrors as $error) {
					echo $error . '<br>' ;
				}

				if (empty($formErrors)){

					// Check if Username exists in database
					
					if(checkItem("Username", "users", $user) == 1) {

						$msg = "Sorry, This User is exist.";
						redirectHome($msg, 'back');

					} else {

						//Insert User into database

						$stmt = $con->prepare(" INSERT INTO 
												users(Username, Password, Email, FullName, RegStatus, Date)
												VALUES (:U, :P, :E, :F, 1, now()) ");
						$stmt->execute(array(
											 'U' => $user,
											 'P' => $hashpass,
											 'E' => $e,
											 'F' => $full ));
					

						$msg = "Inserted Successfully";
						redirectHome($msg, 'back');
					}

				}

			} else {

				$msg = "fuckkkk Hackerrrrrrrrr";
				redirectHome($msg, 'back');
			}

			


		} elseif ($do == 'Edit'){


			// Check if GET REQUEST UserID is number then get the intger

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
			$stmt = $con->prepare("SELECT * 
								   FROM  users 
								   WHERE UserID = ? 
								   LIMIT 1 ");
			$stmt->execute(array($userid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();

			if($count > 0){  ?>
			
							<!-- HTML EDIT FORM -->

			 	<form class="login" action="<?php echo "?do=Update"?>" method="POST" >
					<h2 class="">Edit Member</h2>
					<input type="hidden" name="userid" value="<?php echo $userid ?>" />					
					<input class="form-control" type="text" name="user" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required" />Username*<br>
					<input type="hidden" name="oldpass" value="<?php echo $row['Password'] ?>" />
					<input type="password" name="newpass" autocomplete="new-password" placeholder="Leave blank for no changing" />Password<br>
					<input class="form-control" type="email" name="email" value="<?php echo $row['Email'] ?>" autocomplete="off" required="required" />Email*<br>
					<input class="form-control" type="text" name="full-name" value="<?php echo $row['FullName'] ?>" autocomplete="off" required="required" />Full name*<br>
					<input class= "btn" type="submit" value="Update" />
				</form>

<?php 	} else {
				$msg = "There is no such ID";
				redirectHome($msg);
			}

		} elseif ($do == 'Update') {

			echo "<h1>UPDATE Member</h1>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST'){

				$Uid 	= $_POST['userid'];
				$user 	= $_POST['user'];
				$e 		= $_POST['email'];
				$full 	= $_POST['full-name'];
				
				$pass 	= empty($_POST['newpass']) ? $pass = $_POST['oldpass']  : sha1($_POST['newpass']);

				// Validate EDIT form

				$formErrors = array();

				if (strlen($user) < 4) {
					$formErrors[] = "Username can't be less than 4 characters";
				}
				if (empty($user)) {
					$formErrors[] = "Username can't be empty";
				}
				if (empty($e)) {
					$formErrors[] = "Email can't be empty";
				}
				if (empty($full)) {
					$formErrors[] = "Fullname can't be empty";
				}

				foreach ($formErrors as $error) {
					echo $error . '<br>' ;
				}

				if (empty($formErrors)){

					$stmt = $con->prepare("UPDATE users 
									   SET Username = ?, Email = ?, FullName = ?, Password = ?
									   WHERE UserID = ? ");
				$stmt->execute(array($user , $e, $full, $pass, $Uid));

				$msg = "Updated Successfully";
				redirectHome($msg, 'back');

				}

			} else {

				$msg = "fuckkkk Hackerrrrrrrrr";
				redirectHome($msg, 'back');

			}

		} elseif ($do == 'Delete'){


			// Check if GET_REQUEST UserID is number then get the intger

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
			

			if (checkItem('userid', 'users', $userid) == 1){

				$stmt = $con->prepare("DELETE FROM  users WHERE UserID = :zuser");
				$stmt-> bindParam(":zuser", $userid);
				$stmt->execute();
				$msg = "Deleted Successfully";
				redirectHome($msg, 'back'); 

			}
			else {
				$msg = "The user doesn't exist";
				redirectHome($msg); 

			}

		} elseif ($do == 'Activate'){


			// Check if GET_REQUEST UserID is number then get the intger

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
			

			if (checkItem('userid', 'users', $userid) == 1){

				$stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
				$stmt->execute(array($userid));
				$msg = "Activated Successfully";
				redirectHome($msg, 'back'); 

			} else {
				$msg = "The user doesn't exist";
				redirectHome($msg); 

			}
		}	

	 	include $tpl . 'footer.php';

	} else{

		header('Location: index.php');
		exit();
	
	} 