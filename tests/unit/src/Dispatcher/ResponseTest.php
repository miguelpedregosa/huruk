<?php
/**
 *
 * User: migue
 * Date: 20/04/14
 * Time: 19:35
 */

namespace unit\src\Huruk\Dispatcher;


use Huruk\Dispatcher\Header;
use Huruk\Dispatcher\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $response = new Response('foo:bar');
        $this->assertEquals('foo:bar', $response->getContent());
        $this->assertEquals('foo:bar', Response::make('foo:bar')->getContent());
    }

    public function testMustSendContent()
    {
        $response = Response::make('foo:bar');
        $this->assertTrue($response->mustSendContent());
        $response->disableSendContent();
        $this->assertFalse($response->mustSendContent());
        $response->enableSendContent();
        $this->assertTrue($response->mustSendContent());
    }

    public function testSetContent()
    {
        $response = new Response();
        $response->setContent('foo:bar');
        $this->assertEquals('foo:bar', $response->getContent());
    }

    public function testHeaders()
    {
        $response = Response::make('foo:bar');
        $expected_header = new Header('foo->bar');

        $response->addHeader($expected_header);
        $header = iterator_to_array($response->getHeaders())[0];
        $this->assertEquals($expected_header, $header);

    }

    public function testRedirectionResponse()
    {
        $response = Response::makeRedirectResponse('http://foo.bar');
        $this->assertEquals('', $response->getContent());

        /** @var Header $header */
        $header = iterator_to_array($response->getHeaders())[0];
        $this->assertEquals(302, $header->getHttpResponseCode());
        $this->assertFalse($response->mustSendContent());

        $response = Response::makeRedirectResponse('http://foo.bar', 301);
        $this->assertEquals('', $response->getContent());

        /** @var Header $header */
        $header = iterator_to_array($response->getHeaders())[0];
        $this->assertEquals(301, $header->getHttpResponseCode());
        $this->assertFalse($response->mustSendContent());

        $response = Response::makeRedirectResponse('http://foo.bar', 303);
        $this->assertEquals('', $response->getContent());

        /** @var Header $header */
        $header = iterator_to_array($response->getHeaders())[0];
        $this->assertEquals(303, $header->getHttpResponseCode());
        $this->assertFalse($response->mustSendContent());

    }
}
