<?php

namespace spec\S2Content\Bundle\MarkerBundle\Render;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use S2Content\Bundle\MarkerBundle\Component\Envelope;
use S2Content\Bundle\MarkerBundle\ContentMarker\YoutubeMarker;
use S2Content\Bundle\MarkerBundle\Render\ContentMarkerDispatcher;
use Symfony\Component\DomCrawler\Crawler;

class ContentMarkerTwigExtensionSpec extends ObjectBehavior
{
    public function let(ContentMarkerDispatcher $dispatcher)
    {
        $this->beConstructedWith($dispatcher);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('S2Content\Bundle\MarkerBundle\Render\ContentMarkerTwigExtension');
        $this->shouldHaveType('\Twig_Extension');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldBeString();
    }

    public function it_has_a_filter_registered()
    {
        $this->getFilters()->shouldHaveCount(3);
    }

    public function it_calls_the_dispatcher_on_replaceMarkers(ContentMarkerDispatcher $dispatcher, \Twig_Environment $twig)
    {
        $this->initRuntime($twig);

        $envelope = new Envelope();
        $dispatcher->setTemplating(Argument::type('\Twig_Environment'))->shouldBeCalled();
        $dispatcher->process('foo', null, ['html'])->shouldBeCalled()->willReturn($envelope);

        $this->replaceMarkers('foo', null, ['html'])->shouldBe('bar');
    }

    public function it_calls_the_dispatcher_on_containsMarkers(ContentMarkerDispatcher $dispatcher, \Twig_Environment $twig, Crawler $crawler)
    {
        $string = 'lorem ipsum [youtube id="7711"] dolor';
        $this->initRuntime($twig);

        $marker = new YoutubeMarker('template_file.{{context}}.twig', ['html'], $crawler);
        $marker->setTemplating(new \Twig_Environment);

        $envelope = new Envelope();

        $dispatcher->addContentMarkerProcessor($marker);
        $dispatcher->setTemplating(Argument::type('\Twig_Environment'))->shouldBeCalled();
        $dispatcher->process($string, null, ['html'])->shouldBeCalled()->willReturn($envelope);
        $dispatcher->contains($string, $envelope)->shouldBeCalled()->willReturn(null);

        $this->containsMarkers($string, null, ['html'])->shouldBe(['youtube']);
    }
}
