<?php
namespace TRegx\CleanRegex\Exception;

class FocusGroupNotMatchedException extends GroupNotMatchedException
{
    public function __construct($subject, $nameOrIndex)
    {
        parent::__construct($this->getExceptionMessage($nameOrIndex), $subject, $nameOrIndex);
    }

    private function getExceptionMessage($nameOrIndex): string
    {
        return "Expected to replace focused group '$nameOrIndex', but the group was not matched";
    }
}
