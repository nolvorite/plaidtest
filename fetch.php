<?php

	ob_start();
	session_start();

	$_SESSION['userdata'] = ['username' => 'test_user', 'user_id' => 1];

	require_once("db.php");


	if(isset($_GET['type'])){
		switch($_GET['type']){
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