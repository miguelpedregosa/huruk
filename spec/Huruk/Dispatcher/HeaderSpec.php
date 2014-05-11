<?php

namespace spec\Huruk\Dispatcher;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HeaderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Huruk\Dispatcher\Header');
    }

    function it_should_return_200_status_code_by_default()
    {
        $this->getHttpResponseCode()->shouldReturn(200);
    }

    function it_should_replace_same_header_by_deafult()
    {
        $this->getReplace()->shouldReturn(true);
    }

    function it_should_return_empty_header_string_by_default()
    {
        $this->getHeader()->shouldReturn('');
    }

    function it_should_throw_exception_when_send_empty_header()
    {
        $this->shouldThrow('\Exception')->during('send');
    }

    function it_should_return_the_same_header_string()
    {
        $this->setHeader('Foo:bar');
        $this->getHeader()->shouldReturn('Foo:bar');
    }
}
