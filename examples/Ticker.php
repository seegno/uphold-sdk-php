<?php

require_once 'vendor/autoload.php';

use Uphold\UpholdClient as Client;

// Initialize the client.
$client = new Client(array('sandbox' => true));

// Get rates (public endpoint).
$rates = $client->getRates();

echo "*** Current exchange rates ***\n";

foreach ($rates as $rate) {
    echo sprintf("Pair: %s\n", $rate->getPair());
    echo sprintf("Ask: 1 %s = %s %s\n", substr($rate->getPair(), 0, 3), $rate->getAsk(), $rate->getCurrency());
    echo sprintf("Bid: 1 %s = %s %s\n", substr($rate->getPair(), 0, 3), $rate->getBid(), $rate->getCurrency());
    echo "\n";
}
