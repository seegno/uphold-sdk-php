<?php

namespace Bitreserve\Model;

use Bitreserve\BitreserveClient;

/**
 * User Model.
 */
class User extends BaseModel implements UserInterface
{
    /**
     * @var country
     */
    protected $country;

    /**
     * @var email
     */
    protected $email;

    /**
     * @var firstName
     */
    protected $fistName;

    /**
     * @var lastName
     */
    protected $lastName;

    /**
     * @var name
     */
    protected $name;

    /**
     * @var settings
     */
    protected $settings;

    /**
     * @var state
     */
    protected $state;

    /**
     * @var state
     */
    protected $status;

    /**
     * @var username
     */
    protected $username;

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
    public function getBalances()
    {
        $data = $this->client->get('/me');

        $this->updateFields($data);

        return $this->balances['currencies'];
    }

    /**
     * {@inheritdoc}
     */
    public function getBalanceByCurrency($currency)
    {
        $data = $this->client->get('/me');

        $this->updateFields($data);

        foreach ($this->balances['currencies'] as $balanceCurrency => $balance) {
            if ($currency === $balanceCurrency) {
                return $balance;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getCardById($id)
    {
        $data = $this->client->get(sprintf('/me/cards/%s', $id));

        return new Card($this->client, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getCards()
    {
        $data = $this->client->get('/me/cards');

        return array_map(function($card) {
            return new Card($this->client, $card);
        }, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getCardsByCurrency($currency)
    {
        $data = $this->client->get('/me/cards');

        $cards = array_reduce($data, function($cards, $card) use ($currency) {
            if ($currency !== $card['currency']) {
                return $cards;
            }

            $cards[] = new Card($this->client, $card);

            return $cards;
        });

        return $cards;
    }

    /**
     * {@inheritdoc}
     */
    public function getContacts()
    {
        $data = $this->client->get('/me/contacts');

        return array_map(function($contact) {
            return new Contact($this->client, $contact);
        }, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * {@inheritdoc}
     */
    public function getPhones()
    {
        $data = $this->client->get('/me/phones');

        $this->phones = $data;

        return $this->phones;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettings()
    {
        $data = $this->client->get('/me');

        $this->updateFields($data);

        return $this->settings;
    }

    /**
     * {@inheritdoc}
     */
    public function getState()
    {
        return $this->state;
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
    public function getTotalBalance()
    {
        $data = $this->client->get('/me');

        $this->updateFields($data);

        return array(
            'amount' => $this->balances['total'],
            'currency' => $this->settings['currency'],
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactions()
    {
        $data = $this->client->get('/me/transactions');

        return array_map(function($transaction) {
            return new Transaction($this->client, $transaction);
        }, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function createCard($label, $currency)
    {
        $data = $this->client->post('/me/cards', array('label' => $label, 'currency' => $currency));

        return new Card($this->client, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(array $params)
    {
        $data = $this->client->patch('/me', $params);

        $this->updateFields($data);

        return $this;
    }
}
