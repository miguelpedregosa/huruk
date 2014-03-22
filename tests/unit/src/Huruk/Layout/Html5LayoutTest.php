<?php
/**
 *
 * User: migue
 * Date: 22/03/14
 * Time: 17:23
 */

namespace unit\src\Huruk\Layout;


use Huruk\Layout\Html5Layout;
use W3C\HtmlValidator;

class Html5LayoutTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Assert that a html document is w3c valid.
     * @param $html
     */
    private function assertHtmlValidates($html)
    {
        $validator = new HtmlValidator();
        $validation = $validator->validateInput($html);
        $this->assertTrue($validation->isValid());

    }

    /**
     * @cover Html5Layout:render
     */
    public function testGenerateSimpleDocument()
    {
        $layout = new Html5Layout();
        $title = 'Hello world';
        $layout->setTitle($title);
        $html = $layout->render(' ');

        $this->assertContains('<!DOCTYPE html>', $html);
        $this->assertHtmlValidates($html);
        $this->assertContains('<title>'.$title.'</title>', $html);
    }

}
