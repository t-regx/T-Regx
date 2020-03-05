<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Match\FindFirst\Optional;

interface MatchGroup extends Optional
{
    public function text(): string;

    public function textLength(): int;

    public function toInt(): int;

    public function isInt(): bool;

    public function matched(): bool;

    public function name(): ?string;

    public function index(): int;

    /**
     * @return int|string
     */
    public function usedIdentifier();

    public function offset(): int;

    public function byteOffset(): int;

    public function replace(string $replacement): string;

    public function all(): array;

    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class);
}
