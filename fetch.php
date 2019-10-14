<?php

	require __DIR__ . '/vendor/autoload.php';

	use Plaid\Client;
	use Plaid\PlaidException;
	use Plaid\Requester;

	if(isset($_POST['publicToken'])){

		$clientId = '5d95f5c8c08af900131e573c';
		$secret = 'aa4848a29c00fb894ffa43b43874d3';
		$publicKey = 'a098c6bb0a982318837f9c7018573a';
		$publicToken = $_POST['publicToken'];

		// Available environments are 'sandbox', 'development', and 'production'
		$client = new Client($clientId, $secret, $publicKey, 'development');

		$publicToken = $client->item()->publicToken()->exchange($publicToken,$clientId,$secret);

		if(isset($_GET['type'])){
			switch($_GET['type']){
				case "transactions":
					$response = $client->transactions()->get($publicToken, '2018-01-01', '2018-01-31');
					var_dump($response);
				break;
			}
		}
	}



?>