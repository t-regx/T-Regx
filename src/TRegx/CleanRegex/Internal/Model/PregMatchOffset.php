<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\SafeRegex\preg;

class PregMatchOffset
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
        $count = preg::match($pattern, $subject, $match, self::flags());
        return new self($match, $count);
    }

    private static function flags(): int
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            return PREG_UNMATCHED_AS_NULL | PREG_OFFSET_CAPTURE;
        }
        return PREG_OFFSET_CAPTURE;
    }
}
