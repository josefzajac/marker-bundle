<?php

namespace S2Content\Bundle\MarkerBundle\Render;

use S2Content\Bundle\MarkerBundle\Component\Envelope;

/**
 * ContentMarkerInterface.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
interface ContentMarkerInterface
{
    /**
     * wether or not this replacer feels responsible for the content.
     *
     * @param string $content
     *
     * @return bool
     */
    public function supports($content);

    /**
     * processes the content and replaces a marker with its real content.
     *
     * @param string    $content
     * @param \stdClass $object
     * @param array     $outputContexts html|flipboard|amp|facebook|swipe|rss
     *
     * @return Envelope
     */
    public function process($content, $object, $outputContexts);

    /**
     * inject twig into the replacers.
     *
     * @param \Twig_Environment $templating
     */
    public function setTemplating(\Twig_Environment $templating);
}
