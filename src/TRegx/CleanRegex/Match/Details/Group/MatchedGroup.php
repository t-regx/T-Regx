<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;

class MatchedGroup extends AbstractMatchGroup
{
    /** @var null|string */
    private $name;
    /** @var int */
    private $index;
    /** @var string */
    private $match;
    /** @var int */
    private $offset;

    public function __construct(?string $name, int $index, string $match, int $offset, array $matches)
    {
        parent::__construct($matches, $index);
        $this->name = $name;
        $this->index = $index;
        $this->match = $match;
        $this->offset = $offset;
    }

    public function text(): string
    {
        return $this->match;
    }

    public function matches(): bool
    {
        return true;
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
        return $this->offset;
    }

    public function __toString(): string
    {
        return $this->text();
    }

    /**
     * @param string $exceptionClassName
     * @return mixed
     * @throws \Throwable|SubjectNotMatchedException
     */
    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class): string
    {
        return $this->text();
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    public function orReturn($default): string
    {
        return $this->text();
    }

    /**
     * @param callable $producer
     * @return mixed
     */
    public function orElse(callable $producer): string
    {
        return $this->text();
    }
}
