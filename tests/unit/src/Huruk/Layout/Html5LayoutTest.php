<?php
/**
 *
 * User: migue
 * Date: 22/03/14
 * Time: 17:23
 */

namespace unit\src\Huruk\Layout;


use Huruk\Layout\Html5Layout;
use Huruk\Layout\Link;
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
        $html = $this->layout->render();
        $this->assertContains('<!DOCTYPE html>', $html);
    }

    /**
     * @cover Html5Layout::setTitle
     */
    public function testSetTitle()
    {
        $this->layout->setTitle($this->title);
        $html = $this->layout->render();
        $this->assertContains('<title>Hello world</title>', $html);
    }

    /**
     * @cover Html5Layout::addMeta
     */
    public function testMeta()
    {
        $this->layout->addMeta(Meta::make('author', 'Miguel Pedregosa'));
        $html = $this->layout->render();
        $this->assertContains('<meta name="author" content="Miguel Pedregosa">', $html);
    }

    /**
     * @cover Html5Layout::setAuthor
     */
    public function testSetAuthor()
    {
        $this->layout->setAuthor('Miguel Pedregosa');
        $html = $this->layout->render();
        $this->assertContains('<meta name="author" content="Miguel Pedregosa">', $html);
    }

    /**
     * @cover Html5Layout::setLanguage
     */
    public function testSetLanguage()
    {
        $this->layout->setLanguage('en');
        $html = $this->layout->render();
        $this->assertContains('<html lang="en">', $html);
    }

    /**
     * @cover Html5Layout::setCharset
     */
    public function testSetCharset()
    {
        $this->layout->setCharset('utf-8');
        $html = $this->layout->render();
        $this->assertContains('<meta charset="utf-8">', $html);
    }

    /**
     * @cover Html5Layout::setApplicationName
     */
    public function testSetApplicationName()
    {
        $this->layout->setApplicationName('Huruk');
        $html = $this->layout->render();
        $this->assertContains('<meta name="application-name" content="Huruk">', $html);
    }

    /**
     * @cover Html5Layout::setGenerator
     */
    public function testSetGenerator()
    {
        $this->layout->setGenerator('Huruk');
        $html = $this->layout->render();
        $this->assertContains('<meta name="generator" content="Huruk">', $html);
    }

    /**
     * @cover Html5Layout::setDescription
     */
    public function testSetDescription()
    {
        $this->layout->setDescription('Lorem ipsum');
        $html = $this->layout->render();
        $this->assertContains('<meta name="description" content="Lorem ipsum">', $html);
    }

    /**
     * @cover Html5Layout::setKeywords
     */
    public function testSetKeywords()
    {
        $this->layout->setKeywords('a,b,c');
        $html = $this->layout->render();
        $this->assertContains('<meta name="keywords" content="a,b,c">', $html);
    }

    /**
     * @cover Html5Layout::setViewPort
     */
    public function testSetViewport()
    {
        $this->layout->setViewPort('width=device-width, user-scalable=no');
        $html = $this->layout->render();
        $this->assertContains('<meta name="viewport" content="width=device-width, user-scalable=no">', $html);
    }

    /**
     * @cover Html5Layout::setCanonical
     */
    public function testSetCanonical()
    {
        $this->layout->setCanonical('http://foo.bar');
        $html = $this->layout->render();
        $this->assertContains('<link rel="canonical" href="http://foo.bar">', $html);
    }

    /**
     * @cover Html5Layout::addHttpEquivMetaTag
     */
    public function testAddHttpEquivMetaTag()
    {
        $this->layout->addHttpEquivMetaTag('refresh', '30');
        $html = $this->layout->render();
        $this->assertContains('<meta http-equiv="refresh" content="30">', $html);
    }

    /**
     * @cover Html5Layout::addLink
     */
    public function testAddLink()
    {
        $this->layout->addLink(Link::make("stylesheet", 'text/css', 'theme.css'));
        $html = $this->layout->render();
        $this->assertContains('<link rel="stylesheet" href="theme.css" type="text/css">', $html);
    }

    /**
     * @cover Html5Layout::addCss
     */
    public function testAddCss()
    {
        $this->layout->addCss('theme.css', 'screen');
        $html = $this->layout->render();
        $this->assertContains('<link rel="stylesheet" href="theme.css" type="text/css" media="screen">', $html);
    }

    /**
     * @cover Html5Layout::addJs
     */
    public function testAddJs()
    {
        $this->layout->addJs('script.js');
        $html = $this->layout->render();
        $this->assertContains('<script src="script.js"></script>', $html);
    }

    /**
     * @cover Html5Layout::setBodyAttribute
     * @cover Html5Layout::unsetBodyAttribute
     * @cover Html5Layout::cleanBodyAttributes
     */
    public function testBodyAttributes()
    {
        $this->layout->setBodyAttribute('class', 'foo');
        $this->layout->setBodyAttribute('id', 'body');
        $html = $this->layout->render();
        $this->assertContains('<body class="foo" id="body">', $html);
        $this->layout->unsetBodyAttribute('id');
        $html = $this->layout->render();
        $this->assertContains('<body class="foo">', $html);
        $this->layout->cleanBodyAttributes();
        $html = $this->layout->render();
        $this->assertContains('<body>', $html);
    }

    /**
     *
     */
    public function testHtmlDocumentValidates()
    {
        $this->layout->setAuthor('Miguel Pedregosa');
        $this->layout->setCanonical('http://foo.bar');
        $this->layout->setViewPort('width=device-width, user-scalable=no');
        $this->layout->setKeywords('a,b,c');
        $this->layout->setDescription('Lorem ipsum');
        $this->layout->setGenerator('Huruk');
        $this->layout->setLanguage('en');
        $this->layout->addCss('theme.css', 'screen');
        $this->layout->addJs('script.js');
        $this->layout->setBodyAttribute('class', 'foo');
        $this->layout->setBodyAttribute('id', 'body');
        $this->assertHtmlValidates($this->layout->render());
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
}
