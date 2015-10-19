<?php

namespace Uphold\Model;

use Uphold\UpholdClient;

/**
 * Contact Model.
 */
class Contact extends BaseModel implements ContactInterface
{
    /**
     * Id.
     *
     * @var string
     */
    protected $id;

    /**
     * List of contact addresses.
     *
     * @var array
     */
    protected $addresses;

    /**
     * Company.
     *
     * @var string
     */
    protected $company;

    /**
     * List of contact emails.
     *
     * @var array
     */
    protected $emails;

    /**
     * First name.
     *
     * @var string
     */
    protected $firstName;

    /**
     * Last name.
     *
     * @var string
     */
    protected $lastName;

    /**
     * Name.
     *
     * @var string
     */
    protected $name;

    /**
     * Constructor.
     *
     * @param UpholdClient $client Uphold client
     * @param array $data User data
     */
    public function __construct(UpholdClient $client, $data)
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
