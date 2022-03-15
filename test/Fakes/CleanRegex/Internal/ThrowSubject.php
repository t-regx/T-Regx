<?php
namespace Test\Fakes\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Subject;

class ThrowSubject extends Subject
{
    public function __construct()
    {
        parent::__construct('');
    }

    public function getSubject(): string
    {
        throw new \Exception("Subject wasn't expected to be used");
    }
}
