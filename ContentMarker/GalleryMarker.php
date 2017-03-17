<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use Curved\Model\Cms\Article;
use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * GalleryMarker, replaces [gallery *] within strings with a rendered twig file (gets passed an array of images).
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class GalleryMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[gallery .*\]/miU';

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
        $that  = $this;
        $count = 0;

        return function ($match) use ($object, $that, &$count) {
            $images = [];
            if ($object instanceof Article) {
                $images = isset($object->galleries[$count ? $count : 0]) ? $object->galleries[$count ? $count : 0] : [];
                ++$count;
            }
            if (is_array($object) && isset($object['galleries'])) {
                $images = isset($object['galleries'][$count ? $count : 0]) ? $object['galleries'][$count ? $count : 0] : [];
                ++$count;
            }

            return $that->renderer->render($that->template, ['images' => $images, 'article' => $object]);
        };
    }
}
