<?php
namespace Huruk\Layout;

/**
 * Represents a html link tag
 * Class Link
 * @package Huruk\Layout
 */
class Link
{
    private $href;
    private $hreflang;
    private $media;
    private $rel;
    private $sizes;
    private $type;

    /**
     * Factoria estatica
     * @param string $rel
     * @param string $type
     * @param string $href
     * @param string $media
     * @return Link
     */
    public static function make($rel, $type, $href, $media = '')
    {
        $link = new self();
        $link->setHref($href)->setMedia($media)->setRel($rel)->setType($type);
        return $link;
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @param $href
     * @return Link
     */
    public function setHref($href)
    {
        $this->href = $href;
        return $this;
    }

    /**
     * @param $href_lang
     * @return Link
     */
    public function setHrefLang($href_lang)
    {
        $this->hreflang = $href_lang;
        return $this;
    }

    /**
     * @return string
     */
    public function getHrefLang()
    {
        return $this->hreflang;
    }

    /**
     * @return mixed
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param $media
     * @return Link
     */
    public function setMedia($media)
    {
        $this->media = $media;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRel()
    {
        return $this->rel;
    }

    /**
     * @param $rel
     * @return Link
     */
    public function setRel($rel)
    {
        $this->rel = $rel;
        return $this;
    }

    /**
     * @return string
     */
    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * @param $sizes
     * @return Link
     */
    public function setSizes($sizes)
    {
        $this->sizes = $sizes;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     * @return Link
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}
