<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\CleanRegex\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitFactory;
use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\ForFirst\MatchedOptional;
use TRegx\CleanRegex\Match\ForFirst\NotMatchedOptional;
use TRegx\CleanRegex\Match\ForFirst\Optional;
use TRegx\CleanRegex\Match\Offset\MatchOffsetLimit;
use TRegx\CleanRegex\MatchesPattern;
use TRegx\SafeRegex\Guard\Arrays;
use TRegx\SafeRegex\preg;
use function is_array;

class MatchPattern implements PatternLimit
{
    private const GROUP_WHOLE_MATCH = 0;
    private const FIRST_MATCH = 0;

    /** @var Pattern */
    private $pattern;
    /** @var string */
    private $subject;

    public function __construct(Pattern $pattern, string $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    public function matches(): bool
    {
        return (new MatchesPattern($this->pattern, $this->subject))->matches();
    }

    public function fails(): bool
    {
        return (new MatchesPattern($this->pattern, $this->subject))->fails();
    }

    public function all(): array
    {
        preg::match_all($this->pattern->pattern, $this->subject, $matches);
        return $matches[self::GROUP_WHOLE_MATCH];
    }

    /**
     * @param callable|null $callback
     * @return string|mixed
     * @throws SubjectNotMatchedException
     */
    public function first(callable $callback = null)
    {
        $matches = $this->performMatchAll();
        if (empty($matches[self::GROUP_WHOLE_MATCH])) {
            throw SubjectNotMatchedException::forFirst($this->subject);
        }
        if ($callback !== null) {
            return $callback(new Match($this->subject, self::FIRST_MATCH, $matches));
        }
        list($value, $offset) = $matches[self::GROUP_WHOLE_MATCH][self::FIRST_MATCH];
        return $value;
    }

    public function only(int $limit): array
    {
        return (new MatchOnly($this->pattern, $this->subject, $limit))->get();
    }

    public function forEach(callable $callback): void
    {
        foreach ($this->getMatchObjects() as $object) {
            $callback($object);
        }
    }

    public function iterate(callable $callback): void
    {
        $this->forEach($callback);
    }

    public function map(callable $callback): array
    {
        $results = [];
        foreach ($this->getMatchObjects() as $object) {
            $results[] = $callback($object);
        }
        return $results;
    }

    public function flatMap(callable $callback): array
    {
        return (new FlatMapper($this->getMatchObjects(), $callback))->get();
    }

    /**
     * @param callable $callback
     * @return Optional
     */
    public function forFirst(callable $callback): Optional
    {
        $matches = $this->performMatchAll();
        if (empty($matches[self::GROUP_WHOLE_MATCH])) {
            return new NotMatchedOptional(new NotMatchedOptionalWorker(new FirstMatchMessage(), $this->subject, new NotMatched($matches, $this->subject)));
        }
        $result = $callback(new Match($this->subject, self::FIRST_MATCH, $matches));
        return new MatchedOptional($result);
    }

    /**
     * @param string|int $nameOrIndex
     * @return GroupLimit
     */
    public function group($nameOrIndex): GroupLimit
    {
        (new GroupNameValidator($nameOrIndex))->validate();
        return (new GroupLimitFactory($this->pattern, $this->subject, $nameOrIndex))->create();
    }

    public function offsets(): MatchOffsetLimit
    {
        return (new MatchOffsetLimitFactory($this->pattern, $this->subject, 0))->create();
    }

    public function count(): int
    {
        return preg::match_all($this->pattern->pattern, $this->subject);
    }

    /**
     * @return Match[]
     */
    private function getMatchObjects(): array
    {
        return $this->constructMatchObjects($this->performMatchAll());
    }

    /**
     * @param array $matches
     * @return Match[]
     */
    private function constructMatchObjects(array $matches): array
    {
        $matchObjects = [];
        foreach ($matches[self::GROUP_WHOLE_MATCH] as $index => $match) {
            $matchObjects[] = new Match($this->subject, $index, $matches);
        }
        return $matchObjects;
    }

    private function performMatchAll(): array
    {
        preg::match_all($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);
        return $matches;
    }
}
