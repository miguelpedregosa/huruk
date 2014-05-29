<?php
/**
 *
 * User: migue
 * Date: 19/05/14
 * Time: 21:41
 */

namespace Huruk\Dispatcher;


class JsonResponder extends Responder
{

    public function __construct($content = '')
    {
        parent::__construct('');
        $this->getHeaders()->set('Content-type', 'application/json; charset=UTF-8');
        $this->setContent($content);
    }


    public function setContent($content)
    {
        parent::setContent(json_encode($content));
    }
}
