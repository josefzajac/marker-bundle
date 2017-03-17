<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * BlockQuoteMarker, replaces <blockquote>*</blockquote> within strings with a rendered twig file (gets passed the alignment and string).
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class BlockQuoteMarker extends AbstractXmlMarker implements ContentMarkerInterface
{
    const PATTERN = 'blockquote[class!="twitter-tweet"]';

    /**
     * {@inheritdoc}
     */
    protected function extractData(Crawler $crawler)
    {
        $align = null;

        $nodes = $crawler->filter('*[style="text-align: right;"], *[style="text-align: left;"], *[style="text-align: center;"]');

        if (count($nodes) > 0) {
            $align = $nodes->attr('style');

            $align = str_replace(['text-align:', ' ', ';'], '', $align);
        }

        return [
            'text'  => $crawler->text(),
            'align' => $align,
        ];
    }

    // clean <blockquote
    /**
     * @return callable
     */
    protected function getCleanFunction()
    {
        return function () {
            return '';
        };
    }
}
