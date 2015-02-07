<?php

require_once 'vendor/autoload.php';

use Bitreserve\BitreserveClient as Client;

// Initialize the client.
$client = new Client('AUTHORIZATION_TOKEN');

// Get the current user.
$user = $client->getUser();

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
