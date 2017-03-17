<?php

namespace spec\S2Content\Bundle\MarkerBundle\Render;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use S2Content\Bundle\MarkerBundle\Render\ContentMarkerDispatcher;

class ContentMarkerDispatcherSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('S2Content\Bundle\MarkerBundle\Render\ContentMarkerDispatcher');
        $this->shouldHaveType('S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface');
    }

    public function it_should_always_be_supported()
    {
        $this->supports('foo')->shouldBe(true);
    }

    public function it_should_process_each_processor_which_supports_its_tag(ContentMarkerDispatcher $p1, ContentMarkerDispatcher $p2, ContentMarkerDispatcher $p3, \Twig_Environment $twig)
    {
        $this->setTemplating($twig);

        $this->addContentMarkerProcessor($p3);
        $this->addContentMarkerProcessor($p1);
        $this->addContentMarkerProcessor($p2);

        $p3->setTemplating(Argument::type('\Twig_Environment'))->shouldNotBeCalled();
        $p3->supports('[foo] lorem ipsum [bar]', Argument::any())->shouldBeCalled()->willReturn(false);
        $p3->process('[foo] lorem ipsum [bar]', Argument::any())->shouldNotBeCalled();

        $p1->setTemplating(Argument::type('\Twig_Environment'))->shouldBeCalled();
        $p1->supports('[foo] lorem ipsum [bar]', Argument::any())->shouldBeCalled()->willReturn(true);
        $p1->process('[foo] lorem ipsum [bar]', Argument::any())->shouldBeCalled()->willReturn('fooContent lorem ipsum [bar]');

        $p2->setTemplating(Argument::type('\Twig_Environment'))->shouldBeCalled();
        $p2->supports('fooContent lorem ipsum [bar]', Argument::any())->shouldBeCalled()->willReturn(true);
        $p2->process('fooContent lorem ipsum [bar]', Argument::any())->shouldBeCalled()->willReturn('fooContent lorem ipsum barContent');

        $this->process('[foo] lorem ipsum [bar]', Argument::any())->shouldBe('fooContent lorem ipsum barContent');
    }
}
