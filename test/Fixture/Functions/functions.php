<?php
namespace Test\Fixture\Functions;

use Test\Fixture\Exception\ThrowExpectation;

function catching(callable $block): ThrowExpectation
{
    return new ThrowExpectation($block);
}
