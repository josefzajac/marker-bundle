## Installation

Add the custom Repository to your `composer.json` (until we have a [Composer Repository Manager](https://getcomposer.org/doc/articles/handling-private-packages-with-satis.md))

```json
    "repositories": [
        {
            "type": "vcs",
            "url" : "https://git.sinnerschrader.com/s2content/marker-bundle.git"
        }
    ]
```

then require via composer
```bash
portal\$ php -d memory_limit=-1 composer.phar require s2content/marker-bundle:master -vv
```
OR add to `composer.json`
```json
    "require": {
        "s2content/marker-bundle" : "dev-master"
    }
```

install the Bundle in your `Symfony Kernel`

```php
<?php

// app/AppKernel.php

    public function registerBundles()
    {
        $bundles = [
            new S2Content\Bundle\MarkerBundle\S2ContentMarkerBundle()
        ];

        return $bundles;
    }
```

and register marker config

```xml
    - { resource: marker.xml }
```

## Configuration

```xml
<services>
    <!-- IFrame Marker -->
    <service id="s2_content_marker.marker.iframe" class="S2Content\Bundle\MarkerBundle\ContentMarker\IframeMarker">
        <!-- USE bundle template -->
        <argument type="string">S2ContentMarkerBundle:Marker:Markers/iframe/iframe.{{context}}.twig</argument>
        <!-- OR use project template -->
        <argument type="string">CurvedCmsBundle:Marker:Markers/iframe/iframe.{{context}}.twig</argument>
        <argument type="collection">
            <argument type="string">swipe</argument>
            <argument type="string">amp</argument>
            <argument type="string">rss</argument>
            <argument type="string">facebook</argument>
            <argument type="string">flipboard</argument>
            <argument type="string">html</argument>
        </argument>
        <tag name="content_marker" />
    </service>
```

## Usage in templates
```html
Returns replaced content

{{ content|marker_replace(post, 'amp') }}
{{ content|marker_replace(post, 'html') }}

Returns information what markers are used in content, handy for inlude eq. youtube.js only if needed
{{ content|marker_replace_contains() }}

Returns information from markers, eq what Youtube code is used
{{ content|get_marker_data(article) }}

```

# Examples:
## Source
```html
$content = 'Testing article with video [youtube id="lX8szUWpfjk"]';
```

## Outputs
- HTML
```html
Testing article with video
<div class="video video--youtube">
    <iframe seamless="seamless" allowfullscreen="allowfullscreen" src="//www.youtube.com/embed/lX8szUWpfjkhtml5=1&amp;rel=0&amp;hd=1&amp;quality=hd720&amp;fs=1"></iframe>
</div>
```
- RSS (rss doesn't support youtube iframes, but we can load video title via youtube-api OR cleanup callback will erase marker)
```html
Testing article with video
(AMP Conf: Day 1 Live Stream)
```
- Flipboard feed
```html
Testing article with video
<figure>
    <iframe src="//www.youtube.com/embed/lX8szUWpfjk?html5=1&amp;rel=0&amp;hd=1&amp;quality=hd720&amp;fs=1"></iframe>
</figure>
```
- AMP (https://www.ampproject.org/)
```html
Testing article with video
<section class="mod-newsvideo">
    <amp-youtube data-videoid="lX8szUWpfjk" layout="responsive" width="480" height="270"></amp-youtube>
</section>
```
- Facebook Instant Articles
```html
Testing article with video
<figure class="op-interactive">
    <iframe src="//www.youtube.com/embed/lX8szUWpfjkhtml5=1&amp;rel=0&amp;hd=1&amp;quality=hd720&amp;fs=1" width="560" height="315"></iframe>
</figure>
```



## Tests

`$ vendor/bin/phpspec run -fpretty`
