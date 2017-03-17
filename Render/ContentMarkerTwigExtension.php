<?php

namespace S2Content\Bundle\MarkerBundle\Render;

/**
 * ContentMarkerTwigExtension.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class ContentMarkerTwigExtension extends \Twig_Extension
{
    /**
     * @var ContentMarkerInterface
     */
    private $markerDispatcher;

    /**
     * @var \Twig_Environment
     */
    private $runtime;

    /**
     * construct.
     *
     * @param ContentMarkerInterface $markerDispatcher
     */
    public function __construct(ContentMarkerInterface $markerDispatcher)
    {
        $this->markerDispatcher = $markerDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->runtime = $environment;

        parent::initRuntime($environment);
    }

    /**
     * replaces all markers with their real content.
     *
     * @param string    $content
     * @param \stdClass $object
     * @param string    $outputContext html|flipboard|facebook|amp
     *
     * @return string
     */
    public function replaceMarkers($content, $object = null, $outputContext = 'html')
    {
        $this->markerDispatcher->setTemplating($this->runtime);

        return $this->markerDispatcher->process($content, $object, (array) $outputContext)->getContent();
    }

    /**
     * check all markers used in content.
     *
     * @param string    $content
     * @param \stdClass $object
     * @param string    $outputContext html|flipboard|facebook|amp
     *
     * @return array
     */
    public function containsMarkers($content, $object, $outputContext = 'html')
    {
        $this->markerDispatcher->setTemplating($this->runtime);

        return $this->markerDispatcher->process($content, $object, (array) $outputContext)->getContains();
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('get_marker_data', [$this, 'getMarkerData']),
            new \Twig_SimpleFilter('marker_replace', [$this, 'replaceMarkers']),
            new \Twig_SimpleFilter('marker_replace_contains', [$this, 'containsMarkers']),
        ];
    }

    /**
     * replaces all markers with their real content.
     *
     * @param string    $content
     * @param \stdClass $object
     * @param string    $outputContext html|flipboard|facebook|amp
     *
     * @return array
     */
    public function getMarkerData($content, $object = null, $outputContext = 'html')
    {
        $this->markerDispatcher->setTemplating($this->runtime);

        return $this->markerDispatcher->process($content, $object, (array) $outputContext)->getData();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'curved_content_marker';
    }
}
