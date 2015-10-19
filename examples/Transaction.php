<?php

require_once 'vendor/autoload.php';

use Uphold\UpholdClient as Client;

// Initialize the client.
$client = new Client();

// Get user.
$user = $client->getUser('AUTHORIZATION_TOKEN');

// Get user transactions.
$pager = $user->getTransactions();

echo "*** List of user transactions ***\n";

while ($pager->hasNext()) {
    $transactions = $pager->getNext();

    foreach ($transactions as $transaction) {
        echo sprintf("Date: %s\n", $transaction->getCreatedAt());
        echo sprintf("Status: %s\n", $transaction->getStatus());

        $origin = $transaction->getOrigin();
        echo sprintf("Origin: %s\n", $origin['description']);

        $destination = $transaction->getDestination();
        echo sprintf("Destination: %s\n", $destination['description']);

        echo sprintf("Amount: %s %s\n", $destination['amount'], $destination['currency']);
        echo "\n";
    }
}

echo "\n*** Create and commit a new transaction ***\n";

// Get a user card.
$card = $user->getCardById('ade869d8-7913-4f67-bb4d-72719f0a2be0');

// Create a new transaction.
$transaction = $card->createTransaction('foo@bar.com', '0.001', 'BTC');

// Commit the transaction
$transaction->commit();

print_r($transaction->toArray());
