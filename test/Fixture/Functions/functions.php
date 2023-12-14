<?php
namespace Test\Fixture\Functions;

use Test\Fixture\Exception\ThrowExpectation;

function catching(callable $block): ThrowExpectation
{
    return new ThrowExpectation($block);
}

function systemWarning(callable $block): void
{
    \set_error_handler(null);
    @\trigger_error(\E_USER_WARNING);
    \restore_error_handler();
    try {
        $block();
    } finally {
        \error_clear_last();
    }
}
