<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ConsumesExternalService
{
    public function makeRequest($method, $requestUrl, $queryParams = [], $formParams = [], $headers = [], $isJsonRequest = false)
    {
        $client  = new Client([
            'base_uri' => $this->base_uri,
        ]);

        if(method_exists($this,'resolveAuthorization')){
            $this->resolveAuthorization($queryParams,$formParams,$headers);
        }

        $response  = $client->requset($method, $requestUrl,[
            $isJsonRequest ? 'json' : 'form_params' => $formParams,
            'headers' => $headers,
            'query' => $queryParams
        ]);

        $response = $response->getBody()->getContents();

        if(method_exists($this,'decodeResponse')){
            $this->decodeResponse($response);
        }

        return $response;
    }
}