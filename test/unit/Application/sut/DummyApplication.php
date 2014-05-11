<?php
/**
 *
 * User: migue
 * Date: 11/05/14
 * Time: 18:22
 */

namespace unit\Application\sut;


use Huruk\Application\Application;
use Huruk\Dispatcher\ResponseFactory;
use Huruk\Exception\PageNotFoundException;
use Huruk\Routing\RouteInfo;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class DummyApplication extends Application
{
    public static function getRouteCollection()
    {
        $collection = new RouteCollection();
        $collection->add(
            'foo',
            new Route(
                '/foo',
                array(
                    RouteInfo::ACTION => '\unit\Dispatcher\sut\DummyAction'
                )
            )
        );

        return $collection;
    }

    protected static function handlePageNotFound(PageNotFoundException $exception)
    {
        $response = ResponseFactory::make('Not Found');
        $response->disableSendHeaders();
        return $response;
    }
}
