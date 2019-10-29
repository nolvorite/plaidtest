<?php

	require_once("db.php");

	//dummy user

	$publicToken = $_POST['publicToken'];

	$publicToken2 = $publicToken;

	if($env === "'.$env.'"){

	    $properties1 = [
	        'institution_id' => $_POST['metadata']['institution']['institution_id'],
	        'public_key' => $publicKey,
	        'initial_products' => ['transactions', 'auth']
	    ];

		/////////////////////
		//public key part 1//
		/////////////////////

		$publicTokenDt = cUrlQuick($properties1,'https://'.$env.'.plaid.com/'.$env.'/public_token/create');

		$publicToken2 = $publicTokenDt['public_token'];	

	}

	///////////////////
	//exchange tokens//
	///////////////////



	$properties2 = [
		'client_id' => $clientId,
		'secret' => $secret,
		'public_token' => $publicToken2
	];

	//$accessTokenData = cUrlQuick($properties2,'https://'.$env.'.plaid.com/item/public_token/exchange');

	$accessTokenData = ['access_token' => 'access-development-60ff44f2-5109-4971-a116-2403b01dd94d'];

	////////////////////////////////
	//finally get transactions lol//
	////////////////////////////////

	$properties3 = [
		'client_id' => $clientId,
		'secret' => $secret,
		'access_token' => $accessTokenData['access_token'],
		'start_date' => '2019-01-01',
		'end_date' => '2019-08-01'
	];


	$result = cUrlQuick($properties3,'https://'.$env.'.plaid.com/transactions/get');

	if(isset($result['accounts'])){ //really need a better criteria for success lol

		$result['ins_data'] = getInstitution($result['item']->institution_id);

		if(isset($_GET['official_name'])){ //adding card to data

			//get current cards first

			$cardList = getCards();



			$result['transactions'] = [];
			$result['official_name'] = $_GET['official_name'];

			foreach($result['accounts'] as $account){
				if($account->official_name === $_GET['official_name']){
					$accountId = $account->account_id;
					$actualAcc = $account;
				}
			}

			///////////////////////////////////////////////
			//getting transactions from 1 account ID only//
			///////////////////////////////////////////////

			//start date and end date definitions

			//the date range below is for newly posted cards only. 

			$dates = ['end_date' => date("Y-m-d"),'start_date' => date("Y-m-d",strtotime(date("Y-m-d")." - 2 years"))];

			$needsUpdate = false;

			if(isset($_GET['card_id'])){ //for updates
				$cardCheck = mysqli_query($dbCon, "SELECT * FROM cards WHERE card_id = '". filterQ($_GET['card_id']) ."' AND user_id='". $_SESSION['userdata']['user_id'] ."'");

				if(mysqli_num_rows($cardCheck) > 0){
					//this is the one for newly created cards
					$cardData = mysqli_fetch_assoc($cardCheck);
					$dates['start_date'] = date("Y-m-d",strtotime($cardData['last_updated']));
					if(microtime(true) - strtotime($cardData['last_updated']) > (60*60*24)){
						$needsUpdate = true;
					} 
				}
				
			}

			$properties4 = [
				'client_id' => $clientId,
				'secret' => $secret,
				'access_token' => $accessTokenData['access_token'],
				'start_date' => $dates['start_date'],
				'end_date' => $dates['end_date'],
				'options' => ['account_ids' => [$accountId]]
			];




			$result = cUrlQuick($properties4,'https://'.$env.'.plaid.com/transactions/get');

			if(isset($result['accounts'])){

				$result['account_id'] = $accountId;
				$result['official_name'] = $_GET['official_name'];

				$result['ins_data'] = getInstitution($result['item']->institution_id);
				$result['bank'] = $result['ins_data']['institution']->name;

				//search if card is already in the list

				$cardToCheck = $result['accounts'][0];

				$alreadySelected = false;


				foreach($cardList as $card){
					//var_dump($card);
					if($cardToCheck->official_name === $card['official_name'] && $result['bank'] === $card['bank']){ //same user_id, official name and same bank. This is going to be our criteria for a "same card" for now, might be changed later
						$alreadySelected = true;
					}
				}


				$condition1 = $alreadySelected && !isset($_GET['card_id']); //check if card is already in a card feed

				$condition2 = count($cardList) === 3; //3 is max number of cards

				if($condition1 || $condition2){

					$errors = "";

					if($condition1){ $errors .= 'Card is already in your card feed. Please select another one.';}
					if($condition2){ $errors .= "\nYou already have a maximum number of cards in your card feed. Please delete a card feed.";}

					$result = ['error' => $errors];

				}else{
					//update card data for use

					$isForUpdating = (isset($_GET['card_id']) && $needsUpdate);

					if(!isset($_GET['card_id']) || $isForUpdating){

						if($isForUpdating){
							$updatePosts = mysqli_query($dbCon, "UPDATE cards SET last_updated=NOW() WHERE card_id = '$cardData[card_id]'");

								if($updatePosts){
									$result['start_date'] = $dates['start_date'];
									$result['end_date'] = $dates['end_date'];
									$result['notice'] = "Updated card's last update time.";
								}
						}else {
							//add card first
							$updateCard = submitToDb("cards",[
								'account_id' => $result['account_id'],
								'bank' => $result['ins_data']['institution']->name,
								'user_id' => $_SESSION['userdata']['user_id'],
								'official_name' => $cardToCheck->official_name,
								'name' => $cardToCheck->name
							]);

							//get latest card data
							$getLatestCard = mysqli_query($dbCon, "SELECT * FROM cards WHERE user_id='".$_SESSION['userdata']['user_id']."' ORDER BY card_id DESC");
							$cardData = mysqli_fetch_assoc($getLatestCard);

						}

						//then submit transactions


						$cardId = $cardData['card_id'];
						$result['card_id'] = $cardId;

						$cardName = $cardToCheck->official_name;


						$htmlAppend = tableAppend($result,$cardName);
						
	                 

						foreach($result['transactions'] as $key => $trnsct){
							$trnsct = (array) $trnsct;
							$result['transactions'][$key]->date_posted = date("Y-m-d");

							

							 $submitToData = submitToDb("transactions",
							 	[
							 		'card_id' => $cardId,
							 		'amount' => $trnsct['amount'],
							 		'category' => json_encode($trnsct['category']),
							 		'description' => $trnsct['name'],
							 		'transaction_type' => $trnsct['transaction_type'],
							 		'date_transacted' => $trnsct['date'],
							 		'user_id' => $_SESSION['userdata']['user_id'],
							 		'transaction_id2' => $trnsct['transaction_id']
							 	]
							 );
						}

						
						$result['html'] = $htmlAppend;
					}
				}
			}
		}
		
	}

	echo json_encode($result);

?>