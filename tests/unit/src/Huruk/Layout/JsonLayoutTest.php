<?php
/**
 *
 * User: migue
 * Date: 22/03/14
 * Time: 18:19
 */

namespace unit\src\Huruk\Layout;


use Huruk\Layout\JsonLayout;

class JsonLayoutTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover JsonLayout::render
     */
    public function testRenderJson()
    {
        $array = array('foo' => 'bar');
        $layout = new JsonLayout();
        $this->assertEquals(json_encode($array), $layout->render($array));
    }
}
