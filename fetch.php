<?php

	require_once("db.php");


	if(isset($_GET['type'])){
		switch($_GET['type']){
			case "transactions":

				if(isset($_POST['publicToken'])){
					require("transactions_pt1.php");
				}

			break;

		}
	}
				

	

?>