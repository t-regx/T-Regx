<?php
namespace Test\Feature\CleanRegex\Replace\all\modified;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider offsetMethods
     * @param string $method
     * @param array $expected
     */
    public function shouldReturn_modifiedOffset(string $method, array $expected)
    {
        // given
        $offsets = [];

        // when
        pattern('http://(?<name>[a-z]+)\.(?<domain>com|org)')
            ->replace('Linkś: http://google.com and http://other.org. and again http://danon.com')
            ->all()
            ->callback(function (ReplaceDetail $detail) use ($method, &$offsets) {
                $offsets[] = $detail->$method();

                return 'ę';
            });

        // then
        $this->assertSame($expected, $offsets);
    }

    public function offsetMethods(): array
    {
        return [
            'offset'             => ['offset', [7, 29, 57]],
            'byteOffset'         => ['byteOffset', [8, 30, 58]],
            'modifiedOffset'     => ['modifiedOffset', [7, 13, 26]],
            'byteModifiedOffset' => ['byteModifiedOffset', [8, 15, 29]],
        ];
    }

    /**
     * @test
     */
    public function shouldReturn_modifiedSubject()
    {
        // given
        $subjects = [];

        // when
        pattern('\*([a-zęó])\*', 'u')
            ->replace('words: *ó* *ę* *ó*')
            ->all()
            ->callback(function (ReplaceDetail $detail) use (&$subjects) {
                $subjects[] = $detail->modifiedSubject();
                return 'ą';
            });

        // then
        $expected = [
            'words: *ó* *ę* *ó*',
            'words: ą *ę* *ó*',
            'words: ą ą *ó*',
        ];
        $this->assertSame($expected, $subjects);
    }
}
