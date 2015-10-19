<?php

namespace Uphold\Model;

use Uphold\UpholdClient;
use Uphold\Paginator\Paginator;

/**
 * Reserve Model.
 */
class Reserve extends BaseModel implements ReserveInterface
{
    /**
     * Constructor.
     *
     * @param UpholdClient $client Uphold client
     */
    public function __construct(UpholdClient $client)
    {
        $this->client = $client;;
    }

    /**
     * {@inheritdoc}
     */
    public function getLedger()
    {
        return new Paginator($this->client, '/reserve/ledger');
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
        $pager = new Paginator($this->client, '/reserve/transactions');
        $pager->setModel('Uphold\Model\Transaction');

        return $pager;
    }
}
