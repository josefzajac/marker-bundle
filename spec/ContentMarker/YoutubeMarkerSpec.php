<?php

namespace spec\S2Content\Bundle\MarkerBundle\ContentMarker;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use Symfony\Component\DomCrawler\Crawler;

class YoutubeMarkerSpec extends ObjectBehavior
{
    public function let(\Twig_Environment $twig, Crawler $crawler)
    {
        $this->beConstructedWith('template_file.{{context}}.twig', ['html'], $crawler);
        $this->setTemplating($twig);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('S2Content\Bundle\MarkerBundle\ContentMarker\YoutubeMarker');
        $this->shouldHaveType('S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface');
    }

    public function it_supports_youtube_marker()
    {
        $this->supports('foo [youtube id="7711"] bar')->shouldBe(true);
        $this->supports('foo [lorem] bar')->shouldBe(false);
    }

    public function it_replaces_youtube_marker_correctly_html(\Twig_Environment $twig, FilesystemLoader $loader)
    {
        $string = 'lorem ipsum [youtube id="7711"] dolor';
        $this->supports($string)->shouldBe(true);

        $twig->getLoader()->shouldBeCalled()->willReturn($loader);
        $loader->exists('template_file.html.twig')->shouldBeCalled()->willReturn(true);
        $twig->resolveTemplate('template_file.html.twig')->shouldBeCalled();

        $article = new \stdClass();

        $twig->render('template_file.html.twig', ['id' => '7711', 'article' => $article, 'mainVideo' => false])->shouldBeCalled()->willReturn('<html></html>');

        $this->process($string, $article, ['html'])->shouldBe('lorem ipsum <html></html> dolor');
    }

    public function it_removes_youtube_marker_correctly_rss()
    {
        $string = 'lorem ipsum [youtube id="7711"] dolor';
        $this->supports($string)->shouldBe(true);

        $this->process($string, null, ['rss'])->shouldBe('lorem ipsum  dolor');
    }
}
