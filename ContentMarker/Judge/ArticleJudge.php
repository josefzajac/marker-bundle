<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker\Judge;

use Curved\Model\Cms\Article;
use S2Content\Bundle\MarkerBundle\Component\Envelope;

/**
 * Article Judge - enables for all articles.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class ArticleJudge implements JudgeInterface
{
    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * Method judges if Marker should be triggered.
     *
     * @param $content
     * @param Envelope $envelope
     *
     * @return bool
     */
    public function judge($content, Envelope $envelope = null)
    {
        $this->result = $envelope->getArticle() && $envelope->getArticle() instanceof Article;

        return $this->result;
    }

    public $result;
}
