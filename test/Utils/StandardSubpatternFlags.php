<?php
namespace Test\Utils;

use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;

trait StandardSubpatternFlags
{
    public function subpatternFlagsStandard(): SubpatternFlags
    {
        return SubpatternFlags::from(new Flags('i'));
    }

    public function subpatternFlagsExtended(): SubpatternFlags
    {
        return SubpatternFlags::from(new Flags('x'));
    }
}
