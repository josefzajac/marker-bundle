<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * TweetMarker.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class TweetMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[tweet (.+)\]/miU';

    const TWITTER_API = 'https://api.twitter.com/1/statuses/oembed.json?%s';

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
            $attributes = $that->extractData($match);

            try {
                $response = $this->client->get(sprintf(self::TWITTER_API, http_build_query($attributes)));
            } catch (ClientException $e) {
                return '';
            }

            $attributes['json'] = $response->json();

            return $that->renderer->render($that->template, $attributes);
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
        $match[1] = trim(strip_tags($match[1]));

        //convert the non prefixed url variable to a prefixed variable
        $attrs = explode(' ', $match[1]);
        foreach ($attrs as $k => $attr) {
            if (false !== filter_var($attr, FILTER_VALIDATE_URL)) {
                $attrs[$k] = 'url="' . $attr . '"';
                break;
            }
        }

        $attrs = new \SimpleXMLElement('<element ' . str_replace(' /', '', implode(' ', $attrs)) . '/>');

        $attributes = [];
        foreach ($attrs->attributes() as $name => $value) {
            $attributes[$name] = (string) $value;
        }

        return $attributes;
    }
}
