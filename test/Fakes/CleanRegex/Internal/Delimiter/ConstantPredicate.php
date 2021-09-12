<?php
namespace Test\Fakes\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Internal\Delimiter\PcreDelimiterPredicate;

class ConstantPredicate extends PcreDelimiterPredicate
{
    /** @var bool */
    private $result;

    public function __construct(bool $result)
    {
        $this->result = $result;
    }

    public function test(string $delimiter): bool
    {
        return $this->result;
    }
}
