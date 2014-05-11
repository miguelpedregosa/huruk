<?php
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
    private $headers = null;

    /** @var string */
    private $content = '';

    /**
     * Indica si se debe enviar el contenido o solo los header al navegador
     * @var bool
     */
    private $sendContent = true;

    /**
     * Must send the headers with the content?
     * @var bool
     */
    private $sendHeaders = true;


    public function __construct($content = '')
    {
        $this->setContent($content);
    }

    /**
     * @param Header $header
     * @param int $priority
     */
    public function addHeader(Header $header, $priority = self::PRIORITY_LOW)
    {
        $this->getHeaders()->insert($header, $priority);
    }

    /**
     * @return StablePriorityQueue
     */
    public function getHeaders()
    {
        if (!$this->headers) {
            $this->headers = new StablePriorityQueue();
        }
        return $this->headers;
    }

    public function disableSendContent()
    {
        $this->sendContent = false;
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


    public function enableSendContent()
    {
        $this->sendContent = true;
    }

    /**
     * @return bool
     */
    public function mustSendContent()
    {
        return $this->sendContent;
    }

    public function enableSendHeaders()
    {
        $this->sendHeaders = true;
    }

    public function disableSendHeaders()
    {
        $this->sendHeaders = false;
    }

    public function mustSendHeaders()
    {
        return $this->sendHeaders;
    }

    /**
     * Send the response to the client
     */
    public function send()
    {
        //Send the headers
        if ($this->mustSendHeaders()) {
            while (!$this->getHeaders()->isEmpty()) {
                /** @var $header Header */
                $header = $this->getHeaders()->extract();
                $header->send();
            }
        }

        //Send the content
        if ($this->mustSendContent()) {
            echo $this->getContent();
        }

    }
}
