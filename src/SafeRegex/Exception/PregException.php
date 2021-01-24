<?php
namespace TRegx\SafeRegex\Exception;

use TRegx\Exception\RegexException;

interface PregException extends RegexException
{
    public function getInvokingMethod(): string;

    /**
     * @return string|string[]
     */
    public function getPregPattern();
}
