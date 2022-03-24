<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Match\Details\Detail;

class OffsetFunction implements DetailFunction
{
    public function apply(Detail $detail): int
    {
        return $detail->offset();
    }
}
