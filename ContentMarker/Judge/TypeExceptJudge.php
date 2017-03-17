<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker\Judge;

use Curved\Model\Cms\Article;
use S2Content\Bundle\MarkerBundle\Component\Envelope;

/**
 * Article type Except Judge - enables InjectMarker everything except defined article type.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class TypeExceptJudge implements JudgeInterface
{
    /**
     * @var array
     */
    private $types;

    /**
     * @param $types - argument || collection
     */
    public function __construct($types)
    {
        $this->types = (array) $types;
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
        $this->result = $envelope->getArticle() && $envelope->getArticle() instanceof Article ? ! in_array($envelope->getArticle()->articletype, $this->types, true) : false;

        return $this->result;
    }

    public $result;
}
