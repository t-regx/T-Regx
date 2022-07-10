<?php
namespace TRegx\CleanRegex\Internal\Prepared\Placeholders;

use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;

class LiteralPlaceholders implements Placeholders
{
    public function consumer(): PlaceholderConsumer
    {
        return new LiteralPlaceholderConsumer();
    }

    public function meetExpectation(): void
    {
    }
}
