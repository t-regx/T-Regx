<?php
namespace TRegx\SafeRegex\Exception;

use TRegx\Exception\MalformedPatternException;

class PregMalformedPatternException extends CompilePregException implements MalformedPatternException
{
}
