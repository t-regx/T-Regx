<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupFormat;

class FocusGroupNotMatchedException extends GroupNotMatchedException
{
    public function __construct($subject, $nameOrIndex)
    {
        $name = GroupFormat::group($nameOrIndex);
        parent::__construct("Expected to replace focused group $name, but the group was not matched", $subject, $nameOrIndex);
    }
}
