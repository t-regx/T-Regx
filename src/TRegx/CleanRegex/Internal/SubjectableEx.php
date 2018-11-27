<?php
namespace TRegx\CleanRegex\Internal;

class SubjectableEx implements Subjectable
{
    public function getSubject(): string
    {
        throw new \Exception();
    }
}
