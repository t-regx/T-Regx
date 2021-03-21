<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Subjectable;

class ThrowSubject implements Subjectable
{
    public function getSubject(): string
    {
        throw new \Exception("Subject wasn't expected to be used");
    }
}
