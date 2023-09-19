<?php
namespace Test\Fixture\Functions;

use Regex\Detail;
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

function systemErrorHandler(callable $block): void
{
    \set_error_handler(function () {
    });
    try {
        $block();
    } finally {
        \restore_error_handler();
    }
}

function collect(?Detail &$ref, $return): callable
{
    $wasCollected = false;
    return function ($argument) use (&$ref, &$wasCollected, $return) {
        if (!$wasCollected) {
            $ref = $argument;
            $wasCollected = true;
        }
        return $return;
    };
}

function collectLast(?Detail &$ref, $return): callable
{
    return function ($argument) use (&$ref, $return) {
        $ref = $argument;
        return $return;
    };
}

function since(string $phpVersion): bool
{
    return \version_compare(\PHP_VERSION, $phpVersion, '>=');
}
