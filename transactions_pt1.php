<?php

	//dummy user

	$clientId = '5d95f5c8c08af900131e573c';
	$secret = 'aa4848a29c00fb894ffa43b43874d3';
	$publicKey = 'a098c6bb0a982318837f9c7018573a';
	$publicToken = $_POST['publicToken'];

	$properties = [
		'institution_id' => $_POST['metadata']['institution']['institution_id'],
		'public_key' => $publicKey,
		'initial_products' => ['transactions', 'auth']
	];

	$auth1 = json_encode($properties);

	function getCards(){
		global $dbCon;
		$userId = $_SESSION['userdata']['user_id'];
		$getCards = mysqli_query($dbCon, "SELECT * FROM cards WHERE user_id = ".$userId);
		return mysqli_fetch_all($getCards, MYSQLI_ASSOC);
	}

	function updateCardInDb($cardData){
		
	}

	function getInstitution($institutionId){

		global $publicKey;

		$data = json_encode(['public_key' => $publicKey, 'institution_id' => $institutionId]);
		$ch4 = curl_init('https://sandbox.plaid.com/institutions/get_by_id');
		curl_setopt($ch4, CURLOPT_SSL_VERIFYHOST, 0); //DEVELOPMENT ONLY
		curl_setopt($ch4, CURLOPT_SSL_VERIFYPEER, 0); //DEVELOPMENT ONLY
		curl_setopt($ch4, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch4, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch4, CURLOPT_POST, true);
		curl_setopt($ch4, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch4, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		
		 
		// Submit the POST request
		$result = curl_exec($ch4);

		if(!$result){
			return json_encode(curl_error($ch4));
		}else{
			$result = json_decode($result);
			$result = (array) $result;
			$institution = $result;
		}

		//var_dump($result);

		return $result;
		
	}

	
	

	// Prepare new cURL resource
	$chForAccessToken = curl_init('https://sandbox.plaid.com/sandbox/public_token/create');
	curl_setopt($chForAccessToken, CURLOPT_SSL_VERIFYHOST, 0); //DEVELOPMENT ONLY
	curl_setopt($chForAccessToken, CURLOPT_SSL_VERIFYPEER, 0); //DEVELOPMENT ONLY
	curl_setopt($chForAccessToken, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($chForAccessToken, CURLINFO_HEADER_OUT, true);
	curl_setopt($chForAccessToken, CURLOPT_POST, true);
	curl_setopt($chForAccessToken, CURLOPT_POSTFIELDS, $auth1);
	 
	// Set HTTP Header for POST request 
	curl_setopt($chForAccessToken, CURLOPT_HTTPHEADER, array(
	    'Content-Type: application/json'));
	
	 
	// Submit the POST request
	$result = curl_exec($chForAccessToken);

	if(!$result){
		//var_dump(curl_error($chForAccessToken));
	}else{

		$result = json_decode($result);
		$result = (array) $result;

		$publicToken2 = $result['public_token'];


	}

	


	$properties2 = [
		'client_id' => $clientId,
		'secret' => $secret,
		'public_token' => $publicToken2
	];


	$auth = json_encode($properties2);

	//
	//get data
	//
 
	// Prepare new cURL resource
	$ch = curl_init('https://sandbox.plaid.com/item/public_token/exchange');
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //DEVELOPMENT ONLY
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //DEVELOPMENT ONLY
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $auth);
	 
	// Set HTTP Header for POST request 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    'Content-Type: application/json'));
	
	 
	// Submit the POST request
	$result = curl_exec($ch);

	if(!$result){
		var_dump(json_decode(curl_error($ch)));
	}else{
		$result = json_decode($result);
		$result = (array) $result;

		$accessTokenData = $result;


	}

	$properties3 = [
		'client_id' => $clientId,
		'secret' => $secret,
		'access_token' => $accessTokenData['access_token'],
		'start_date' => '2019-01-01',
		'end_date' => '2019-08-01'
	];



	$properties3 = json_encode($properties3);

	// Prepare new cURL resource
	$ch2 = curl_init('https://sandbox.plaid.com/transactions/get');
	curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0); //DEVELOPMENT ONLY
	curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0); //DEVELOPMENT ONLY
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch2, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch2, CURLOPT_POST, true);
	curl_setopt($ch2, CURLOPT_POSTFIELDS, $properties3);
	 
	// Set HTTP Header for POST request 
	curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
	    'Content-Type: application/json'));
	
	 
	// Submit the POST request
	$result = curl_exec($ch2);

	


	if(!$result){
		echo json_encode(curl_error($ch2));
	}else{
		$result = json_decode($result);
		$result = (array) $result;

		$result['ins_data'] = getInstitution($result['item']->institution_id);

		curl_close($ch2);


		

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

			$properties3 = [
				'client_id' => $clientId,
				'secret' => $secret,
				'access_token' => $accessTokenData['access_token'],
				'start_date' => '2019-01-01',
				'end_date' => '2019-08-01',
				'options' => ['account_ids' => [$accountId]]
			];


			$properties3 = json_encode($properties3);

			// Prepare new cURL resource
			$ch3 = curl_init('https://sandbox.plaid.com/transactions/get');
			curl_setopt($ch3, CURLOPT_SSL_VERIFYHOST, 0); //DEVELOPMENT ONLY
			curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, 0); //DEVELOPMENT ONLY
			curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch3, CURLINFO_HEADER_OUT, true);
			curl_setopt($ch3, CURLOPT_POST, true);
			curl_setopt($ch3, CURLOPT_POSTFIELDS, $properties3);
			 
			// Set HTTP Header for POST request 
			curl_setopt($ch3, CURLOPT_HTTPHEADER, array(
			    'Content-Type: application/json'));

			$result = curl_exec($ch3);

			if(!$result){
				echo json_encode(curl_error($ch3));
			}else{

				

				$result = json_decode($result);
				$result = (array) $result;		
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

				if($alreadySelected){
					$result = ['error' => 'Card is already in your card feed. Please select another one.'];
				}else{
					//update card data for use


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
	
				

		


		echo json_encode($result);


	}


	 
	// Close cURL session handle
	curl_close($ch);


	

		
	


	/*				

		curl -X POST https://sandbox.plaid.com/item/public_token/create \
		  -H 'Content-Type: application/json' \
		  -d '{
		    "client_id": String,
		    "secret": String,
		    "access_token": 
		"access-sandbox-de3ce8ef-33f8-452c-a685-8671031fc0f6"
		  }'

	*/

?>