<?php
/**
 *
 * User: migue
 * Date: 23/03/14
 * Time: 12:22
 */

namespace unit\src\Huruk\Routing;


use Huruk\Routing\RouteInfo;

class RouteInfoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover RouteInfo::__construct
     */
    public function testConstruct()
    {
        $info = array(
            '_route' => 'dummy',
            '_controller' => '\dummy\Controller',
            '_action' => 'testAction'
        );
        $route_info = new RouteInfo($info);

        $this->assertEquals('dummy', $route_info->getRouteName());
        $this->assertEquals('\dummy\Controller', $route_info->getControllerClass());
        $this->assertEquals('testAction', $route_info->getAction());
        $this->assertEquals(array(), $route_info->getParams());

    }

    /**
     * @cover ReouteInfo::setRouteName
     * @cover ReouteInfo::getRouteName
     */
    public function testSetRoute()
    {
        $route_info = new RouteInfo();
        $route_info->setRouteName('dummy');
        $this->assertEquals('dummy', $route_info->getRouteName());
    }

    /**
     * @cover ReouteInfo::setControllerClass
     * @cover ReouteInfo::getControllerClass
     */
    public function testSetControllerClass()
    {
        $route_info = new RouteInfo();
        $route_info->setControllerClass('dummy');
        $this->assertEquals('dummy', $route_info->getControllerClass());
    }

    /**
     * @cover ReouteInfo::setAction
     * @cover ReouteInfo::getAction
     */
    public function testSetAction()
    {
        $route_info = new RouteInfo();
        $route_info->setAction('dummy');
        $this->assertEquals('dummy', $route_info->getAction());
    }

    /**
     * @cover ReouteInfo::setParams
     * @cover ReouteInfo::getParams
     */
    public function testSetParams()
    {
        $route_info = new RouteInfo();
        $route_info->setParams(array('dummy'));
        $this->assertEquals(array('dummy'), $route_info->getParams());
    }
}
