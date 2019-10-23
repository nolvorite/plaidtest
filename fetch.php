<?php

	ob_start();
	session_start();

	$_SESSION['userdata'] = ['username' => 'test_user', 'user_id' => 1];

	require_once("db.php");


	if(isset($_GET['type'])){
		switch($_GET['type']){
			case "session_log":
				$userId = $_SESSION['userdata']['user_id'];
				$updateUser = mysqli_query("UPDATE users SET public_token_last='".filterQ($publicToken)."'");
				$_SESSION['institution'] = $_POST['institution'];
				$_SESSION['public_token'] = $_POST['public_token'];
			break;
			case "transactions":

				if(isset($_POST['publicToken'])){
					require("transactions_pt1.php");
				}

			break;
			case "update_user":

				//update user
				$updateUser = mysqli_query($dbCon, "UPDATE users SET last_active = NOW() WHERE username = '". filterQ($_SESSION['userdata']['username']) ."'");
				echo "Updated user's last login time.";

			break;

		}
	}
				

	

?>