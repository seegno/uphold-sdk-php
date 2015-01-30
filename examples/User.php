<?php

require_once 'vendor/autoload.php';

use Bitreserve\BitreserveClient as Client;

// Initialize the client.
$client = new Client('AUTHORIZATION_TOKEN');

// Get the current user.
$user = $client->getUser();

echo "\n*** User Information ***\n";
echo sprintf("Name: %s\n", $user->getName());
echo sprintf("Username: %s\n", $user->getUsername());
echo sprintf("Email: %s\n", $user->getEmail());
echo sprintf("Country: %s\n", $user->getCountry());

$totalBalance = $user->getTotalBalance();

echo sprintf("Total Balance: %s %s\n", $totalBalance['amount'], $totalBalance['currency']);

// Get balances for all currencies.
echo "\n*** User Balances ***\n";
$balances = $user->getBalances();

foreach ($balances as $currency => $balance) {
    echo sprintf("Balance in %s: %s (%s %s)\n", $currency, $balance['balance'], $balance['amount'], $balance['currency']);
}

// Expose all user information.
echo "\n*** User full information ***\n";
print_r($user->toArray());
