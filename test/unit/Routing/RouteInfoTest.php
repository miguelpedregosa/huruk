<?php
/**
 *
 * User: migue
 * Date: 23/03/14
 * Time: 12:22
 */

namespace unit\src\Huruk\Routing;


use Huruk\Routing\RouteInfo;

/**
 * Class RouteInfoTest
 * @package unit\src\Huruk\Routing
 * @coversDefaultClass \Huruk\Routing\RouteInfo
 */
class RouteInfoTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $info = array(
            '_route' => 'dummy',
            '_action' => 'testAction'
        );
        $route_info = new RouteInfo($info);

        $this->assertEquals('dummy', $route_info->getRouteName());
        $this->assertEquals('testAction', $route_info->getAction());
        $this->assertEquals(array(), $route_info->getParams());

    }

    public function testSetRoute()
    {
        $route_info = new RouteInfo();
        $route_info->setRouteName('dummy');
        $this->assertEquals('dummy', $route_info->getRouteName());
    }


    public function testSetAction()
    {
        $route_info = new RouteInfo();
        $route_info->setAction('dummy');
        $this->assertEquals('dummy', $route_info->getAction());
    }

    public function testSetParams()
    {
        $route_info = new RouteInfo();
        $route_info->setParams(array('dummy'));
        $this->assertEquals(array('dummy'), $route_info->getParams());
    }
}
