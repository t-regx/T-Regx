<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\all\modified\group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

/**
 * @coversNothing
 */
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
                $offsets[] = $detail->group('name')->$method();

                return 'ę';
            });

        // then
        $this->assertSame($expected, $offsets);
    }

    public function offsetMethods(): array
    {
        return [
            'offset'             => ['offset', [14, 36, 64]],
            'byteOffset'         => ['byteOffset', [15, 37, 65]],
            'modifiedOffset'     => ['modifiedOffset', [14, 20, 33]],
            'byteModifiedOffset' => ['byteModifiedOffset', [15, 22, 36]],
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
        pattern('http://(?<name>[a-z]+)\.(?<domain>com|org)')
            ->replace('Linkś: http://google.com and http://other.org. and again http://danon.com')
            ->all()
            ->callback(function (ReplaceDetail $detail) use (&$subjects) {
                $subjects[] = $detail->group('name')->modifiedSubject();
                return 'ą';
            });

        // then
        $expected = [
            'Linkś: http://google.com and http://other.org. and again http://danon.com',
            'Linkś: ą and http://other.org. and again http://danon.com',
            'Linkś: ą and ą. and again http://danon.com',
        ];
        $this->assertSame($expected, $subjects);
    }
}
