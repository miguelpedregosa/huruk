<?php
namespace Huruk\Dispatcher;

/**
 * Represents a http header
 * Class Header
 * @package Huruk\Dispatcher
 */
class Header
{
    /** @var string */
    private $header;

    /** @var bool */
    private $replace;

    /** @var int */
    private $httpResponseCode;

    /**
     * @param $header
     * @param bool $replace
     * @param int $httpResponseCode
     */
    public function __construct($header = '', $replace = true, $httpResponseCode = 200)
    {
        $this->setHeader($header)
            ->setReplace($replace)
            ->setHttpResponseCode($httpResponseCode);
    }

    /**
     * Send header to client
     * @throws \Exception
     */
    public function send()
    {
        if (!$this->getHeader()) {
            throw new \Exception('Empty header string');
        }
        header($this->getHeader(), $this->getReplace(), $this->getHttpResponseCode());
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param $header
     * @return Header
     */
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @return bool
     */
    public function getReplace()
    {
        return $this->replace;
    }

    /**
     * @param bool $replace
     * @return Header
     */
    public function setReplace($replace = true)
    {
        $this->replace = (bool)$replace;
        return $this;
    }

    /**
     * @return int
     */
    public function getHttpResponseCode()
    {
        return $this->httpResponseCode;
    }

    /**
     * @param null $response_code
     * @return Header
     */
    public function setHttpResponseCode($response_code = null)
    {
        $this->httpResponseCode = (int)$response_code;
        return $this;
    }
}
