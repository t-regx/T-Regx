<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\NoFirstElementFluentMessage;
use TRegx\CleanRegex\Exception\CleanRegexException;

class NoFirstElementFluentException extends CleanRegexException
{
    public function __construct()
    {
        parent::__construct((new NoFirstElementFluentMessage())->getMessage());
    }
}
