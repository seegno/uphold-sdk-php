<?php

namespace Uphold\Tests\Unit\Paginator;

use Seegno\TestBundle\TestCase\BaseTestCase;
use Uphold\Exception\UpholdClientException;
use Uphold\Paginator\Paginator;

/**
 * PaginatorTest.
 */
class PaginatorTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfPaginator()
    {
        $client = $this->getMock('Uphold\UpholdClient');

        $pager = new Paginator($client, '/path');

        $this->assertInstanceOf('Uphold\Paginator\Paginator', $pager);
    }

    /**
     * @test
     */
    public function shouldSetModel()
    {
        $client = $this->getMock('Uphold\UpholdClient');

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

        $response = $this
            ->getMockBuilder('Uphold\HttpClient\Message\Response')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $response
            ->expects($this->once())
            ->method('getContentRange')
            ->will($this->returnValue($contentRange))
        ;

        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock()
        ;

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/path', array(), array('range' => 'items=0-1'))
            ->will($this->returnValue($response))
        ;

        $pager = new Paginator($client, '/path');

        $this->assertEquals($contentRange['count'], $pager->count());
    }

    /**
     * @test
     */
    public function shouldReturnZeroIfHttpCodeIs412OnCount()
    {
        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock()
        ;

        $client
            ->expects($this->once())
            ->method('get')
            ->will($this->throwException(new UpholdClientException('foobar', 'qux', 412)))
        ;

        $pager = new Paginator($client, '/path');

        $this->assertEquals(0, $pager->count());
    }

    /**
     * @test
     */
    public function shouldReturnZeroIfHttpCodeIs416OnCount()
    {
        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock()
        ;

        $client
            ->expects($this->once())
            ->method('get')
            ->will($this->throwException(new UpholdClientException('foobar', 'qux', 416)))
        ;

        $pager = new Paginator($client, '/path');

        $this->assertEquals(0, $pager->count());
    }

    /**
     * @test
     *
     * @expectedException Uphold\Exception\UpholdClientException
     * @expectedExceptionMessage foobar
     */
    public function shouldThrownAnExceptionIfHttpCodeIsNot412Or416OnCount()
    {
        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock()
        ;

        $client
            ->expects($this->once())
            ->method('get')
            ->will($this->throwException(new UpholdClientException('foobar', 'qux', 500)))
        ;

        $pager = new Paginator($client, '/path');
        $pager->count();
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

        $response = $this
            ->getMockBuilder('Uphold\HttpClient\Message\Response')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $response
            ->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($data))
        ;

        $response
            ->expects($this->once())
            ->method('getContentRange')
            ->will($this->returnValue($contentRange))
        ;

        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock()
        ;

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/path', array(), array('range' => 'items=0-49'))
            ->will($this->returnValue($response))
        ;

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

        $response = $this
            ->getMockBuilder('Uphold\HttpClient\Message\Response')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $response
            ->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($data))
        ;

        $response
            ->expects($this->once())
            ->method('getContentRange')
            ->will($this->returnValue($contentRange))
        ;

        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock()
        ;

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/path', array(), array('range' => 'items=0-49'))
            ->will($this->returnValue($response))
        ;

        $pager = new Paginator($client, '/path');
        $pager->setModel('Uphold\Model\Transaction');

        $transactions = $pager->getNext();

        foreach ($transactions as $transaction) {
            $this->assertInstanceOf('Uphold\Model\Transaction', $transaction);
        }
    }

        /**
     * @test
     */
    public function shouldReturnZeroIfHttpCodeIs412OnGetNext()
    {
        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock()
        ;

        $client
            ->expects($this->once())
            ->method('get')
            ->will($this->throwException(new UpholdClientException('foobar', 'qux', 412)))
        ;

        $pager = new Paginator($client, '/path');

        $this->assertEquals(array(), $pager->getNext());
    }

    /**
     * @test
     */
    public function shouldReturnZeroIfHttpCodeIs416OnGetNext()
    {
        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock()
        ;

        $client
            ->expects($this->once())
            ->method('get')
            ->will($this->throwException(new UpholdClientException('foobar', 'qux', 416)))
        ;

        $pager = new Paginator($client, '/path');

        $this->assertEquals(array(), $pager->getNext());
    }

    /**
     * @test
     *
     * @expectedException Uphold\Exception\UpholdClientException
     * @expectedExceptionMessage foobar
     */
    public function shouldThrownAnExceptionIfHttpCodeIsNot412Or416OnGetNext()
    {
        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock()
        ;

        $client
            ->expects($this->once())
            ->method('get')
            ->will($this->throwException(new UpholdClientException('foobar', 'qux', 500)))
        ;

        $pager = new Paginator($client, '/path');
        $pager->getNext();
    }

    /**
     * @test
     */
    public function shouldReturnFalseIfStartIsGreaterThanOrEqualsCount()
    {
        $pager = $this
            ->getMockBuilder('Uphold\Paginator\Paginator')
            ->disableOriginalConstructor()
            ->setMethods(array('count', 'getNextRange'))
            ->getMock()
        ;

        $pager
            ->expects($this->once())
            ->method('count')
            ->willReturn(1)
        ;

        $pager
            ->expects($this->once())
            ->method('getNextRange')
            ->willReturn(array('start' => 2))
        ;

        $this->assertEquals(false, $pager->hasNext());
    }

    /**
     * @test
     */
    public function shouldReturnTrueIfStartIsLessThanCount()
    {
        $pager = $this
            ->getMockBuilder('Uphold\Paginator\Paginator')
            ->disableOriginalConstructor()
            ->setMethods(array('count', 'getNextRange'))
            ->getMock()
        ;

        $pager
            ->expects($this->once())
            ->method('count')
            ->willReturn(1)
        ;

        $pager
            ->expects($this->once())
            ->method('getNextRange')
            ->willReturn(array('start' => 0))
        ;

        $this->assertEquals(true, $pager->hasNext());
    }

    /**
     * @test
     */
    public function shouldReturnGivenDataIfModelIsNull()
    {
        $pager = $this
            ->getMockBuilder('Uphold\Paginator\Paginator')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock()
        ;

        $data = array('foo' => 'bar');

        $this->assertEquals($data, $pager->hydrate($data));
    }

    /**
     * @test
     */
    public function shouldReturnHydratedResults()
    {
        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $pager = new Paginator($client, '/path');
        $pager->setModel('Uphold\Model\User');

        $data = array(
            array(
                'firstName' => 'foo',
                'lastName' => 'bar',
            ),
            array(
                'firstName' => 'waldo',
                'lastName' => 'fred',
            ),
        );

        $results = $pager->hydrate($data);

        foreach ($results as $key => $object) {
            $this->assertInstanceOf('Uphold\Model\User', $object);
            $this->assertEquals($data[$key]['firstName'], $object->getFirstName());
            $this->assertEquals($data[$key]['lastName'], $object->getLastName());
        }
    }
}
