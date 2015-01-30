<?php

namespace Bitreserve\Model;

/**
 * ReserveInterface.
 */
interface ReserveInterface
{
    /**
     * Return the public view of any transaction.
     *
     * @return Transaction The transaction identified by a given id.
     */
    public function getTransactionById($id);

    /**
     * Return the public view of all transactions from the beginning of time.
     *
     * @return array The list all public transactions.
     */
    public function getTransactions();

    /**
     * Get the reserve summary of all the obligations and assets within it.
     *
     * @return array The list of each holdings in all available currencies.
     */
    public function getStatistics();
}
