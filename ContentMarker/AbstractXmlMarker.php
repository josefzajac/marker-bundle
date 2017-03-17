<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use Curved\Model\Cms\Article;
use Symfony\Component\DomCrawler\Crawler;

/**
 * AbstractNodeMarker.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
abstract class AbstractXmlMarker extends AbstractNodeMarker
{
    /**
     * {@inheritdoc}
     */
    public function supports($content)
    {
        $this->crawler->clear();
        $this->crawler->addHtmlContent($content);

        return $this->crawler->filter(static::PATTERN)->count() > 0;
    }

    /**
     * @param string             $content
     * @param null|Article|array $object
     * @param array              $outputContexts
     *
     * @return mixed
     */
    public function process($content, $object, $outputContexts)
    {
        $that      = $this;
        $fnReplace = function (Crawler $node) use (&$content, $that) {
            $newContent = $that->renderer->render($that->template, $that->extractData($node));
            $content    = $this->replaceElement($node, $newContent, $content);
        };
        if (!$this->validateContext($outputContexts)) {
            $fnReplace = $this->getCleanFunction();
        }
        $this->crawler->filter(static::PATTERN)->each($fnReplace);

        return $content;
    }

    /**
     * replaces the node.
     *
     * @param Crawler $crawler
     * @param string  $newContent
     * @param string  $content
     *
     * @return string
     */
    protected function replaceElement(Crawler $crawler, $newContent, $content)
    {
        $self = $crawler->getNode(0);

        $oldContent = $self->ownerDocument->saveXML($self, LIBXML_NOEMPTYTAG);

        return str_replace($oldContent, $newContent, str_replace(['<body>', '</body>'], '', $content));
    }
}
