<?php
namespace Test\Unit\TRegx\CleanRegex\Replace\NonReplaced;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Replace\NonReplaced\MapReplaceStrategy;

class MapReplaceStrategyTest extends TestCase
{
    /**
     * @test
     * @happyPath
     */
    public function shouldReplace()
    {
        // given
        $mapReplace = new MapReplaceStrategy(['old' => 'new']);

        // when
        $result = $mapReplace->replacementResult('old');

        // then
        $this->assertEquals('new', $result);
    }

    /**
     * @test
     */
    public function shouldReturnNull_forMissing()
    {
        // given
        $mapReplace = new MapReplaceStrategy(['old' => 'new']);

        // when
        $result = $mapReplace->replacementResult('missing');

        // then
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidKey()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid replacement map key. Expected string, but integer (1) given');

        // when
        new MapReplaceStrategy([1 => 'new']);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidValue()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid replacement map value. Expected string, but integer (1) given');

        // when
        new MapReplaceStrategy(['old' => 1]);
    }
}
