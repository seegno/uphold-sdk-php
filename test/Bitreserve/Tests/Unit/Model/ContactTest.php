<?php

namespace Bitreserve\Tests\Unit\Model;

use Bitreserve\Model\Contact;
use Bitreserve\Tests\Unit\Model\ModelTestCase;

/**
 * ContactTest.
 */
class ContactTest extends ModelTestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfContact()
    {
        $data = array('id' => $this->getFaker()->randomDigitNotNull);

        $client = $this->getBitreserveClientMock();

        $contact = new Contact($client, $data);

        $this->assertInstanceOf('Bitreserve\BitreserveClient', $contact->getClient());
        $this->assertInstanceOf('Bitreserve\Model\Contact', $contact);
    }

    /**
     * @test
     */
    public function shouldReturnId()
    {
        $data = array('id' => $this->getFaker()->randomDigitNotNull);

        $client = $this->getBitreserveClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['id'], $contact->getId());
    }

    /**
     * @test
     */
    public function shouldReturnFirstName()
    {
        $data = array('firstName' => $this->getFaker()->firstName);

        $client = $this->getBitreserveClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['firstName'], $contact->getFirstName());
    }

    /**
     * @test
     */
    public function shouldReturnLastName()
    {
        $data = array('lastName' => $this->getFaker()->lastName);

        $client = $this->getBitreserveClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['lastName'], $contact->getLastName());
    }

    /**
     * @test
     */
    public function shouldReturnName()
    {
        $data = array('name' => $this->getFaker()->name);

        $client = $this->getBitreserveClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['name'], $contact->getName());
    }

    /**
     * @test
     */
    public function shouldReturnCompany()
    {
        $data = array('company' => $this->getFaker()->company);

        $client = $this->getBitreserveClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['company'], $contact->getCompany());
    }

    /**
     * @test
     */
    public function shouldReturnEmails()
    {
        $data = array('emails' => array($this->getFaker()->email));

        $client = $this->getBitreserveClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['emails'], $contact->getEmails());
    }

    /**
     * @test
     */
    public function shouldReturnAddresses()
    {
        $data = array('addresses' => array('id' => '1GpBtJXXa1NdG94cYPGZTc3DfRY2P7EwzH', 'network' => 'bitcoin'));

        $client = $this->getBitreserveClientMock();

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
        return 'Bitreserve\Model\Contact';
    }
}
