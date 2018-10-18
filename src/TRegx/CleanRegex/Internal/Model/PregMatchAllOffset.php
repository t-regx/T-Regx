<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\SafeRegex\preg;

class PregMatchAllOffset
{
    /** @var array */
    private $matches;
    /** @var int */
    private $count;

    public function __construct(array $matches, int $count)
    {
        $this->matches = $matches;
        $this->count = $count;
    }

    public static function from(string $pattern, string $subject): self
    {
        $count = preg::match_all($pattern, $subject, $matches, self::flags());
        return new self($matches, $count);
    }

    private static function flags(): int
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            return PREG_UNMATCHED_AS_NULL | PREG_OFFSET_CAPTURE;
        }
        return PREG_OFFSET_CAPTURE;
    }
}
