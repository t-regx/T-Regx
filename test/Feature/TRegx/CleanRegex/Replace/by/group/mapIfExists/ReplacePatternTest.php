<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\group\mapIfExists;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_all()
    {
        // given
        $subject = 'Replace One and Two';
        $map = [
            'O' => '1',
            'T' => '2',
        ];

        // when
        $result = pattern('(?<capital>[OT])(ne|wo)')
            ->replace($subject)
            ->all()
            ->by()
            ->group('capital')
            ->mapIfExists($map);

        // then
        $this->assertEquals('Replace 1 and 2', $result);
    }

    /**
     * @test
     */
    public function shouldNotReplace_onMissingReplacementsKey()
    {
        // given
        $subject = 'Replace One and Two, and maybe Four';
        $map = [
            'O' => '1',
            'T' => '2'
        ];

        // when
        $result = pattern('(?<capital>[OTF])(ne|wo|our)')
            ->replace($subject)
            ->all()
            ->by()
            ->group('capital')
            ->mapIfExists($map);

        // then
        $this->assertEquals('Replace 1 and 2, and maybe Four', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidKey()
    {
        // given
        $map = [2 => ''];

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid replacement map key. Expected string, but integer (2) given");

        // when
        pattern('(One|Two)')->replace('')->first()->by()->group(1)->mapIfExists($map);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidValue()
    {
        // given
        $map = ['' => true];

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid replacement map value. Expected string, but boolean (true) given");

        // when
        pattern('(One|Two)')->replace('')->first()->by()->group(1)->mapIfExists($map);
    }
}
