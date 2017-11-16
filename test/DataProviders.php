<?php
namespace Test;

class DataProviders
{
    public static function invalidPregPatterns(): array
    {
        return [
            ['/{2,1}/'],
            ['/)unopened.group/'],
            ['/un(closed.group/'],
            ['/*starting.quantifier/'],
            [' /\/'],
            ['/\/'],
            ['/\\/'],
            ['/(/'],
            ['/{1}/'],
        ];
    }
}
