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
