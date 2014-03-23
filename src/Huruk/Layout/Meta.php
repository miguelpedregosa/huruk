<?php
/**
 *
 * User: migue
 * Date: 9/02/14
 * Time: 17:47
 */

namespace Huruk\Layout;

/**
 * Represents a html meta tag
 * Class Meta
 * @package Huruk\Layout
 */
class Meta
{
    private $name;
    private $content;
    private $charset;
    private $http_equiv = '';

    /**
     * Factoria estatica
     * @param string $name
     * @param string $content
     * @param string $charset
     * @param string $http_equiv
     * @return Meta
     */
    public static function make($name = '', $content = '', $charset = '', $http_equiv = '')
    {
        $meta = new self();
        $meta->setName($name)
            ->setContent($content)
            ->setCharset($charset)
            ->setHttpEquiv($http_equiv);
        return $meta;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return Meta
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $content
     * @return Meta
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param $charset
     * @return Meta
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * @return string
     */
    public function getHttpEquiv()
    {
        return $this->http_equiv;
    }

    /**
     * @param $http_equiv
     * @return Meta
     */
    public function setHttpEquiv($http_equiv)
    {
        $this->http_equiv = $http_equiv;
        return $this;
    }
}
