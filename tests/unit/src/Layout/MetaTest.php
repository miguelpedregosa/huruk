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

    public function testName()
    {
        $this->meta->setName('foo');
        $this->assertEquals('foo', $this->meta->getName());
    }

    public function testContent()
    {
        $this->meta->setContent('bar');
        $this->assertEquals('bar', $this->meta->getContent());
    }

    public function testCharset()
    {
        $this->meta->setCharset('utf-8');
        $this->assertEquals('utf-8', $this->meta->getCharset());
    }

    public function testHttpEquiv()
    {
        $this->meta->setHttpEquiv('rel');
        $this->assertEquals('rel', $this->meta->getHttpEquiv());
    }

    protected function setUp()
    {
        parent::setUp();
        $this->meta = new Meta();
    }
}
