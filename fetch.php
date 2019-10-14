<?php


	$dbConnection = mysqli_connect("localhost","root","","plaidtest");

	//INSERT INTO `transactions` (`transaction_id`, `card_id`, `user_id`, `category`, `date_posted`, `date_transacted`, `description`, `transaction_type`, `amount`) VALUES (NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL);

	//INSERT INTO `cards` (`card_id`, `account_id`, `balance`, `available`, `current`, `limits`, `last_updated`) VALUES (NULL, NULL, NULL, NULL, NULL, NULL, NULL);


	if(isset($_POST['publicToken'])){

		require("pt1.php");

		//get credit card only
		

	}

?>