<?php

namespace Uphold\Model;

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
     * Gets list of card addresses.
     *
     * @return array
     */
    public function getAddresses();

    /**
     * Gets the transactions associated with the card identified by the user.
     *
     * @return array
     */
    public function getTransactions();

    /**
     * Creates a new crypto address for the card.
     *
     * @param string $network The type of crypto address. Possible values are: bitcoin, ethereum, litecoin or voxel.
     *
     * @return Card
     */
    public function createCryptoAddress($network);

    /**
     * Creates a new transaction.
     *
     * @param string $destination Email or bitcoin address.
     * @param string $amount The amount to be transfered.
     * @param array $denomination Transaction denomination.
     *
     * @return Transaction
     */
    public function createTransaction($destination, $amount, $denomination);

    /**
     * Updates card information.
     *
     * @param array $params Card information to update.
     *
     * @return Card
     */
    public function update(array $params);
}
