<?php

namespace Uphold\Model;

use Uphold\Model\BaseModel;
use Uphold\Paginator\Paginator;
use Uphold\UpholdClient;

/**
 * Card Model.
 */
class Card extends BaseModel implements CardInterface
{
    /**
     * Id.
     *
     * @var string
     */
    protected $id;

    /**
     * Address.
     *
     * @var string
     */
    protected $address;

    /**
     * Available amount.
     *
     * @var string
     */
    protected $available;

    /**
     * Balance amount.
     *
     * @var string
     */
    protected $balance;

    /**
     * Currency.
     *
     * @var string
     */
    protected $currency;

    /**
     * Label.
     *
     * @var string
     */
    protected $label;

    /**
     * Last transaction date.
     *
     * @var string
     */
    protected $lastTransactionAt;

    /**
     * Settings.
     *
     * @var array
     */
    protected $settings;

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
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * {@inheritdoc}
     */
    public function getBalance()
    {
        return $this->balance;
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
    public function getLastTransactionAt()
    {
        return $this->lastTransactionAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddresses()
    {
        return $this->client->get(sprintf('/me/cards/%s/addresses', $this->id))->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactions()
    {
        $pager = new Paginator($this->client, sprintf('/me/cards/%s/transactions', $this->id));
        $pager->setModel('Uphold\Model\Transaction');

        return $pager;
    }

    /**
     * {@inheritdoc}
     */
    public function createCryptoAddress($network)
    {
        $this->client->post(sprintf('/me/cards/%s/addresses', $this->id), array('network' => $network));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createTransaction($destination, $amount, $currency, $message = null, $commit = false)
    {
        $postData = array(
            'destination' => $destination,
            'denomination' => array(
                'amount' => $amount,
                'currency' => $currency,
            ),
            'message' => $message,
        );

        $response = $this->client->post(sprintf('/me/cards/%s/transactions?commit=%s', $this->id, $commit), $postData);

        $transaction = new Transaction($this->client, $response->getContent());

        return $transaction;
    }

    /**
     * {@inheritdoc}
     */
    public function update(array $data)
    {
        $response = $this->client->patch(sprintf('/me/cards/%s', $this->id), $data);

        $this->updateFields($response->getContent());

        return $this;
    }
}
