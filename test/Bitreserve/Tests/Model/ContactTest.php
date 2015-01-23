<?php

namespace Bitreserve\Tests\Model;

use Bitreserve\Model\Contact;

class ContactTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfContact()
    {
        $data = array('id' => '1');

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
        $data = array('id' => '1');

        $client = $this->getBitreserveClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['id'], $contact->getId());
    }

    /**
     * @test
     */
    public function shouldReturnFirstName()
    {
        $data = array('firstName' => 'Foobar');

        $client = $this->getBitreserveClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['firstName'], $contact->getFirstName());
    }

    /**
     * @test
     */
    public function shouldReturnLastName()
    {
        $data = array('lastName' => 'Foobar');

        $client = $this->getBitreserveClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['lastName'], $contact->getLastName());
    }

    /**
     * @test
     */
    public function shouldReturnName()
    {
        $data = array('name' => 'Foobar');

        $client = $this->getBitreserveClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['name'], $contact->getName());
    }

    /**
     * @test
     */
    public function shouldReturnCompany()
    {
        $data = array('company' => 'Foobar');

        $client = $this->getBitreserveClientMock();

        $contact = new Contact($client, $data);

        $this->assertEquals($data['company'], $contact->getCompany());
    }

    /**
     * @test
     */
    public function shouldReturnEmails()
    {
        $data = array('emails' => array('foo@bar.com'));

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

    protected function getModelClass()
    {
        return 'Bitreserve\Model\Contact';
    }
}
