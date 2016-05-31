<?php

namespace Uphold\Model;

use Uphold\Model\BaseModel;
use Uphold\UpholdClient;

/**
 * Account Model.
 */
class Account extends BaseModel implements AccountInterface
{
    /**
     * Id.
     *
     * @var string
     */
    protected $id;

    /**
     * Currency.
     *
     * @var string
     */
    protected $currency;

    /**
     * Label.
     *
     * @var array
     */
    protected $label;

    /**
     * Status.
     *
     * @var string
     */
    protected $status;

    /**
     * Type.
     *
     * @var string
     */
    protected $type;

    /**
     * Constructor.
     *
     * @param UpholdClient $client Uphold client
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
    public function getCurrency()
    {
        return $this->currency;
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
    public function getLabel()
    {
        return $this->label;
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
    public function getType()
    {
        return $this->type;
    }
}
