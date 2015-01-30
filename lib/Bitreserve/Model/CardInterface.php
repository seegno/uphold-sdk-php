<?php

namespace Bitreserve\Model;

/**
 * CardInterface.
 */
interface CardInterface
{
    /**
     * Gets main card address.
     *
     * @return $address
     */
    public function getAddress();

    /**
     * Gets list of card addresses.
     *
     * @return $addresses
     */
    public function getAddresses();

    /**
     * Checks if the card is currently available.
     *
     * @return $available
     */
    public function getAvailable();

    /**
     * Gets card current balance.
     *
     * @return $balance
     */
    public function getBalance();

    /**
     * Gets card currency.
     *
     * @return $currency
     */
    public function getCurrency();

    /**
     * Gets card id.
     *
     * @return $id
     */
    public function getId();

    /**
     * Gets card label.
     *
     * @return $label
     */
    public function getLabel();

    /**
     *  Gets the date of the last transaction of the card.
     *
     * @return $lastTransactionAt
     */
    public function getLastTransactionAt();

    /**
     * Gets card settings.
     *
     * @return $settings
     */
    public function getSettings();

    /**
     * Gets a transaction from this card based on given id.
     *
     * @param  string $id Transaction id.
     *
     * @return Transaction $transaction One instance of transaction.
     */
    public function getTransactionById($id);

    /**
     * Gets the transactions associated with the card identified by the user.
     *
     * @return array $transactions An array of transactions.
     */
    public function getTransactions();

    /**
     * Creates a new transaction.
     *
     * @param  string $destination  Email or bitcoin address.
     * @param  string $amount       The amount to be transfered.
     * @param  array $denomination  Transaction denomination.
     *
     * @return Transaction          An Instance of a transaction.
     */
    public function createTransaction($destination, $amount, $denomination);

    /**
     * Updates card information
     * @param  array  $params Card information to update.
     *
     * @return Card           Updated card.
     */
    public function update(array $params);
}
