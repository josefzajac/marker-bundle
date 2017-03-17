<?php

namespace spec\S2Content\Bundle\MarkerBundle\ContentMarker;

use PhpSpec\ObjectBehavior;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use Symfony\Component\DomCrawler\Crawler;

class BlockQuoteMarkerSpec extends ObjectBehavior
{
    public function let(\Twig_Environment $twig, Crawler $crawler)
    {
        $this->beConstructedWith('template_file.{{context}}.twig', ['html'], $crawler);
        $this->setTemplating($twig);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('S2Content\Bundle\MarkerBundle\ContentMarker\BlockQuoteMarker');
        $this->shouldHaveType('S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface');
    }

    public function it_supports_blockquote_marker()
    {
        $this->supports('foo <blockquote>foo</blockquote> bar')->shouldBe(true);
        $this->supports('foo <blockquote class="twitter-tweet">foo</blockquote> bar')->shouldBe(false);
        $this->supports('foo [lorem] bar')->shouldBe(false);
    }

    public function it_replaces_blockquote_marker_correctly_html(\Twig_Environment $twig, FilesystemLoader $loader)
    {
        $string = '<div>lorem ipsum <blockquote><p style="text-align: right;">lorem</p></blockquote> dolor</div>';
        $this->supports($string)->shouldBe(true);

        $twig->getLoader()->shouldBeCalled()->willReturn($loader);
        $loader->exists('template_file.html.twig')->shouldBeCalled()->willReturn(true);
        $twig->resolveTemplate('template_file.html.twig')->shouldBeCalled();

        $twig->render('template_file.html.twig', ['text' => 'lorem', 'align' => 'right'])->shouldBeCalled()->willReturn('<html></html>');

        $this->process($string, null, ['html'])->shouldBe('<div>lorem ipsum <html></html> dolor</div>');
    }

    public function it_removes_blockquote_marker_correctly_rss()
    {
        $string = '<div>lorem ipsum <blockquote><p style="text-align: right;">lorem</p></blockquote> dolor</div>';
        $this->supports($string)->shouldBe(true);

        $this->process($string, null, ['rss'])->shouldBe('<div>lorem ipsum  dolor</div>');
    }
}
