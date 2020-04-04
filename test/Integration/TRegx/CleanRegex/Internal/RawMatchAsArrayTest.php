<?php
namespace Test\Integration\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
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
        $this->assertEquals([0 => 'Foo:cm', 1 => null, 'group1' => null, 2 => 'cm', 'group2' => 'cm', 3 => null, 'group3' => null], $array);
    }

    private function base(): ApiBase
    {
        return new ApiBase(
            InternalPattern::standard('(?:Foo|Bar):(?<group1>Nope)?(?<group2>cm|mm)(?<group3>Really nope)?'),
            'Foo:cm Bar:mm',
            new UserData()
        );
    }
}
