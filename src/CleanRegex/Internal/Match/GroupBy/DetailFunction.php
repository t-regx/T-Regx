<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Match\Details\Detail;

interface DetailFunction
{
    public function apply(Detail $detail);
}
