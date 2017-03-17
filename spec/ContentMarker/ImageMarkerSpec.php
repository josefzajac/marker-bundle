<?php

namespace spec\S2Content\Bundle\MarkerBundle\ContentMarker;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use spec\Utils\TestArticle;

class ImageMarkerSpec extends ObjectBehavior
{
    public function let(\Twig_Environment $twig, Crawler $crawler, LoggerInterface $logger)
    {
        $this->beConstructedWith('template_file.{{context}}.twig', ['html'], $crawler);
        $this->setTemplating($twig);
        $this->setLogger($logger);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('S2Content\Bundle\MarkerBundle\ImageMarker');
        $this->shouldHaveType('S2Content\Bundle\Render\ContentMarkerInterface');
    }

    public function it_supports_image_marker()
    {
        $this->supports('[caption id="7711"]<a><img src="//lolcathost.com/foo.png" alt="alt" title="title" width="100" height="200"/>caption</a>[/caption]')->shouldBe(true);
        $this->supports('foo [lorem] bar')->shouldBe(false);
    }

    public function it_replaces_image_marker_correctly(\Twig_Environment $twig)
    {
        $twig->resolveTemplate('template_file.html.twig')->shouldBeCalled();

        $article = new TestArticle();

        $twig->render('template_file.html.twig', Argument::allOf(['image' => [
            'align'         => 'align',
            'src'           => '//lolcathost.com/foo.png',
            'alt'           => 'alt',
            'copyright'     => '',
            'copyrighturl'  => '',
            'copyrightyear' => '',
            'expiration'    => '',
            'caption'       => 'caption',
            'title'         => 'title',
            'height'        => '200',
            'width'         => '100',
        ]]))->shouldBeCalled()->willReturn('<html></html>');

        $this->process('lorem ipsum [caption id="7711" align="align"]<a><img src="//lolcathost.com/foo.png" alt="alt" title="title" width="100" height="200"/></a>caption[/caption] dolor', $article)
            ->shouldBe('lorem ipsum <html></html> dolor');
    }

    public function it_replaces_faulty_image_tags_with_an_empty_string(\Twig_Environment $twig, LoggerInterface $logger)
    {
        $twig->resolveTemplate('template_file.html.twig')->shouldBeCalled();

        $article = new TestArticle();

        $twig->render()->shouldNotBeCalled();
        $logger->critical(Argument::that(function ($a) {return strpos($a, '[caption ]<a/a>caption[/caption]') !== false;}))->shouldBeCalled();

        $this->process('lorem ipsum [caption ]<a/a>caption[/caption] dolor', $article)->shouldBe('lorem ipsum  dolor');
    }
}
