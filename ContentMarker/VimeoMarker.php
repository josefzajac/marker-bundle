<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * Vimee, replaces [vimeo url="*"] within strings with a rendered twig file (gets passed the vimeo url & id).
 *
 * @author Jakub Cerny <jakub.cerny@sinnerschrader.com>
 */
class VimeoMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[vimeo url="(.+)"\]/miU';

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
                'url'     => empty($match[1]) ? '' : $match[1],
                'id'      => empty($tmp[1]) ? '' : $tmp[1],
                'article' => $object, // needed??
            ];

            return $that->renderer->render($that->template, $options);
        };
    }
}
