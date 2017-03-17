<?php

namespace spec\S2Content\Bundle\MarkerBundle\ContentMarker;

use PhpSpec\ObjectBehavior;
use spec\Utils\TestArticle;

class GalleryMarkerSpec extends ObjectBehavior
{
    public function let(\Twig_Environment $twig, Crawler $crawler)
    {
        $this->beConstructedWith('template_file.{{context}}.twig', ['html'], $crawler);
        $this->setTemplating($twig);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('S2Content\Bundle\MarkerBundle\GalleryMarker');
        $this->shouldHaveType('S2Content\Bundle\Render\ContentMarkerInterface');
    }

    public function it_supports_gallery_marker()
    {
        $this->supports('foo [gallery id="23,45,46"] bar')->shouldBe(true);
        $this->supports('foo [lorem] bar')->shouldBe(false);
    }

    public function it_replaces_gallery_tags_correctly(\Twig_Environment $twig)
    {
        $twig->resolveTemplate('template_file.html.twig')->shouldBeCalled();

        $article              = new TestArticle();
        $article->galleries[] = [];

        $twig->render('template_file.html.twig', ['images' => []])->shouldBeCalled()->willReturn('<html></html>');

        $this->process('lorem ipsum [gallery id="12,34,56"] dolor', $article)->shouldBe('lorem ipsum <html></html> dolor');
    }
}
