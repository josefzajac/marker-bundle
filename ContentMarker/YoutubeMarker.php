<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * Youtube, replaces [youtube *] within strings with a rendered twig file (gets passed the youtube id).
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class YoutubeMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[youtube id="(.+)"\]/miU';

    /**
     * {@inheritdoc}
     */
    public function supports($content)
    {
        return !!preg_match(self::PATTERN, $content);
    }

    protected function getFnReplace($object)
    {
        $that = $this;

        return function ($match) use ($object, $that) {
            return $that->renderer->render($that->template, [
                'id'        => $match[1],
                'article'   => $object,
                'mainVideo' => false,
            ]);
        };
    }
}
