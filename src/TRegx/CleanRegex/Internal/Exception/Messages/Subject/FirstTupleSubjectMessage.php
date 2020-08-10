<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Subject;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;

class FirstTupleSubjectMessage implements NotMatchedMessage
{
    /** @var string|int */
    private $nameOrIndex1;
    /** @var string|int */
    private $nameOrIndex2;

    public function __construct($nameOrIndex1, $nameOrIndex2)
    {
        $this->nameOrIndex1 = $nameOrIndex1;
        $this->nameOrIndex2 = $nameOrIndex2;
    }

    public function getMessage(): string
    {
        return "Expected to get a tuple of groups '$this->nameOrIndex1' and '$this->nameOrIndex2' from the first match, "
            . "but subject was not matched at all";
    }
}
