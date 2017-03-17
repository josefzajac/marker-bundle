<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use Psr\Log\LoggerInterface;
use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * ImageMarker, replaces [caption *]*[/caption] within strings with a rendered twig file (gets passed an array of image data).
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class ImageMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[caption (.*)\](.*)\[\/caption\]/miU';

    /**
     * @var LoggerInterface
     */
    protected $logger;

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
        $that  = $this;
        $count = 0;

        return function ($match) use ($object, $that, &$count) {
            try {
                $data = $that->extractData($match);

                return $that->renderer->render($that->template, ['image' => $data]);
            } catch (\Exception $e) {
                $that->logger->critical(sprintf('unable to replace marker "%s" message: "%s"', $match[0], $e->getMessage()));

                return '';
            }
        };
    }

    /**
     * extracts all relevant image data from marker.
     *
     * @param array $match
     *
     * @return array
     */
    private function extractData(array $match)
    {
        $attrs   = new \SimpleXMLElement('<element ' . strip_tags($match[1]) . '/>');
        $content = new \SimpleXMLElement('<html>' . $match[2] . '</html>');

        if (is_null($el = $content->a->img)) {
            $el = $content->img;
        }

        return [
            'align'         => (string) $attrs->attributes()->align,
            'url'           => $el ? (string) $el->attributes()->src : '',
            'alt'           => $el ? (string) $el->attributes()->alt : '',
            'copyright'     => $el ? (string) $el->attributes()->{'data-copyright'} : '',
            'copyrighturl'  => $el ? (string) $el->attributes()->{'data-copyrighturl'} : '',
            'copyrightyear' => $el ? (string) $el->attributes()->{'data-copyrightyear'} : '',
            'expiration'    => $el ? (string) $el->attributes()->{'data-expiration'} : '',
            'caption'       => (string) $content,
            'title'         => $el ? (string) $el->attributes()->title : '',
            'width'         => $el ? (string) $el->attributes()->width : '',
            'height'        => $el ? (string) $el->attributes()->height : '',
        ];
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
