<?php

require_once 'vendor/autoload.php';

use \Bitreserve\BitreserveClient as Client;

// Initialize the client. In this case, we don't need an
// AUTHORIZATION_TOKEN because the Ticker endpoint is public.
$client = new Client();

// Get tickers.
$tickers = $client->getTicker();

echo "*** Current exchange rates ***\n";

foreach ($tickers as $ticker) {
    echo sprintf("Pair: %s\n", $ticker->getPair());
    echo sprintf("Ask: 1 %s = %s %s\n", substr($ticker->getPair(), 0, 3), $ticker->getAsk(), $ticker->getCurrency());
    echo sprintf("Bid: 1 %s = %s %s\n", substr($ticker->getPair(), 0, 3), $ticker->getBid(), $ticker->getCurrency());
    echo "\n";
}
