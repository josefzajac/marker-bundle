<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * Content anchor, replaces [anchor-target *] within strings with a rendered twig file.
 *
 * @author Oleg Ivaniv <oleg.ivaniv@sinnerschrader.com>
 */
class ContentAnchorTargetMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[anchor-target id="(.+)"\]/miU';

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
            $options = [
                'id'    => $match[1],
            ];

            return $that->renderer->render($that->template, $options);
        };
    }
}
