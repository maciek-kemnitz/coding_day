<?php

$today = date('Y-m-d');

$client = new \Github\Client();
$client->authenticate(OAUTH_TOKEN,null, 'http_token');
$issues = $client->api('issue')->all('ZnanyLekarz', 'znanylekarz', array('labels' => "coding day", 'state' => 'open'));


$leftOpen = count($issues);
var_dump($leftOpen);

echo json_encode(array('issues_count' => $leftOpen));