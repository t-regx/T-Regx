<?php
namespace Test\Feature\CleanRegex\Match\group\asInt\_unmatched_group;

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
    public function shouldGetEmptyOptional()
    {
        // given
        $optional = pattern('#(Foo)?')->match('#')->group(1)->asInt()->findFirst(Functions::fail());
        // then
        $this->assertOptionalEmpty($optional);
    }

    /**
     * @test
     */
    public function shouldGetEmptyOptional_distinct()
    {
        // given
        $optional = pattern('#(Foo)?')->match('#')->group(1)->asInt()->distinct()->findFirst(Functions::fail());
        // then
        $this->assertOptionalEmpty($optional);
    }
}
