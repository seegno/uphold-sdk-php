<?php

require_once 'vendor/autoload.php';

use Uphold\UpholdClient as Client;

// Initialize the client.
$client = new Client(array('sandbox' => true));

// Get user.
$user = $client->getUser('AUTHORIZATION_TOKEN');

// Get current user accounts.
$accounts = $user->getAccounts();

echo "*** List of user accounts ***\n";

foreach ($accounts as $account) {
    echo sprintf("Id: %s\n", $account->getId());
    echo sprintf("Label: %s\n", $account->getLabel());
    echo sprintf("Currency: %s\n", $account->getCurrency());
    echo sprintf("Status: %s\n", $account->getStatus());
    echo sprintf("Type: %s\n", $account->getType());
    echo "\n";
}
