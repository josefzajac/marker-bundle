<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker\Judge;

use Curved\Model\Cms\Article;
use S2Content\Bundle\MarkerBundle\Component\Envelope;

/**
 * Article has products Judge.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class ArticleHasProductsJudge implements JudgeInterface
{
    /**
     * Method judges if Article has products in Artikel-VerKnupfung.
     *
     * @param $content
     * @param Envelope $envelope
     *
     * @return bool
     */
    public function judge($content, Envelope $envelope = null)
    {
        $this->result = $envelope->getArticle() && $envelope->getArticle() instanceof Article ? count($envelope->getArticle()->getProducts()) : false;

        return $this->result;
    }

    public $result;
}
