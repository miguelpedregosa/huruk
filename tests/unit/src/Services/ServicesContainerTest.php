<?php
/**
 * User: migue
 * Date: 6/04/14
 * Time: 14:46
 */

namespace unit\src\Huruk\Services;


use Huruk\Services\ServicesContainer;
use unit\src\Huruk\Services\sut\DummyService;

class ServicesContainerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Huruk\Services\ServicesContainer */
    private $servicesContainer;

    public static function setupBeforeClass()
    {
        parent::setUpBeforeClass();
        require_once __DIR__ . '/sut/DummyService.php';
    }

    public function setUp()
    {
        parent::setUp();
        $this->servicesContainer = new ServicesContainer();
    }

    public function testRegisterService()
    {
        $this->servicesContainer->registerService(
            'dummy',
            function () {
                return new DummyService();
            }
        );

        /** @var \unit\src\Huruk\Services\sut\DummyService $service */
        $service = $this->servicesContainer->getService('dummy');
        $this->assertInstanceOf('\unit\src\Huruk\Services\sut\DummyService', $service);

        $service->setValue(125);
        $this->assertEquals(125, $service->getValue());

        /** @var \unit\src\Huruk\Services\sut\DummyService $service_again */
        $service_again = $this->servicesContainer->getService('dummy');
        $this->assertEquals(125, $service_again->getValue());
    }

    public function testReRegisterService()
    {
        $this->servicesContainer->registerService(
            'dummy',
            function () {
                return new DummyService();
            }
        );

        /** @var \unit\src\Huruk\Services\sut\DummyService $service */
        $service = $this->servicesContainer->getService('dummy');
        $this->assertInstanceOf('\unit\src\Huruk\Services\sut\DummyService', $service);

        $service->setValue(125);
        $this->assertEquals(125, $service->getValue());

        $this->servicesContainer->registerService(
            'dummy',
            function () {
                return new DummyService();
            }
        );

        /** @var \unit\src\Huruk\Services\sut\DummyService $service */
        $service = $this->servicesContainer->getService('dummy');
        $this->assertNotEquals(125, $service->getValue());
        $this->assertNull($service->getValue());
    }

    public function testNonRegisteredService()
    {
        $this->assertNull($this->servicesContainer->getService('new_service'));
    }
}
