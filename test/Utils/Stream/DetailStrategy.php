<?php
namespace Test\Utils\Stream;

use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Matcher;

abstract class DetailStrategy
{
    public abstract function first(Matcher $matcher): Detail;

    public static function useFirst(): DetailStrategy
    {
        return new class extends DetailStrategy {
            public function first(Matcher $matcher): Detail
            {
                return $matcher->first();
            }
        };
    }

    public static function useMap(): DetailStrategy
    {
        return new class extends DetailStrategy {
            public function first(Matcher $matcher): Detail
            {
                [$detail] = $matcher->map(Functions::identity());
                return $detail;
            }
        };
    }
}
