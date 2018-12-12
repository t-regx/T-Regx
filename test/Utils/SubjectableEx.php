<?php
namespace Test\Utils;

use TRegx\CleanRegex\Internal\Subjectable;

class SubjectableEx implements Subjectable
{
    public function getSubject(): string
    {
        throw new \Exception();
    }
}
