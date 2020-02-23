<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\NoFirstElementFluentMessage;

class NoFirstElementFluentException extends CleanRegexException
{
    public function __construct()
    {
        parent::__construct((new NoFirstElementFluentMessage())->getMessage());
    }
}
