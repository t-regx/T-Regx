<?php
namespace Test\Feature\CleanRegex\Replace\Details;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        Pattern::of('"[ \w]+"')
            ->replace('"Tyler Durden", "Marla Singer"')
            ->callback(DetailFunctions::out($detail, ''));
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
    }
}
