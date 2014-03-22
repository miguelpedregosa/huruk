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

    public function setup()
    {
        $this->link = new Link();
    }

    /**
     * @cover Link::setHref
     * @cover Link::getHref
     */
    public function testHref()
    {
        $this->link->setHref('foo');
        $this->assertEquals('foo', $this->link->getHref());
    }

    /**
     * @cover Link::setHrefLang
     * @cover Link::getHrefLang
     */
    public function testHrefLang()
    {
        $this->link->setHrefLang('en');
        $this->assertEquals('en', $this->link->getHrefLang());
    }

    /**
     * @cover Link::setMedia
     * @cover Link::getMedia
     */
    public function testMedia()
    {
        $this->link->setMedia('screen');
        $this->assertEquals('screen', $this->link->getMedia());
    }

    /**
     * @cover Link::setRel
     * @cover Link::getRel
     */
    public function testRel()
    {
        $this->link->setRel('stylesheet');
        $this->assertEquals('stylesheet', $this->link->getRel());
    }

    /**
     * @cover Link::setSizes
     * @cover Link::getSizes
     */
    public function testSizes()
    {
        $this->link->setSizes('bar');
        $this->assertEquals('bar', $this->link->getSizes());
    }

    /**
     * @cover Link::setType
     * @cover Link::getType
     */
    public function testType()
    {
        $this->link->setType('text');
        $this->assertEquals('text', $this->link->getType());
    }

    /**
     * @cover Link::make
     */
    public function testFactory()
    {
        $link = Link::make('xxx', 'screen', 'style', 'foo');
        $this->assertEquals('xxx', $link->getHref());
        $this->assertEquals('screen', $link->getMedia());
        $this->assertEquals('style', $link->getRel());
        $this->assertEquals('foo', $link->getType());
    }
}
