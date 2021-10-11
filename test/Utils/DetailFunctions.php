<?php
namespace Test\Utils;

use TRegx\CleanRegex\Match\Details\Detail;

class DetailFunctions
{
    public static function text(): callable
    {
        return static function (Detail $detail): string {
            return $detail->text();
        };
    }
}
