<?php

require_once 'vendor/autoload.php';

use \Uphold\UpholdClient as Client;

// Initialize the client.
$client = new Client(array('sandbox' => true));

// Get the reserve summary of all the obligations and assets within it (public endpoint).
$statistics = $client->getReserve()->getStatistics();

print_r($statistics);

// Get the reserve ledger
$pager = $client->getReserve()->getLedger();

print_r($pager->getNext());

// Get latest transactions
$pager = $client->getReserve()->getTransactions();

print_r($pager->getNext());
