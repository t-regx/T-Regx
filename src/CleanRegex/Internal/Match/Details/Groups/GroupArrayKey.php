<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Groups;

/**
 * @template T of int|string
 */
interface GroupArrayKey
{
    /**
     * @param mixed $nameOrIndex
     * @phpstan-assert-if-true T $nameOrIndex
     */
    public function applies($nameOrIndex): bool;

    /**
     * @param T $nameOrIndex
     * @return T
     */
    public function key($nameOrIndex);
}
