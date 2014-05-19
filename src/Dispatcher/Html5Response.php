<?php
/**
 *
 * User: migue
 * Date: 19/05/14
 * Time: 21:17
 */

namespace Huruk\Dispatcher;


use Huruk\Layout\Html5Layout;

class Html5Response extends Response
{
    /** @var Html5Layout */
    private $htmlLayout;
    private $bodyContent = '';

    public function __construct($bodyContent = '', $status = 200, $headers = array())
    {
        parent::__construct('', $status, $headers);
        $this->htmlLayout = new Html5Layout();
        $this->setBodyContent($bodyContent);
    }

    /**
     * @return Html5Layout
     */
    public function getHtmlLayout()
    {
        return $this->htmlLayout;
    }

    /**
     * @return mixed
     */
    public function getBodyContent()
    {
        return $this->bodyContent;
    }

    /**
     * @param $bodyContent
     * @return $this
     */
    public function setBodyContent($bodyContent)
    {
        if (null !== $bodyContent &&
            !is_string($bodyContent) &&
            !is_numeric($bodyContent) &&
            !is_callable(array($bodyContent, '__toString'))
        ) {
            throw new \UnexpectedValueException(
                sprintf(
                    'The body content must be a string or object implementing __toString(), "%s" given.',
                    gettype($bodyContent)
                )
            );
        }
        $this->bodyContent = (string)$bodyContent;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return strlen($this->content) ?
            $this->content : $this->getHtmlLayout()->render($this->getBodyContent());
    }


    /**
     * @return $this|\Symfony\Component\HttpFoundation\Response
     */
    public function send()
    {
        $this->content = $this->getContent();
        return parent::send();
    }
}
