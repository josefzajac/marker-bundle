<?php

namespace spec\S2Content\Bundle\MarkerBundle\ContentMarker;

use PhpSpec\ObjectBehavior;
use spec\Utils\TestArticle;

class IframeMarkerSpec extends ObjectBehavior
{
    public function let(\Twig_Environment $twig, Crawler $crawler)
    {
        $this->beConstructedWith('template_file.{{context}}.twig', ['html'], $crawler);
        $this->setTemplating($twig);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('S2Content\Bundle\MarkerBundle\IframeMarker');
        $this->shouldHaveType('S2Content\Bundle\Render\ContentMarkerInterface');
    }

    public function it_supports_blockquote_marker()
    {
        $this->supports('foo <iframe src="http://google.com" width="800" height="600"></iframe> bar')->shouldBe(true);
        $this->supports('foo [lorem] bar')->shouldBe(false);
    }

    public function it_replaces_blockquote_marker_correctly(\Twig_Environment $twig)
    {
        $string = '<p> foo <iframe src="http://google.com" width="800" height="600"></iframe> bar </p>';
        $this->supports($string)->shouldBe(true);

        $twig->resolveTemplate('template_file.html.twig')->shouldBeCalled();

        $article = new TestArticle();

        $twig->render('template_file.html.twig', ['src' => 'http://google.com', 'ratio' => '75'])->shouldBeCalled()->willReturn('<html></html>');

        $this->process($string, $article)->shouldBe('<p> foo <html></html> bar </p>');
    }
}
