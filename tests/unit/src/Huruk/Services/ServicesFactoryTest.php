<?php
/**
 * User: migue
 * Date: 6/04/14
 * Time: 14:46
 */

namespace unit\src\Huruk\Services;


use Huruk\Services\ServicesFactory;
use unit\src\Huruk\Services\sut\DummyService;

class ServicesFactoryTest extends \PHPUnit_Framework_TestCase
{
    public static function setupBeforeClass()
    {
        parent::setUpBeforeClass();
        require_once __DIR__ . '/sut/DummyService.php';
    }

    /**
     *
     */
    public function testRegisterService()
    {
        ServicesFactory::registerService(
            'dummy',
            function () {
                return new DummyService();
            }
        );

        /** @var \unit\src\Huruk\Services\sut\DummyService $service */
        $service = ServicesFactory::getService('dummy');
        $this->assertInstanceOf('\unit\src\Huruk\Services\sut\DummyService', $service);

        $service->setValue(125);
        $this->assertEquals(125, $service->getValue());

        /** @var \unit\src\Huruk\Services\sut\DummyService $service_again */
        $service_again = ServicesFactory::getService('dummy');
        $this->assertEquals(125, $service_again->getValue());
    }

    /**
     *
     */
    public function testReRegisterService()
    {
        ServicesFactory::registerService(
            'dummy',
            function () {
                return new DummyService();
            }
        );

        /** @var \unit\src\Huruk\Services\sut\DummyService $service */
        $service = ServicesFactory::getService('dummy');
        $this->assertInstanceOf('\unit\src\Huruk\Services\sut\DummyService', $service);

        $service->setValue(125);
        $this->assertEquals(125, $service->getValue());

        ServicesFactory::registerService(
            'dummy',
            function () {
                return new DummyService();
            }
        );

        /** @var \unit\src\Huruk\Services\sut\DummyService $service */
        $service = ServicesFactory::getService('dummy');
        $this->assertNotEquals(125, $service->getValue());
        $this->assertNull($service->getValue());
    }
}
