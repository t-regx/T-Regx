<?php
namespace TRegx\CleanRegex\Exception;

class SubjectNotMatchedException extends \RuntimeException implements PatternException
{
    public function __construct()
    {
        parent::__construct('Expected to get the first match, but subject was not matched');
    }
}
