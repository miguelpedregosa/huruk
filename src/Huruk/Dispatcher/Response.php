<?php
/**
 *
 * @author Miguel A. Pedregosa <miguelpedregosa@gmail.com>
 * @version 1.0
 * @created 3/03/13 - 2:24
 */
namespace Huruk\Dispatcher;

use Huruk\Util\StablePriorityQueue;

/**
 * Class Response
 * @package Huruk\Dispatcher
 */
class Response
{
    const PRIORITY_HIGH = 3;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_LOW = 1;

    /** @var StablePriorityQueue */
    private $headers;

    /** @var string */
    private $content = '';

    /**
     * Indica si se debe enviar el contenido o solo los header al navegador
     * @var bool
     */
    private $send_content = true;

    public function __construct($content = '')
    {
        $this->headers = new StablePriorityQueue();
        $this->setContent($content);
    }

    /**
     * Crea un objeto action result con el codigo de estado que se le proporciona
     * @param string $content
     * @param int $status_code
     * @return Response
     */
    public static function make($content = '', $status_code = 200)
    {
        $action_result = new self($content);
        $action_result->addHeader(Header::makeFromStatusCode($status_code), Response::PRIORITY_HIGH);
        return $action_result;
    }

    /**
     * @param Header $header
     * @param int $priority
     */
    public function addHeader(Header $header, $priority = self::PRIORITY_LOW)
    {
        $this->headers->insert($header, $priority);
    }

    /**
     * Crea una Response para redirigir a otra pagina
     * @param $redirect_to
     * @param int $http_response_code
     * @return Response
     */
    public static function makeRedirectResponse($redirect_to, $http_response_code = 302)
    {
        $action_result = new self();

        switch ($http_response_code) {
            case 301:
                $action_result->addHeader(new Header('HTTP/1.1 301 Moved Permanently', true, 301));
                $action_result->addHeader(new Header('Location: ' . $redirect_to, true, 301));
                $action_result->disableSendContent();
                break;

            case 303:
                $action_result->addHeader(new Header('HTTP/1.1 303 See Other', true, 303));
                $action_result->addHeader(new Header('Location: ' . $redirect_to, true, 303));
                $action_result->disableSendContent();
                break;

            default:
                $action_result->addHeader(new Header('Location: ' . $redirect_to));
                $action_result->disableSendContent();
                break;
        }

        return $action_result;
    }

    /**
     *
     */
    public function disableSendContent()
    {
        $this->send_content = false;
    }

    /**
     * @return StablePriorityQueue
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     *
     */
    public function enableSendContent()
    {
        $this->send_content = true;
    }

    /**
     * @return bool
     */
    public function mustSendContent()
    {
        return $this->send_content;
    }
}
