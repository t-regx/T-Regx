<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;

interface GroupReplace
{
    public function orElseThrow(string $exceptionClassName = GroupNotMatchedException::class): string;

    public function orElseIgnore(): string;

    public function orElseEmpty(): string;

    public function orElseWith(string $replacement): string;

    public function orElseCalling(callable $replacementProducer): string;
}
