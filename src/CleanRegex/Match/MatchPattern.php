<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\MethodPredicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\SafeRegex\preg;

class MatchPattern extends AbstractMatchPattern
{
    /** @var Definition */
    private $definition;
    /** @var string */
    private $subject;

    public function __construct(Definition $definition, string $subject)
    {
        parent::__construct(new ApiBase($definition, $subject, new UserData()));
        $this->definition = $definition;
        $this->subject = $subject;
    }

    public function test(): bool
    {
        return preg::match($this->definition->pattern, $this->base->getSubject()) === 1;
    }

    public function count(): int
    {
        return preg::match_all($this->definition->pattern, $this->subject);
    }

    public function remaining(callable $predicate): RemainingMatchPattern
    {
        return new RemainingMatchPattern(new DetailPredicateBaseDecorator($this->base, new MethodPredicate($predicate, 'remaining')), $this->base);
    }
}
