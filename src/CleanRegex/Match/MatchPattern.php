<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\MethodPredicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\SafeRegex\preg;

class MatchPattern extends AbstractMatchPattern
{
    /** @var ApiBase */
    private $apiBase;
    /** @var InternalPattern */
    private $pattern;
    /** @var string */
    private $subject;

    public function __construct(InternalPattern $pattern, string $subject)
    {
        $this->apiBase = new ApiBase($pattern, $subject, new UserData());
        parent::__construct($this->apiBase);
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    public function test(): bool
    {
        return preg::match($this->pattern->pattern, $this->base->getSubject()) === 1;
    }

    public function count(): int
    {
        return preg::match_all($this->pattern->pattern, $this->subject);
    }

    public function remaining(callable $predicate): RemainingMatchPattern
    {
        return new RemainingMatchPattern(new DetailPredicateBaseDecorator($this->apiBase, new MethodPredicate($predicate, 'remaining')), $this->apiBase);
    }
}
