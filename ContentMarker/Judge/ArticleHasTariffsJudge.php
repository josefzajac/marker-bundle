<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker\Judge;

use Curved\Model\Cms\Article;
use S2Content\Bundle\MarkerBundle\Component\Envelope;

/**
 * Article has tariffs Judge.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class ArticleHasTariffsJudge implements JudgeInterface
{
    /**
     * Method judges if Article has tariffs in Artikel-VerKnupfung.
     *
     * @param $content
     * @param Envelope $envelope
     *
     * @return bool
     */
    public function judge($content, Envelope $envelope = null)
    {
        $this->result = $envelope->getArticle() && $envelope->getArticle() instanceof Article ? count($envelope->getArticle()->getTariffs()) : false;

        return $this->result;
    }

    public $result;
}
