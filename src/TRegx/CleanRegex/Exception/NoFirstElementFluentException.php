<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Exception\Messages\NoFirstElementFluentMessage;

class NoFirstElementFluentException extends PatternException
{
    public function __construct()
    {
        parent::__construct((new NoFirstElementFluentMessage())->getMessage());
    }
}
