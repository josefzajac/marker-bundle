<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker\InjectRule;

/**
 * Rule Interface - defines Injection marker callback.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
interface RuleInterface
{
    public function apply($content);
}
