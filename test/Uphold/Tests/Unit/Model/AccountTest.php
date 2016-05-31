<?php

namespace Uphold\Tests\Unit\Model;

use Uphold\Model\Account;
use Uphold\Tests\Unit\Model\ModelTestCase;

/**
 * AccountTest.
 */
class AccountTest extends ModelTestCase
{
    /**
     * @test
     */
    public function shouldReturnAllFieldsFromModel()
    {
        $data = array(
            'currency' => $this->getFaker()->currencyCode,
            'id' => $this->getFaker()->uuid,
            'label' => $this->getFaker()->sentence(3),
            'status' => 'ok',
            'type' => 'card',
        );

        $client = $this->getUpholdClientMock();
        $account = new Account($client, $data);

        $this->assertEquals($data, $account->toArray());
    }

    /**
     * @test
     */
    public function shouldReturnInstanceOfAccount()
    {
        $client = $this->getUpholdClientMock();
        $data = array('id' => $this->getFaker()->randomDigitNotNull);
        $account = new Account($client, $data);

        $this->assertInstanceOf('Uphold\UpholdClient', $account->getClient());
        $this->assertInstanceOf('Uphold\Model\Account', $account);
    }

    /**
     * @test
     *
     * @expectedException PHPUnit_Framework_Error
     * @expectedExceptionMessage Argument 1 passed to Uphold\Model\Account::__construct() must be an
     *                           instance of Uphold\UpholdClient, string given
     */
    public function shouldThrowExceptionWhenFirstArgumentIsNotAnInstanceOfUpholdClient()
    {
        $account = new Account('foo', 'bar');
    }

    /**
     * @test
     */
    public function shouldReturnId()
    {
        $client = $this->getUpholdClientMock();
        $data = array('id' => $this->getFaker()->randomDigitNotNull);
        $account = new Account($client, $data);

        $this->assertEquals($data['id'], $account->getId());
    }

    /**
     * @test
     */
    public function shouldReturnCurrency()
    {
        $client = $this->getUpholdClientMock();
        $data = array('currency' => $this->getFaker()->currencyCode);
        $account = new Account($client, $data);

        $this->assertEquals($data['currency'], $account->getCurrency());
    }

    /**
     * @test
     */
    public function shouldReturnLabel()
    {
        $client = $this->getUpholdClientMock();
        $data = array('label' => $this->getFaker()->sentence(3));
        $account = new Account($client, $data);

        $this->assertEquals($data['label'], $account->getLabel());
    }

    /**
     * @test
     */
    public function shouldReturnStatus()
    {
        $client = $this->getUpholdClientMock();
        $data = array('status' => 'foobar');
        $account = new Account($client, $data);

        $this->assertEquals($data['status'], $account->getStatus());
    }

    /**
     * @test
     */
    public function shouldReturnType()
    {
        $client = $this->getUpholdClientMock();
        $data = array('type' => 'foobiz');
        $account = new Account($client, $data);

        $this->assertEquals($data['type'], $account->getType());
    }

    /**
     * Get model class.
     *
     * @return string
     */
    protected function getModelClass()
    {
        return 'Uphold\Model\Account';
    }
}
