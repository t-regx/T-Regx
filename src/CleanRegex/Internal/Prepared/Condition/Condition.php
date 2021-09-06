<?php
namespace TRegx\CleanRegex\Internal\Prepared\Condition;

/**
 * Dictionary definition
 * <i>Condition</i> - the state of something with regard to its appearance, quality, or working order
 */
interface Condition
{
    public function suitable(string $candidate): bool;
}
