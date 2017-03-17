<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * InstagramMarker.
 *
 * @author Oleg Ivaniv
 */
class InstagramMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[instagram id="(.+)"\]/miU';

    const INSTAGRAM_URL = 'https://www.instagram.com/p/%s';
    const INSTAGRAM_API = 'https://api.instagram.com/oembed/?url=https://www.instagram.com/p/%s';

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
            try {
                $response = $this->client->get(sprintf(urldecode(self::INSTAGRAM_API), $match[1]));
            } catch (ClientException $e) {
                return '';
            }

            $attrs['json'] = $response->json();
            $attrs['url']  = sprintf(self::INSTAGRAM_URL, $match[1]);

            return $that->renderer->render($that->template, $attrs);
        };
    }
}
