<?php
namespace Test\Feature\CleanRegex\Replace\by\map;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_first()
    {
        // given
        $subject = 'Replace one and two';
        $map = [
            'one' => 'first',
            'two' => 'second'
        ];

        // when
        $result = pattern('(one|two)')->replace($subject)->first()->by()->map($map);

        // then
        $this->assertSame('Replace first and two', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_all()
    {
        // given
        $subject = 'Replace one!, two! and three!, and one!';
        $map = [
            'one!'   => 'first',
            'two!'   => 'second',
            'three!' => 'third'
        ];

        // when
        $result = pattern('(one|two|three)!')->replace($subject)->all()->by()->map($map);

        // then
        $this->assertSame('Replace first, second and third, and first', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_onMissingReplacementsKey()
    {
        // given
        $subject = 'Replace one, two and three, and one';
        $map = ['one' => 'first'];

        // then
        $this->expectException(MissingReplacementKeyException::class);
        $this->expectExceptionMessage("Expected to replace value 'two', but such key is not found in replacement map");

        // when
        pattern('(one|two)')->replace($subject)->all()->by()->map($map);
    }

    /**
     * @test
     */
    public function shouldThrow_onMissingReplacementsKey_group0()
    {
        // given
        $subject = 'Replace one, two and three, and one';
        $map = ['one' => 'first'];

        // then
        $this->expectException(MissingReplacementKeyException::class);
        $this->expectExceptionMessage("Expected to replace value 'two' by group #0 ('two'), but such key is not found in replacement map");

        // when
        pattern('(one|two)')->replace($subject)->all()->by()->group(0)->map($map)->orElseThrow();
    }

    /**
     * @test
     */
    public function shouldReplaceWithNumericString()
    {
        // when
        $result = pattern('123')->replace('123')->first()->by()->map(['123' => '345']);

        // then
        $this->assertSame('345', $result);
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
        pattern('(one|two)')->replace('')->first()->by()->map($map);
    }
}
