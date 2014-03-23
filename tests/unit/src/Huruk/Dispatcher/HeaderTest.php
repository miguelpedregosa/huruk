<?php
/**
 *
 * User: migue
 * Date: 23/03/14
 * Time: 12:53
 */

namespace unit\src\Huruk\Dispatcher;


use Huruk\Dispatcher\Header;

class HeaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @cover Header::__construct
     */
    public function testNewHeader()
    {
        $header = new Header('Foo:bar');
        $this->assertEquals('Foo:bar', $header->getHeader());
        $this->assertEquals(true, $header->getReplace());
        $this->assertEquals(200, $header->getHttpResponseCode());

        $header = new Header('Foo:bar', false, 301);
        $this->assertEquals('Foo:bar', $header->getHeader());
        $this->assertEquals(false, $header->getReplace());
        $this->assertEquals(301, $header->getHttpResponseCode());
    }

    /**
     * @cover Header::setHeader
     * @cover Header::getHeader
     */
    public function testSetHeader()
    {
        $header = new Header();
        $this->assertInstanceOf('\Huruk\Dispatcher\Header', $header->setHeader('Foo:bar'));
        $this->assertEquals('Foo:bar', $header->getHeader());

    }

    /**
     * @cover Header::setReeplace
     * @cover Header::getReplace
     */
    public function testSetReplace()
    {
        $header = new Header();
        $this->assertInstanceOf('\Huruk\Dispatcher\Header', $header->setReeplace(false));
        $this->assertFalse($header->getReplace());

    }

    /**
     * @cover Header::setHttpResponseCode
     * @cover Header::getHttpResponseCode
     */
    public function testHttpResponseCode()
    {
        $header = new Header();
        $this->assertInstanceOf('\Huruk\Dispatcher\Header', $header->setHttpResponseCode(302));
        $this->assertEquals(302, $header->getHttpResponseCode());
    }

    /**
     * @cover Header::make
     */
    public function testMake()
    {
        $header = new Header('Foo:bar');
        $header->setReeplace(false)->setHttpResponseCode(302);

        $this->assertEquals($header, Header::make('Foo:bar', false, 302));
    }

    /**
     * @cover Header::makeFromStatusCode
     */
    public function testMakeFromStatusCode()
    {
        $header = Header::makeFromStatusCode(404);
        $this->assertEquals('Not Found', $header->getHeader());
        $this->assertEquals(true, $header->getReplace());
        $this->assertEquals(404, $header->getHttpResponseCode());
    }

    /**
     * @cover Header::makeJsonHeader
     */
    public function testMakeJsonHeader()
    {
        $header = Header::makeJsonHeader('utf-8');
        $this->assertEquals(200, $header->getHttpResponseCode());
        $this->assertEquals('Content-Type: application/json; charset=utf-8', $header->getHeader());
        $this->assertEquals(true, $header->getReplace());

        $header = Header::makeJsonHeader('utf-8', true);
        $this->assertEquals(200, $header->getHttpResponseCode());
        $this->assertEquals('Content-Type: application/javascript; charset=utf-8', $header->getHeader());
        $this->assertEquals(true, $header->getReplace());
    }
}
