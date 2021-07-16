<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Subject;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\GroupFormat;

class FirstTupleSubjectMessage implements NotMatchedMessage
{
    /** @var string */
    private $group1;
    /** @var string */
    private $group2;

    public function __construct($nameOrIndex1, $nameOrIndex2)
    {
        $this->group1 = GroupFormat::group($nameOrIndex1);
        $this->group2 = GroupFormat::group($nameOrIndex2);
    }

    public function getMessage(): string
    {
        return "Expected to get a tuple of groups $this->group1 and $this->group2 from the first match, but subject was not matched at all";
    }
}
