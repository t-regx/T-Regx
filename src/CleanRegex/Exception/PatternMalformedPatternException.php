<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\Exception\MalformedPatternException;

class PatternMalformedPatternException extends \RuntimeException implements PatternException, MalformedPatternException
{
}
