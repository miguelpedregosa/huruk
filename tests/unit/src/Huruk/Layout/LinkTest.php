<?php
/**
 *
 * User: migue
 * Date: 22/03/14
 * Time: 18:01
 */

namespace unit\src\Huruk\Layout;


use Huruk\Layout\Link;

class LinkTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Link */
    private $link;

    public function testHref()
    {
        $this->link->setHref('foo');
        $this->assertEquals('foo', $this->link->getHref());
    }

    public function testHrefLang()
    {
        $this->link->setHrefLang('en');
        $this->assertEquals('en', $this->link->getHrefLang());
    }

    public function testMedia()
    {
        $this->link->setMedia('screen');
        $this->assertEquals('screen', $this->link->getMedia());
    }

    public function testRel()
    {
        $this->link->setRel('stylesheet');
        $this->assertEquals('stylesheet', $this->link->getRel());
    }

    public function testSizes()
    {
        $this->link->setSizes('bar');
        $this->assertEquals('bar', $this->link->getSizes());
    }

    public function testType()
    {
        $this->link->setType('text');
        $this->assertEquals('text', $this->link->getType());
    }

    public function testFactory()
    {
        $link = Link::make('stylesheet', 'text/css', 'theme.css', 'screen');
        $this->assertEquals('stylesheet', $link->getRel());
        $this->assertEquals('text/css', $link->getType());
        $this->assertEquals('theme.css', $link->getHref());
        $this->assertEquals('screen', $link->getMedia());
    }

    protected function setup()
    {
        parent::setUp();
        $this->link = new Link();
    }
}
