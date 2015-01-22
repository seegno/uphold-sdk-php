<?php

namespace Bitreserve\Model;

use Bitreserve\BitreserveClient;

class Ticker extends BaseModel implements TickerInterface
{
    /**
     * @var ask
     */
    protected $ask;

    /**
     * @var bid
     */
    protected $bid;

    /**
     * @var currency
     */
    protected $currency;

    /**
     * @var pair
     */
    protected $pair;

    /**
     * Constructor.
     *
     * @param BitreserveClient $client Bitreserve client
     * @param array $data User data
     */
    public function __construct(BitreserveClient $client, $data)
    {
        $this->client = $client;

        $this->updateFields($data);
    }

    /**
     * {@inheritdoc}
     */
    public function getAsk()
    {
        return $this->ask;
    }

    /**
     * {@inheritdoc}
     */
    public function getBid()
    {
        return $this->bid;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * {@inheritdoc}
     */
    public function getPair()
    {
        return $this->pair;
    }
}
