<?php
namespace Test\Interaction\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\LazyRawWithGroups;
use TRegx\CleanRegex\Internal\RawMatchAsArray;

class RawMatchAsArrayTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetRawAsArray_match()
    {
        // when
        $array = RawMatchAsArray::fromMatch($this->base()->matchOffset(), new LazyRawWithGroups($this->base()));

        // then
        $expected = [
            0        => 'Foo:cm',
            'group1' => null,
            1        => null,
            'group2' => 'cm',
            2        => 'cm',
            'group3' => null,
            3        => null,
        ];
        $this->assertSame($expected, $array);
    }

    private function base(): ApiBase
    {
        return new ApiBase(
            Internal::pattern('(?:Foo|Bar):(?<group1>Nope)?(?<group2>cm|mm)(?<group3>Really nope)?'),
            'Foo:cm Bar:mm',
            new UserData());
    }
}
