<?php

namespace Bitreserve\Tests\Unit\Model;

use Bitreserve\Model\Rate;
use Bitreserve\Tests\Unit\Model\ModelTestCase;

/**
 * RateTest.
 */
class RateTest extends ModelTestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfRate()
    {
        $data = array('ask' => '1');

        $client = $this->getBitreserveClientMock();

        $rate = new Rate($client, $data);

        $this->assertInstanceOf('Bitreserve\BitreserveClient', $rate->getClient());
        $this->assertInstanceOf('Bitreserve\Model\Rate', $rate);
    }

    /**
     * @test
     */
    public function shouldReturnAsk()
    {
        $data = array('ask' => '1');

        $client = $this->getBitreserveClientMock();

        $rate = new Rate($client, $data);

        $this->assertEquals($data['ask'], $rate->getAsk());
    }

    /**
     * @test
     */
    public function shouldReturnBid()
    {
        $data = array('bid' => '1');

        $client = $this->getBitreserveClientMock();

        $rate = new Rate($client, $data);

        $this->assertEquals($data['bid'], $rate->getBid());
    }

    /**
     * @test
     */
    public function shouldReturnCurrency()
    {
        $data = array('currency' => 'BTC');

        $client = $this->getBitreserveClientMock();

        $rate = new Rate($client, $data);

        $this->assertEquals($data['currency'], $rate->getCurrency());
    }

    /**
     * @test
     */
    public function shouldReturnPair()
    {
        $data = array('pair' => '1');

        $client = $this->getBitreserveClientMock();

        $rate = new Rate($client, $data);

        $this->assertEquals($data['pair'], $rate->getPair());
    }

    /**
     * Get model class.
     *
     * @return string
     */
    protected function getModelClass()
    {
        return 'Bitreserve\Model\Rate';
    }
}
