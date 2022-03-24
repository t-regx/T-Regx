<?php
namespace Test\Feature\CleanRegex\Replace\Details;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Replace\Details\ReplaceDetail
 */
class ReplaceDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        Pattern::of('"[ \w]+"')
            ->replace('"Tyler Durden", "Marla Singer"')
            ->callback(Functions::out($detail, ''));

        // when
        $all = $detail->all();
        // then
        $this->assertSame(['"Tyler Durden"', '"Marla Singer"'], $all);
    }

    /**
     * @test
     */
    public function shouldReturnModifiedValues()
    {
        // when
        $replaced = Pattern::alteration(['sz', 'cz', 'rz', 'ch'], 'i')
            ->replace('Chrząszcz brzmi w trzcinie, w Szczebrzeszynie')
            ->callback(Functions::collect($details, 'X'));
        // then
        $this->assertSame('XXąXX bXmi w tXcinie, w XXebXeXynie', $replaced);
        // dependency
        return $details;
    }

    /**
     * @test
     * @depends shouldReturnModifiedValues
     */
    public function shouldGet_modifiedOffset(array $details)
    {
        // when + then
        $this->assertSame([0, 2, 5, 7, 11, 19, 30, 32, 36, 39], $this->each($details, Functions::property('offset')));
        $this->assertSame([0, 1, 3, 4, 7, 14, 24, 25, 28, 30], $this->each($details, Functions::property('modifiedOffset')));
    }

    /**
     * @test
     * @depends shouldReturnModifiedValues
     */
    public function shouldGet_byteModifiedOffset(array $details)
    {
        // when + then
        $this->assertSame([0, 2, 6, 8, 12, 20, 31, 33, 37, 40], $this->each($details, Functions::property('byteOffset')));
        $this->assertSame([0, 1, 4, 5, 8, 15, 25, 26, 29, 31], $this->each($details, Functions::property('byteModifiedOffset')));
    }

    private function each(array $array, callable $mapper): array
    {
        return \array_map($mapper, $array);
    }
}
