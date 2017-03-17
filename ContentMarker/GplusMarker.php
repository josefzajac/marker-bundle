<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * G+, replaces [gplus url="*"] within strings with a rendered twig file (gets passed the image/video url).
 *
 * @author Petr Enin <petr.enin@sinnerschrader.com>
 */
class GplusMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[gplus url="(.+)"\]/miU';

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
            $options = [
                'url'     => empty($match[1]) ? '' : $match[1],
                'article' => $object,
            ];

            return $that->renderer->render($that->template, $options);
        };
    }
}
