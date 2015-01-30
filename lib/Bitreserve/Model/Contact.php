<?php

namespace Bitreserve\Model;

use Bitreserve\BitreserveClient;

/**
 * Contact Model.
 */
class Contact extends BaseModel implements ContactInterface
{
    /**
     * @var id
     */
    protected $id;

    /**
     * @var addresses
     */
    protected $addresses;

    /**
     * @var company
     */
    protected $company;

    /**
     * @var emails
     */
    protected $emails;

    /**
     * @var firstName
     */
    protected $firstName;

    /**
     * @var lastName
     */
    protected $lastName;

    /**
     * @var name
     */
    protected $name;

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
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmails()
    {
        return $this->emails;
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
    public function getId()
    {
        return $this->id;
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
}
