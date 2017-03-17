<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * Liveblog, replaces [liveblog *] within strings with a rendered twig file (gets passed the liveblog id).
 *
 * @author Jakub Cerny <jakub.cerny@sinnerschrader.com>
 */
class LiveblogMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[liveblog id="(.+)"\]/miU';

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
            return $that->renderer->render($that->template, ['id' => $match[1], 'article' => $object]);
        };
    }
}
