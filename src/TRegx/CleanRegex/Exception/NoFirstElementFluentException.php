<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Exception\Messages\NoFirstElementFluentMessage;

class NoFirstElementFluentException extends CleanRegexException
{
    public function __construct()
    {
        parent::__construct((new NoFirstElementFluentMessage())->getMessage());
    }
}
