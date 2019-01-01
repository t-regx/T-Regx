<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\group\map;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Replace\Map\Exception\MissingReplacementKeyException;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_all()
    {
        // given
        $subject = 'Replace One!, Two! and One!';
        $map = [
            'O' => '1',
            'T' => '2'
        ];

        // when
        $result = pattern('(?<capital>[OT])(ne|wo)')
            ->replace($subject)
            ->all()
            ->by()
            ->group('capital')
            ->map($map);

        // then
        $this->assertEquals('Replace 1!, 2! and 1!', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_onMissingReplacementsKey()
    {
        // given
        $subject = 'Replace One and Two';
        $map = [
            'O' => '1',
        ];

        // then
        $this->expectException(MissingReplacementKeyException::class);
        $this->expectExceptionMessage("Expected to replace value 'Two' by group 'capital' ('T'), but such key is not found in replacement map.");

        // when
        pattern('(?<capital>[OT])(ne|wo)')
            ->replace($subject)
            ->all()
            ->by()
            ->group('capital')
            ->map($map);
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
        pattern('(One|Two)')->replace('')->first()->by()->group(1)->map($map);
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
        pattern('(One|Two)')->replace('')->first()->by()->group(1)->map($map);
    }
}
