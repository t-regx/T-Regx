<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

interface ReplaceCallbackArgumentStrategy
{
    public function mapArgument(ReplaceDetail $detail);
}
