<?php
namespace TRegx\CleanRegex\Internal\AutoCapture;

use TRegx\CleanRegex\Internal\AutoCapture\Group\GroupAutoCapture;
use TRegx\CleanRegex\Internal\AutoCapture\Pattern\PatternAutoCapture;

interface AutoCapture extends PatternAutoCapture, GroupAutoCapture
{
}
