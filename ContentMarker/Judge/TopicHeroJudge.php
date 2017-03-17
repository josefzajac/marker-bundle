<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker\Judge;

use Curved\Model\Cms\Article;
use S2Content\Bundle\MarkerBundle\Component\Envelope;
use S2Content\Bundle\MarkerBundle\Component\HeroManager;

/**
 * Article has tariffs Judge.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class TopicHeroJudge implements JudgeInterface
{
    /**
     * @var HeroManager
     */
    protected $heroManager;

    public function __construct(HeroManager $heroManager = null)
    {
        $this->heroManager = $heroManager;
    }

    /**
     * Method judges if Article has any Topic with "has_hero" Entity.
     *
     * @param $content
     * @param Envelope $envelope
     *
     * @return bool
     */
    public function judge($content, Envelope $envelope = null)
    {
        if (!($envelope->getArticle() && $envelope->getArticle() instanceof Article)) {
            return false;
        }

        $this->result = !!($this->heroManager->compareHeroTopics($envelope->getArticle()->relations_slugs) || $this->heroManager->getDefaultHero());

        return $this->result;
    }

    public $result;
}
