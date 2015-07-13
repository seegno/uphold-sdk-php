<?php

namespace Bitreserve\Model;

use Bitreserve\BitreserveClient;

/**
 * Rate Model.
 */
class Rate extends BaseModel implements RateInterface
{
    /**
     * Ask.
     *
     * @var string
     */
    protected $ask;

    /**
     * Bid.
     *
     * @var string
     */
    protected $bid;

    /**
     * Currency.
     *
     * @var string
     */
    protected $currency;

    /**
     * Pair.
     *
     * @var string
     */
    protected $pair;

    /**
     * Constructor.
     *
     * @param BitreserveClient $client Bitreserve client.
     * @param array $data User data.
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
