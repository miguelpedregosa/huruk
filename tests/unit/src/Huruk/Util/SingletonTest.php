<?php
/**
 *
 * User: migue
 * Date: 22/03/14
 * Time: 13:15
 */

namespace unit\src\Huruk\Util;

use unit\src\Huruk\Util\sut\DummySingleton;

class SingletonTest extends \PHPUnit_Framework_TestCase
{
    public static function setupBeforeClass()
    {
        require_once __DIR__ . '/sut/DummySingleton.php';
    }

    /**
     *
     */
    public function testSingleton()
    {
        /** @var DummySingleton $dummy */
        $dummy = DummySingleton::getInstance();
        $dummy->setValue(1);
        $this->assertInstanceOf('unit\src\Huruk\Util\sut\DummySingleton', $dummy);
        $this->assertEquals(1, $dummy->getValue());

        /** @var DummySingleton $dummy2 */
        $dummy2 = DummySingleton::getInstance();
        $this->assertInstanceOf('unit\src\Huruk\Util\sut\DummySingleton', $dummy2);
        $this->assertEquals(1, $dummy2->getValue());

        /** @var DummySingleton $new_dummy */
        $new_dummy = DummySingleton::getInstance(true);
        $this->assertInstanceOf('unit\src\Huruk\Util\sut\DummySingleton', $new_dummy);
        $this->assertNull($new_dummy->getValue());
    }

}
