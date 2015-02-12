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
    public function getLedger()
    {
        $response = $this->client->get('/reserve/ledger');

        return $response->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function getStatistics()
    {
        $response = $this->client->get('/reserve/statistics');

        return $response->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionById($id)
    {
        $response = $this->client->get(sprintf('/reserve/transactions/%s', $id));

        return new Transaction($this->client, $response->getContent());
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactions()
    {
        $response = $this->client->get('/reserve/transactions');

        return array_map(function($transaction) {
            return new Transaction($this->client, $transaction);
        }, $response->getContent());
    }
}
