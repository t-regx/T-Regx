<?php
namespace TRegx\SafeRegex\Exception;

use TRegx\Exception\RegexException;

/**
 * @deprecated
 */
interface PregException extends RegexException
{
    public function getInvokingMethod(): string;

    /**
     * @return string|string[]
     */
    public function getPregPattern();
}
