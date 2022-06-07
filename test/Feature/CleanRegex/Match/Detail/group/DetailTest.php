<?php
namespace Test\Feature\CleanRegex\Match\Detail\group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_onMissingGroup()
    {
        // given
        $detail = pattern('(?<one>hello)')->match('hello')->first();
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'two'");
        // when
        $detail->group('two');
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidGroupNameType()
    {
        // given
        $detail = Pattern::of('Yikes!')->match('Yikes!')->first();
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be an integer or a string, but boolean (true) given');
        // when
        $detail->group(true);
    }

    /**
     * @test
     */
    public function shouldThrow_forMalformedGroupName()
    {
        // given
        $detail = Pattern::of('Yikes!')->match('Yikes!')->first();
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");
        // when
        $detail->group("2group");
    }
}
