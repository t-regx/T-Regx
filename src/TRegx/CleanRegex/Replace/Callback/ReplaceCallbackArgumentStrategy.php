<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Match\Details\ReplaceMatch;

interface ReplaceCallbackArgumentStrategy
{
    public function mapArgument(ReplaceMatch $match);
}
