<?php
namespace Test\Feature\CleanRegex\Match\group\asInt\_unmatched;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsOptionalEmpty;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCaseExactMessage;

class MatchGroupIntStreamTest extends TestCase
{
    use TestCaseExactMessage, AssertsOptionalEmpty;

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
