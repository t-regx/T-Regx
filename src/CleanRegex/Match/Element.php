<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\Match\Intable;

/**
 * @deprecated
 */
interface Element extends Intable
{
    public function text(): string;

    /**
     * @deprecated
     */
    public function toInt(int $base = 10): int;

    /**
     * @deprecated
     */
    public function isInt(int $base = 10): bool;

    /**
     * @deprecated
     */
    public function offset(): int;

    /**
     * @deprecated
     */
    public function tail(): int;

    /**
     * @deprecated
     */
    public function length(): int;

    /**
     * @deprecated
     */
    public function byteOffset(): int;

    /**
     * @deprecated
     */
    public function byteTail(): int;

    /**
     * @deprecated 
     */
    public function byteLength(): int;

    public function subject(): string;
}
