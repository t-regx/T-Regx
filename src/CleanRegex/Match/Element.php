<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\Match\Intable;

interface Element extends Intable
{
    public function text(): string;

    public function toInt(int $base = 10): int;

    public function isInt(int $base = 10): bool;

    public function offset(): int;

    public function tail(): int;

    public function length(): int;

    public function byteOffset(): int;

    public function byteTail(): int;

    public function byteLength(): int;

    public function subject(): string;
}
