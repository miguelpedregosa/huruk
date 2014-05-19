<?php
/**
 *
 * User: migue
 * Date: 19/05/14
 * Time: 21:52
 */

namespace unit\Dispatcher;


use Huruk\Dispatcher\JsonResponse;

class JsonResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $data = array('foo' => 'bar');
        $response = new JsonResponse($data);
        $response->disableSendHeaders();
        ob_start();
        $response->send();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains(
            '{"foo":"bar"}',
            $output
        );
    }
}
