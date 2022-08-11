<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Match\Detail;

interface ReplaceCallbackArgumentStrategy
{
    public function mapArgument(Detail $detail);
}
