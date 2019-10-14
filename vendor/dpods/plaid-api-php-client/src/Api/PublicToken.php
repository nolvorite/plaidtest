<?php

namespace Plaid\Api;

class PublicToken extends Api
{
    public function __construct($client)
    {
        parent::__construct($client);
    }

    public function exchange($publicToken,$clientId,$secret)
    {
        return $this->client()->post('/item/public_token/exchange', [
            'public_token' => $publicToken,
            'client_id' => $clientId,
            'secret' => $secret
        ]);
    }

    public function create($accessToken)
    {
        return $this->client->post('/item/public_token/create', [
            'access_token' => $accessToken
        ]);
    }
}
