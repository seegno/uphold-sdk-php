<?php

namespace Uphold\Model;

use Uphold\UpholdClient;

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
     * @param UpholdClient $client Uphold client.
     * @param array $data User data.
     */
    public function __construct(UpholdClient $client, $data)
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
