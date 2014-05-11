<?php
/**
 *
 * User: migue
 * Date: 11/05/14
 * Time: 16:08
 */

namespace Huruk\Dispatcher;


class ResponseFactory
{
    /**
     * Crea un objeto action result con el codigo de estado que se le proporciona
     * @param string $content
     * @param int $statusCode
     * @return Response
     */
    public static function make($content = '', $statusCode = 200)
    {
        $response = new Response($content);
        $response->addHeader(HeaderFactory::makeFromStatusCode($statusCode), Response::PRIORITY_HIGH);
        return $response;
    }

    /**
     * Crea una Response para redirigir a otra pagina
     * @param $redirectTo
     * @param int $httpResponseCode
     * @return Response
     */
    public static function makeRedirectResponse($redirectTo, $httpResponseCode = 302)
    {
        $response = new Response();
        $response->disableSendContent();

        switch ($httpResponseCode) {
            case 301:
                $response->addHeader(new Header('HTTP/1.1 301 Moved Permanently', true, 301));
                $response->addHeader(new Header('Location: ' . $redirectTo, true, 301));
                break;

            case 303:
                $response->addHeader(new Header('HTTP/1.1 303 See Other', true, 303));
                $response->addHeader(new Header('Location: ' . $redirectTo, true, 303));
                break;

            default:
                $response->addHeader(new Header('Location: ' . $redirectTo, true, 302));
                break;
        }

        return $response;
    }
}
