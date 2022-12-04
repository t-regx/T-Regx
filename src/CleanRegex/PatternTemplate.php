<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\AutoCapture\AutoCapture;
use TRegx\CleanRegex\Internal\Prepared\Cluster\IndividualCluster;
use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Orthography;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\AtomicGroup;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\NonCaptureGroup;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\AlterationFigure;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\LiteralFigure;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\MaskFigure;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\PatternFigure;

class PatternTemplate
{
    /** @var AutoCapture */
    private $autoCapture;
    /** @var Orthography */
    private $orthography;

    public function __construct(AutoCapture $autoCapture, Orthography $orthography)
    {
        $this->autoCapture = $autoCapture;
        $this->orthography = $orthography;
    }

    public function mask(string $mask, array $keywords): Pattern
    {
        return $this->template(new NonCaptureGroup(new MaskFigure($this->autoCapture, $mask, $this->orthography->flags(), $keywords)));
    }

    public function literal(string $text): Pattern
    {
        return $this->template(new AtomicGroup(new LiteralFigure($text)));
    }

    public function alteration(array $figures): Pattern
    {
        return $this->template(new NonCaptureGroup(new AlterationFigure($figures)));
    }

    public function pattern(string $pattern): Pattern
    {
        return $this->template(new NonCaptureGroup(new PatternFigure($this->autoCapture, $pattern)));
    }

    private function template(Cluster $cluster): Pattern
    {
        return new Pattern(new Template($this->autoCapture, $this->orthography->spelling($cluster), new IndividualCluster($cluster)));
    }
}
