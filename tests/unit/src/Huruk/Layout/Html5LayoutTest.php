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
    /** @var  Html5Layout */
    private $layout;

    /**
     *
     */
    public function setUp()
    {
        $this->layout = new Html5Layout();
    }

    /**
     * @cover Html5Layout:render
     */
    public function testGenerateSimpleDocument()
    {
        $title = 'Hello world';
        $this->layout->setTitle($title);
        $html = $this->layout->render(' ');

        $this->assertContains('<!DOCTYPE html>', $html);
        $this->assertHtmlValidates($html);
        $this->assertContains('<title>' . $title . '</title>', $html);
    }

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


}
