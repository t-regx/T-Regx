<?php
namespace Test\Feature\CleanRegex\Match\group\asInt\_unmatched\_custom;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;

class MatchGroupIntStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_asInt()
    {
        // given
        $optional = pattern('(Foo)')->match('Bar')->group(1)->asInt()->filter(Functions::fail())->findFirst(Functions::fail());

        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage('Expected to get group #1 as integer from the first match, but subject was not matched at all');

        // when
        $optional->orThrow(CustomSubjectException::class);
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_asInt()
    {
        // given
        $optional = pattern('(Foo)')->match('Bar')->group(1)->asInt()->filter(Functions::fail())->asInt()->findFirst(Functions::fail());

        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage('Expected to get group #1 as integer from the first match, but subject was not matched at all');

        // when
        $optional->orThrow(CustomSubjectException::class);
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_distinct()
    {
        // given
        $optional = pattern('(Foo)')->match('Bar')->group(1)->asInt()->filter(Functions::fail())->distinct()->findFirst(Functions::fail());

        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage('Expected to get group #1 as integer from the first match, but subject was not matched at all');

        // when
        $optional->orThrow(CustomSubjectException::class);
    }
}
