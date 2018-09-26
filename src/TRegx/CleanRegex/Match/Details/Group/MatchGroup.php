<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Match\ForFirst\Optional;

interface MatchGroup extends Optional
{
    public function text(): string;

    public function matches(): bool;

    public function name(): ?string;

    public function index(): int;

    public function offset(): int;

    public function all();

    public function __toString(): string;
}
