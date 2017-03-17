<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * Inject marker.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class InjectMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports($content)
    {
        return $this->judge->judge($content, $this->envelope);
    }

    /**
     * {@inheritDoc}
     */
    public function process($content, $object, $outputContexts)
    {
        $updatedContent = $this->rule->apply($content);
        if (strlen($updatedContent) !== strlen($content)) {
            $this->envelope->addContains($this->rule->getAlias());
        }

        return $updatedContent;
    }
}
