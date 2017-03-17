<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * Teasers, replaces [teasers].
 *
 * @author Jakub Cerny <jakub.cerny@sinnerschrader.com>
 */
class TeasersMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[teasers ids="(.+)"\]/miU';

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

        return function ($match) use ($that) {
            $that->getEnvelope()->setData($that->getAlias(), explode(',', $match[1]));

            return $that->renderer->render($that->template, [
                    'teasers' => explode(',', $match[1]),
                ]);
        };
    }
}
