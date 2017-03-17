<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * Twitter, replaces [tweet url="*"] within strings with a rendered twig file (gets passed the tweet url).
 *
 * @author Petr Enin <petr.enin@sinnerschrader.com>
 */
class TwitterMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[tweet url="(.+)"\]/miU';

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
            $tweet   = explode('/', parse_url($match[1], PHP_URL_PATH));
            $options = [
                'url'     => empty($match[1]) ? '' : $match[1],
                'author'  => empty($tweet[1]) ? '' : $tweet[1],
                'tweetid' => empty($tweet[3]) ? '' : $tweet[3],
                'article' => $object,
            ];

            return $that->renderer->render($that->template, $options);
        };
    }
}
