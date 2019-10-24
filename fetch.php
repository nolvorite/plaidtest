<?php

	ob_start();
	session_start();

	$_SESSION['userdata'] = ['username' => 'test_user', 'user_id' => 1];

	require_once("db.php");


	if(isset($_GET['type'])){
		switch($_GET['type']){
			case "session_log":
				$userId = $_SESSION['userdata']['user_id'];
				$publicToken = filterQ($_POST['public_token']);
				$updateUser = mysqli_query("UPDATE users SET public_token_last='". $publicToken ."'");
				$_SESSION['institution'] = $_POST['institution'];
				$_SESSION['public_token'] = $_POST['public_token'];
				$_SESSION['metadata'] = $_POST['metadata'];
			break;
			case "confirm_nickname":
			case "confirm_deletion":
				//check if they're logged in and it belongs to them, etc
				$result = [];
				if(isset($_SESSION['userdata'])){
					$userId = $_SESSION['userdata']['user_id'];
					$cardId = filterQ($_POST['card_id']); 
					$cardCheck = mysqli_query($dbCon, "SELECT * FROM cards WHERE user_id = '$userId' AND card_id='$cardId'");
					$cardData = mysqli_fetch_assoc($cardCheck);
					if(mysqli_num_rows($cardCheck) > 0){
						switch($_GET['type']){
							case "confirm_nickname":
								$nickname = preg_replace("#^[\t ]+(.+)#","$1",filterQ($_POST['nickname']));
								$defaultNickname = "($cardData[bank]) $cardData[official_name]";

								$nicknameInput = preg_match("#^[ \t]{0,}$#",$nickname) ? "'$defaultNickname'" : "'$nickname'";
								try { 
									$updateNickname = mysqli_query($dbCon, "UPDATE cards SET nickname=$nicknameInput WHERE card_id='$cardId' AND user_id='$userId'") or die(mysqli_error($dbCon));
									$result['notice'] = "Edited nickname successfully." ;
									$result['card_id'] = $cardId;
									$result['nickname'] = preg_match("#^[ \t]{0,}$#",$nickname) ? $defaultNickname : $nickname;
								} catch(Exception $error){
									$result['error'] = "Error updating nickname.";
								}
							break;
							case "confirm_deletion":
								try { 
									$deleteCard = mysqli_query($dbCon, "DELETE FROM cards WHERE card_id='$cardId' AND user_id='$userId'") or die(mysqli_error($dbCon));
									$deleteTransactions = mysqli_query($dbCon, "DELETE FROM transactions WHERE card_id='$cardId' AND user_id='$userId'") or die(mysqli_error($dbCon));

									$result['notice'] = "Deleted card data and all trasaction records successfully." ;
								} catch(Exception $error){
									$result['error'] = "Error doing action.";
								}
							break;
						}
					}
					
				}
				
				echo json_encode($result);
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