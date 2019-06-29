<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

use Exception;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\NoFirstElementFluentMessage;

class NoFirstElementFluentException extends Exception
{
    public function __construct()
    {
        parent::__construct((new NoFirstElementFluentMessage())->getMessage());
    }
}
