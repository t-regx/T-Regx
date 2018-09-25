<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\GroupMessage;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\UnknownSignatureExceptionFactory;
use TRegx\CleanRegex\Match\Details\NotMatched;

class NotMatchedGroup implements MatchGroup
{
    /** @var string */
    private $subject;
    /** @var string|int */
    private $group;
    /** @var null|string */
    private $name;
    /** @var int */
    private $index;
    /** @var array */
    private $matches;

    public function __construct(?string $name, int $index, $group, string $subject, array $matches)
    {
        $this->subject = $subject;
        $this->group = $group;
        $this->name = $name;
        $this->index = $index;
        $this->matches = $matches;
    }

    public function text(): string
    {
        throw $this->groupNotMatched('text');
    }

    public function matches(): bool
    {
        return false;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function index(): int
    {
        return $this->index;
    }

    public function offset(): int
    {
        throw $this->groupNotMatched('offset');
    }

    public function __toString(): string
    {
        return '';
    }

    /**
     * @param string $exceptionClassName
     * @return mixed
     * @throws \Throwable|SubjectNotMatchedException
     */
    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class)
    {
        throw (new UnknownSignatureExceptionFactory($exceptionClassName, new GroupMessage($this->group)))->create($this->subject);
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    public function orReturn($default)
    {
        return $default;
    }

    /**
     * @param callable $producer
     * @return mixed
     */
    public function orElse(callable $producer)
    {
        return call_user_func($producer, new NotMatched($this->matches, $this->subject));
    }

    private function groupNotMatched(string $method): GroupNotMatchedException
    {
        return GroupNotMatchedException::forMethod($this->subject, $this->group, $method);
    }
}
