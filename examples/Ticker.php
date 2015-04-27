<?php

require_once 'vendor/autoload.php';

use Bitreserve\BitreserveClient as Client;

// Initialize the client. In this case, we don't need an
// AUTHORIZATION_TOKEN because the Ticker endpoint is public.
$client = new Client();

// Get rates.
$rates = $client->getTicker();

echo "*** Current exchange rates ***\n";

foreach ($rates as $rate) {
    echo sprintf("Pair: %s\n", $rate->getPair());
    echo sprintf("Ask: 1 %s = %s %s\n", substr($rate->getPair(), 0, 3), $rate->getAsk(), $rate->getCurrency());
    echo sprintf("Bid: 1 %s = %s %s\n", substr($rate->getPair(), 0, 3), $rate->getBid(), $rate->getCurrency());
    echo "\n";
}
