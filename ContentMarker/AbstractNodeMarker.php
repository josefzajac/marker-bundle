<?php

namespace S2Content\Bundle\MarkerBundle\ContentMarker;

use Curved\Model\Cms\Article;
use GuzzleHttp\Client;
use S2Content\Bundle\MarkerBundle\Component\Envelope;
use S2Content\Bundle\MarkerBundle\ContentMarker\InjectRule\RuleInterface;
use S2Content\Bundle\MarkerBundle\ContentMarker\Judge\JudgeInterface;
use S2Content\Bundle\MarkerBundle\Render\ContentMarkerInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * AbstractNodeMarker.
 *
 * @author Josef Zajac <josef.zajac@sinnerschrader.com>
 */
abstract class AbstractNodeMarker implements ContentMarkerInterface
{
    /**
     * @var \Twig_Environment
     */
    protected $renderer;

    protected $template;

    /**
     * @var array
     */
    protected $enabledContexts = [];

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * @var Envelope
     */
    protected $envelope;

    /**
     * @var JudgeInterface
     */
    protected $judge;

    /**
     * @var RuleInterface
     */
    protected $rule;

    /**
     * constructor.
     *
     * @param $template
     * @param array $contexts
     */
    public function __construct($template, $enabledContexts)
    {
        $this->template        = $template;
        $this->enabledContexts = $enabledContexts;
        $this->crawler         = new Crawler();
        $this->client          = new Client();
    }

    /**
     * Contexts template fallback
     * usage:
     * - ['amp']
     * - ['amp, 'rss'] -> engine will try load .amp. template, if not exists go for rss
     * - no default fallback.
     *
     * @param string             $content
     * @param null|Article|array $object
     * @param array              $outputContexts
     *
     * @return string
     */
    public function process($content, $object, $outputContexts)
    {
        $fnReplace = $this->getFnReplace($object);
        if (!$this->validateContext($outputContexts)) {
            $fnReplace = $this->getCleanFunction();
        }

        return preg_replace_callback(static::PATTERN, $fnReplace, $content);
    }

    protected function validateContext($outputContexts)
    {
        foreach ($outputContexts as $context) {
            if (in_array($context, $this->enabledContexts, true)) {
                $contextTemplate = str_replace('.{{context}}.', '.' . $context . '.', $this->template);
                if ($this->renderer->getLoader()->exists($contextTemplate)) {
                    $this->template = $contextTemplate;
                    $this->renderer->resolveTemplate($this->template);

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param null|Article|array $object
     *
     * @return callable
     */
    protected function getFnReplace($object)
    {
        return function () {};
    }

    /**
     * @return callable
     */
    protected function getCleanFunction()
    {
        return function () {
            return '';
        };
    }

    /**
     * @param Envelope $envelope
     */
    public function setEnvelope(Envelope $envelope)
    {
        $this->envelope = $envelope;
    }

    public function getEnvelope()
    {
        return $this->envelope;
    }

    /**
     * @param \Twig_Environment $templateEngine
     */
    public function setTemplating(\Twig_Environment $templateEngine)
    {
        $this->renderer = $templateEngine;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        $x = explode('\\', get_class($this));

        return strtolower(substr(end($x), 0, -6));
    }

    /**
     * @param JudgeInterface $judge
     */
    public function setJudge(JudgeInterface $judge)
    {
        $this->judge = $judge;
    }

    /**
     * @param RuleInterface $rule
     */
    public function setRule(RuleInterface $rule)
    {
        $this->rule = $rule;
    }
}
