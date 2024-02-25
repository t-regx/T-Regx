<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\AutoCapture\AutoCapture;
use TRegx\CleanRegex\Internal\Prepared\Clusters;
use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Orthography;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\AtomicGroup;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\NonCaptureGroup;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\AlterationGroup;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\MaskFigure;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\PatternFigure;

/**
 * @deprecated
 */
class TemplateBuilder
{
    /** @var AutoCapture */
    private $autoCapture;
    /** @var Orthography */
    private $orthography;
    /** @var Clusters */
    private $clusters;

    public function __construct(AutoCapture $autoCapture, Orthography $orthography, Clusters $clusters)
    {
        $this->autoCapture = $autoCapture;
        $this->orthography = $orthography;
        $this->clusters = $clusters;
    }

    /**
     * @deprecated
     */
    public function mask(string $mask, array $keywords): TemplateBuilder
    {
        return $this->next(new NonCaptureGroup(new MaskFigure($this->autoCapture, $mask, $this->orthography->flags(), $keywords)));
    }

    /**
     * @deprecated
     */
    public function literal(string $text): TemplateBuilder
    {
        return $this->next(new AtomicGroup($text));
    }

    /**
     * @deprecated
     */
    public function alteration(array $figures): TemplateBuilder
    {
        return $this->next(new AlterationGroup($figures));
    }

    /**
     * @deprecated
     */
    public function pattern(string $pattern): TemplateBuilder
    {
        return $this->next(new NonCaptureGroup(new PatternFigure($this->autoCapture, $pattern)));
    }

    private function next(Cluster $cluster): TemplateBuilder
    {
        return new TemplateBuilder($this->autoCapture, $this->orthography, $this->clusters->next($cluster));
    }

    /**
     * @deprecated
     */
    public function build(): Pattern
    {
        return new Pattern(new Template($this->autoCapture, $this->orthography->spelling($this->clusters), $this->clusters->clusters()));
    }
}
