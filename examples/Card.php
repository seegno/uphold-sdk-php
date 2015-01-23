<?php

require_once 'vendor/autoload.php';

use \Bitreserve\BitreserveClient as Client;

$client = new Client('AUTHORIZATION_TOKEN');
$cards = $user->getUser()->getCards();

echo "*** List of user cards ***\n";

foreach ($cards as $card) {
    echo sprintf("Label: %s\n", $card->getLabel());
    echo sprintf("Id: %s\n", $card->getId());
    echo sprintf("Bitcoin Address: %s\n", $card->getAddress()['bitcoin']);
    echo sprintf("Balance: %s\n", $card->getBalance());
    echo "\n";
}
