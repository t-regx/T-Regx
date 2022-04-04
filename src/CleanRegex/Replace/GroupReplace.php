<?php
namespace TRegx\CleanRegex\Replace;

interface GroupReplace
{
    public function orElseThrow(\Throwable $throwable = null): string;

    public function orElseIgnore(): string;

    public function orElseEmpty(): string;

    public function orElseWith(string $replacement): string;

    public function orElseCalling(callable $replacementProducer): string;
}
