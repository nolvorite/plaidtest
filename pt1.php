<?php

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
		'end_date' => '2019-08-01',
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

		$transactions = $result['transactions'];
		

		if(isset($_GET['official_name'])){
			$result['transactions'] = [];
			$result['official_name'] = $_GET['official_name'];
			$result['transaction_truecount'] = count($transactions);
			for($i = 0; $i < count($transactions); $i++){
				if($transactions[$i]->official_name === $_GET['official_name']){
					$result['transactions'][count($result['transactions'])] = $transactions[$i];
				}
			}
			$result['total_transactions'] = count($result['transactions']);
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