<?php
namespace Test\Fakes\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Subject;

class ThrowSubject implements Subject
{
    public function getSubject(): string
    {
        throw new \Exception("Subject wasn't expected to be used");
    }
}