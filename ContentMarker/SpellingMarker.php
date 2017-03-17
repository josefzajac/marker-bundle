<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * Spelling Marker.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 *
 * regexp test subject:
 *
 * O2 Blau-WeiO2ß in Wo2eiß-Blau: O2Der O2-Truck in München
 * Wir freuen uns auf euren Besuch am <a href="https://www.o2-online.de/guru/on-tour/">O2-Truck</a> auf dem Odeonsplatz! Und das war noch lange nicht alles. o_2.
 * <a href="https://www.o2-online.de/guru/on-tour/">O2-Truck</a>
 */
class SpellingMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    private $replacement;
    private $pattern;

    public function __construct($replacement, $enabledContexts, array $needle)
    {
        $this->replacement = $replacement;
        $this->enabledContexts = $enabledContexts;

        // explanation: match an item of the source with boundaries on both sides, item is not in opened html tag
        // with negative lookahead (will cause match to fail if contents match)
        $this->pattern = sprintf('/\b(%s)\b(?![^<]*>)/mU', implode('|', $needle));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($content)
    {
        return !!preg_match($this->pattern, $content);
    }

    /**
     * {@inheritdoc}
     */
    public function process($content, $object, $outputContexts)
    {
        return preg_replace($this->pattern, $this->replacement, $content);
    }
}
