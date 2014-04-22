<?php
namespace Huruk\Layout;

use Huruk\Util\StablePriorityQueue;

/**
 * Layout for crate Html5 documents
 * Class Html5Layout
 * @package Huruk\Layout
 */
class Html5Layout implements LayoutInterface
{
    const PRIORITY_HIGH = 3;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_LOW = 1;

    /** @var string Idioma del documento */
    public $language;

    /** @var string  Titulo de la pagina */
    public $title;

    /** @var  string Charset de la pagina */
    public $charset;

    /** @var  string Autor del documento */
    public $author;

    /** @var  string Nombre de la aplicacion */
    public $applicationName;

    /** @var  string Generador de la aplicacion */
    public $generator;

    /** @var  string Descripcion del documento */
    public $description;

    /** @var  string Palabras clave asociadas al documento */
    public $keywords;

    /** @var  string Viewport del documento */
    public $viewport;

    /** @var  string URL canonica del documento */
    public $canonical;
    /** @var bool */
    protected $debug = false;

    /** @var  StablePriorityQueue */
    private $metas;
    /** @var  StablePriorityQueue */
    private $links;
    /** @var array */
    private $bodyAttributes = array();
    /** @var  \Twig_Environment */
    private $twigEnvironment;
    /** @var  StablePriorityQueue */
    private $jsAssets;
    /** @var  StablePriorityQueue */
    private $cssAssets;

    /**
     * Aniade una etiqueta meta al documento
     * @param Meta $meta
     * @param int $priority
     * @return Html5Layout
     */
    public function addMeta(Meta $meta, $priority = self::PRIORITY_LOW)
    {
        $this->getMetasContainer()->insert($meta, $priority);
        return $this;
    }

    /**
     * @return StablePriorityQueue
     */
    private function getMetasContainer()
    {
        if (!$this->metas instanceof StablePriorityQueue) {
            $this->metas = new StablePriorityQueue();
        }
        return $this->metas;
    }

    /**
     * Aniade un meta de tipo Http-eqiov al documento
     * @param $http_equiv
     * @param $content
     * @param int $priority
     * @return Html5Layout
     */
    public function addHttpEquivMetaTag($http_equiv, $content, $priority = self::PRIORITY_LOW)
    {
        $meta = Meta::make()->setHttpEquiv($http_equiv)->setContent($content);
        $this->getMetasContainer()->insert($meta, $priority);
        return $this;
    }

