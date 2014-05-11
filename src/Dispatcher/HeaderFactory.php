<?php
/**
 *
 * User: migue
 * Date: 11/05/14
 * Time: 16:22
 */

namespace Huruk\Dispatcher;


class HeaderFactory
{
    /**
     * Codigos de estado por defecto
     * @var array
     */
    private static $statusCodes = array(
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

    /**
     * Create a new header
     * @param $header
     * @param bool $replace
     * @param int $httpResponseCode
     * @return Header
     */
    public static function make($header = '', $replace = true, $httpResponseCode = 200)
    {
        return new Header($header, $replace, $httpResponseCode);
    }

    /**
     * Create a new header from a status code
     * @param $code
     * @return Header
     */
    public static function makeFromStatusCode($code)
    {
        $statusCode = (isset(self::$statusCodes[$code])) ? $code : 200;
        $headerString = self::$statusCodes[$statusCode];
        return new Header($headerString, true, $statusCode);
    }

    /**
     * Create a new header for send Json content to the client
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
        return new Header($header_str);
    }
}
