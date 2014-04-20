<?php
namespace Huruk\Dispatcher;

/**
 * Represents a http header
 * Class Header
 * @package Huruk\Dispatcher
 */
class Header
{

    /**
     * Codigos de estado por defecto
     * @var array
     */
    private static $status_codes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended'
    );

    /** @var string */
    private $header;

    /** @var bool */
    private $replace;

    /** @var int */
    private $http_response_code;

    /**
     * @param $header
     * @param bool $replace
     * @param int $http_response_code
     */
    public function __construct($header = '', $replace = true, $http_response_code = 200)
    {
        $this->setHeader($header)
            ->setReplace($replace)
            ->setHttpResponseCode($http_response_code);
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
     * Factoria estatica
     * @param $header
     * @param bool $replace
     * @param int $http_response_code
     * @return Header
     */
    public static function make($header = '', $replace = true, $http_response_code = 200)
    {
        return new self($header, $replace, $http_response_code);
    }

    /**
     * Crea un header de estado
     * @param $code
     * @return Header
     */
    public static function makeFromStatusCode($code)
    {
        $status_code = (isset(self::$status_codes[$code])) ? $code : 200;
        $header_str = self::$status_codes[$status_code];
        return new self($header_str, true, $status_code);
    }

    /**
     * Genera el header correcto para servir datos en json
     * @param string $charset
     * @param bool $json_p
     * @return Header
     */
    public static function makeJsonHeader($charset = 'utf-8', $json_p = false)
    {
        $header_str = ($json_p) ? 'Content-Type: application/javascript' : 'Content-Type: application/json';
        if (!is_null($charset)) {
            $header_str .= '; charset=' . strtolower($charset);
        }
        return new self($header_str);
    }

    /**
     * Envia un header al navegador
     */
    public function send()
    {
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
     * Metodos factoria
     */

    /**
     * @return int
     */
    public function getHttpResponseCode()
    {
        return $this->http_response_code;
    }

    /**
     * @param null $response_code
     * @return Header
     */
    public function setHttpResponseCode($response_code = null)
    {
        $this->http_response_code = (int)$response_code;
        return $this;
    }
}
