<?php
namespace TRegx\CleanRegex\Match;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\SafeRegex\preg;
use function array_slice;

class MatchOnly
{
    private const GROUP_WHOLE_MATCH = 0;

    /** @var int */
    private $limit;
    /** @var string */
    private $subject;
    /** @var Pattern */
    private $pattern;

    public function __construct(Pattern $pattern, string $subject, int $limit)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
    }

    /**
     * @return (string|null)[]
     */
    public function get(): array
    {
        if ($this->limit < 0) {
            throw new InvalidArgumentException("Negative limit $this->limit");
        }
        if ($this->limit === 0) {
            $this->validatePattern();
            return [];
        }
        if ($this->limit === 1) {
            return $this->getOneMatch();
        }
        return $this->getSlicedAll();
    }

    private function validatePattern(): void
    {
        preg::match($this->pattern->pattern, '');
    }

    /**
     * @return (string|null)[]
     */
    private function getOneMatch(): array
    {
        preg::match($this->pattern->pattern, $this->subject, $matches);
        if (empty($matches)) {
            return [];
        }
        $match = $matches[self::GROUP_WHOLE_MATCH];
        return [$match];
    }

    /**
     * @return (string|null)[]
     */
    private function getSlicedAll(): array
    {
        return array_slice($this->getAll(), 0, $this->limit);
    }

    private function getAll(): array
    {
        preg::match_all($this->pattern->pattern, $this->subject, $matches);
        return $matches[self::GROUP_WHOLE_MATCH];
    }
}
