<?php
namespace TRegx\CleanRegex\Exception;

class FocusGroupNotMatchedException extends GroupNotMatchedException
{
    public function __construct($subject, $nameOrIndex)
    {
        parent::__construct("Expected to replace focused group '$nameOrIndex', but the group was not matched", $subject, $nameOrIndex);
    }
}
