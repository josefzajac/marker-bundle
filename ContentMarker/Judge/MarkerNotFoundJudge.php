<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker\Judge;

use Curved\Model\Cms\Article;
use S2Content\Bundle\MarkerBundle\Component\Envelope;

/**
 * Check if markers from arguments exists in article.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class MarkerNotFoundJudge implements JudgeInterface
{
    /**
     * @var array
     */
    private $markers;

    /**
     * @param $types - argument || collection
     */
    public function __construct($markers)
    {
        $this->markers = (array) $markers;
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
        $this->result = ! (bool) array_intersect(array_keys($envelope->getContains()), $this->markers) && $envelope->getArticle();

        return $this->result;
    }

    public $result;
}
