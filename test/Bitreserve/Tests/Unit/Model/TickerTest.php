<?php

namespace Bitreserve\Tests\Unit\Model;

use Bitreserve\Model\Ticker;

/**
 * TickerTest.
 */
class TickerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfTicker()
    {
        $data = array('ask' => '1');

        $client = $this->getBitreserveClientMock();

        $ticker = new Ticker($client, $data);

        $this->assertInstanceOf('Bitreserve\BitreserveClient', $ticker->getClient());
        $this->assertInstanceOf('Bitreserve\Model\Ticker', $ticker);
    }

    /**
     * @test
     */
    public function shouldReturnAsk()
    {
        $data = array('ask' => '1');

        $client = $this->getBitreserveClientMock();

        $ticker = new Ticker($client, $data);

        $this->assertEquals($data['ask'], $ticker->getAsk());
    }

    /**
     * @test
     */
    public function shouldReturnBid()
    {
        $data = array('bid' => '1');

        $client = $this->getBitreserveClientMock();

        $ticker = new Ticker($client, $data);

        $this->assertEquals($data['bid'], $ticker->getBid());
    }

    /**
     * @test
     */
    public function shouldReturnCurrency()
    {
        $data = array('currency' => 'BTC');

        $client = $this->getBitreserveClientMock();

        $ticker = new Ticker($client, $data);

        $this->assertEquals($data['currency'], $ticker->getCurrency());
    }

    /**
     * @test
     */
    public function shouldReturnPair()
    {
        $data = array('pair' => '1');

        $client = $this->getBitreserveClientMock();

        $ticker = new Ticker($client, $data);

        $this->assertEquals($data['pair'], $ticker->getPair());
    }

    protected function getModelClass()
    {
        return 'Bitreserve\Model\Ticker';
    }
}
