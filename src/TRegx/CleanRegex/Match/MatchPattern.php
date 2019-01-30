<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\TestMatchesPattern;
use TRegx\SafeRegex\preg;

class MatchPattern extends AbstractMatchPattern
{
    /** @var InternalPattern */
    private $pattern;
    /** @var string */
    private $subject;

    public function __construct(InternalPattern $pattern, string $subject)
    {
        parent::__construct(new ApiBase($pattern, $subject, new UserData()));
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    public function test(): bool
    {
        return (new TestMatchesPattern($this->base->getPattern(), $this->base))->test();
    }

    public function count(): int
    {
        return preg::match_all($this->pattern->pattern, $this->subject);
    }
}
