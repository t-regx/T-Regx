<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Match\Details\Detail;

class TextFunction implements DetailFunction
{
    public function apply(Detail $detail): string
    {
        return $detail->text();
    }
}
