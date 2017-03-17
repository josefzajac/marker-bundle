<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * Ad marker.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class AdMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[ad id="(.+)"\]/miU';

    /**
     * {@inheritDoc}
     */
    public function supports($content)
    {
        return !!preg_match(self::PATTERN, $content);
    }

    /**
     * {@inheritDoc}
     */
    protected function getFnReplace($object)
    {
        $that = $this;

        return function ($match) use ($object, $that) {
            return $that->renderer->render($that->template, [
                    'id'        => $match[1],
                    'article'   => $object,
                ]);
        };
    }
}
