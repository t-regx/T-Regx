<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Prepared\Cluster\IndividualCluster;
use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Orthography;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\FigureCluster;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\AlterationFigure;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\LiteralFigure;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\MaskFigure;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\PatternFigure;
use TRegx\CleanRegex\Pattern;

class PatternTemplate
{
    /** @var Orthography */
    private $orthography;

    public function __construct(Orthography $orthography)
    {
        $this->orthography = $orthography;
    }

    public function mask(string $mask, array $keywords): Pattern
    {
        return $this->template(new FigureCluster(new MaskFigure($mask, $keywords)));
    }

    public function literal(string $text): Pattern
    {
        return $this->template(new FigureCluster(new LiteralFigure($text)));
    }

    public function alteration(array $figures): Pattern
    {
        return $this->template(new FigureCluster(new AlterationFigure($figures)));
    }

    public function pattern(string $pattern): Pattern
    {
        return $this->template(new FigureCluster(new PatternFigure($pattern)));
    }

    private function template(Cluster $cluster): Pattern
    {
        return new Pattern(new Template($this->orthography->spelling($cluster), new IndividualCluster($cluster)));
    }
}
