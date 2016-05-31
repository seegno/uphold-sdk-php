<?php

namespace Uphold\Model;

interface UserInterface
{
    /**
     * * Gets all user's accounts.
     *
     * @return $accounts
     */
    public function getAccounts();

    /**
     * Gets the details associated with the account ID provided.
     *
     * @return $account
     */
    public function getAccountById($id);

    /**
     * * Gets all balances for current user.
     *
     * @return $balances
     */
    public function getBalances();

    /**
     * Gets current user balance on a given currency.
     *
     * @return $balance
     */
    public function getBalanceByCurrency($currency);

    /**
     * Gets the details associated with the card address provided.
     *
     * @return $card
     */
    public function getCardByAddress($address);

    /**
     * Gets the details associated with the card ID provided.
     *
     * @return $card
     */
    public function getCardById($id);

    /**
     * Gets an array of the current user’s cards.
     *
     * @return $cards
     */
    public function getCards();

    /**
     * Gets current user’s cards on a given currency.
     *
     * @return array
     */
    public function getCardsByCurrency($currency);

    /**
     * Gets current user contacts.
     *
     * @return array
     */
    public function getContacts();

    /**
     * Gets user country.
     *
     * @return $country
     */
    public function getCountry();

    /**
     * Gets user currencies.
     *
     * @return $currencies
     */
    public function getCurrencies();

    /**
     * Gets user email.
     *
     * @return $email
     */
    public function getEmail();

    /**
     * Gets user firt name.
     *
     * @return $firstName
     */
    public function getFirstName();

    /**
     * Gets user last name.
     *
     * @return $lastName
     */
    public function getLastName();

    /**
     * Gets user full name.
     *
     * @return $name
     */
    public function getName();

    /**
     * * Gets all phones associated with the current user.
     *
     * @return $balances
     */
    public function getPhones();

    /**
     * Gets user settings.
     *
     * @return $settings
     */
    public function getSettings();

    /**
     * Gets user state.
     *
     * @return $state
     */
    public function getState();

    /**
     * Gets user current state.
     *
     * @return $status
     */
    public function getStatus();

    /**
     * Gets user current total balance.
     *
     * @return string
     */
    public function getTotalBalance();

    /**
     * Gets all transactions associated with the current user.
     *
     * @param string $limit Number of transactions.
     *
     * @return $transactions
     */
    public function getTransactions($limit = null);

    /**
     * Gets user username.
     *
     * @return $username
     */
    public function getUsername();

    /**
     * Creates a new Card for this user.
     *
     * @param string $label Card label.
     * @param string $currency Card currency
     *
     * @return Card
     */
    public function createCard($label, $currency);

    /**
     * Update current user information.
     *
     * @param array $params List of parameters to update.
     *
     * @return $this
     */
    public function update(array $params);

    /**
     * Revoke current token.
     *
     * @return Response
     */
    public function revokeToken();
}
