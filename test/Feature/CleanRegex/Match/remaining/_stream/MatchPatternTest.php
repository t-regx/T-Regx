<?php
namespace Test\Feature\TRegx\CleanRegex\Match\remaining\_stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_offsets_keys_first()
    {
        // given
        $firstKey = pattern('\w+')->match('One Two Three')
            ->remaining(Functions::oneOf(['Two', 'Three']))
            ->offsets()
            ->keys()
            ->first();

        // when
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGet_offsets_keys_all()
    {
        // given
        $keys = pattern('\w+')->match('One Two Three')
            ->remaining(Functions::oneOf(['Two', 'Three']))
            ->offsets()
            ->keys()
            ->all();

        // when
        $this->assertSame([0, 1], $keys);
    }
}
