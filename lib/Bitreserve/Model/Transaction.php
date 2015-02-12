<?php

namespace Bitreserve\Model;

use Bitreserve\BitreserveClient;
use Bitreserve\Exception\LogicException;

/**
 * Transaction Model.
 */
class Transaction extends BaseModel implements TransactionInterface
{
    /**
     * @var id
     */
    protected $id;

    /**
     * @var createdAt
     */
    protected $createdAt;

    /**
     * @var denomination
     */
    protected $denomination;

    /**
     * @var destination
     */
    protected $destination;

    /**
     * @var message
     */
    protected $message;

    /**
     * @var origin
     */
    protected $origin;

    /**
     * @var params
     */
    protected $params;

    /**
     * @var refundedById
     */
    protected $refundedById;

    /**
     * @var status
     */
    protected $status;

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
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getDenomination()
    {
        return $this->denomination;
    }

    /**
     * {@inheritdoc}
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * {@inheritdoc}
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * {@inheritdoc}
     */
    public function getRefundedById()
    {
        return $this->refundedById;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        if (empty($this->origin['CardId'])) {
            throw new LogicException('Origin CardId is missing from this transaction');
        }

        if ('pending' !== $this->status) {
            throw new LogicException(sprintf('This transaction cannot be committed, because the current status is "%s"', $this->status));
        }

        $response = $this->client->post(sprintf('/me/cards/%s/transactions/%s/commit', $this->origin['CardId'], $this->id));

        $this->updateFields($response->getContent());
    }

    /**
     * {@inheritdoc}
     */
    public function cancel()
    {
        if (empty($this->origin['CardId'])) {
            throw new LogicException('Origin CardId is missing from this transaction');
        }

        if ('pending' === $this->status) {
            throw new LogicException('Unable to cancel uncommited transaction');
        }

        if ('waiting' !== $this->status) {
            throw new LogicException(sprintf('This transaction cannot be cancelled, because the current status is %s', $this->status));
        }

        $response = $this->client->post(sprintf('/me/cards/%s/transactions/%s/cancel', $this->origin['CardId'], $this->id));

        $this->updateFields($response->getContent());
    }
}
