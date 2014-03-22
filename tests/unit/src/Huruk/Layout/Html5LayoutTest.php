<?php
/**
 *
 * User: migue
 * Date: 22/03/14
 * Time: 17:23
 */

namespace unit\src\Huruk\Layout;


use Huruk\Layout\Html5Layout;
use Huruk\Layout\Meta;
use W3C\HtmlValidator;

class Html5LayoutTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Html5Layout */
    private $layout;
    private $title = 'Hello world';

    /**
     *
     */
    public function setUp()
    {
        $this->layout = new Html5Layout();
        $this->layout->setTitle($this->title);
    }

    /**
     * @cover Html5Layout:render
     */
    public function testGenerateSimpleDocument()
    {
        $html = $this->layout->render(' ');

        $this->assertContains('<!DOCTYPE html>', $html);
        $this->assertHtmlValidates($html);
        $this->assertContains('<title>' . $this->title . '</title>', $html);
    }

    /**
     * Assert that a html document is w3c valid.
     * @param $html
     */
    private function assertHtmlValidates($html)
    {
        $validator = new HtmlValidator();
        $validation = $validator->validateInput($html);
        $res = $validation->isValid();
        $msg = '';
        if (!$res) {
            $errors = $validation->getErrors();
            $msg = '';
            foreach ($errors as $error) {
                $msg .= $error->getMessage();
            }
        }
        $this->assertTrue($res, $msg);

    }

    /**
     * @cover Html5Layout::addMeta
     */
    public function testMeta()
    {
        $this->layout->addMeta(Meta::make('author', 'Miguel Pedregosa'));
        $html = $this->layout->render('');
        $this->assertHtmlValidates($html);
        $this->assertContains('<meta name="author" content="Miguel Pedregosa">', $html);
    }

}
