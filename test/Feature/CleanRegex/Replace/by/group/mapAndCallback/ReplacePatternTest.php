<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\group\mapAndCallback;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;

/**
 * @coversNothing
 */
class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace()
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
            ->mapAndCallback($map, function (string $key) {
                return "**$key**";
            })
            ->orElseThrow();

        // then
        $this->assertSame('Replace **1**!, **2**! and **1**!', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_forMissingReplacement()
    {
        // given
        $map = [
            // Missing mapping value
        ];

        // then
        $this->expectException(MissingReplacementKeyException::class);
        $this->expectExceptionMessage("Expected to replace value 'One' by group 'capital' ('O'), but such key is not found in replacement map");

        // when
        pattern('(?<capital>O)?ne')
            ->replace('One')
            ->all()
            ->by()
            ->group('capital')
            ->mapAndCallback($map, function ($key) {
                return "**$key**";
            })
            ->orElseThrow();
    }
}
