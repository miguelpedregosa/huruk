<?php
/**
 *
 * User: migue
 * Date: 19/05/14
 * Time: 21:32
 */

namespace unit\Dispatcher;


use Huruk\Dispatcher\Html5Responder;

class Html5ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $response = new Html5Responder('<p>foo:bar</p>');
        $response->getHtmlLayout()->setTitle('Test');
        $response->disableSendHeaders();
        ob_start();
        $response->send();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains(
            '<body>',
            $output
        );

        $this->assertContains(
            '<p>foo:bar</p>',
            $output
        );
        $this->assertContains(
            '<title>Test</title>',
            $output
        );
    }
}
