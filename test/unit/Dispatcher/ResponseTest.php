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
use Huruk\Dispatcher\ResponseFactory;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $response = new Response('foo:bar');
        $this->assertEquals('foo:bar', $response->getContent());
        $this->assertEquals('foo:bar', ResponseFactory::make('foo:bar')->getContent());
    }

    public function testMustSendContent()
    {
        $response = ResponseFactory::make('foo:bar');
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
        $response = ResponseFactory::make('foo:bar');
        $expected_header = new Header('foo->bar');

        $response->addHeader($expected_header);
        $header = iterator_to_array($response->getHeaders())[0];
        $this->assertEquals($expected_header, $header);

    }

    public function testRedirectionResponse()
    {
        $response = ResponseFactory::makeRedirectResponse('http://foo.bar');
        $this->assertEquals('', $response->getContent());

        /** @var Header $header */
        $header = iterator_to_array($response->getHeaders())[0];
        $this->assertEquals(302, $header->getHttpResponseCode());
        $this->assertFalse($response->mustSendContent());

        $response = ResponseFactory::makeRedirectResponse('http://foo.bar', 301);
        $this->assertEquals('', $response->getContent());

        /** @var Header $header */
        $header = iterator_to_array($response->getHeaders())[0];
        $this->assertEquals(301, $header->getHttpResponseCode());
        $this->assertFalse($response->mustSendContent());

        $response = ResponseFactory::makeRedirectResponse('http://foo.bar', 303);
        $this->assertEquals('', $response->getContent());

        /** @var Header $header */
        $header = iterator_to_array($response->getHeaders())[0];
        $this->assertEquals(303, $header->getHttpResponseCode());
        $this->assertFalse($response->mustSendContent());

    }

    public function testSend()
    {
        $response = new Response('foo:bar');
        $response->disableSendHeaders();
        ob_start();
        $response->send();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('foo:bar', $output);
    }
}
