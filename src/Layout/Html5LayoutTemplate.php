<?php
namespace Huruk\Layout;

/**
 * Render a Html5 document
 * Class Html5LayoutTemplate
 * @package Huruk\Layout
 */
class Html5LayoutTemplate
{
    /**
     * @param array $context
     * @return string
     */
    public function render(array $context)
    {
        ob_start();
        $this->doDisplay($context);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * @param array $context
     */
    private function doDisplay(array $context)
    {
        echo "<!DOCTYPE html>\n";
        if (isset($context['language']) && $language = $context['language']) {
            echo "<html lang=\"$language\">\n";
        } else {
            echo "<html>\n";
        }
        //Head and Title
        echo "<head>\n";
        if (isset($context['title']) && $title = $context['title']) {
            echo "<title>$title</title>\n";
        }
        //Metas
        if (isset($context['metas'])) {
            $this->displayMetas($context['metas']);
        }

        //Links
        if (isset($context['links'])) {
            $this->displayLinks($context['links']);
        }

        echo "</head>\n";

        //Body
        $this->displayBody($context);

        echo "</html>";
    }

    /**
     * @param array $context
     */
    private function displayBody(array &$context)
    {
        echo "<body";
        if (isset($context['body_attributes']) && $bodyAtributtes = $context['body_attributes']) {
            foreach ($bodyAtributtes as $name => $value) {
                echo " $name=\"$value\"";
            }
        }
        echo ">\n";
        if (isset($context['body'])) {
            $body = $context['body'];
            echo $body;
            echo "\n";
        }
        //Js Scripts
        if (isset($context['js_files'])) {
            foreach ($context['js_files'] as $jsSrc) {
                echo "<script src=\"$jsSrc\"></script>\n";
            }
        }
        echo "</body>\n";
    }

    /**
     * @param $metas
     */
    private function displayMetas($metas)
    {
        foreach ($metas as $meta) {
            if ($meta instanceof Meta && ($meta->getContent() || $meta->getCharset())) {
                echo "<meta";
                if ($meta->getHttpEquiv()) {
                    $httpEquiv = $meta->getHttpEquiv();
                    echo " http-equiv=\"$httpEquiv\"";
                }
                if ($meta->getName()) {
                    $name = $meta->getName();
                    echo " name=\"$name\"";
                }
                if ($meta->getContent()) {
                    $content = $meta->getContent();
                    echo " content=\"$content\"";
                }
                if ($meta->getCharset()) {
                    $charset = $meta->getCharset();
                    echo " charset=\"$charset\"";
                }
                echo ">\n";
            }
        }
    }

    /**
     * @param $links
     */
    private function displayLinks($links)
    {
        foreach ($links as $link) {
            if ($link instanceof Link && ($link->getHref() || $link->getRel())) {
                echo "<link";
                if ($link->getRel()) {
                    $rel = $link->getRel();
                    echo " rel=\"$rel\"";
                }
                if ($link->getHref()) {
                    $href = $link->getHref();
                    echo " href=\"$href\"";
                }
                if ($link->getType()) {
                    $type = $link->getType();
                    echo " type=\"$type\"";
                }
                if ($link->getMedia()) {
                    $media = $link->getMedia();
                    echo " media=\"$media\"";
                }
                if ($link->getHrefLang()) {
                    $hreflang = $link->getHrefLang();
                    echo " hreflang=\"$hreflang\"";
                }
                if ($link->getSizes()) {
                    $sizes = $link->getSizes();
                    echo " sizes=\"$sizes\"";
                }
                echo ">\n";
            }
        }
    }
}
