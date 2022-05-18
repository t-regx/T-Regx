<?php
namespace Test\Feature\CleanRegex\Match\group\asInt\_unmatched;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;

class MatchGroupIntStreamTest extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldThrow_asInt()
    {
        // given
        $optional = pattern('(Foo)')->match('Bar')->group(1)->asInt()->findFirst(Functions::fail());

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get group #1 as integer from the first match, but subject was not matched at all');

        // when
        $optional->get();
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_distinct()
    {
        // given
        $optional = pattern('(Foo)')->match('Bar')->group(1)->asInt()->distinct()->findFirst(Functions::fail());

        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get group #1 as integer from the first match, but subject was not matched at all');

        // when
        $optional->get();
    }
}
