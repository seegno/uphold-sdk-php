<?php

namespace Uphold\Model;

/**
 * ContactInterface.
 */
interface ContactInterface
{
    /**
     * Gets list of contact addresses.
     *
     * @return $addresses
     */
    public function getAddresses();

    /**
     * Gets contact email.
     *
     * @return $company
     */
    public function getCompany();

    /**
     * Gets list of contact emails.
     *
     * @return $emails
     */
    public function getEmails();

    /**
     * Gets contact first name.
     *
     * @return $firstName
     */
    public function getFirstName();

    /**
     * Gets contact id.
     *
     * @return $id
     */
    public function getId();

    /**
     * Gets contact last name.
     *
     * @return $lastName
     */
    public function getLastName();

    /**
     * Gets contact full name.
     *
     * @return $name
     */
    public function getName();
}
