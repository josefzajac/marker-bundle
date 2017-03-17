<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Iframe, replaces <iframe>*</iframe> within a responsive version.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class IframeMarker extends AbstractXmlMarker implements ContentMarkerInterface
{
    const PATTERN = 'iframe';

    /**
     * Text column width used for iframe dimension calculation.
     *
     * @var int
     */
    private $columnWidth = 733;

    /**
     * {@inheritdoc}
     */
    protected function extractData(Crawler $crawler)
    {
        $h    = $crawler->attr('height');
        $w    = $crawler->attr('width') ?: $this->columnWidth;
        $wAMP = $w;

        if (false !== strpos($w, '%')) {
            $w    = $this->columnWidth;
            $wAMP = 500;
        }

        return [
            'src'   => $crawler->attr('src'),
            'ratio' => number_format(($h / $w) * 100, 5, '.', ''),
            'h'     => $crawler->attr('height'),
            'wAMP'  => $wAMP,
        ];
    }

    public function setColumnWidth($width)
    {
        $this->columnWidth = $width;
    }
}
