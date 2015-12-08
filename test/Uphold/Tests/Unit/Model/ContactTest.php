<?php

namespace Uphold\Tests\Unit\Model;

use Uphold\Model\Contact;
use Uphold\Tests\Unit\Model\ModelTestCase;

/**
 * ContactTest.
 */
class ContactTest extends ModelTestCase
{
    /**
     * @test
     */
    public function shouldReturnAllFieldsFromModel()
    {
        $data = array(
            'addresses' => array('id' => '1GpBtJXXa1NdG94cYPGZTc3DfRY2P7EwzH', 'network' => 'bitcoin'),
            'company' => $this->getFaker()->company,
            'emails' => array($this->getFaker()->email),
            'firstName' => $this->getFaker()->firstName,
            'id' => $this->getFaker()->uuid,
            'lastName' => $this->getFaker()->lastName,
            'name' => $this->getFaker()->name,
        );

        $client = $this->getUpholdClientMock();
        $contact = new Contact($client, $data);

        $this->assertEquals($data, $contact->toArray());
    }

    /**
     * @test
     */
    public function shouldReturnInstanceOfContact()
    {
        $data = array('id' => $this->getFaker()->randomDigitNotNull);

        $client = $this->getUpholdClientMock();

        $contact = new Contact($client, $data);

        $this->assertInstanceOf('Uphold\UpholdClient', $contact->getClient());
        $this->assertInstanceOf('Uphold\Model\Contact', $contact);
    }

    /**
     * @test
     */
    public function shouldReturnId()
    {
        $data = array('id' => $this->getFaker()->randomDigitNotNull);

        $client = $this->getUpholdClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['id'], $contact->getId());
    }

    /**
     * @test
     */
    public function shouldReturnFirstName()
    {
        $data = array('firstName' => $this->getFaker()->firstName);

        $client = $this->getUpholdClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['firstName'], $contact->getFirstName());
    }

    /**
     * @test
     */
    public function shouldReturnLastName()
    {
        $data = array('lastName' => $this->getFaker()->lastName);

        $client = $this->getUpholdClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['lastName'], $contact->getLastName());
    }

    /**
     * @test
     */
    public function shouldReturnName()
    {
        $data = array('name' => $this->getFaker()->name);

        $client = $this->getUpholdClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['name'], $contact->getName());
    }

    /**
     * @test
     */
    public function shouldReturnCompany()
    {
        $data = array('company' => $this->getFaker()->company);

        $client = $this->getUpholdClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['company'], $contact->getCompany());
    }

    /**
     * @test
     */
    public function shouldReturnEmails()
    {
        $data = array('emails' => array($this->getFaker()->email));

        $client = $this->getUpholdClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['emails'], $contact->getEmails());
    }

    /**
     * @test
     */
    public function shouldReturnAddresses()
    {
        $data = array('addresses' => array(array('id' => '1GpBtJXXa1NdG94cYPGZTc3DfRY2P7EwzH', 'network' => 'bitcoin')));

        $client = $this->getUpholdClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['addresses'], $contact->getAddresses());
    }

    /**
     * Get model class.
     *
     * @return string
     */
    protected function getModelClass()
    {
        return 'Uphold\Model\Contact';
    }
}
