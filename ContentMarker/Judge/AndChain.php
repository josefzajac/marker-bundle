<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker\Judge;

use S2Content\Bundle\MarkerBundle\Component\Envelope;

/**
 * Check AND condition for judges.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class AndChain implements JudgeInterface
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
        $this->result = true;
        foreach ($this->judges as $judge) {
            if (!$this->result = $this->result && $judge->judge($content, $envelope)) {
                return $this->result;
            }
        }

        return $this->result;
    }

    public $result;
}
