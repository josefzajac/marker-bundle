<?php

namespace S2Content\Bundle\MarkerBundle\Render;

use Curved\Model\Cms\Article;
use S2Content\Bundle\MarkerBundle\Component\Envelope;
use S2Content\Bundle\MarkerBundle\ContentMarker\AbstractNodeMarker;

/**
 * ContentMarkerDispatcher.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class ContentMarkerDispatcher implements ContentMarkerInterface
{
    private $processors = [];

    /**
     * @var \Twig_Environment
     */
    private $templating;

    /**
     * adds a content marker processor.
     *
     * @param ContentMarkerInterface $processor
     */
    public function addContentMarkerProcessor(ContentMarkerInterface $processor)
    {
        $this->processors[] = $processor;
        dump(get_class($processor));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($content)
    {
        return true;
    }

    private static $cache = [];

    /**
     * {@inheritdoc}
     */
    public function process($content, $object, $outputContext = 'html')
    {
        $key = md5($content);

        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        self::$cache[$key] = $envelope = new Envelope();
        $envelope->setArticle($object instanceof Article ? $object : null);
        $this->contains($content, $envelope);
        foreach ($this->processors as $processor) {
            /* @var AbstractNodeMarker $processor */
            $processor->setEnvelope($envelope);
            if ($processor->supports($content)) {
                $processor->setTemplating($this->templating);
                $content = $processor->process($content, $object, $outputContext);
            }
        }
        $envelope->setContent($content);

        return self::$cache[$key];
    }
    /**
     * {@inheritDoc}
     */
    public function contains($content, Envelope $envelope)
    {
        foreach ($this->processors as $processor) {
            /* @var AbstractNodeMarker $processor */
            $processor->setEnvelope($envelope);
            if ($processor->supports($content)) {
                $envelope->addContains($processor->getAlias());
            }
        }
    }

    /**`
     * {@inheritdoc}
     */
    public function setTemplating(\Twig_Environment $templating)
    {
        $this->templating = $templating;
    }

    public function getAlias($obj)
    {
        $x = explode('\\', get_class($obj));

        return strtolower(substr(end($x), 0, -6));
    }
}
