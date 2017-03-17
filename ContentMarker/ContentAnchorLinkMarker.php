<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Content anchor, replaces [anchor-link *] within strings with a rendered twig file.
 *
 * @author Oleg Ivaniv <oleg.ivaniv@sinnerschrader.com>
 */
class ContentAnchorLinkMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[anchor-link id="(.+)" text="(.+)"\]/miU';

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * {@inheritdoc}
     */
    public function supports($content)
    {
        return !!preg_match(self::PATTERN, $content);
    }

    /**
     * {@inheritdoc}
     */
    protected function getFnReplace($object)
    {
        $that = $this;

        return function ($match) use ($object, $that) {
            return $that->renderer->render($that->template, [
                'id'      => $match[1],
                'text'    => $match[2],
                'baseUrl' => $that->getRequestStack()->getCurrentRequest()->getUri(),
            ]);
        };
    }

    public function setRequest(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getRequestStack()
    {
        return $this->requestStack;
    }
}
