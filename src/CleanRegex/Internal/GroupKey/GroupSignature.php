<?php
namespace TRegx\CleanRegex\Internal\GroupKey;

class GroupSignature
{
    /** @var int */
    private $index;
    /** @var string|null */
    private $name;

    public function __construct(int $index, ?string $name)
    {
        $this->index = $index;
        $this->name = $name;
    }

    public function index(): int
    {
        return $this->index;
    }

    public function name(): ?string
    {
        return $this->name;
    }
}
