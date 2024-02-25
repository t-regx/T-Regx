<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\Exception\MalformedPatternException;

/**
 * @deprecated
 */
class PatternMalformedPatternException extends \RuntimeException implements PatternException, MalformedPatternException
{
}
