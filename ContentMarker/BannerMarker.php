<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use Curved\Model\Cms\Article;
use S2Content\Bundle\MarkerBundle\Component\HeroManager;
use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * Banner, replaces [banner].
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class BannerMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[banner\]/miU';

    /**
     * @var HeroManager
     */
    protected $heroManager;

    /**
     * {@inheritdoc}
     */
    public function supports($content)
    {
        return $this->judge->judge($content, $this->envelope);
    }

    /**
     * {@inheritdoc}
     */
    protected function getFnReplace($object)
    {
        $that     = $this;
        $entityId = null;

        /* @var $object \Curved\Model\Cms\Article */
        if ($object instanceof Article) {
            if ($tariffs = $object->getTariffs()) {
                $entityId = $tariffs[0]['id'];
            }
            if (!$entityId && $products = $object->getProducts()) {
                $entityId = $products[0]['id'];
            }
            if (!$entityId && $entityId = $this->heroManager->compareHeroTopics($object->relations_slugs));
            if (!$entityId && $entityId = $this->heroManager->getDefaultHero());
        }

        return function ($match) use ($object, $that, $entityId) {
            return $that->renderer->render($that->template, [
                'entityId' => $entityId,
                'article'  => $object,
                ]);
        };
    }

    /**
     * @param HeroManager $heroManager
     */
    public function setHeroManager(HeroManager $heroManager)
    {
        $this->heroManager = $heroManager;
    }
}
