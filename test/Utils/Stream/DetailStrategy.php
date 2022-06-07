<?php
namespace Test\Utils\Stream;

use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\MatchPattern;

abstract class DetailStrategy
{
    public abstract function first(MatchPattern $match): Detail;

    public static function useFirst(): DetailStrategy
    {
        return new class extends DetailStrategy {
            public function first(MatchPattern $match): Detail
            {
                return $match->first();
            }
        };
    }

    public static function useMap(): DetailStrategy
    {
        return new class extends DetailStrategy {
            public function first(MatchPattern $match): Detail
            {
                [$detail] = $match->map(Functions::identity());
                return $detail;
            }
        };
    }
}
