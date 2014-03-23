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
    private $errors;

    public static function setupBeforeClass()
    {
        parent::setUpBeforeClass();
        require_once __DIR__ . '/sut/DummySingleton.php';
    }

    public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $this->errors[] = compact(
            "errno",
            "errstr",
            "errfile",
            "errline",
            "errcontext"
        );
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

    public function testClone ()
    {
        /** @var DummySingleton $dummy */
        $dummy = DummySingleton::getInstance();
        $dummy->setValue(10);
        $new_dummy = clone $dummy;
        $this->assertError("Clone not allowed.", E_USER_ERROR);
        $this->assertInstanceOf('unit\src\Huruk\Util\sut\DummySingleton', $new_dummy);
    }

    public function assertError($errstr, $errno)
    {
        foreach ($this->errors as $error) {
            if ($error["errstr"] === $errstr
                && $error["errno"] === $errno
            ) {
                return;
            }
        }
        $this->fail(
            "Error with level " . $errno .
            " and message '" . $errstr . "' not found in ",
            var_export($this->errors, true)
        );
    }

    protected function setUp()
    {
        parent::setUp();
        $this->errors = array();
        set_error_handler(array($this, "errorHandler"));
    }

}
