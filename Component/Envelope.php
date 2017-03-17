<?php

namespace S2Content\Bundle\MarkerBundle\Component;

use Curved\Model\Cms\Article;

/**
 * Envelope information for article content.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
class Envelope
{
    private $article;
    private $data = [];
    private $content;
    private $contains = [];

    public function setArticle(Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    public function setData($alias, $value)
    {
        if (!isset($this->data[$alias])) {
            $this->data[$alias] = [];
        }

        $this->data[$alias][] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    public function addContains($alias)
    {
        if (!isset($this->contains[$alias])) {
            $this->contains[$alias] = 0;
        }
        $this->contains[$alias]++;

        return $this;
    }

    /**
     * @return array
     */
    public function getContains()
    {
        return $this->contains;
    }
}
