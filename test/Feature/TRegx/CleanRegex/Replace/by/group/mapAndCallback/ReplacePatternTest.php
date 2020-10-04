<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\group\mapAndCallback;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Match\Details\Match;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     * @happyPath
     * @dataProvider optionals
     */
    public function shouldReplace(string $method, array $arguments)
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
            ->$method(...$arguments);

        // then
        $this->assertEquals('Replace **1**!, **2**! and **1**!', $result);
    }

    /**
     * @test
     * @happyPath
     * @dataProvider optionals
     */
    public function shouldThrow_forMissingReplacement(string $method, array $arguments)
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
            ->$method(...$arguments);
    }

    public function optionals(): array
    {
        return [
            'orElseWith'    => ['orElseWith', ['word']],
            'orElseCalling' => ['orElseCalling', [function (Match $match) {
            }]],
            'orElseThrow'   => ['orElseThrow', []],
            'orElseIgnore'  => ['orElseIgnore', []],
            'orElseEmpty'   => ['orElseEmpty', []],
        ];
    }
}
