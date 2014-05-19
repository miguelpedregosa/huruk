<?php
/**
 *
 * User: migue
 * Date: 20/04/14
 * Time: 19:35
 */

namespace unit\src\Huruk\Dispatcher;


use Huruk\Dispatcher\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /** @var null|Response  */
    private $response = null;

    protected function setUp()
    {
        parent::setUp();
        $this->response = new Response();
    }

    public function testConstructor()
    {
        $response = new Response('foo:bar');
        $this->assertEquals('foo:bar', $response->getContent());
    }

    public function testMustSendContent()
    {
        $this->assertTrue($this->response->mustSendContent());
        $this->response->disableSendContent();
        $this->assertFalse($this->response->mustSendContent());
        $this->response->enableSendContent();
        $this->assertTrue($this->response->mustSendContent());
    }

    public function testSetContent()
    {
        $this->response->setContent('foo:bar');
        $this->assertEquals('foo:bar', $this->response->getContent());
    }

    public function testHeaders()
    {
        $this->response->addHeader('content-type', 'html');
        $this->assertArrayHasKey('content-type', $this->response->getHeaders()->all());

    }

    public function testRedirectionResponse()
    {
//        $response = ResponseFactory::makeRedirectResponse('http://foo.bar');
//        $this->assertEquals('', $response->getContent());
//
//        /** @var Header $header */
//        $header = iterator_to_array($response->getHeaders())[0];
//        $this->assertEquals(302, $header->getHttpResponseCode());
//        $this->assertFalse($response->mustSendContent());
//
//        $response = ResponseFactory::makeRedirectResponse('http://foo.bar', 301);
//        $this->assertEquals('', $response->getContent());
//
//        /** @var Header $header */
//        $header = iterator_to_array($response->getHeaders())[0];
//        $this->assertEquals(301, $header->getHttpResponseCode());
//        $this->assertFalse($response->mustSendContent());
//
//        $response = ResponseFactory::makeRedirectResponse('http://foo.bar', 303);
//        $this->assertEquals('', $response->getContent());
//
//        /** @var Header $header */
//        $header = iterator_to_array($response->getHeaders())[0];
//        $this->assertEquals(303, $header->getHttpResponseCode());
//        $this->assertFalse($response->mustSendContent());

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
