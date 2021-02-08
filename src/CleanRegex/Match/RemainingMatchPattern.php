<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Base\IgnoreBaseDecorator;
use TRegx\CleanRegex\Internal\Match\MethodPredicate;

class RemainingMatchPattern extends AbstractMatchPattern
{
    /** @var ApiBase */
    private $originalBase;

    public function __construct(IgnoreBaseDecorator $base, Base $original)
    {
        parent::__construct($base);
        $this->originalBase = $original;
    }

    public function test(): bool
    {
        return !empty($this->getDetailObjects());
    }

    public function count(): int
    {
        return \count($this->getDetailObjects());
    }

    public function remaining(callable $predicate): RemainingMatchPattern
    {
        return new RemainingMatchPattern(
            new IgnoreBaseDecorator($this->base, new MethodPredicate($predicate, 'remaining')),
            $this->originalBase);
    }
}
