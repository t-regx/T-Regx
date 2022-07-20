<?php
namespace Test\Utils;

use TRegx\CleanRegex\Match\Detail;
use TRegx\CleanRegex\Match\Group;

class DetailFunctions
{
    public static function text(): callable
    {
        return static function (Detail $detail): string {
            return $detail->text();
        };
    }

    public static function index(): callable
    {
        return static function (Detail $detail): int {
            return $detail->index();
        };
    }

    public static function equals(string $text): callable
    {
        return function (Detail $detail) use ($text) {
            return "$detail" === $text;
        };
    }

    public static function notEquals(string $detail): callable
    {
        return function ($match) use ($detail) {
            if ($match instanceof Detail || $match instanceof Group) {
                return $match->text() !== $detail;
            }
            throw new \TypeError();
        };
    }

    public static function collect(?array &$details, $return = null): callable
    {
        return function (Detail $detail) use (&$details, $return) {
            $details[$detail->index()] = $detail->text();
            return $return;
        };
    }

    public static function out(&$argument, $return = null): callable
    {
        $captured = false;
        return function ($capturedArgument) use (&$captured, &$argument, $return) {
            if ($captured) {
                return $return;
            }
            $argument = $capturedArgument;
            $captured = true;
            return $return;
        };
    }

    public static function outLast(?Detail &$detail, $return): callable
    {
        return function (Detail $argument) use (&$detail, $return) {
            $detail = $argument;
            return $return;
        };
    }

    public static function outGroup($nameOrIndex, ?Group &$group, $return): callable
    {
        return function (Detail $detail) use ($nameOrIndex, &$group, $return) {
            $group = $detail->group($nameOrIndex);
            return $return;
        };
    }

    public static function toInt(): callable
    {
        return function (Detail $detail) {
            return $detail->toInt();
        };
    }

    public static function get(string $groupName): callable
    {
        return function (Detail $detail) use ($groupName): string {
            return $detail->get($groupName);
        };
    }

    public static function group(string $groupname): callable
    {
        return function (Detail $detail) use ($groupname): Group {
            return $detail->group($groupname);
        };
    }

    public static function duplicate(): callable
    {
        return function (Detail $detail): array {
            return [$detail->text(), $detail->text()];
        };
    }
}
