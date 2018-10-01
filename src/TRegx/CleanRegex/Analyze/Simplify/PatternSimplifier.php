<?php
namespace TRegx\CleanRegex\Analyze\Simplify;

use TRegx\CleanRegex\Analyze\Simplify\Alternative\AlternativeGrouper;
use TRegx\CleanRegex\Analyze\Simplify\Set\SetGrouper;
use TRegx\CleanRegex\Analyze\Simplify\Model\Model;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\PatternVerifier;

class PatternSimplifier
{
    /** @var PatternVerifier */
    private $verifier;

    /** @var SetGrouper */
    private $setGrouper;

    /** @var AlternativeGrouper */
    private $alternativeGrouper;

    public function __construct(InternalPattern $pattern)
    {
        $this->verifier = new PatternVerifier($pattern->pattern);
        $this->setGrouper = new SetGrouper(new QuotesBreaker($pattern->originalPattern), new ModelFactory());
        $this->alternativeGrouper = new AlternativeGrouper();
    }

    public function simplify(): string
    {
        $this->verifier->verify();
        return $this->simplifyPattern();
    }

    private function simplifyPattern(): string
    {
        $models = $this->setGrouper->getGrouped();
        $grouped = $this->alternativeGrouper->getGrouped($models);
        return join($this->getContent($grouped));
    }

    private function getContent(array $broken): array
    {
        return array_map(function (Model $model) {
            return $model->getContent();
        }, $broken);
    }
}
