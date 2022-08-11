<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Match\Detail;

class MatchStrategy implements ReplaceCallbackArgumentStrategy
{
    public function mapArgument(Detail $detail): Detail
    {
        return $detail;
    }
}
