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

class Html5LayoutTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Html5Layout */
    private $layout;
    private $title = 'Hello world';

    public function testGenerateSimpleDocument()
    {
        $html = $this->layout->render();
        $this->assertContains('<!DOCTYPE html>', $html);
    }

    public function testSetTitle()
    {
        $this->layout->setTitle($this->title);
        $html = $this->layout->render();
        $this->assertContains('<title>Hello world</title>', $html);
    }

    public function testMeta()
    {
        $this->layout->addMeta(Meta::make('author', 'Miguel Pedregosa'));
        $html = $this->layout->render();
        $this->assertContains('<meta name="author" content="Miguel Pedregosa">', $html);
    }

    public function testSetAuthor()
    {
        $this->layout->setAuthor('Miguel Pedregosa');
        $html = $this->layout->render();
        $this->assertContains('<meta name="author" content="Miguel Pedregosa">', $html);
    }

    public function testSetLanguage()
    {
        $this->layout->setLanguage('en');
        $html = $this->layout->render();
        $this->assertContains('<html lang="en">', $html);
    }

    public function testHtmlAttributes()
    {
        $this->layout->setHtmlAttribute('foo', 'bar');
        $this->layout->setHtmlAttribute('ng-app', 'MyApp');

        $this->assertContains('<html foo="bar" ng-app="MyApp"', $this->layout->render());
    }

    public function testSetCharset()
    {
        $this->layout->setCharset('utf-8');
        $html = $this->layout->render();
        $this->assertContains('<meta charset="utf-8">', $html);
    }

    public function testSetApplicationName()
    {
        $this->layout->setApplicationName('Huruk');
        $html = $this->layout->render();
        $this->assertContains('<meta name="application-name" content="Huruk">', $html);
    }

    public function testSetGenerator()
    {
        $this->layout->setGenerator('Huruk');
        $html = $this->layout->render();
        $this->assertContains('<meta name="generator" content="Huruk">', $html);
    }

    public function testSetDescription()
    {
        $this->layout->setDescription('Lorem ipsum');
        $html = $this->layout->render();
        $this->assertContains('<meta name="description" content="Lorem ipsum">', $html);
    }

    public function testSetKeywords()
    {
        $this->layout->setKeywords('a,b,c');
        $html = $this->layout->render();
        $this->assertContains('<meta name="keywords" content="a,b,c">', $html);
    }

    public function testSetViewport()
    {
        $this->layout->setViewPort('width=device-width, user-scalable=no');
        $html = $this->layout->render();
        $this->assertContains('<meta name="viewport" content="width=device-width, user-scalable=no">', $html);
    }

    public function testSetCanonical()
    {
        $this->layout->setCanonical('http://foo.bar');
        $html = $this->layout->render();
        $this->assertContains('<link rel="canonical" href="http://foo.bar">', $html);
    }

    public function testAddHttpEquivMetaTag()
    {
        $this->layout->addHttpEquivMetaTag('refresh', '30');
        $html = $this->layout->render();
        $this->assertContains('<meta http-equiv="refresh" content="30">', $html);
    }

    public function testAddLink()
    {
        $this->layout->addLink(Link::make("stylesheet", 'text/css', 'theme.css'));
        $html = $this->layout->render();
        $this->assertContains('<link rel="stylesheet" href="theme.css" type="text/css">', $html);
    }

    public function testAddCss()
    {
        $this->layout->addCss('theme.css', 'screen');
        $html = $this->layout->render();
        $this->assertContains('<link rel="stylesheet" href="theme.css" type="text/css" media="screen">', $html);
    }

    public function testAddJs()
    {
        $this->layout->addJs('script.js');
        $html = $this->layout->render();
        $this->assertContains('<script src="script.js"></script>', $html);
    }

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

    protected function setUp()
    {
        parent::setUp();
        $this->layout = new Html5Layout();
        $this->layout->setTitle($this->title);
    }
}
