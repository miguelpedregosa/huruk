<?php
/**
 *
 * User: migue
 * Date: 22/03/14
 * Time: 18:00
 */

namespace unit\src\Huruk\Layout;


use Huruk\Layout\Meta;

class MetaTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Meta */
    private $meta;

    public function setUp()
    {
        $this->meta = new Meta();
    }

    /**
     * @cover Meta::setName
     * @cover Meta::getName
     */
    public function testName()
    {
        $this->meta->setName('foo');
        $this->assertEquals('foo', $this->meta->getName());
    }

    /**
     * @cover Meta::getContent
     * @cover Meta::setContent
     */
    public function testContent()
    {
        $this->meta->setContent('bar');
        $this->assertEquals('bar', $this->meta->getContent());
    }

    /**
     * @cover Meta::setCharset
     * @cover Meta::getCharset
     */
    public function testCharset()
    {
        $this->meta->setCharset('utf-8');
        $this->assertEquals('utf-8', $this->meta->getCharset());
    }

    /**
     * @cover Meta::setHttpEquiv
     * @cover Meta::getHttpEquiv
     */
    public function testHttpEquiv()
    {
        $this->meta->setHttpEquiv('rel');
        $this->assertEquals('rel', $this->meta->getHttpEquiv());
    }
}
