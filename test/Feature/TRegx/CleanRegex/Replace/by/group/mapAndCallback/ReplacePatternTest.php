<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\group\mapAndCallback;

use PHPUnit\Framework\TestCase;
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

    public function optionals(): array
    {
        return [
            'orReturn' => ['orReturn', ['word']],
            'orElse'   => ['orElse', [function (Match $match) {
            }]],
            'orThrow'  => ['orThrow', []],
        ];
    }
}
