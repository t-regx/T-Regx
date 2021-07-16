<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Subject;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\GroupFormat;

class FirstTripleSubjectMessage implements NotMatchedMessage
{
    /** @var string */
    private $group1;
    /** @var string */
    private $group2;
    /** @var string */
    private $group3;

    public function __construct($nameOrIndex1, $nameOrIndex2, $nameOrIndex3)
    {
        $this->group1 = GroupFormat::group($nameOrIndex1);
        $this->group2 = GroupFormat::group($nameOrIndex2);
        $this->group3 = GroupFormat::group($nameOrIndex3);
    }

    public function getMessage(): string
    {
        return "Expected to get a triple of groups $this->group1, $this->group2 and $this->group3 from the first match, but subject was not matched at all";
    }
}
