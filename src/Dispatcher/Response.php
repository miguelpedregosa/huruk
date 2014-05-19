<?php
namespace Huruk\Dispatcher;


/**
 * Class Response
 * @package Huruk\Dispatcher
 */
class Response extends \Symfony\Component\HttpFoundation\Response
{
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


    /**
     * @param $key
     * @param $values
     * @param bool $replace
     */
    public function addHeader($key, $values, $replace = true)
    {
        $this->headers->set($key, $values, $replace);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\ResponseHeaderBag
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    public function disableSendContent()
    {
        $this->sendContent = false;
    }


    public function enableSendContent()
    {
        $this->sendContent = true;
    }

    public function enableSendHeaders()
    {
        $this->sendHeaders = true;
    }

    public function disableSendHeaders()
    {
        $this->sendHeaders = false;
    }

    /**
     * @return $this|\Symfony\Component\HttpFoundation\Response
     */
    public function sendHeaders()
    {
        if ($this->mustSendHeaders()) {
            return parent::sendHeaders();
        }
        return $this;
    }

    public function mustSendHeaders()
    {
        return $this->sendHeaders;
    }

    /**
     * @return $this|\Symfony\Component\HttpFoundation\Response
     */
    public function send()
    {
        if ($this->mustSendContent()) {
            return parent::send();
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function mustSendContent()
    {
        return $this->sendContent;
    }
}
