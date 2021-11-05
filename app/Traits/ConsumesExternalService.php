<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ConsumesExternalService
{
    public function makeRequest($method, $requestUrl, $quesryParams = [], $formParams = [], $headers = [], $isJsonRequest = false)
    {
        $client  = new Client([
            'base_uri' => $this->baseUri,
        ]);

        if(method_exists($this,'resolveAuthorization')){
            $this->resolveAuthorization($quesryParams,$formParams,$headers);
        }

        $response  = $client->requset($method,  $requestUrl,[
            $isJsonRequest ? 'json' : 'form_params' => $formParams,
            'headers' => $headers,
            'query' => $quesryParams
        ]);

        $response = $response->getBody()->getContents();

        if(method_exists($this,'decodeResponse')){
            $this->decodeResponse($quesryParams,$formParams,$headers);
        }

        return $response;
    }
}