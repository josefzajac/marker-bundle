<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker\Judge;

use S2Content\Bundle\MarkerBundle\Component\Envelope;

/**
 * Judge Interface - defines when should be InjectMarker enabled.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
interface JudgeInterface
{
    /**
     * Method judges if Marker should be triggered.
     *
     * @param $content
     * @param Envelope $envelope
     *
     * @return bool
     */
    public function judge($content, Envelope $envelope = null);
}