    /**
     * Almacena un atributo para la etiqueta body
     * @param $name
     * @param $value
     * @return Html5Layout
     */
    public function setBodyAttribute($name, $value)
    {
        $this->bodyAttributes[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @return Html5Layout
     */
    public function unsetBodyAttribute($name)
    {
        if (isset($this->bodyAttributes[$name])) {
            unset ($this->bodyAttributes[$name]);
        }
        return $this;
    }

    /**
     * @return Html5Layout
     */
    public function cleanBodyAttributes()
    {
        $this->bodyAttributes = array();
        return $this;
    }

    /**
     * Renderiza un documento Html 5 con el contenido del body que se le pasa
     * @param $body_contents
     * @return string
     */
    public function render($body_contents = '')
    {
        //Metas comunes
        $this->addCommonMetaTags();

        //Links comunes
        $this->addCommonLinkTags();

        //Archivos Js a incluir en el documento
        $js_files = $this->getJsAssetsContainer();

        //Archivos Css a incluir en el documento
        $this->processCssAssets();

        $context = array(
            'language' => $this->language,
            'title' => $this->title,
            'metas' => $this->getMetasContainer(),
            'links' => $this->getLinksContainer(),
            'body' => $body_contents,
            'js_files' => $js_files,
            'body_attributes' => $this->bodyAttributes
        );

        return $this->getTwigEnvironment()->render('html5.twig', $context);
    }

    private function addCommonMetaTags()
    {
        //Charset
        $this->setCharsetMeta();

        //Author
        $this->addMetaTag('author', $this->author, self::PRIORITY_HIGH);

        //Aplication name
        $this->addMetaTag('application-name', $this->applicationName, self::PRIORITY_HIGH);

        //Generador
        $this->addMetaTag('generator', $this->generator, self::PRIORITY_HIGH);

        //Descripcion
        $this->addMetaTag('description', $this->description, self::PRIORITY_HIGH);

        //Keywords
        $this->addMetaTag('keywords', $this->keywords, self::PRIORITY_HIGH);

        //Viewport
        $this->addMetaTag('viewport', $this->viewport, self::PRIORITY_HIGH);

    }

    private function setCharsetMeta()
    {
        $meta = Meta::make()->setCharset(strtolower($this->charset));
        $this->getMetasContainer()->insert($meta, self::PRIORITY_HIGH);
    }

    /**
     * Aniade una etiqueta meta al documento
     * @param $name
     * @param $content
     * @param int $priority
     * @return Html5Layout
     */
    public function addMetaTag($name, $content, $priority = self::PRIORITY_LOW)
    {
        $meta = Meta::make($name, $content);
        $this->getMetasContainer()->insert($meta, $priority);
        return $this;
    }

    private function addCommonLinkTags()
    {
        //Url Canonica
        $link = new Link();
        $link->setRel('canonical')->setHref($this->canonical);
        $this->getLinksContainer()->insert($link, self::PRIORITY_HIGH);
    }

    /**
     * @return StablePriorityQueue
     */
    private function getLinksContainer()
    {
        if (!$this->links instanceof StablePriorityQueue) {
            $this->links = new StablePriorityQueue();
        }
        return $this->links;
    }

    /**
     * @return StablePriorityQueue
     */
    private function getJsAssetsContainer()
    {
        if (!$this->jsAssets instanceof StablePriorityQueue) {
            $this->jsAssets = new StablePriorityQueue();
        }

        return $this->jsAssets;
    }

    /**
     * Procesa los recursos CSS a incluir en el documento
     */
    private function processCssAssets()
    {
        /** @var StablePriorityQueue $css_assets */
        $css_assets = $this->getCssAssetsContainer();
        while (!$css_assets->isEmpty()) {
            $asset = $css_assets->extract();
            $link = new Link();
            $link->setHref($asset['asset'])
                ->setMedia($asset['media'])
                ->setType('text/css')
                ->setRel('stylesheet');
            $this->addLink($link, 0);
        }
    }

    /**
     * @return StablePriorityQueue
     */
    private function getCssAssetsContainer()
    {
        if (!$this->cssAssets instanceof StablePriorityQueue) {
            $this->cssAssets = new StablePriorityQueue();
        }

        return $this->cssAssets;
    }

    /**
     * @param Link $link
     * @param int $priority
     * @return Html5Layout
     */
    public function addLink(Link $link, $priority = self::PRIORITY_LOW)
    {
        $this->getLinksContainer()->insert($link, $priority);
        return $this;
    }

    /**
     * Devuelve la instancia de TwigEnviroment que debe usar para renderizar el documento
     * @return \Twig_Environment
     */
    private function getTwigEnvironment()
    {
        if (!$this->twigEnvironment || !$this->twigEnvironment instanceof \Twig_Environment) {
            $loader = new \Twig_Loader_Filesystem(array(
                __DIR__ . '/../../templates/layout'
            ));
            $options = array(
                'cache' => '/tmp/twigcache',
                'strict_variables' => $this->debug,
                'auto_reload' => true,
                'debug' => $this->debug
            );
            $this->twigEnvironment = new \Twig_Environment($loader, $options);
            $this->twigEnvironment->addExtension(new \Twig_Extension_Debug());
        }
        return $this->twigEnvironment;
    }

    /**
     * @param $path
     * @param string $media
     * @param int $priority
     * @return Html5Layout
     */
    public function addCss($path, $media = '', $priority = self::PRIORITY_HIGH)
    {
        $css = array(
            'asset' => $path,
            'media' => $media
        );
        $this->getCssAssetsContainer()->insert($css, $priority);
        return $this;
    }

    /**
     * @param $path
     * @param int $priority
     * @return Html5Layout
     */
    public function addJs($path, $priority = self::PRIORITY_LOW)
    {
        $this->getJsAssetsContainer()->insert($path, $priority);
        return $this;
    }

    /**
     * @param $title
     * @return Html5Layout
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param $language
     * @return Html5Layout
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @param $charset
     * @return Html5Layout
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * @param $author
     * @return Html5Layout
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @param $application_name
     * @return Html5Layout
     */
    public function setApplicationName($application_name)
    {
        $this->applicationName = $application_name;
        return $this;
    }

    /**
     * @param $generator
     * @return Html5Layout
     */
    public function setGenerator($generator)
    {
        $this->generator = $generator;
        return $this;
    }

    /**
     * @param $description
     * @return Html5Layout
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param $keywords
     * @return Html5Layout
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * @param $viewport
     * @return Html5Layout
     */
    public function setViewPort($viewport)
    {
        $this->viewport = $viewport;
        return $this;
    }

    /**
     * @param $canonical
     * @return Html5Layout
     */
    public function setCanonical($canonical)
    {
        $this->canonical = $canonical;
        return $this;
    }
}
