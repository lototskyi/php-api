<?php
require __DIR__ . "/vendor/autoload.php";

$client = new GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.github.com/user/repos', [
    'headers' => [
        'Authorization' => 'token API_KEY',
        'User-Agent' => 'lototskyi'
    ]
]);

echo $response->getStatusCode() . "\n";
echo $response->getHeader('content-type')[0] . "\n";
echo substr($response->getBody(), 0, 200) . "...\n";