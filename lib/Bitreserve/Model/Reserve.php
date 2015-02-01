<?php

namespace Bitreserve\Model;

use Bitreserve\BitreserveClient;

/**
 * Reserve Model.
 */
class Reserve extends BaseModel implements ReserveInterface
{
    /**
     * Constructor.
     *
     * @param BitreserveClient $client Bitreserve client
     */
    public function __construct(BitreserveClient $client)
    {
        $this->client = $client;;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatistics()
    {
        return $this->client->get('/reserve/statistics');
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionById($id)
    {
        $data = $this->client->get(sprintf('/reserve/transactions/%s', $id));

        return new Transaction($this->client, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactions()
    {
        $data = $this->client->get('/reserve/transactions');

        return array_map(function($transaction) {
            return new Transaction($this->client, $transaction);
        }, $data);
    }
}
