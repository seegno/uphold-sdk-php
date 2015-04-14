<?php

namespace Bitreserve\Tests\Unit\Paginator;

use Bitreserve\Paginator\ArrayPaginator;
use Bitreserve\Paginator\Paginator;

/**
 * PaginatorTest.
 */
class PaginatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfPaginator()
    {
        $client = $this->getMock('Bitreserve\BitreserveClient');

        $pager = new Paginator($client, '/path');

        $this->assertInstanceOf('Bitreserve\Paginator\Paginator', $pager);
    }

    /**
     * @test
     */
    public function shouldSetModel()
    {
        $client = $this->getMock('Bitreserve\BitreserveClient');

        $pager = new Paginator($client, '/path');
        $pager->setModel('foobar');

        $this->assertEquals('foobar', $pager->getModel());
    }

    /**
     * @test
     */
    public function shouldReturnCount()
    {
        $contentRange = array(
            'count' => 200,
            'end' => 19,
            'start' => 0,
        );

        $response = $this->getMockBuilder('Bitreserve\HttpClient\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('getContentRange')
            ->will($this->returnValue($contentRange));

        $client = $this->getMockBuilder('Bitreserve\BitreserveClient')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock();

        $client->expects($this->once())
            ->method('get')
            ->with('/path', array(), array('range' => 'items=0-1'))
            ->will($this->returnValue($response));

        $pager = new Paginator($client, '/path');

        $this->assertEquals($contentRange['count'], $pager->count());
    }

    /**
     * @test
     */
    public function shouldReturnNextResults()
    {
        $data = array(
            array('1' => 'foo'),
            array('2' => 'bar'),
            array('3' => 'foobar'),
        );

        $contentRange = array(
            'count' => 200,
            'end' => 2,
            'start' => 0,
        );

        $response = $this->getMockBuilder('Bitreserve\HttpClient\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($data));

        $response->expects($this->once())
            ->method('getContentRange')
            ->will($this->returnValue($contentRange));

        $client = $this->getMockBuilder('Bitreserve\BitreserveClient')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock();

        $client->expects($this->once())
            ->method('get')
            ->with('/path', array(), array('range' => 'items=0-49'))
            ->will($this->returnValue($response));

        $pager = new Paginator($client, '/path');

        $this->assertEquals($data, $pager->getNext());
    }

    /**
     * @test
     */
    public function shouldReturnNextResultsWithGivenModel()
    {
        $data = array(
            array('id' => 1),
            array('id' => 2),
            array('id' => 3),
        );

        $contentRange = array(
            'count' => 200,
            'end' => 2,
            'start' => 0,
        );

        $response = $this->getMockBuilder('Bitreserve\HttpClient\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($data));

        $response->expects($this->once())
            ->method('getContentRange')
            ->will($this->returnValue($contentRange));

        $client = $this->getMockBuilder('Bitreserve\BitreserveClient')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock();

        $client->expects($this->once())
            ->method('get')
            ->with('/path', array(), array('range' => 'items=0-49'))
            ->will($this->returnValue($response));

        $pager = new Paginator($client, '/path');
            $pager->setModel('Bitreserve\Model\Transaction');

        $transactions = $pager->getNext();

        foreach ($transactions as $transaction) {
            $this->assertInstanceOf('Bitreserve\Model\Transaction', $transaction);
        }
    }
}
