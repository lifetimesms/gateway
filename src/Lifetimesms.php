<?php

namespace Lifetimesms\Gateway;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\MultipartStream;

class Lifetimesms
{
	public function singleSMS($params = [])
    {
    	if (empty($params)) {
    		# code...
    		return ['status' => false, 'response' => 'Missing some mandatory parameters'];
    	}

    	if (!isset($params['to']) || empty($params['to'])) {
    		# code...
    		return ['status' => false, 'response' => 'Missing some mandatory parameters'];
    	}

    	if (!isset($params['from']) || empty($params['from'])) {
    		# code...
    		return ['status' => false, 'response' => 'Missing some mandatory parameters'];
    	}

    	if (!isset($params['message']) || empty($params['message'])) {
    		# code...
    		return ['status' => false, 'response' => 'Missing some mandatory parameters'];
    	}

    	if (!is_string($params['to'])) {
    		# code...
    		return ['status' => false, 'response' => 'Invalid phone number'];
    	}

    	$api_token = config('lifetimesms.api_token');
    	$api_secret = config('lifetimesms.api_secret');

    	if (!$api_secret || !$api_token) {
    		# code...
    		return ['status' => false, 'response' => 'API credentials are missing'];
    	}

    	if (isset($params['unicode']) && $params['unicode']) {
    		# code...
    		$type = 'unicode';
    	}
    	else
    	{
    		$type = 'text';
    	}

    	$uri = 'http://lifetimesms.com/plain?api_token=' . urlencode($api_token) . '&api_secret=' . urlencode($api_secret) . '&to=' . urlencode($params['to']) . '&from=' . urlencode($params['from']) . '&message=' . urlencode($params['message']) . '&type=' . $type . '';

    	$response = self::makeSingleAPIGetRequest($uri);

    	if (preg_match("/OK/", $response))
    	{
    		return ['status' => true, 'response' => 'SMS sent', 'message_id' => explode(":", $response)[1]];
    	}
    	else
    	{
    		return ['status' => false, 'response' => $response];
    	}
    }

    public function bulkSMS($params = [])
    {
    	if (empty($params)) {
    		# code...
    		return ['status' => false, 'response' => 'Missing some mandatory parameters'];
    	}

    	if (!isset($params['to']) || empty($params['to'])) {
    		# code...
    		return ['status' => false, 'response' => 'Missing some mandatory parameters'];
    	}

    	if (!isset($params['from']) || empty($params['from'])) {
    		# code...
    		return ['status' => false, 'response' => 'Missing some mandatory parameters'];
    	}

    	if (!isset($params['message']) || empty($params['message'])) {
    		# code...
    		return ['status' => false, 'response' => 'Missing some mandatory parameters'];
    	}

    	if (!is_array($params['to'])) {
    		# code...
    		return ['status' => false, 'response' => 'Invalid phone numbers'];
    	}

    	$api_token = config('lifetimesms.api_token');
    	$api_secret = config('lifetimesms.api_secret');

    	if (!$api_secret || !$api_token) {
    		# code...
    		return ['status' => false, 'response' => 'API credentials are missing'];
    	}

    	if (isset($params['unicode']) && $params['unicode']) {
    		# code...
    		$type = 'unicode';
    	}
    	else
    	{
    		$type = 'text';
    	}

    	$response = self::makeBulkAPIPostRequest($params);

    	if (isset($response['totalprice'])) {
    		# code...
    		return ['status' => true, 'response' => $response];
    	}

    	return ['status' => false, 'response' => $response];
    }

    public function balanceInquiry()
    {
    	$api_token = config('lifetimesms.api_token');
    	$api_secret = config('lifetimesms.api_secret');

    	if (!$api_secret || !$api_token) {
    		# code...
    		return ['status' => false, 'response' => 'API credentials are missing'];
    	}

    	$uri = 'http://lifetimesms.com/balance-inquiry?api_token=' . urlencode($api_token) . '&api_secret=' . urlencode($api_secret) . '';

    	$response = self::makeSingleAPIGetRequest($uri);

    	if (empty($response)) {
    		# code...
    		return ['status' => false, 'response' => "No response from server"];
    	}

    	$response = json_decode($response, true);

    	if (isset($response['status']) && $response['status'] == 1) {
    		# code...
    		$response = ['sms' => $response['sms'] . " " . $response['sms_credit_type'], 'minutes'=> $response['minutes'] . " " . $response['minutes_credit_type']];
    		return ['status' => true, 'response' => $response];
    	}

    	return ['status' => false, 'response' => $response];
    }

    public static function makeSingleAPIGetRequest($uri)
    {
    	$client = new Client(['timeout' => 320, 'verify' => false]);
    	$recieve_response = null;

    	$requests = function() use ($uri) {
    		yield 1 => new GuzzleRequest('GET', $uri);
		};

		$pool = new Pool($client, $requests(1), [
		    'concurrency' => 1,
		    'fulfilled' => function ($response, $id) use (&$recieve_response) {

		    	$recieve_response = $response->getBody()->getContents();
		    },
		    'rejected' => function ($reason, $id) use (&$recieve_response) {
		    	$recieve_response = $reason->getMessage();
		    },
		]);

		$promise = $pool->promise();
		$promise->wait();

		return $recieve_response;
    }

    public static function makeBulkAPIPostRequest($params)
    {
    	$client = new Client(['timeout' => 320]);
    	$recieve_response = null;

    	$api_token = config('lifetimesms.api_token');
    	$api_secret = config('lifetimesms.api_secret');

    	if (isset($params['unicode']) && $params['unicode']) {
    		# code...
    		$type = 'unicode';
    	}
    	else
    	{
    		$type = 'text';
    	}

    	$query = [
		    [
		        'name' => 'api_token',
		        'contents' => $api_token,
		    ],
		    [
		        'name' => 'api_secret',
		        'contents' => $api_secret,
		    ],
		    [
		        'name' => 'type',
		        'contents' => $type,
		    ],
		    [
		        'name' => 'message',
		        'contents' => $params['message'],
		    ],
		    [
		        'name' => 'from',
		        'contents' => $params['from'],
		    ],
		    [
		        'name' => 'to',
		        'contents' => implode(",", $params['to']),
		    ],
		];

    	$requests = function() use ($query) {

    		$uri = "http://Lifetimesms.com/json";
			$parameters = new MultipartStream($query);
			yield 1 => new GuzzleRequest('POST', $uri, [], $parameters);
		};

    	$pool = new Pool($client, $requests(1), [
		    'concurrency' => 1,
		    'fulfilled' => function ($response, $id) use (&$recieve_response) {

		    	$response = $response->getBody()->getContents();
		    	$recieve_response = json_decode($response, true);
		    },
		    'rejected' => function ($reason, $id) use (&$recieve_response) {
		        $recieve_response = $reason->getMessage();
		    },
		]);

		$promise = $pool->promise();
		$promise->wait();

		return $recieve_response;
    }
}