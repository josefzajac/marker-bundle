<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * Content shoplink, replaces [content-shoplink *] within strings with a rendered twig file.
 *
 * @author Jakub Cerny <jakub.cerny@sinnerschrader.com>
 */
class ContentShoplinkMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[content-shoplink id="(.+)" name="(.+)" txt="(.+)"\]/miU';

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
        $i    = 1;

        return function ($match) use ($object, $that, &$i) {
            return $that->renderer->render($that->template, [
                    'id'    => $match[1],
                    'name'  => $match[2],
                    'txt'   => $match[3],
                    'index' => $i++,
                ]);
        };
    }
}
