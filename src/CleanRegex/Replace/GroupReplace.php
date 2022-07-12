<?php
namespace TRegx\CleanRegex\Replace;

/**
 * @deprecated
 */
interface GroupReplace
{
    /**
     * @deprecated
     */
    public function orElseThrow(\Throwable $throwable = null): string;

    /**
     * @deprecated
     */
    public function orElseIgnore(): string;

    /**
     * @deprecated
     */
    public function orElseEmpty(): string;

    /**
     * @deprecated
     */
    public function orElseWith(string $replacement): string;

    /**
     * @deprecated
     */
    public function orElseCalling(callable $replacementProducer): string;
}
