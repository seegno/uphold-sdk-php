<?php

require_once 'vendor/autoload.php';

use \Bitreserve\BitreserveClient as Client;

// Initialize the client. In this case, we don't need an
// AUTHORIZATION_TOKEN because the Ticker endpoint is public.
$client = new Client();

// Get the reserve summary of all the obligations and assets within it.
$statistics = $client->getReserve()->getStatistics();

print_r($statistics);
