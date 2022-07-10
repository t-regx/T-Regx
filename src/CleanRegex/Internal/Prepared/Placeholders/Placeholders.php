<?php
namespace TRegx\CleanRegex\Internal\Prepared\Placeholders;

use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;

interface Placeholders
{
    public function consumer(): PlaceholderConsumer;

    public function meetExpectation(): void;
}
