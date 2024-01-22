<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Unirest\Request;

class BaseTestCase extends TestCase
{
    public function post(string $uri, array $body = [])
    {
        $headers = ['Content-Type' => 'application/json'];
        $response = Request::post($uri, $headers, Request\Body::Json($body));
        return json_decode($response->raw_body, true);
    }
}