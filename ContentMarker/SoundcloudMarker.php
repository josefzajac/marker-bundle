<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;

/**
 * Soundcloud, replaces [soundcloud *] within strings with a rendered twig file.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class SoundcloudMarker extends AbstractNodeMarker implements ContentMarkerInterface
{
    const PATTERN = '/\[soundcloud (.+)\]/miU';

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
            $attrs = $this->extractData($match);

            return $that->renderer->render($that->template, $attrs);
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
        $attrs = new \SimpleXMLElement('<element ' . str_replace([' /', '&amp;', '&'], ['', '&', '&amp;'], $match[1]) . '/>');

        $attributes = [
            'url'     => '',
            'trackid' => '',
            'isTrack' => '',
        ];
        foreach ($attrs->attributes() as $name => $value) {
            $attributes[$name] = (string) $value;
        }

        $tmp                   = explode('/', parse_url($attributes['url'], PHP_URL_PATH));
        $attributes['trackid'] = end($tmp);
        $attributes['isTrack'] = $tmp[1] === 'tracks';

        return $attributes;
    }
}
