<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

interface GroupEntries
{
    public function groupTexts(): array;

    public function groupOffsets(): array;
}
