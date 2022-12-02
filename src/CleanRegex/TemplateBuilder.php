<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\Prepared\Clusters;
use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Orthography;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\AtomicGroup;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\NonCaptureGroup;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\AlterationFigure;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\LiteralFigure;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\MaskFigure;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\PatternFigure;

class TemplateBuilder
{
    /** @var Orthography */
    private $orthography;
    /** @var Clusters */
    private $clusters;

    public function __construct(Orthography $orthography, Clusters $clusters)
    {
        $this->orthography = $orthography;
        $this->clusters = $clusters;
    }

    public function mask(string $mask, array $keywords): TemplateBuilder
    {
        return $this->next(new NonCaptureGroup(new MaskFigure($mask, $this->orthography->flags(), $keywords)));
    }

    public function literal(string $text): TemplateBuilder
    {
        return $this->next(new AtomicGroup(new LiteralFigure($text)));
    }

    public function alteration(array $figures): TemplateBuilder
    {
        return $this->next(new NonCaptureGroup(new AlterationFigure($figures)));
    }

    public function pattern(string $pattern): TemplateBuilder
    {
        return $this->next(new NonCaptureGroup(new PatternFigure($pattern)));
    }

    private function next(Cluster $cluster): TemplateBuilder
    {
        return new TemplateBuilder($this->orthography, $this->clusters->next($cluster));
    }

    public function build(): Pattern
    {
        return new Pattern(new Template($this->orthography->spelling($this->clusters->condition()), $this->clusters->clusters()));
    }
}
