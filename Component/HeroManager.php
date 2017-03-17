<?php

namespace S2Content\Bundle\MarkerBundle\Component;

use Curved\Model\Edma\Homepage;
use Curved\Model\Edma\Repository\ProductRepository;
use Curved\Model\Edma\Topic;

/**
 * Hero Banner manager.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class HeroManager
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var bool
     */
    private $isProcessed = false;

    private $defaultHero;

    private $heroTopicsIds = [];

    public function __construct(ProductRepository $productRepository = null)
    {
        $this->productRepository = $productRepository;
    }

    private function process()
    {
        if ($this->isProcessed) {
            return;
        }

        $heroEntities = $this->productRepository->findHeros();

        if ($homepages = array_filter($heroEntities, function ($x) {
                return $x instanceof Homepage;
            })) {
            sort($homepages);
            $homepage          = $homepages[0];
            $homepageHeros     = $homepage->getTopicHeros();
            $this->defaultHero = (int) $homepageHeros[0]['id'];
        }

        if ($topics = array_filter($heroEntities, function ($x) {
                return $x instanceof Topic;
            })) {
            usort($topics, function (Topic $a, Topic $b) {
                return $a->getTeaserPlacement() < $b->getTeaserPlacement();
            });
            foreach ($topics as $heroTopic) {
                foreach ($heroTopic->getTopicHeros() as $hero) {
                    $this->heroTopicsIds[] = ['slug' => $heroTopic->slug, 'id' => (int) $hero['id']];
                }
            }
        }

        $this->isProcessed = true;
    }

    public function getDefaultHero()
    {
        $this->process();

        return $this->defaultHero;
    }

    public function compareHeroTopics(array $topicSlugs)
    {
        $this->process();
        foreach ($this->heroTopicsIds as $hero) {
            if (!array_search($hero['slug'], $topicSlugs, true)) {
                continue;
            }

            return $hero['id'];
        }

        return;
    }
}
