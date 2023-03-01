<?php
namespace TRegx\CleanRegex\Internal\Prepared\Cluster;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class PhantomPhrase implements Phrase
{
    public function conjugated(string $delimiter): string
    {
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    public function unconjugated(string $delimiter): string
    {
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }
}
