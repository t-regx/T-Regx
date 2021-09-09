<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Internal\Delimiter\PcreDelimiterPredicate;

class AcceptPredicate extends PcreDelimiterPredicate
{
    /** @var string */
    private $accept;
    /** @var bool */
    private $result;

    public function __construct(string $accept, bool $result)
    {
        $this->accept = $accept;
        $this->result = $result;
    }

    public function test(string $delimiter): bool
    {
        if ($delimiter === $this->accept) {
            return $this->result;
        }
        throw new AssertionError("Failed to assert that PcreDelimiterPredicate was called with accepted argument");
    }
}
