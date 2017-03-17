<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * Toplist, replaces [toplist].
 *
 * @author Jakub Cerny <jakub.cerny@sinnerschrader.com>
 */
class ToplistMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[toplist\]/miU';

    /**
     * {@inheritdoc}
     */
    public function supports($content)
    {
        return !!preg_match(self::PATTERN, $content);
    }

    /**
     * {@inheritdoc}
     */
    protected function getFnReplace($object)
    {
        $that = $this;

        return function ($match) use ($object, $that) {
            return $that->renderer->render($that->template, ['article' => $object]);
        };
    }
}
