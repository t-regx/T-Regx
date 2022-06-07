<?php
namespace Test\Utils\Structure;

use TRegx\CleanRegex\Match\Details\Detail;

interface Expectation
{
    public function apply(Detail $detail): void;
}
