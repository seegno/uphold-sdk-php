<?php

namespace Uphold\Model;

/**
 * ReserveInterface.
 */
interface ReserveInterface
{
    /**
     * Return the public view of any transaction.
     *
     * @param string $id  The transaction id.
     *
     * @return Transaction
     */
    public function getTransactionById($id);

    /**
     * Return the public view of all transactions from the beginning of time.
     *
     * @return array
     */
    public function getTransactions();

    /**
     * Get the reserve summary of all the obligations and assets within it.
     *
     * @return array
     */
    public function getStatistics();
}
