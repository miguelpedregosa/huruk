<?php
/**
 *
 * User: migue
 * Date: 23/03/14
 * Time: 12:53
 */

namespace unit\src\Huruk\Dispatcher;


use Huruk\Dispatcher\Header;

/**
 * Class HeaderTest
 * @package unit\src\Huruk\Dispatcher
 * @coversDefaultClass \Huruk\Dispatcher\Header
 */
class HeaderTest extends \PHPUnit_Framework_TestCase
{

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

    public function testSetHeader()
    {
        $header = new Header();
        $this->assertInstanceOf('\Huruk\Dispatcher\Header', $header->setHeader('Foo:bar'));
        $this->assertEquals('Foo:bar', $header->getHeader());

    }

    public function testSetReplace()
    {
        $header = new Header();
        $this->assertInstanceOf('\Huruk\Dispatcher\Header', $header->setReplace(false));
        $this->assertFalse($header->getReplace());

    }

    public function testHttpResponseCode()
    {
        $header = new Header();
        $this->assertInstanceOf('\Huruk\Dispatcher\Header', $header->setHttpResponseCode(302));
        $this->assertEquals(302, $header->getHttpResponseCode());
    }

    public function testMake()
    {
        $header = new Header('Foo:bar');
        $header->setReplace(false)->setHttpResponseCode(302);

        $this->assertEquals($header, Header::make('Foo:bar', false, 302));
    }

    public function testMakeFromStatusCode()
    {
        $header = Header::makeFromStatusCode(404);
        $this->assertEquals('Not Found', $header->getHeader());
        $this->assertEquals(true, $header->getReplace());
        $this->assertEquals(404, $header->getHttpResponseCode());
    }

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
