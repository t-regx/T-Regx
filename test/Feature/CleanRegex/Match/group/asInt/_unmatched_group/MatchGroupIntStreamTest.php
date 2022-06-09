<?php
namespace Test\Feature\CleanRegex\Match\group\asInt\_unmatched_group;

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
