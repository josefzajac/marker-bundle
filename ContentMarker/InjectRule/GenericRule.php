<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker\InjectRule;

/**
 * Generic Rule - defines Injection marker callback.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class GenericRule implements RuleInterface
{
    private $alias;
    private $match;
    private $counter;
    private $content;
    private $position;

    public function __construct($alias, $match, $counter, $content, $position = 'after')
    {
        $this->alias    = $alias;
        $this->match    = $match;
        $this->counter  = $counter;
        $this->content  = $content;
        $this->position = $position;
    }

    public function apply($content)
    {
        $that = $this;

        $i = 0;

        return preg_replace_callback(sprintf("|%s|", preg_quote($this->match)), function ($match) use ($that, &$i) {
            return ++$i === (int) $that->counter ? $that->inject() : $that->match;
        }, $content);
    }

    private function inject()
    {
        switch ($this->position) {
            case 'before' : return $this->content . $this->match;
            case 'after'  : return $this->match . $this->content;
            default:
                return $this->content;
        }
    }

    public function getAlias()
    {
        return $this->alias;
    }
}
