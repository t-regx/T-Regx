<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Match\Details\ReplaceDetail;

interface ReplaceCallbackArgumentStrategy
{
    public function mapArgument(ReplaceDetail $detail);
}
