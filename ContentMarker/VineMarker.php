<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * Vine, replaces [vine url="*"] within strings with a rendered twig file (gets passed the vine url & id).
 *
 * @author Jakub Cerny <jakub.cerny@sinnerschrader.com>
 */
class VineMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[vine url="(.+)"\]/miU';

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
            $tmp     = explode('/', parse_url($match[1], PHP_URL_PATH));
            $options = [
                'url' => empty($match[1]) ? '' : $match[1],
                'id'  => empty($tmp[2]) ? '' : $tmp[2],
            ];

            return $that->renderer->render($that->template, $options);
        };
    }
}
