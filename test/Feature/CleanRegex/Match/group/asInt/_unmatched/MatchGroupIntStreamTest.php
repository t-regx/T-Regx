<?php
namespace Test\Feature\CleanRegex\Match\group\asInt\_unmatched;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsOptionalEmpty;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\Functions;

class MatchGroupIntStreamTest extends TestCase
{
    use ExactExceptionMessage, AssertsOptionalEmpty;

    /**
     * @test
     */
    public function shouldReturnEmptyOptional()
    {
        // when
        $optional = pattern('(Foo)')->match('Bar')->group(1)->asInt()->findFirst(Functions::fail());
        // then
        $this->assertOptionalEmpty($optional);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyOptional_distinct()
    {
        // when
        $optional = pattern('(Foo)')->match('Bar')->group(1)->asInt()->distinct()->findFirst(Functions::fail());
        // then
        $this->assertOptionalEmpty($optional);
    }
}
