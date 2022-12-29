<?php
namespace Test\Fakes\CleanRegex\Internal\Prepared\Parser\Consumer;

use Test\Utils\Assertion\Fails;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;

class ThrowPlaceholderConsumer implements PlaceholderConsumer
{
    use Fails;

    public function consume(EntitySequence $entities): void
    {
        throw $this->fail();
    }
}
