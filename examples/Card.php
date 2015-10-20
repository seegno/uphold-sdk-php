<?php

require_once 'vendor/autoload.php';

use Uphold\UpholdClient as Client;

// Initialize the client.
$client = new Client();

// Get user.
$user = $client->getUser('AUTHORIZATION_TOKEN');

// Get current user cards.
$cards = $user->getCards();

echo "*** List of user cards ***\n";

foreach ($cards as $card) {
    echo sprintf("Label: %s\n", $card->getLabel());
    echo sprintf("Id: %s\n", $card->getId());
    echo sprintf("Bitcoin Address: %s\n", $card->getAddress()['bitcoin']);
    echo sprintf("Balance: %s\n", $card->getBalance());
    echo "\n";
}

// Get card by address.
$card = $user->getCardByAddress('1GpBtJXXa1NdG94cYPGZTc3DfRY2P7EwzH');
