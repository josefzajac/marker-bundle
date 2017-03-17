<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker\Judge;

use S2Content\Bundle\MarkerBundle\Component\Envelope;

/**
 * Preg match Judge.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class MatchJudge implements JudgeInterface
{
    /**
     * @var string
     */
    private $match;

    /**
     * @param $match
     */
    public function __construct($match)
    {
        $this->match = $match;
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
        $this->result = !!preg_match($this->match, $content);

        return $this->result;
    }

    public $result;
}
