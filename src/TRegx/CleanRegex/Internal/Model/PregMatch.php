<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\SafeRegex\preg;

class PregMatch
{
    /** @var array */
    private $match;
    /** @var int */
    private $count;

    public function __construct(array $match, int $count)
    {
        $this->match = $match;
        $this->count = $count;
    }

    public static function from(string $pattern, string $subject): self
    {
        $count = preg::match($pattern, $subject, $match);
        return new self($match, $count);
    }
}
