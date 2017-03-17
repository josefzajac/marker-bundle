<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker\Judge;

use S2Content\Bundle\MarkerBundle\Component\Envelope;

/**
 * Check OR condition for judges.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class OrChain implements JudgeInterface
{
    /**
     * @var array
     */
    private $judges;

    /**
     * @param $types - argument || collection
     */
    public function __construct($judges)
    {
        $this->judges = (array) $judges;
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
        $this->result = false;
        foreach ($this->judges as $judge) {
            $this->result = $this->result || $judge->judge($content, $envelope);
        }

        return $this->result;
    }

    public $result;
}
